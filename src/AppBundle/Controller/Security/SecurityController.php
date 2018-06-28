<?php

namespace AppBundle\Controller\Security;
use AppBundle\Entity\User;
use AppBundle\Form\AdministratorRegistrationForm;
use AppBundle\Form\ForgotPasswordForm;
use AppBundle\Form\LoginForm;
use AppBundle\Form\NewAdministratorForm;
use AppBundle\Form\ResetPasswordForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends Controller
{

    /**
     * @Route("/login/admin",name="admin-login")
     *
     */
    public function loginAdminAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginForm::class,[
            '_username' => $lastUsername
        ]);

        return $this->render(
            'loginAdmin.htm.twig',
            array(
                'loginForm' => $form->createView(),
                'error' => $error,
            ));
    }
    /**
     * @Route("/login/agosto",name="super-admin-login")
     *
     */
    public function loginSuperAdminAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginForm::class,[
            '_username' => $lastUsername
        ]);

        return $this->render(
            'loginAdmin.htm.twig',
            array(
                'loginForm' => $form->createView(),
                'error' => $error,
            ));
    }

    /**
     * @Route("/login",name="home-login")
     *
     */
    public function loginHomeAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginForm::class,[
            '_username' => $lastUsername
        ]);

        return $this->render(
            'loginAdmin.htm.twig',
            array(
                'loginForm' => $form->createView(),
                'error' => $error,
            ));
    }

    /**
     * @Route("/reset/password",name="forgot-password")
     */
    public function forgotPasswordAction(Request $request){
        $form = $this->createForm(ForgotPasswordForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()){
            $email = $form['email']->getData();
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository("AppBundle:User")
                ->findOneBy([
                    'email'=>$email
                ]);
            if ($user){
                $resetToken = $this->generate_random_str(6);
                $user->setIsResetTokenValid(true);
                $user->setPasswordResetToken($resetToken);
                $em->persist($user);
                $em->flush();
                $this->sendEmail($user->getFirstName(),"Password Reset",$user->getEmail(),'resetRequest.htm.twig',$resetToken);
                //return new Response(null,200);
            }
                return new Response(null,200);


        }
        return $this->render('forgot.htm.twig',[
            'form'=>$form->createView()
        ]);
    }
    /**
     * @Route("/account/{code}/activate",name="user-activate-account")
     */
    public function userFirstLoginAction(Request $request,$code){
        $em = $this->getDoctrine()->getManager();

        $idNumber = base64_decode($code);

        $profile = $em->getRepository("AppBundle:Artist")
            ->findOneBy([
                'idNumber'=>$code
            ]);
        $user = $profile->getWhoseProfile();
        //$user->setRoles('["R"]')
        $form = $this->createForm(ResetPasswordForm::class,$user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user=$form->getData();
            $user->setIsPasswordCreated(true);
            $em->persist($user);
            $em->flush();

            return $this->render('user/accountUpdated.htm.twig');
        }

        if ($user->getIsPasswordCreated()){
            $activated = true;
        }else{
            $activated = false;
        }

        return $this->render('user/activate.htm.twig',[
            'user'=>$user,
            'activationForm'=>$form->createView(),
            'isActivated'=>$activated
        ]);
    }
    /**
     * @Route("/account/admin/{id}/activate",name="user-activate-account")
     */
    public function adminFirstLoginAction(Request $request,User $user){
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(ResetPasswordForm::class,$user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user=$form->getData();
            $user->setIsPasswordCreated(true);
            $em->persist($user);
            $em->flush();

            return $this->render('user/adminAccountUpdated.htm.twig');
        }

        if ($user->getIsPasswordCreated()){
            $activated = true;
        }else{
            $activated = false;
        }

        return $this->render('user/adminActivate.htm.twig',[
            'user'=>$user,
            'activationForm'=>$form->createView(),
            'isActivated'=>$activated
        ]);
    }
    /**
     * @Route("/account/{code}/reset-password",name="user-reset-password")
     */
    public function resetPasswordAction(Request $request,$code){
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository("AppBundle:User")
            ->findOneBy([
                'passwordResetToken'=>$code
            ]);

        $form = $this->createForm(ResetPasswordForm::class,$user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user=$form->getData();
            $user->setIsResetTokenValid(false);
            $em->persist($user);
            $em->flush();

            return new Response(null,200);
        }

        if ($user->getIsResetTokenValid()){
            $validToken = true;
        }else{
            $validToken = false;
        }

        return $this->render('reset.htm.twig',[
            'user'=>$user,
            'form'=>$form->createView(),
            'isTokenValid'=>$validToken
        ]);
    }
   /**
     * @Route("/account/request",name="request-admin-account")
     */
    public function requestAccountAction(Request $request){

        $em = $this->getDoctrine()->getManager();

        $user = new User();
        $user->setIsActive(false);
        $user->setRoles(["ROLE_ADMIN"]);
        //$user->setIsPasswordCreated(false);

        $form = $this->createForm(AdministratorRegistrationForm::class,$user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();

            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute("admin-account-requested");
        }
        return $this->render(':admin:register.htm.twig',[
            'registerForm'=>$form->createView()
        ]);

    }
    /**
     * @Route("/account/admin/requested",name="admin-account-requested")
     */
    public function accountCreatedAction(Request $request){
        return $this->render(':admin:created.htm.twig');
    }


    /**
     * @Route("/logout",name="security_logout")
     */
    public function logoutAction(){
        throw new \Exception('This should not be reached');
    }
    /**
     * @Route("/logout",name="user_logout")
     */
    public function logoutUserAction(){
        throw new \Exception('This should not be reached');
    }

    private function generate_random_str(
        $length,
        $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ) {
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        if ($max < 1) {
            throw new Exception('$keyspace must be at least two characters long');
        }
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }
        return $str;
    }
    protected function sendEmail($firstName,$subject,$emailAddress,$twigTemplate,$code){
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom('kamp@patchcreate.com','TAMASHA Recordings Management Portal')
            ->setTo($emailAddress)
            ->setBody(
                $this->renderView(
                    'Emails/'.$twigTemplate,
                    array(
                        'name' => $firstName,
                        'code' => $code
                    )
                ),
                'text/html'
            );
        $this->get('mailer')->send($message);
    }
}
