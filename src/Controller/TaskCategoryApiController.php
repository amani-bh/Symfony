<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\TaskCategory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TaskCategoryApiController extends AbstractController
{
    /**
     * @Route("/api/taskCategory", name="task_category_api")
     */
    public function index(NormalizerInterface $normalizer)
    {
        $categories=$this->getDoctrine()->getRepository(TaskCategory::class)->findBy(['isDeleted'=>'0'],['createdAt'=>'DESC']);
        $jsonContent=$normalizer->normalize($categories, 'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/api/getTaskCategory/{name}", name="task_category_get_api")
     */
    public function cat(NormalizerInterface $normalizer,string $name)
    {
        $categories=$this->getDoctrine()->getRepository(TaskCategory::class)->findBy(['name'=>$name]);
        $jsonContent=$normalizer->normalize($categories, 'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }
}
