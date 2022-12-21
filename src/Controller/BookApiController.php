<?php

namespace App\Controller;


use App\Entity\Book;
use App\Entity\BookCategory;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Hshn\Base64EncodedFile\HttpFoundation\File\Base64EncodedFile;
use Hshn\Base64EncodedFile\HttpFoundation\File\UploadedBase64EncodedFile;

class BookApiController extends AbstractController
{
    /**
     * @Route("/api/books", name="book_list_api")
     */
    public function list(NormalizerInterface $normalizer)
    {

        $books = $this->getDoctrine()->getRepository(Book::class)->findBy(['isDeleted'=>0],['createdAt'=>'DESC']);



        $data=$normalizer->normalize($books, 'json',['groups'=>'post:read']);

        return new Response(json_encode($data));
    }


    /**
     * @Route("/api/addBook", name="Book_add_api")
     */
    public function add(Request $request,SerializerInterface $serializer)
    {
        $content=$request->getContent();
        $book=$serializer->deserialize($content,Book::class,'json');
        $parameters = json_decode($content, true);
        $upload_pdf=$this->getParameter('pdf_directory_book');
        $filenamepdf = md5(uniqid()) . '.' .'pdf';
        $file1=new UploadedBase64EncodedFile(new Base64EncodedFile($parameters['filepdf']));
        $file1->move(
            $upload_pdf,
            $filenamepdf
        );
        $uploads_directory = $this->getParameter('images_directory_book');
        $filename = md5(uniqid()) . '.' .$parameters['ext'];
        $file=new UploadedBase64EncodedFile(new Base64EncodedFile($parameters['file']));
        $file->move(
            $uploads_directory,
            $filename
        );
        $fs=new Filesystem();
        $fs->mirror($this->getParameter('images_directory_book'), '../../CoHeal-Desktop/src/coheal/resources/images/books');
        $fs->mirror($this->getParameter('pdf_directory_book'), '../../CoHeal-Desktop/src/coheal/resources/images/bookfiles');
        $user=$this->getDoctrine()->getRepository(User::class)->find($parameters['user']);
        $cat=$this->getDoctrine()->getRepository(BookCategory::class)->find($parameters['cat']);
        $book->setCat($cat);
        $book->setUser($user);
        $book->setCreatedAt(new \DateTime());
        $book->setModifiedAt(new \DateTime());
        // $book->setIsDeleted(false);
        $book->setImgUrl($filename);
        $book->setFilePath($filenamepdf);
        $em=$this->getDoctrine()->getManager();
        $em->persist($book);
        $em->flush();
        return new Response("Book added successfully");
    }

    /**
     * @Route("/api/updateBook/{id}", name="book_update_api")
     */
    public function update(Request $request,SerializerInterface $serializer,int $id)
    {
        $book=$this->getDoctrine()->getRepository(Book::class)->find($id);
        $content=$request->getContent();
        $parameters = json_decode($content, true);
        //$user=$this->getDoctrine()->getRepository(User::class)->find($parameters['user']);
        // $cat=$this->getDoctrine()->getRepository(BookCategory::class)->find($parameters['cat']);
        // $book->setCat($cat);
        // $book->setUser($user);
        $book->setTitle($parameters['title']);
        $book->setAuthor($parameters['author']);
        $book->setDescription($parameters['description']);
        $book->setModifiedAt(new \DateTime());
        $em=$this->getDoctrine()->getManager();
        $em->persist($book);
        $em->flush();
        return new Response("Book modified successfully");

    }
    /**
     * @Route("/api/updateViews/{id}", name="book_updateviews_api")
     */
    public function updateViews(Request $request,int $id)
    {
        $book=$this->getDoctrine()->getRepository(Book::class)->find($id);
        $content=$request->getContent();
        $parameters = json_decode($content, true);
        //$user=$this->getDoctrine()->getRepository(User::class)->find($parameters['user']);
        // $cat=$this->getDoctrine()->getRepository(BookCategory::class)->find($parameters['cat']);
        // $book->setCat($cat);
        // $book->setUser($user);
        $book->setViews($parameters['views']);

        $em=$this->getDoctrine()->getManager();
        $em->persist($book);
        $em->flush();
        return new Response("Book modified successfully");

    }

    /**
     * @Route("/api/deleteBook/{id}", name="book_delete_api")
     */
    public function delete(Request $request,NormalizerInterface $normalizer,int $id)
    {
        $book=$this->getDoctrine()->getRepository(Book::class)->find($id);
        $book->setIsDeleted(true);
        $book->setDeletedAt(new \DateTime());
        $em=$this->getDoctrine()->getManager();
        $em->flush();

        return new Response("book deleted successfully");
    }


    /**
     * @Route("/api/bookDetails/{id}", name="book_details_api")
     */
    public function details(Request $request,NormalizerInterface $normalizer,int $id)
    {
        $book = $this->getDoctrine()->getRepository(book::class)->findBy(['bookId'=>$id]);
        $jsonContent=$normalizer->normalize( $book, 'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }
}
