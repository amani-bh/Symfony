<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Entity\RecipeCategory;
use App\Entity\Session;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class SessionApiController extends AbstractController
{
    /**
     * @Route("/api/sessions", name="session_list_api")
     */
    public function list(NormalizerInterface $normalizer)
    {
        $recipes = $this->getDoctrine()->getRepository(Session::class)->findBy(['isDeleted' => '0'], ['createdAt' => 'DESC']);
        $data = $normalizer->normalize($recipes, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($data));
    }

    /**
     * @Route("/api/addSession", name="add_session_api")
     */
    public function addSession(Request $request, SerializerInterface $serializer)
    {
        $content = $request->getContent();
        $data = $serializer->deserialize($content,Session::class, 'json');
        $parameters = json_decode($content, true);

        $user = $this->getDoctrine()->getRepository(User::class)->find($parameters['therp']);
        $data->setTherp($user);

        $data->setIsDeleted(false);
        $data->setCreatedAt(new \DateTime());


        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();

        return new Response("Session added successfully");
    }

    /**
     * @Route("/api/sessionDetails/{id}", name="session_details_api")
     */
    public function SessionDetails(Request $request, NormalizerInterface $normalizer, int $id)
    {
        $recipe = $this->getDoctrine()->getRepository(Session::class)->findBy(['sessionId'=>$id]);
        $jsonContent = $normalizer->normalize($recipe, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/api/updateSession/{id}", name="update_session_api")
     */
    public function updateSession(Request $request, SerializerInterface $serializer, int $id)
    {
        $session = $this->getDoctrine()->getRepository(Session::class)->find($id);
        $content = $request->getContent();
        $parameters = json_decode($content, true);

        $user = $this->getDoctrine()->getRepository(User::class)->find($parameters['therp']);
        $session->setTherp($user);
        $session->setTitle($parameters['title']);
        $session->setDescription($parameters['description']);
        $session->setNumOfDays($parameters['numOfDays']);

        $session->setModifiedAt(new \DateTime());


        $em = $this->getDoctrine()->getManager();
        $em->persist($session);
        $em->flush();

        return new Response("Recipe updated successfully");
    }

    /**
     * @Route("/api/deleteSession/{id}", name="delete_recipe_api")
     */
    public function deleteSession(Request $request, NormalizerInterface $normalizer, int $id)
    {
        $recipe = $this->getDoctrine()->getRepository(Session::class)->find($id);
        $recipe->setIsDeleted(true);
        $recipe->setDeletedAt(new \DateTime());
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return new Response("Recipe deleted successfully");
    }
}