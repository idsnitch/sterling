<?php

namespace AppBundle\Controller\Home;

use AppBundle\Entity\Article;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Notification;
use AppBundle\Entity\Profile;
use AppBundle\Form\CommentForm;
use AppBundle\Form\UpdateAccountForm;
use AppBundle\Form\UpdateProfileForm;
use AppBundle\Form\UserRegistration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/home")
 * @Security("is_granted('ROLE_USER')")
 *
 */
class HomeController extends Controller
{
    /**
     * @Route("/account",name="my-lti")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $article = $em->getRepository("AppBundle:Article")
            ->findOneBy([
                'isActive'=>true
            ],
                [
                    'createdAt'=>'Desc'
                ]);
        return $this->render('home/account.htm.twig',[
            'article'=>$article
        ]);
    }

    /**
     * @Route("/profile/update",name="update-profile")
     */
    public function updateProfileAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $profiles = $user->getProfile();
        if (!$profiles[0]){
            $profile = new Profile();
            $profile->setUser($user);
            $profile->setCreatedAt(new \DateTime());
            $profile->setUpdatedAt(new \DateTime());
            $profile->setDateOfBirth(new \DateTime());
        }else{
            $profile= $profiles[0];
        }
        $em= $this->getDoctrine()->getManager();

        $form = $this->createForm(UpdateAccountForm::class,$profile);
        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
            $account= $form->getData();

            $em->persist($account);
            $em->flush();
            return new Response(null,200);

        }
        return $this->render('home/update.htm.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/profile-picture/update",name="update-picture")
     */
    public function updateProfilePictureAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $profiles = $user->getProfile();
        if (!$profiles[0]){
            $profile = new Profile();
            $profile->setUser($user);
            $profile->setCreatedAt(new \DateTime());
            $profile->setUpdatedAt(new \DateTime());
            $profile->setDateOfBirth(new \DateTime());
        }else{
            $profile= $profiles[0];
        }
        $em= $this->getDoctrine()->getManager();

        $form = $this->createForm(UpdateProfileForm::class,$profile);
        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
            $account= $form->getData();

            $em->persist($account);
            $em->flush();
            return new Response(null,200);

        }
        return $this->render('home/updateImage.htm.twig',[
            'form'=>$form->createView(),
            'profile'=>$profile
        ]);
    }


    /**
     * @Route("/article/{id}/comment",name="add-comment")
     */
    public function newCommentAction(Request $request, Article $article){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $comment = new Comment();
        $comment->setArticle($article);
        $comment->setCreatedAt(new \DateTime());
        $comment->setUpdatedAt(new \DateTime());
        $comment->setIsApproved(false);
        $comment->setAuthor($user);

        $notification = new Notification();
        $notification->setMessage("New Comment for article: ".$article->getTitle());
        $notification->setType("Comment");
        $notification->setObjectId($article->getId());
        $notification->setCreatedAt(new \DateTime());

        $em->persist($notification);

        $form = $this->createForm(CommentForm::class,$comment,[
            'action' => $this->generateUrl('add-comment',['id'=>$article->getId()]),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
            $userComment = $form->getData();
            //var_dump($userComment);exit;

            $em->persist($userComment);
            $em->flush();

            return new Response(null,200);
        }
        return $this->render('article/newComment.htm.twig',[
            'form'=>$form->createView()
        ]);

    }

    /**
     * @Route("/get/{id}/comments",name="get-comments")
     */
    public function getCommentsAction(Request $request,Article $article){
        $em = $this->getDoctrine()->getManager();
        $comments = $em->getRepository("AppBundle:Comment")
            ->findBy([
                'article'=>$article->getId()
            ],
                [
                    'createdAt'=>'Desc'
                ]);

        return $this->render('article/comments.htm.twig',[
            'comments'=>$comments
        ]);

    }

    /**
     * @Route("/remove/{id}/comment",name="remove-item")
     */
    public function removeCommentAction(Request $request,Comment $comment){
        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();

        return new Response(null,200);
    }

    private function sendNotificationEmail($firstName,$emailAddress, $codeArray)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject("Password Reset Requested")
            ->setFrom('lti@lifetouchimpact.com','LTI Team')
            ->setTo($emailAddress)
            ->setBody(
                $this->renderView(
                    'Emails/notification.htm.twig',
                    array(
                        'name' => $firstName,
                        'code' => $codeArray
                    )
                ),
                'text/html'
            );
        $this->get('mailer')->send($message);
    }

}
