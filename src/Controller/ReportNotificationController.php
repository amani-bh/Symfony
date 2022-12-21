<?php

namespace App\Controller;

use App\Entity\ReportNotification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportNotificationController extends AbstractController
{
    /**
     * @Route("/unread-count", name="unread_count")
     */
    public function unreadCount()
    {
        $unreadNotification=$this->getDoctrine()->getRepository(ReportNotification::class)->findBy(['seenByAdmin'=>'0', 'closed'=>'0'], ['createdAt'=>'asc']);
        $count=count($unreadNotification);

        return new JsonResponse([
            'unread'=>$unreadNotification,
            'count'=>$count
        ]);
    }

    /**
     * @Route("/unread-count-user", name="unread_count_user")
     */
    public function unreadCountUser()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $unreadNotification=$this->getDoctrine()->getRepository(ReportNotification::class)->findBy(['user'=>$this->getUser(), 'seenByUser'=>'0', 'closed'=>'1'], ['createdAt'=>'asc']);
        $count=count($unreadNotification);

        return new JsonResponse([
            'unread'=>$unreadNotification,
            'count'=>$count
        ]);
    }

    /**
     * @Route("/mark-all", name="markall")
     */
    public function markAllAsRead()
    {
        $notif=$this->getDoctrine()->getRepository(ReportNotification::class)->markAllAsSeen();
        return $this->redirectToRoute('moderation');
    }

    /**
     * @Route("/mark-all-user", name="markall_user")
     */
    public function markAllAsReadUser()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $notif=$this->getDoctrine()->getRepository(ReportNotification::class)->markAllAsSeenUser($this->getUser());
        return $this->redirectToRoute('home');
    }
}
