<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\BookCategory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


class BookCategoryApiController extends AbstractController
{
    /**
     * @Route("/api/bookCategory", name="book_category_api")
     */
    public function index(NormalizerInterface $normalizer)
    {
        $categories=$this->getDoctrine()->getRepository(BookCategory::class)->findBy(['isDeleted'=>'0'],['createdAt'=>'DESC']);
        $jsonContent=$normalizer->normalize($categories, 'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/api/getBookCategory/{name}", name="book_category_get_api")
     */
    public function cat(NormalizerInterface $normalizer,string $name)
    {
        $categories=$this->getDoctrine()->getRepository(BookCategory::class)->findBy(['name'=>$name]);
        $jsonContent=$normalizer->normalize($categories, 'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }
}
