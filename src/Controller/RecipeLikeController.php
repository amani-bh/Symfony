<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Entity\RecipeLike;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class RecipeLikeController extends AbstractController
{
    /**
     * @Route("/recipe/like", name="recipe_like")
     */
    public function index(): Response
    {
        return $this->render('recipe_like/index.html.twig', [
            'controller_name' => 'RecipeLikeController',
        ]);
    }

    /**
     * @Route("/addLike/{id}", name="addLike")
     */
    public function like(Recipe $id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $like = new RecipeLike();
        $user=$this->getUser();
        $like->setUser($user);
        $like->setRecipe($id);
        $em=$this->getDoctrine()->getManager();
        $em->persist($like);
        $em->flush();

        $id->addLike($like);
        $em=$this->getDoctrine()->getManager();
        $em->persist($id);
        $em->flush();
        $count=$this->getDoctrine()->getRepository(Recipe::class)->find($id);
        return new JsonResponse([
            'count'=>$count
        ]);
    }

    /**
     * @Route("/dislikerecipe/{id}", name="dislike")
     */
    public function dislike($id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user=$this->getUser();
        $like=$this->getDoctrine()->getRepository(RecipeLike::class)->findOneBy(['user'=>$user,'recipe'=>$id]);
        dump($id);
        $recipe=$this->getDoctrine()->getRepository(Recipe::class)->find($id);
        $recipe->removeLike($like);
        $em=$this->getDoctrine()->getManager();
        $em->remove($like);
        $em->flush();
        $count=$this->getDoctrine()->getRepository(Recipe::class)->find($id);
        return new JsonResponse([
            'count'=>$count
        ]);
    }

    /**
     * @Route("/count/{id}", name="count")
     */
    public function count($id, NormalizerInterface $normalizer)
    {
        $count=$this->getDoctrine()->getRepository(Recipe::class)->find($id);
        $data=$normalizer->normalize($count, 'json',['groups'=>'post:read']);
        return new Response(json_encode($data));
    }
}


