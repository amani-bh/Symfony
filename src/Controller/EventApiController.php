<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\EventCategory;
use App\Entity\User;
use DateTime;
use Hshn\Base64EncodedFile\HttpFoundation\File\Base64EncodedFile;
use Hshn\Base64EncodedFile\HttpFoundation\File\UploadedBase64EncodedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class EventApiController extends AbstractController
{
    /**
     * @Route("/api/events", name="event_list_api")
     */
    public function list(NormalizerInterface $normalizer)
    {
        $events=$this->getDoctrine()->getRepository(Event::class)->findBy(['isDeleted'=>'0'],['createdAt'=>'DESC']);
        $data=$normalizer->normalize($events, 'json',['groups'=>'post:read']);
        return new Response(json_encode($data));
    }


    /**
     * @Route("/api/eventsByStartDate/{date}", name="event_list_date_api")
     */
    public function listByDate(NormalizerInterface $normalizer,string $date)
    {
        $d = new \DateTime($date);
        $d->format("Y-m-d");
        $events=$this->getDoctrine()->getRepository(Event::class)->createQueryBuilder('e')->where('e.isDeleted =0')->andWhere('e.startDate like ?1')->setParameter(1, $d->format("Y-m-d"))->getQuery()->getResult();
        $data=$normalizer->normalize($events, 'json',['groups'=>'post:read']);
        return new Response(json_encode($data));
    }

    /**
     * @Route("/api/addEvent", name="add_event_api")
     */
    public function addEvent(Request $request,SerializerInterface $serializer)
    {
        $content=$request->getContent();
        $data=$serializer->deserialize($content,Event::class,'json');
        $parameters = json_decode($content, true);

        $uploads_directory = $this->getParameter('images_directory_event');
        $filename = md5(uniqid()) . '.' .$parameters['ext'];
        $file=new UploadedBase64EncodedFile(new Base64EncodedFile($parameters['file']));
        $file->move(
            $uploads_directory,
            $filename
        );
        $fs=new Filesystem();
        $fs->mirror($this->getParameter('images_directory_event'), '../../CoHeal-Desktop/src/coheal/resources/images/events');
        $user=$this->getDoctrine()->getRepository(User::class)->find($parameters['u']);
        $cat=$this->getDoctrine()->getRepository(EventCategory::class)->find($parameters['cat']);
        $data->setU($user);
        $data->setCat($cat);
        $data->setCreatedAt(new \DateTime());
        $data->setIsDeleted(false);
        $data->setImgUrl($filename);

        $em=$this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();

        return new Response("Event added successfully");
    }

    /**
     * @Route("/api/eventDetails/{id}", name="event_details_api")
     */
    public function eventDetails(Request $request,NormalizerInterface $normalizer,int $id)
    {
        $event = $this->getDoctrine()->getRepository(Event::class)->findBy(['eventId'=>$id]);
        $jsonContent=$normalizer->normalize($event, 'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/api/updateEvent/{id}", name="update_event_api")
     */
    public function updateEvente(Request $request,SerializerInterface $serializer,int $id)
    {
        $event=$this->getDoctrine()->getRepository(Event::class)->find($id);
        $content=$request->getContent();
        $parameters = json_decode($content, true);

        $user=$this->getDoctrine()->getRepository(User::class)->find($parameters['u']);
        $cat=$this->getDoctrine()->getRepository(EventCategory::class)->find($parameters['cat']);
        $event->setU($user);
        $event->setCat($cat);
        $event->setTitle($parameters['title']);
        $event->setDescription($parameters['description']);
        $event->setType($parameters['title']);
        $event->setPrice($parameters['price']);
        $event->setLocation($parameters['location']);
        $event->setStartDate(DateTime::createFromFormat('Y-m-d',$parameters['startDate']));
        $event->setEndDate(DateTime::createFromFormat('Y-m-d',$parameters['endDate']));
        $event->setModifiedAt(new \DateTime());


        $em=$this->getDoctrine()->getManager();
        $em->persist($event);
        $em->flush();

        return new Response("Event updated successfully");
    }

    /**
     * @Route("/api/deleteEvent/{id}", name="delete_event_api")
     */
    public function deleteEvent(Request $request,NormalizerInterface $normalizer,int $id)
    {
        $event=$this->getDoctrine()->getRepository(Event::class)->find($id);
        $event->setIsDeleted(true);
        $event->setDeletedAt(new \DateTime());
        $em=$this->getDoctrine()->getManager();
        $em->flush();

        return new Response("Event deleted successfully");
    }

    /**
     * @Route("/api/stat", name="stat", methods={"GET"})
     */

    public function StatAction()
    {
        $statistique=$this->getDoctrine()->getRepository(Event::class)->statistique_abo();
        $serializer = new Serializer( [new ObjectNormalizer()]);
        $formated = $serializer->normalize($statistique);
        return new JsonResponse($formated);
    }

}
