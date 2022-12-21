<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Entity\RecipeCategory;
use App\Entity\RecipeLike;
use App\Entity\User;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Hshn\Base64EncodedFile\HttpFoundation\File\Base64EncodedFile;
use Hshn\Base64EncodedFile\HttpFoundation\File\UploadedBase64EncodedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class RecipeApiController extends AbstractController
{
    /**
     * @Route("/api/recipes", name="recipe_list_api")
     */
    public function list(NormalizerInterface $normalizer)
    {
        $recipes=$this->getDoctrine()->getRepository(Recipe::class)->findBy(['isDeleted'=>'0'],['createdAt'=>'DESC']);
        $data=$normalizer->normalize($recipes, 'json',['groups'=>'post:read']);
        return new Response(json_encode($data));
    }

    /**
     * @Route("/api/addRecipe", name="add_recipe_api")
     */
    public function addRecipe(Request $request,SerializerInterface $serializer)
    {
        $content=$request->getContent();
        $data=$serializer->deserialize($content,Recipe::class,'json');
        $parameters = json_decode($content, true);

        $uploads_directory = $this->getParameter('images_directory_recipe');
        $filename = md5(uniqid()) . '.' .$parameters['ext'];
        $file=new UploadedBase64EncodedFile(new Base64EncodedFile($parameters['file']));
        $file->move(
            $uploads_directory,
            $filename
        );
        $fs=new Filesystem();
        $fs->mirror($this->getParameter('images_directory_recipe'), '../../CoHeal-Desktop/src/coheal/resources/images/recipes');
        $user=$this->getDoctrine()->getRepository(User::class)->find($parameters['user']);
        $cat=$this->getDoctrine()->getRepository(RecipeCategory::class)->find($parameters['cat']);
        $data->setUser($user);
        $data->setCat($cat);
        $data->setImgUrl($filename);
        $data->setCreatedAt(new \DateTime());
        $data->setIsDeleted(false);
        $data->setCount(0);

        $em=$this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();

        return new Response("Recipe added successfully");
    }

    /**
     * @Route("/api/recipeDetails/{id}", name="recipe_details_api")
     */
    public function recipeDetails(Request $request,NormalizerInterface $normalizer,int $id)
    {
       $recipe = $this->getDoctrine()->getRepository(Recipe::class)->findBy(['recipeId'=>$id]);
       $jsonContent=$normalizer->normalize($recipe, 'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/api/updateRecipe/{id}", name="update_recipe_api")
     */
    public function updateRecipe(Request $request,SerializerInterface $serializer,int $id)
    {
        $recipe=$this->getDoctrine()->getRepository(Recipe::class)->find($id);
        $content=$request->getContent();
        $parameters = json_decode($content, true);

        $recipe->setTitle($parameters['title']);
        $recipe->setDescription($parameters['description']);
        $recipe->setIngredients($parameters['ingredients']);
        $recipe->setSteps($parameters['steps']);
        $recipe->setDuration($parameters['duration']);
        $recipe->setPersons($parameters['persons']);
        $recipe->setCalories($parameters['calories']);
        $recipe->setModifiedAt(new \DateTime());

        $em=$this->getDoctrine()->getManager();
        $em->persist($recipe);
        $em->flush();

        return new Response("Recipe updated successfully");
    }

    /**
     * @Route("/api/delRecipe/{id}", name="del_recipe_api")
     */
    public function delRecipe(Request $request,NormalizerInterface $normalizer,int $id)
    {
        $recipe=$this->getDoctrine()->getRepository(Recipe::class)->find($id);
        $recipe->setIsDeleted(true);
        $recipe->setDeletedAt(new \DateTime());
        $em=$this->getDoctrine()->getManager();
        $em->flush();

        return new Response("Recipe deleted successfully");
    }


    /**
     * @Route("/api/addLike/{id}/{userId}", name="addLikeApi")
     */
    public function addLike(Recipe $id,User $userId)
    {
        $like = new RecipeLike();
        $like->setUser($userId);
        $like->setRecipe($id);
        $em=$this->getDoctrine()->getManager();
        $em->persist($like);
        $em->flush();

        $id->addLike($like);
        $em=$this->getDoctrine()->getManager();
        $em->persist($id);
        $em->flush();
        return new Response("like added");
    }

    /**
     * @Route("/api/removeLike/{id}/{userId}", name="removeLikeApi")
     */
    public function removedLike(Recipe $id,User $userId)
    {
        $user=$this->getUser();
        $like=$this->getDoctrine()->getRepository(RecipeLike::class)->findOneBy(['user'=>$userId,'recipe'=>$id]);
        $recipe=$this->getDoctrine()->getRepository(Recipe::class)->find($id);
        $recipe->removeLike($like);
        $em=$this->getDoctrine()->getManager();
        $em->remove($like);
        $em->flush();
        return new Response("like removed");
    }

    /**
     * @Route("/api/likes", name="likes")
     */
    public function likes(NormalizerInterface $normalizer)
    {
        $likes=$this->getDoctrine()->getRepository(RecipeLike::class)->findAll();
        $data=$normalizer->normalize($likes, 'json',['groups'=>'post:read']);
        return new Response(json_encode($data));
    }
}
