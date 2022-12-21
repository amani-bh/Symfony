<?php

namespace App\Controller;

use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController
{
    /**
     * @Route("/calendar", name="calendar")
     */
    public function index(): Response
    {
        return $this->render('calendar/index.html.twig', [
            'controller_name' => 'CalendarController',
        ]);
    }



    /**
     * @Route("/calendar", name="calendar")
     */
    public function success(): Response
    {
        $now= new \DateTime();
        $events=$this->getDoctrine()->getRepository(Event::class)->createQueryBuilder('t')
            ->where('t.isDeleted=0')->getQuery()->getResult();
        $rdvs = [];

        foreach($events as $event){
            $rdvs[] = [
                'eventId' => $event->getEventId(),
                'start' => $event->getStartDate()->format('Y-m-d'),
                'end' => $event->getEndDate()->format('Y-m-d'),
                'title' => $event->getTitle(),
            ];
        }

        $data = json_encode($rdvs);

        return $this->render('calendar/calendar.html.twig', compact('data'));
    }
}
