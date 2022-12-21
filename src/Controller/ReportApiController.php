<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\BookReport;
use App\Entity\Event;
use App\Entity\EventReport;
use App\Entity\Recipe;
use App\Entity\RecipeReport;
use App\Entity\Report;
use App\Entity\ReportNotification;
use App\Entity\Session;
use App\Entity\SessionReport;
use App\Entity\Task;
use App\Entity\TaskReport;
use App\Entity\User;
use App\Form\ReportType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportApiController extends AbstractController
{
    /**
     * @Route("/api/report/{reportedId}/{type}/{note}/{user}", name="report_api")
     */
    public function add(Request $request, $reportedId, $type,$note,User $user)
    {
        $report=new Report();
        $report->setType($type);
        $report->setCreatedAt(new \DateTime());

        $report->setReporter($user);
        $report->setNote($note);
        $notification=new ReportNotification();
        $notification->setUser($user);

        switch ($type){
            case 'book':$reported = $this->getDoctrine()->getRepository(Book::class)->find($reportedId);
                $itemReport=new BookReport();
                $itemReport->setBook($reported);
                $notification->setBook($reported);
                break;
            case 'event':$reported = $this->getDoctrine()->getRepository(Event::class)->find($reportedId);
                $itemReport=new EventReport();
                $itemReport->setEvent($reported);
                $notification->setEvent($reported);
                break;
            case 'task':$reported = $this->getDoctrine()->getRepository(Task::class)->find($reportedId);
                $itemReport=new TaskReport();
                $itemReport->setTask($reported);
                $notification->setTask($reported);
                break;
            case 'session':$reported = $this->getDoctrine()->getRepository(Session::class)->find($reportedId);
                $itemReport=new SessionReport();
                $itemReport->setSession($reported);
                $notification->setSession($reported);
                break;
            case 'recipe':$reported = $this->getDoctrine()->getRepository(Recipe::class)->find($reportedId);
                $itemReport=new RecipeReport();
                $itemReport->setRecipe($reported);
                $notification->setRecipe($reported);
                break;
        }


        $report->setTitle($reported->getTitle());
        $form = $this->createForm(ReportType::class, $report);
        $form->handleRequest($request);

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

        return new Response("Reported");

    }

}
