<?php

namespace App\Controller;

use App\Entity\RecipeCategory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class RecipeCategoryApiController extends AbstractController
{
    /**
     * @Route("/api/recipeCategory", name="recipe_category_api")
     */
    public function index(NormalizerInterface $normalizer)
    {
        $categories=$this->getDoctrine()->getRepository(RecipeCategory::class)->findBy(['isDeleted'=>'0'],['createdAt'=>'DESC']);
        $jsonContent=$normalizer->normalize($categories, 'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/api/getRecipeCategory/{name}", name="recipe_category_get_api")
     */
    public function categories(NormalizerInterface $normalizer,string $name)
    {
        $categories=$this->getDoctrine()->getRepository(RecipeCategory::class)->findBy(['name'=>$name]);
        $jsonContent=$normalizer->normalize($categories, 'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }
}
