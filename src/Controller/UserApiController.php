<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\EventCategory;
use App\Entity\Role;
use App\Entity\User;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class UserApiController extends AbstractController
{
    /**
     * @Route("/api/users", name="users_list_api")
     */
    public function list(NormalizerInterface $normalizer)
    {
        $events=$this->getDoctrine()->getRepository(User::class)->findBy(['isDeleted'=>'0'],['createdAt'=>'DESC']);
        $data=$normalizer->normalize($events, 'json',['groups'=>'post:read']);
        return new Response(json_encode($data));
    }

    /**
     * @Route("/api/addUser", name="add_user_api")
     */
    public function addEvent(Request $request,SerializerInterface $serializer)
    {
        $content=$request->getContent();
        $data=$serializer->deserialize($content,User::class,'json');

        $role =$this->getDoctrine()->getRepository(Role::class)->find(4);
        $data->addRole($role);

        $data->setCreatedAt(new \DateTime());
        $data->setIsDeleted(false);

        $em=$this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();

        return new Response("User added successfully");
    }


    /**
     * @Route("/api/updateUser/{id}", name="update_user_api")
     */
    public function updateUser(Request $request,SerializerInterface $serializer,int $id)
    {
        $user=$this->getDoctrine()->getRepository(User::class)->find($id);
        $content=$request->getContent();
        $parameters = json_decode($content, true);

        $user->setFirstName($parameters['firstName']);
        $user->setLastName($parameters['lastName']);
        $user->setEmail($parameters['email']);
        $user->setDateOfBirth(DateTime::createFromFormat('Y-m-d',$parameters['dateOfBirth']));
        $user->setUpdatedAt(new \DateTime());

        $em=$this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return new Response("User updated successfully");
    }

    /**
     * @Route("/api/updateUserPassword/{id}", name="update_user_password_api")
     */
    public function updateUserPassword(Request $request,SerializerInterface $serializer,int $id)
    {
        $user=$this->getDoctrine()->getRepository(User::class)->find($id);
        $content=$request->getContent();
        $parameters = json_decode($content, true);

        $user->setPassword2($parameters['password2']);

        $em=$this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return new Response("User password updated successfully");
    }


    /**
     * @Route("/api/deleteUser/{id}", name="delete_user_api")
     */
    public function deleteEvent(Request $request,NormalizerInterface $normalizer,int $id)
    {
        $event=$this->getDoctrine()->getRepository(User::class)->find($id);
        $event->setIsDeleted(true);
        $event->setDeletedAt(new \DateTime());
        $em=$this->getDoctrine()->getManager();
        $em->flush();

        return new Response("Event deleted successfully");
    }


    /**
     * @Route("/api/userDetails/{id}", name="user_details_api")
     */
    public function userDetails(Request $request,NormalizerInterface $normalizer,int $id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $jsonContent=$normalizer->normalize($user, 'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

}
