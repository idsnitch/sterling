<?php
/**
 * Created by PhpStorm.
 * User: Mgeni
 * Date: 5/24/2017
 * Time: 3:53 PM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\NextOfKin;
use AppBundle\Entity\Artist;
use AppBundle\Entity\Page;
use AppBundle\Entity\Partner;
use AppBundle\Entity\Recording;
use AppBundle\Entity\Research;
use AppBundle\Entity\Scheme;
use AppBundle\Entity\Service;
use AppBundle\Entity\ServiceScheme;
use AppBundle\Entity\ServiceSettings;
use AppBundle\Entity\SubPage;
use AppBundle\Entity\User;
use AppBundle\Form\CategoryForm;
use AppBundle\Form\ClientTypeForm;
use AppBundle\Form\HomeSettingsForm;
use AppBundle\Form\NewAdministratorForm;
use AppBundle\Form\NewClientForm;
use AppBundle\Form\NewSchemeForm;
use AppBundle\Form\PageForm;
use AppBundle\Form\PartnerForm;
use AppBundle\Form\PartnerUserForm;
use AppBundle\Form\ProducerForm;
use AppBundle\Form\ProfileReviewForm;
use AppBundle\Form\RecordingForm;
use AppBundle\Form\ReportForm;
use AppBundle\Form\SchemeForm;
use AppBundle\Form\ServiceForm;
use AppBundle\Form\SubPageForm;
use AppBundle\Form\UserUpdateForm;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WhiteOctober;

/**
 * @Route("/administrator")
 * @Security("is_granted('ROLE_CAN_ADMINISTER')")
 *
 */
class AdminController extends Controller
{
    /**
     * @Route("/",name="admin-home")
     */
    public function homeAction(Request $requet)
    {
        $dashboard = "active";
        return $this->render('admin/dashboard.htm.twig', [
            'dashboard'=>$dashboard
        ]);
    }
    /**
     * @Route("/report/new",name="new-report")
     */
    public function newReportAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $admin = $this->get('security.token_storage')->getToken()->getUser();

        $research = new Research();
        $research->setCreatedAt(new \DateTime());
        $research->setCreatedBy($admin);
        $research->setUpdatedAt(new \DateTime());
        $research->setUpdatedBy($admin);

        $form = $this->createForm(ReportForm::class,$research);

        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
            $pro = $form->getData();
            $em->persist($pro);
            $em->flush();

            return $this->redirectToRoute('research');
        }
        return $this->render(':admin/research:new.htm.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/report/{id}/edit",name="update-report")
     */
    public function updateReportAction(Request $request, Research $report){
        $em = $this->getDoctrine()->getManager();
        $admin = $this->get('security.token_storage')->getToken()->getUser();

        $form = $this->createForm(ReportForm::class,$report);

        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
            $pro = $form->getData();
            $em->persist($pro);
            $em->flush();

            return $this->redirectToRoute('research-pages');
        }
        return $this->render(':admin/research:edit.htm.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/reports",name="research-pages")
     */
    public function researchListAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $reports = $em->getRepository("AppBundle:Research")
            ->findBy([

            ],
                [
                    'createdAt'=>'Desc'
                ]);
        return $this->render(":admin/research:list.htm.twig",[
            'reports'=>$reports,
            'researchx'=>'Active'
        ]);
    }
    /**
     * @Route("/research/categories",name="research-categories")
     */
    public function researchCategoriesListAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository("AppBundle:Category")
            ->findBy([

            ],
                [
                    'createdAt'=>'Desc'
                ]);
        return $this->render(":admin/category:list.htm.twig",[
            'categories'=>$categories,
            'categoriesx'=>'active'
        ]);
    }
    /**
     * @Route("/research/category/new",name="new-category")
     */
    public function newCategoryAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $admin = $this->get('security.token_storage')->getToken()->getUser();

        $category = new Category();
        $category->setCreatedAt(new \DateTime());
        $category->setCreatedBy($admin);
        $category->setUpdatedAt(new \DateTime());
        $category->setUpdatedBy($admin);

        $form = $this->createForm(CategoryForm::class,$category);

        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
            $pro = $form->getData();
            $em->persist($pro);
            $em->flush();

            return $this->redirectToRoute('research-categories');
        }
        return $this->render(':admin/category:new.htm.twig',[
            'form'=>$form->createView()
        ]);
    }
    /**
     * @Route("/research/category/form",name="new-category-form")
     */
    public function newCategoryFormAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $admin = $this->get('security.token_storage')->getToken()->getUser();

        $category = new Category();
        $category->setCreatedAt(new \DateTime());
        $category->setCreatedBy($admin);
        $category->setUpdatedAt(new \DateTime());
        $category->setUpdatedBy($admin);

        $form = $this->createForm(CategoryForm::class,$category);

        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
            $pro = $form->getData();
            $em->persist($pro);
            $em->flush();

            return $this->redirectToRoute('research-categories');
        }
        return $this->render(':admin/category:newForm.htm.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/research/categories/{id}/edit",name="update-category")
     */
    public function updateCategoryAction(Request $request, Category $category){
        $em = $this->getDoctrine()->getManager();
        $admin = $this->get('security.token_storage')->getToken()->getUser();

        $form = $this->createForm(CategoryForm::class,$category);

        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
            $pro = $form->getData();
            $em->persist($pro);
            $em->flush();

            return $this->redirectToRoute('research-categories');
        }
        return $this->render(':admin/category:edit.htm.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/report/form",name="new-reportform")
     */
    public function newReportFormAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $admin = $this->get('security.token_storage')->getToken()->getUser();

        $research = new Research();
        $research->setCreatedAt(new \DateTime());
        $research->setCreatedBy($admin);
        $research->setUpdatedAt(new \DateTime());
        $research->setUpdatedBy($admin);

        $form = $this->createForm(ReportForm::class,$research);

        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
            $pro = $form->getData();
            $em->persist($pro);
            $em->flush();

            return $this->redirectToRoute('research');
        }
        return $this->render(":admin/research:newReport.htm.twig",[
            'form'=>$form->createView()
        ]);

    }



    /**
     * @Route("/homepage-settings",name="homepage-settings")
     */
    public function homepageSettingsAction(Request $request){
        $homepage = "active";
        $em = $this->getDoctrine()->getManager();
        $homepageSettings = $em->getRepository("AppBundle:ServiceSettings")
            ->findBy(
                [],['sortOrder'=>'Asc']);
        return $this->render('admin/homepageSettings/list.htm.twig',[
            'settings'=>$homepageSettings,
            'homepage'=>$homepage
        ]);
    }
    /**
     * @Route("/homepage-settings/{id}/edit",name="update-homepage-settings")
     */
    public function editHomepageSettingAction(Request $request,ServiceSettings $settings){
        $form = $this->createForm(ServiceForm::class,$settings);
        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
            $pro = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($pro);
            $em->flush();
            return $this->redirectToRoute('admin-services');
        }
        return $this->render('admin/services/edit.htm.twig',[
            'form'=>$form->createView()
        ]);
    }
    /**
     * @Route("/services",name="admin-services")
     */
    public function servicesAction(Request $request){
        $servicesx = "active";
        $em = $this->getDoctrine()->getManager();
        $services = $em->getRepository("AppBundle:Service")
            ->findBy(
                [],['sortOrder'=>'Asc']);
        return $this->render('admin/services/list.htm.twig',[
            'servicesx'=>$services,
            'services'=>$servicesx
        ]);
    }
    /**
     * @Route("/services/{id}/edit",name="update-services")
     */
    public function editServicesAction(Request $request,Service $service){
        $form = $this->createForm(ServiceForm::class,$service);
        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
            $pro = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($pro);
            $em->flush();
            return $this->redirectToRoute('admin-services');
        }
        return $this->render('admin/services/edit.htm.twig',[
            'form'=>$form->createView()
        ]);
    }
    /**
     * @Route("/schemes",name="schemes")
     */
    public function schemeAction(Request $request){
        $schemesx = "active";
        $em = $this->getDoctrine()->getManager();
        $schemes = $em->getRepository("AppBundle:Scheme")
            ->findAll();
        return $this->render('admin/schemes/list.htm.twig',[
            'schemes'=>$schemes,
            'schemesx'=>$schemesx
        ]);
    }
    /**
     * @Route("/schemes/{id}/edit",name="update-scheme")
     */
    public function editSchemeAction(Request $request,Scheme $scheme){
        $form = $this->createForm(ClientTypeForm::class,$scheme);
        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
            $pro = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($pro);
            $em->flush();
            return $this->redirectToRoute('schemes');
        }
        return $this->render('admin/schemes/edit.htm.twig',[
            'form'=>$form->createView()
        ]);
    }
    /**
     * @Route("/clients",name="clientList")
     */
    public function clientsAction(Request $request){
        $schemesx = "active";
        $em = $this->getDoctrine()->getManager();
        $schemes = $em->getRepository("AppBundle:Scheme")
            ->findAll();
        return $this->render('admin/clients/list.htm.twig',[
            'schemes'=>$schemes,
            'schemesx'=>$schemesx
        ]);
    }
    /**
     * @Route("/clients/{id}/edit",name="update-client")
     */
    public function editClientsAction(Request $request,ServiceScheme $scheme){
        $form = $this->createForm(ClientTypeForm::class,$scheme);
        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
            $pro = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($pro);
            $em->flush();
            return $this->redirectToRoute('schemes');
        }
        return $this->render('admin/schemes/edit.htm.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/pages",name="pages")
     */
    public function pagesAction(Request $request){
        $pagesx = "active";
        $em = $this->getDoctrine()->getManager();
        $pages = $em->getRepository("AppBundle:Page")
            ->findAll();
        return $this->render('admin/pages/list.htm.twig',[
            'pagesx'=>$pages,
            'pages'=>$pagesx
        ]);
    }
    /**
     * @Route("/pages/{id}/edit",name="update-page")
     */
    public function editPagesAction(Request $request,Page $page){
        $pages = "active";
        $form = $this->createForm(PageForm::class,$page);
        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
            $pro = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($pro);
            $em->flush();
            return $this->redirectToRoute('pages');
        }
        return $this->render('admin/pages/edit.htm.twig',[
            'form'=>$form->createView(),
            'pages'=>$pages
        ]);
    }
    /**
     * @Route("/sub-pages",name="sub-pages")
     */
    public function subPagesAction(Request $request){
        $pagesx = "active";
        $em = $this->getDoctrine()->getManager();
        $pages = $em->getRepository("AppBundle:SubPage")
            ->findAll();
        return $this->render('admin/subpages/list.htm.twig',[
            'pagesx'=>$pages,
            'subpages'=>$pagesx
        ]);
    }
    /**
     * @Route("/sub-pages/{id}/edit",name="update-subpage")
     */
    public function editSubPagesAction(Request $request,SubPage $page){
        $pages = "active";
        $form = $this->createForm(SubPageForm::class,$page);
        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
            $pro = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($pro);
            $em->flush();
            return $this->redirectToRoute('sub-pages');
        }
        return $this->render('admin/subpages/edit.htm.twig',[
            'form'=>$form->createView(),
            'subpages'=>$pages
        ]);
    }
    /**
     * @Route("/sub-pages/new",name="new-subpage")
     */
    public function newSubPageAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $admin = $this->get('security.token_storage')->getToken()->getUser();

        $page = new SubPage();
        $page->setCreatedAt(new \DateTime());
        $page->setCreatedBy($admin);
        $page->setUpdatedAt(new \DateTime());
        $page->setUpdatedBy($admin);
        $page->setIsActive(true);

        $form = $this->createForm(SubPageForm::class,$page);

        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
            $pro = $form->getData();
            $em->persist($pro);
            $em->flush();

            return $this->redirectToRoute('sub-pages');
        }
        return $this->render(':admin/subpages:new.htm.twig',[
            'form'=>$form->createView()
        ]);
    }
    /**
     * @Route("/settings",name="new-models")
     */
    public function allSettingsPage(Request $request)
    {
        $new = "active";
        return $this->render(':admin/settings:dashboard.htm.twig',[
            'new'=>$new
        ]);
    }

    /**
     * @Route("/services/new",name="new-service")
     */
    public function newServiceAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $admin = $this->get('security.token_storage')->getToken()->getUser();

        $service = new Service();
        $service->setCreatedAt(new \DateTime());
        $service->setCreatedBy($admin);
        $service->setUpdatedAt(new \DateTime());
        $service->setUpdatedBy($admin);
        $service->setIsActive(true);

        $form = $this->createForm(ServiceForm::class,$service);

        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
            $pro = $form->getData();
            $em->persist($pro);
            $em->flush();

            return $this->redirectToRoute('new-models');
        }
        return $this->render(':admin/models/service:new.htm.twig',[
            'form'=>$form->createView()
        ]);
    }
    /**
     * @Route("/services/form",name="new-serviceform")
     */
    public function newServiceFormAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $admin = $this->get('security.token_storage')->getToken()->getUser();


        $service = new Service();
        $service->setCreatedAt(new \DateTime());
        $service->setCreatedBy($admin);
        $service->setUpdatedAt(new \DateTime());
        $service->setUpdatedBy($admin);
        $service->setIsActive(true);

        $form = $this->createForm(ServiceForm::class,$service);

        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
            $pro = $form->getData();
            $em->persist($pro);
            $em->flush();

        }
        return $this->render("admin/models/service/newService.htm.twig",[
            'form'=>$form->createView()
        ]);

    }

    /**
     * @Route("/scheme/new",name="new-scheme")
     */
    public function newSchemeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $admin = $this->get('security.token_storage')->getToken()->getUser();

        $scheme = new Scheme();
        $scheme->setCreatedAt(new \DateTime());
        $scheme->setCreatedBy($admin);
        $scheme->setModifiedAt(new \DateTime());
        $scheme->setModifiedBy($admin);

        $form = $this->createForm(ClientTypeForm::class,$scheme);

        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
            $pro = $form->getData();
            $em->persist($pro);
            $em->flush();

            return $this->redirectToRoute('new-models');
        }
        return $this->render(':admin/models/scheme:new.htm.twig',[
            'form'=>$form->createView()
        ]);
    }
    /**
     * @Route("/scheme/form",name="new-schemeform")
     */
    public function newSchemeFormAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $admin = $this->get('security.token_storage')->getToken()->getUser();


        $scheme = new Scheme();
        $scheme->setCreatedAt(new \DateTime());
        $scheme->setCreatedBy($admin);
        $scheme->setModifiedAt(new \DateTime());
        $scheme->setModifiedBy($admin);

        $form = $this->createForm(ClientTypeForm::class,$scheme);

        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
            $pro = $form->getData();
            $em->persist($pro);
            $em->flush();

        }
        return $this->render("admin/models/scheme/newScheme.htm.twig",[
            'form'=>$form->createView()
        ]);

    }
    /**
     * @Route("/client/new",name="new-client")
     */
    public function newClientAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $admin = $this->get('security.token_storage')->getToken()->getUser();

        $scheme = new ServiceScheme();
        $scheme->setCreatedAt(new \DateTime());
        $scheme->setCreatedBy($admin);
        $scheme->setUpdatedAt(new \DateTime());
        $scheme->setUpdatedBy($admin);

        $form = $this->createForm(NewClientForm::class,$scheme);

        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
            $pro = $form->getData();
            $em->persist($pro);
            $em->flush();

            return $this->redirectToRoute('new-models');
        }
        return $this->render(':admin/models/serviceScheme:new.htm.twig',[
            'form'=>$form->createView()
        ]);
    }
    /**
     * @Route("/client/form",name="new-clientform")
     */
    public function newClientFormAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $admin = $this->get('security.token_storage')->getToken()->getUser();


        $scheme = new ServiceScheme();
        $scheme->setCreatedAt(new \DateTime());
        $scheme->setCreatedBy($admin);
        $scheme->setUpdatedAt(new \DateTime());
        $scheme->setUpdatedBy($admin);

        $form = $this->createForm(NewClientForm::class,$scheme);


        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
            $pro = $form->getData();
            $em->persist($pro);
            $em->flush();

        }
        return $this->render("admin/models/serviceScheme/newScheme.htm.twig",[
            'form'=>$form->createView()
        ]);

    }

    /**
     * @Route("/pages/new",name="new-page")
     */
    public function newPageAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $admin = $this->get('security.token_storage')->getToken()->getUser();

        $page = new Page();
        $page->setCreatedAt(new \DateTime());
        $page->setCreatedBy($admin);
        $page->setUpdatedAt(new \DateTime());
        $page->setUpdatedBy($admin);
        $page->setIsActive(true);

        $form = $this->createForm(PageForm::class,$page);

        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
            $pro = $form->getData();
            $em->persist($pro);
            $em->flush();

            return $this->redirectToRoute('new-models');
        }
        return $this->render(':admin/models/page:new.htm.twig',[
            'form'=>$form->createView()
        ]);
    }
    /**
     * @Route("/pages/form",name="new-pageform")
     */
    public function newPageFormAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $admin = $this->get('security.token_storage')->getToken()->getUser();

        $page = new Page();
        $page->setCreatedAt(new \DateTime());
        $page->setCreatedBy($admin);
        $page->setUpdatedAt(new \DateTime());
        $page->setUpdatedBy($admin);
        $page->setIsActive(true);

        $form = $this->createForm(PageForm::class,$page);

        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
            $pro = $form->getData();
            $em->persist($pro);
            $em->flush();

        }
        return $this->render("admin/models/page/newPage.htm.twig",[
            'form'=>$form->createView()
        ]);

    }
    /**
     * @Route("/settings/new",name="new-setting")
     */
    public function newSettingsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $admin = $this->get('security.token_storage')->getToken()->getUser();

        $setting = new ServiceSettings();
        $setting->setCreatedAt(new \DateTime());
        $setting->setCreatedBy($admin);
        $setting->setUpdatedAt(new \DateTime());
        $setting->setUpdatedBy($admin);

        $form = $this->createForm(HomeSettingsForm::class,$setting);

        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
            $pro = $form->getData();
            $em->persist($pro);
            $em->flush();

            return $this->redirectToRoute('new-models');
        }
        return $this->render(':admin/models/homepage:new.htm.twig',[
            'form'=>$form->createView()
        ]);
    }
    /**
     * @Route("/settings/form",name="new-settingform")
     */
    public function newSettingFormAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $admin = $this->get('security.token_storage')->getToken()->getUser();

        $setting = new ServiceSettings();
        $setting->setCreatedAt(new \DateTime());
        $setting->setCreatedBy($admin);
        $setting->setUpdatedAt(new \DateTime());
        $setting->setUpdatedBy($admin);

        $form = $this->createForm(HomeSettingsForm::class,$setting);

        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
            $pro = $form->getData();
            $em->persist($pro);
            $em->flush();

        }
        return $this->render("admin/models/homepage/newService.htm.twig",[
            'form'=>$form->createView()
        ]);

    }

    /**
     * @Route("/users",name="users")
     */
    public function allUsersAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository("AppBundle:User")->findAll();
        $usersx = "active";
        return $this->render('admin/users/all.htm.twig', ['users' => $users, 'usersx' => $usersx]);
    }

    /**
     * @Route("/users/list",name="users-list")
     */
    public function allUsersListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository("AppBundle:User")->findAll();
        $usersx = "active";
        return $this->render('admin/users/list.htm.twig', ['users' => $users, 'usersx' => $usersx]);
    }

    /**
     * @Route("/users/administrators",name="administrator-users")
     */
    public function allAdministratorsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository("AppBundle:User")->findAll();
        return $this->render('admin/users/all.htm.twig', ['users' => $users]);
    }

    /**
     * @Route("/users/partners",name="partner-users")
     */
    public function allPartnerUsersAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository("AppBundle:User")->findAll();
        return $this->render('admin/users/all.htm.twig', ['users' => $users]);
    }

    /**
     * @Route("/users/{id}/update",name="update-user")
     */
    public function updateUserAction(Request $request, User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $admin = $this->get('security.token_storage')->getToken()->getUser();
        $form = $this->createForm(UserUpdateForm::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pro = $form->getData();
            $em->persist($pro);
            $em->flush();
            return $this->redirectToRoute("users");
        }
        return $this->render("admin/partners/new.htm.twig", ['form' => $form->createView()]);

    }

    /**
     * @Route("/users/admin/pending",name="pending-admin-accounts")
     */
    public function pendingAdminAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository("AppBundle:User")->findAllPendingAdminUsers();
        $usersx = "active";
        return $this->render('admin/users/adminPending.htm.twig', ['users' => $users, 'usersx' => $usersx]);
    }

    /**
     * @Route("/users/administrators",name="admin-accounts")
     */
    public function adminAccountsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository("AppBundle:User")->findAllAdministratorUsers();
        return $this->render('admin/admin-users.htm.twig', ['users' => $users]);
    }

    /**
     * @Route("/user/account/{id}/approve",name="approve-admin-account")
     */
    public function approveAccountAction(Request $request, User $user)
    {
        $admin = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $user->setIsActive(true);
        $user->setIsPasswordCreated(true);
        $user->setApprovedBy($admin);
        //$user->setRoles(["ROLE_ADMINISTRATOR"]);
        $em->persist($user);
        $em->flush();
        $this->sendEmail($user->getFirstName(), "Administrator Account Approved", $user->getEmail(), "accountApproved.htm.twig", null);
        return new Response(null, 204);
    }

    /**
     * @Route("/administrator/new",name="new-administrator")
     */
    public function newAdministratorAction(Request $request)
    {
        $admin = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $accountToken = time();
        $user = new User();
        $user->setIsActive(true);
        $user->setPlainPassword(base64_encode(random_bytes(10)));
        $user->setAccountCreatedBy($admin);
        $user->setPasswordResetToken($accountToken);
        $form = $this->createForm(NewAdministratorForm::class, $user);
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()) {
            $user = $form->getData();
            $role = $request->request->get('role');
            if ($role == "Membership") {
                $user->setRoles(["ROLE_MEMBERSHIP"]);
            } elseif ($role == "Board") {
                $user->setRoles(["ROLE_BOARD"]);
            } elseif ($role == "Administrator") {
                $user->setRoles(["ROLE_ADMINISTRATOR"]);
            }
            $em->persist($user);
            $em->flush();
            $this->sendEmail($user->getFirstName(), "KAMP Portal Administrator Account", $user->getEmail(), "adminAccountCreated.htm.twig", $user->getId());
            return $this->redirectToRoute('admin-accounts');
        }
        return $this->render(':admin:new.htm.twig', ['adminForm' => $form->createView()]);
    }

    /**
     * @Route("/activate/{id}/account",name="activate-account")
     */
    public function activateAccountAction(Request $request, User $user)
    {
        $admin = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $user->setIsActive(true);
        $user->setUpdatedBy($admin);
        $user->setUpdatedAt(new \DateTime());
        $em->persist($user);
        $em->flush();
        $this->sendEmail($user->getFirstName(), "Account Activated", $user->getEmail(), "accountActivated.htm.twig", null);
        return new Response(null, 204);
    }

    /**
     * @Route("/deactivate/{id}/account",name="deactivate-account")
     */
    public function deActivateAccountAction(Request $request, User $user)
    {
        $admin = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $user->setIsActive(false);
        $user->setUpdatedBy($admin);
        $user->setUpdatedAt(new \DateTime());
        $em->persist($user);
        $em->flush();
        $this->sendEmail($user->getFirstName(), "Account Deactivated", $user->getEmail(), "accountDeactivated.htm.twig", null);
        return new Response(null, 204);
    }

    protected function sendEmail($firstName, $subject, $emailAddress, $twigTemplate, $code)
    {
        $message = \Swift_Message::newInstance()->setSubject($subject)->setFrom('kamp@patchcreate.com', 'TAMASHA Recordings Management Portal')->setTo($emailAddress)->setBody($this->renderView('Emails/' . $twigTemplate, array('name' => $firstName, 'code' => $code)), 'text/html');
        $this->get('mailer')->send($message);
    }

    /**
     * @Route("/member/update",name="update-member")
     */
    public function updateRoleFunction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $memberId = $request->request->get('pk');
        $roleValue = $request->request->get('value');
        switch ($roleValue) {
            case 1:
                $role = ["ROLE_DATA_CAPTURE"];
                break;
            case 2:
                $role = ["ROLE_ADMINISTRATOR"];
                break;
            case 3:
                $role = ["ROLE_MANAGER"];
                break;
            case 4:
                $role = ["ROLE_CEO"];
                break;
            default:
                $role = ["ROLE_DATA_CAPTURE"];
                break;
        }
        $member = $em->getRepository("AppBundle:User")->findOneBy(['id' => $memberId]);
        if ($member) {
            $member->setRoles($role);
            $em->persist($member);
            $em->flush();
            return new Response(null, 200);
        } else {
            return new Response(null, 500);
        }
    }

    private function generate_random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
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

}
