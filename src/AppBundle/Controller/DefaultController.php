<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Notification;
use AppBundle\Entity\Profile;
use AppBundle\Entity\User;
use AppBundle\Form\CommentForm;
use AppBundle\Form\UserRegistration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $home = "select";
        $em = $this->getDoctrine()->getManager();
        $services = $em->getRepository("AppBundle:ServiceSettings")
            ->findBy(
                [],
                ['sortOrder'=>'Asc']);
        //var_dump($recentArticles);exit;
        // replace this example code with whatever you need
        return $this->render('page/home.htm.twig', [
            'services'=>$services,
            'home'=>$home
        ]);
    }
    /**
     * @Route("/services", name="services")
     */
    public function servicesAction(Request $request)
    {
        $servicesx = "select";
        $em = $this->getDoctrine()->getManager();
        $services = $em->getRepository("AppBundle:ServiceSettings")
            ->findBy(
                [],
                ['sortOrder'=>'Asc']);
        //var_dump($recentArticles);exit;
        // replace this example code with whatever you need
        return $this->render('page/services.htm.twig', [
            'services'=>$services,
            'servicex'=>$servicesx
        ]);
    }

    /**
     * @Route("/services/{slug}",name="service")
     */
    public function serviceAction(Request $request,$slug){
        $servicesx = "select";
        $em = $this->getDoctrine()->getManager();
        $service = $em->getRepository("AppBundle:Service")
            ->findOneBy([
                'slug'=>$slug
            ]);
        return $this->render('page/service.htm.twig',[
            'service'=>$service,
            'servicex'=>$servicesx
        ]);
    }

    /**
     * @Route("/article/{slug}",name="view-article")
     */
    public function viewArticleAction(Request $request,Article $article){
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
        $notification->setObjectId($article->getSlug());
        $notification->setCreatedAt(new \DateTime());

        $em->persist($notification);


        $form = $this->createForm(CommentForm::class,$comment);

        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
            $userComment = $form->getData();
            //var_dump($userComment);exit;

            $em->persist($userComment);
            $em->flush();
            return new Response(null,200);
        }

        $comments = $em->getRepository("AppBundle:Comment")
            ->findBy([
                'article'=>$article->getId()
            ],
                [
                    'createdAt'=>'Desc'
                ]);

        return $this->render('article/view.htm.twig',[
            'article'=>$article,
            'comments'=>$comments
        ]);
    }

    /**
     * @Route("/about",name="about")
     */
    public function aboutAction(Request $request){
        $em = $this->getDoctrine()->getManager();

        $page = $em->getRepository("AppBundle:Pages")
            ->findOneBy([
                'title'=>"about us"
            ]);
        return $this->render("page/about.htm.twig",[
            'page'=>$page
        ]);
    }

    /**
     * @Route("/register",name="register")
     */
    public function registerAction(Request $request){
        $em = $this->getDoctrine()->getManager();

        $user = new User();
        $user->setCreatedAt(new \DateTime());
        $user->setUpdatedAt(new \DateTime());
        $user->setRoles(["ROLE_USER"]);

        $profile = new Profile();

        $form = $this->createForm(UserRegistration::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
            $userProfile = $form->getData();
            $user->setEmail($userProfile['email']);
            $user->setFirstName($userProfile['firstName']);
            $user->setLastName($userProfile['lastName']);
            $user->setPlainPassword($userProfile['plainPassword']);


            $profile->setDateOfBirth($userProfile['dateOfBirth']);
            $profile->setLevelOfEducation($userProfile['levelOfEducation']);
            $profile->setLifeStatus($userProfile['lifeStatus']);
            $profile->setUser($user);
            $profile->setImageFile($userProfile['imageFile']);

            $em->persist($user);
            $em->persist($profile);

            $em->flush();
            return $this->redirectToRoute('registered');
        }
        return $this->render('home/register.htm.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/registered",name="registered")
     */
    public function registeredAction(Request $request){
        return $this->render('home/registered.htm.twig');
    }

}
