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
use App\Form\ReportType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    /**
     * @Route("/report/{reportedId}", name="report", methods={"GET","POST"})
     */
    public function new(Request $request, $reportedId): Response
    {
        $type=$request->query->get('type');
        $report=new Report();
        $report->setType($type);
        $report->setCreatedAt(new \DateTime());

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $report->setReporter($user);

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

        if ($form->isSubmitted() && $form->isValid()) {
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

            return $this->redirectToRoute('all');
        }

        return $this->render('FrontOffice/details.html.twig', [
            'report' => $report,
            'form' => $form->createView(),
        ]);
    }
}
