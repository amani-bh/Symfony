<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\BookRate;
use App\Entity\BookReport;
use App\Entity\Event;
use App\Entity\EventRate;
use App\Entity\EventReport;
use App\Entity\Favorite;
use App\Entity\Rate;
use App\Entity\Recipe;
use App\Entity\RecipeRate;
use App\Entity\RecipeReport;
use App\Entity\Report;
use App\Entity\ReportNotification;
use App\Entity\Session;
use App\Entity\SessionRate;
use App\Entity\SessionReport;
use App\Entity\Task;
use App\Entity\TaskRate;
use App\Entity\TaskReport;
use App\Form\RateType;
use App\Form\ReportType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(): Response
    {
        $newBooks=$this->getDoctrine()->getRepository(Book::class)->findBy([],['createdAt'=>'desc'],'3');
        $newTasks=$this->getDoctrine()->getRepository(Task::class)->findBy([],['createdAt'=>'desc'],'3');
        $newEvents=$this->getDoctrine()->getRepository(Event::class)->findBy([],['createdAt'=>'desc'],'3');
        $newRecipes=$this->getDoctrine()->getRepository(Recipe::class)->findBy([],['createdAt'=>'desc'],'3');
        $newSessions=$this->getDoctrine()->getRepository(Session::class)->findBy([],['createdAt'=>'desc'],'3');

        return $this->render('FrontOffice/index.html.twig',['newBooks'=>$newBooks,'newTasks'=>$newTasks,'newEvents'=>$newEvents,'newRecipes'=>$newRecipes,'newSessions'=>$newSessions]);
    }

    /**
     * @Route("/all", name="all")
     */
    public function all()
    {
        $books=$this->getDoctrine()->getRepository(Book::class)->findAll();
        $events=$this->getDoctrine()->getRepository(Event::class)->findAll();
        $recipe=$this->getDoctrine()->getRepository(Recipe::class)->findAll();
        $task=$this->getDoctrine()->getRepository(Task::class)->findAll();
        $session=$this->getDoctrine()->getRepository(Session::class)->findAll();
        $all=array_merge($books,$events,$recipe,$task,$session);
        return $this->render('FrontOffice/all.html.twig', ['allItems'=>$all]);
    }


    /**
     * @Route("/details/{id}", name="details")
     */
    public function details(Request $request, $id){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $type=$request->query->get('type');
        $report=new Report();
        $report->setType($type);
        $report->setCreatedAt(new \DateTime());

        $report->setReporter($user);

        $notification=new ReportNotification();
        $notification->setUser($user);

        switch ($type){
            case 'book':$reported = $this->getDoctrine()->getRepository(Book::class)->find($id);
                $itemReport=new BookReport();
                $itemReport->setBook($reported);
                $notification->setBook($reported);
                break;
            case 'event':$reported = $this->getDoctrine()->getRepository(Event::class)->find($id);
                $itemReport=new EventReport();
                $itemReport->setEvent($reported);
                $notification->setEvent($reported);
                break;
            case 'task':$reported = $this->getDoctrine()->getRepository(Task::class)->find($id);
                $itemReport=new TaskReport();
                $itemReport->setTask($reported);
                $notification->setTask($reported);
                break;
            case 'session':$reported = $this->getDoctrine()->getRepository(Session::class)->find($id);
                $itemReport=new SessionReport();
                $itemReport->setSession($reported);
                $notification->setSession($reported);
                break;
            case 'recipe':$reported = $this->getDoctrine()->getRepository(Recipe::class)->find($id);
                $itemReport=new RecipeReport();
                $itemReport->setRecipe($reported);
                $notification->setRecipe($reported);
                break;
        }


        $report->setTitle($reported->getTitle());

            if ($request->isMethod('POST') && $request->query->get('t')=='report') {
                $note=$request->get('reportText');
                $report->setNote($note);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($report);
                $entityManager->flush();
                $itemReport->setReport($report);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($itemReport);
                $entityManager->flush();
                $notification->setReport($report);
                $notification->setCreatedAt(new \DateTime());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($notification);
                $entityManager->flush();
            }
        $rate=new Rate();
        $rate->setType($type);
        $rate->setCreatedAt(new \DateTime());
        $rate->setUser($user);

        switch ($type){
            case 'book':$rated = $this->getDoctrine()->getRepository(Book::class)->find($id);
                $itemRate=new BookRate();
                $itemRate->setBook($rated);
                break;
            case 'event':$rated = $this->getDoctrine()->getRepository(Event::class)->find($id);
                $itemRate=new EventRate();
                $itemRate->setEvent($rated);
                break;
            case 'task':$rated = $this->getDoctrine()->getRepository(Task::class)->find($id);
                $itemRate=new TaskRate();
                $itemRate->setTask($rated);
                break;
            case 'session':$rated = $this->getDoctrine()->getRepository(Session::class)->find($id);
                $itemRate=new SessionRate();
                $itemRate->setSession($rated);
                break;
            case 'recipe':$rated = $this->getDoctrine()->getRepository(Recipe::class)->find($id);
                $itemRate=new RecipeRate();
                $itemRate->setRecipe($rated);
                break;
        }

        if ($request->isMethod('POST') && $request->query->get('t')=='rate') {
            $rate->setScore($request->get('rate'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rate);
            $entityManager->flush();
            $itemRate->setRate($rate);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($itemRate);
            $entityManager->flush();
        }
        switch ($type){
            case 'book':$item=$this->getDoctrine()->getRepository(Book::class)->find($id);
                break;
            case 'event':$item=$this->getDoctrine()->getRepository(Event::class)->find($id);
                break;
            case 'task':$item=$this->getDoctrine()->getRepository(Task::class)->find($id);
                break;
            case 'session':$item=$this->getDoctrine()->getRepository(Session::class)->find($id);
                break;
            case 'recipe':$item=$this->getDoctrine()->getRepository(Recipe::class)->find($id);
                break;
        }
        $favorite=$this->getDoctrine()->getRepository(Favorite::class)->findOneBy([
            'user' => $user,
            $type => $id,
        ]);
        return $this->render('FrontOffice/details.html.twig',['item'=>$item,'fav'=>$favorite]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function about(){
        return $this->render('FrontOffice/about.html.twig');
    }

}
