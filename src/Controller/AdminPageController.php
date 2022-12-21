<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\EventCategory;
use App\Entity\Report;
use App\Entity\Session;
use App\Entity\ReportNotification;
use App\Entity\Task;
use App\Entity\Recipe;
use App\Entity\RecipeCategory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPageController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('BackOffice/dashboard.html.twig',[
            'controller_name' => 'AdminPageController',
        ]);
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard(){
        return $this->render('BackOffice/dashboard.html.twig');
    }

    /**
     * @Route("/tables", name="tables")
     */
    public function tables(){
        return $this->render('BackOffice/tables.html.twig');
    }

    /**
     * @Route("/moderation", name="moderation")
     */
    public function moderation(){
        $reports=$this->getDoctrine()->getRepository(Report::class)->findBy([],['createdAt'=>'DESC']);
        $unreadNotification=$this->getDoctrine()->getRepository(ReportNotification::class)->findBy(['seenByAdmin'=>'0','closed'=>'0'], ['createdAt'=>'desc']);
        $count=count($unreadNotification);
        return $this->render('BackOffice/moderation.html.twig', ['allReports'=>$reports,'count'=>$count]);
    }

    /**
     * @Route("/moderation/closereport/{reportId}", name="close_report")
     */
    public function closeReport($reportId){
        $report=$this->getDoctrine()->getRepository(Report::class)->find($reportId);
        $report->setIsClosed(1);
        $report->setClosedAt(new \DateTime());
        $em=$this->getDoctrine()->getManager();
        $em->persist($report);
        $em->flush();
        $notif=$this->getDoctrine()->getRepository(ReportNotification::class)->findOneBy(['report'=>$report]);
        $notif->setClosed(true);
        $em=$this->getDoctrine()->getManager();
        $em->persist($notif);
        $em->flush();


        return $this->redirectToRoute("moderation");
    }

    /**
     * @Route("/billing", name="billing")
     */
    public function billing(){
        return $this->render('BackOffice/billing.html.twig');
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function profile(){
        return $this->render('BackOffice/profile.html.twig');
    }

    /**
     * @Route("/signin", name="sign_in")
     */
    public function signIn(){
        return $this->render('BackOffice/sign_in.html.twig');
    }

    /**
     * @Route("/signup", name="sign_up")
     */
    public function signUp(){
        return $this->render('BackOffice/sign_up.html.twig');
    }

    /**
     * @Route("/Tasks", name="tasks")
     */
    public function tasks(){
        //$tasks=$this->getDoctrine()->getRepository(Task::class)->createQueryBuilder('t')->where('t.isDeleted=0')->orderBy('t.createdAt','desc')->getQuery()->getResult();
        //$categories=$this->getDoctrine()->getRepository(TaskCategory::class)->createQueryBuilder('tc')->where('tc.isDeleted=0')->orderBy('tc.createdAt','desc')->getQuery()->getResult();
        $categories = $this->getDoctrine()->getRepository(Task::class)->createQueryBuilder(' t ')
            ->addSelect('tc.catId, tc.name, tc.imgUrl,count(t) as totalTasks')->join('t.cat','tc')
            ->where('t.cat=tc.catId')
            ->andWhere('tc.isDeleted=0')->andWhere('t.isDeleted=0')->groupBy('tc.catId ')->orderBy('tc.createdAt', 'desc')
            ->getQuery()->getResult();

        $tasks = $this->getDoctrine()->getRepository(Task::class)->createQueryBuilder(' t ')
            ->join('t.cat','tc')->where('t.cat=tc.catId')
            ->andWhere('tc.isDeleted=0')->andWhere('t.isDeleted=0')->orderBy('t.createdAt', 'desc')
            ->getQuery()->getResult();

        return $this->render('BackOffice/tasks.html.twig', ['tasks'=>$tasks,'categories'=>$categories]);
        // return $this->render('admin_page/tasks.html.twig');
    }
    /**
     * @Route("/sessions", name="sessions")
     */
    public function session(){
        $session=$this->getDoctrine()->getRepository(Session::class)->createQueryBuilder('s')->where('s.isDeleted=0')->orderBy('s.createdAt','desc')->getQuery()->getResult();
        //  $categories=$this->getDoctrine()->getRepository(TaskCategory::class)->createQueryBuilder('tc')->where('tc.isDeleted=0')->orderBy('tc.createdAt','desc')->getQuery()->getResult();

        return $this->render('BackOffice/session.html.twig', ['sessions'=>$session]);
        // return $this->render('admin_page/tasks.html.twig');
    }

    /**
     * @Route("/Events", name="events")
     */
    public function events(){
        $events=$this->getDoctrine()->getRepository(Event::class)->createQueryBuilder('e')->where('e.isDeleted=0')->orderBy('e.createdAt','desc')->getQuery()->getResult();
        $categories=$this->getDoctrine()->getRepository(EventCategory::class)->createQueryBuilder('ec')->where('ec.isDeleted=0')->orderBy('ec.createdAt','desc')->getQuery()->getResult();

        return $this->render('BackOffice/events.html.twig', ['events'=>$events,'categories'=>$categories]);
        // return $this->render('BackOffice/events.html.twig');
    }

    /**
     * @Route("/recipesPage", name="recipesPage")
     */
    public function recipes(){
        $recipes=$this->getDoctrine()->getRepository(Recipe::class)->createQueryBuilder('r')->where('r.isDeleted=0')->orderBy('r.createdAt','desc')->getQuery()->getResult();
        $categories=$this->getDoctrine()->getRepository(RecipeCategory::class)->createQueryBuilder('rc')->where('rc.isDeleted=0')->orderBy('rc.createdAt','desc')->getQuery()->getResult();
        return $this->render('BackOffice/recipesPage.html.twig', ['recipes'=>$recipes,'categories'=>$categories]);
    }

    /**
     * @Route("/deletesesssionadmin/{id}", name="delete_Sessionadmin")
     */
    public function deleteadmin(int $id)
    {
        $Session = $this->getDoctrine()->getRepository(Session::class)->find($id);
        $Session->setIsDeleted(1);
        $em = $this->getDoctrine()->getManager();
        $em->persist($Session);
        $em->flush();
        return $this->redirectToRoute("sessions");
    }
}
