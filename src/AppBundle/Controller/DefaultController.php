<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Notification;
use AppBundle\Entity\Profile;
use AppBundle\Entity\User;
use AppBundle\Form\CommentForm;
use AppBundle\Form\LoanForSharesForm;
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
        $schemes = $em->getRepository("AppBundle:Scheme")
            ->findBy(
                [],
                ['sortOrder'=>'Asc']);
        //var_dump($recentArticles);exit;
        // replace this example code with whatever you need
        return $this->render('page/home.htm.twig', [
            'services'=>$services,
            'schemes'=>$schemes,
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
     * @Route("/services/{serviceSlug}/{slug}",name="scheme")
     */
    public function schemeAction(Request $request,$serviceSlug,$slug){

        $servicesx = "select";
        $em = $this->getDoctrine()->getManager();

        $services =$em->getRepository("AppBundle:Service")
            ->findBy(
                [],
                ['sortOrder'=>'Asc']
            );

        $service = $em->getRepository("AppBundle:Service")
            ->findOneBy([
                'slug'=>$serviceSlug
            ]);

        $scheme = $em->getRepository("AppBundle:Scheme")
            ->findOneBy([
                'slug'=>$slug
            ]);

        $scheme = $em->getRepository("AppBundle:ServiceScheme")
            ->findOneBy([
                'scheme'=>$scheme,
                'service'=>$service
            ]);

        return $this->render(':page:scheme.htm.twig',[
            'scheme'=>$scheme,
            'thisService'=>$service,
            'servicex'=>$servicesx,
            'services'=>$services,
        ]);
    }
    /**
     * @Route("/clients/{slug}",name="clients")
     */
    public function clientsAction(Request $request,$slug){
        $servicesx = "select";
        $em = $this->getDoctrine()->getManager();

        $scheme = $em->getRepository("AppBundle:Scheme")
            ->findOneBy([
                'slug'=>$slug
            ]);

        return $this->render(':page:client.htm.twig',[
            'clientsx'=>$servicesx,
            'scheme'=>$scheme,
        ]);
    }
    /**
     * @Route("/{slug}",name="view-page")
     */
    public function viewPageAction(Request $request,$slug){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $page = $em->getRepository("AppBundle:Page")
            ->findOneBy([
                'slug'=>$slug
            ]);
        $subpages = $em->getRepository("AppBundle:SubPage")
                ->findBy([
                    'page'=>$page
                ],
                    [
                        'sortOrder'=>'Asc'
                    ]);

        return $this->render('page/page.htm.twig',[
            'page'=>$page,
            'subpages'=>$subpages
        ]);
    }
    /**
     * @Route("/r/{pageSlug}/{slug}",name="view-subpage")
     */
    public function viewSubPageAction(Request $request,$pageSlug,$slug){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $page = $em->getRepository("AppBundle:Page")
            ->findOneBy([
                'slug'=>$pageSlug
            ]);
        $subpage = $em->getRepository("AppBundle:SubPage")
            ->findOneBy([
                'slug'=>$slug
            ]);
        return $this->render('page/subpage.htm.twig',[
            'page'=>$page,
            'subpage'=>$subpage
        ]);
    }

    /**
     * @Route("/search",name="search")
     *
     */
    public function searchAction(Request $request) {
        if ($request->getMethod() == 'GET') {
            $title = $request->get('Search_term');
            $em = $this->getDoctrine()->getManager();
            $qb = $em->getRepository('AppBundle:Page')
                ->createQueryBuilder('a');
            $searches= explode(' ', $title);

            foreach ($searches as $sk => $sv) {
                $cqb[]=$qb->expr()->like("CONCAT($sv, '')", "'%$sv%'");
            }

            $qb->andWhere(call_user_func_array(array($qb->expr(),"content"),$cqb));

            $adverts = $qb->getResult();
        }
        return $this->render('YCRYcrBundle:Search:search.html.twig'
            , array('adverts' => $adverts));
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
     * @Route("/reports/{slug}",name="reports")
     */
    public function reportsAction(Request $request,$slug){
        $category = ucwords(str_replace("-", " ", $slug));
        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('AppBundle:Research')
            ->createQueryBuilder('research')
            ->andWhere('research.category = :category')
            ->setParameter(':category',$category)
            ->orderBy('research.createdAt', 'DESC');

        $query = $queryBuilder->getQuery();
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $reports = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 30)
        );


        return $this->render(':page:reports.htm.twig',[
            'category'=>$category,
            'reports'=>$reports
        ]);
    }
    /**
     * @Route("/page/contacts",name="contactsPage")
     */
    public function contactsAction(Request $request){
        $em = $this->getDoctrine()->getManager();

        return $this->render("page/contacts.htm.twig");
    }
    /**
     * @Route("/page/loans-for-shares",name="loansPage")
     */
    public function loansAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(LoanForSharesForm::class);
        return $this->render("page/loans.htm.twig",[
            'form'=>$form->createView()
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
