<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Book;
use App\Entity\BookCategory;
use App\Entity\User;
use App\Form\BookType;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Knp\Component\Pager\PaginatorInterface;
use  Symfony\Component\Form\Extension\Core\Type\FileType;

class BookController extends AbstractController
{
    /**
     * @Route("/book", name="book")
     */
    public function liste(PaginatorInterface $paginator,Request $request)
    { $repository = $this->getDoctrine()->getrepository(Book::Class)->findBy(array('isDeleted' => '0'));;//recuperer repisotory

       // $books = $repository->findBy(array('isDeleted' => '0'));//affichage
        $books = $paginator->paginate(
            $repository,
            $request->query->getInt('page', 1), 9
        );


        $repository = $this->getDoctrine()->getrepository(BookCategory::Class);//recuperer repisotory
        $cats = $repository->findBy(array('isDeleted' => '0'));//affichage
        return $this->render('FrontOffice/book/book.html.twig', [
            'books' => $books,'cats' => $cats,'paginator' => $paginator,
        ]);

    }


    /**
     * @Route("/bookdetails/{bookId}", name="bookdetails")
     */
    public function listedetails($bookId)
    { $repository = $this->getDoctrine()->getrepository(Book::Class);//recuperer repisotory

        $book = $repository->findBy(
            ['bookId' => $bookId]
        );
        foreach ($book as $b ){

        $b->setViews( $b->getViews()+1);
            $em = $this->getDoctrine()->getManager();//recupuration entity manager
            $em->persist($b);//l'ajout de la objet cree
            $em->flush();
        }



        return $this->render('FrontOffice/book/details.html.twig', [
            'book' => $book,
        ]);
    }
    /**
     * @Route("/bookfilter/{catId}", name="bookfilter")
     */
    public function listefilters($catId)
    {  $repository = $this->getDoctrine()->getrepository(Book::Class);//recuperer repisotory

        $books = $repository->findBy(
            ['cat' => $catId ,
                'isDeleted' => '0']
        );

        $repository = $this->getDoctrine()->getrepository(BookCategory::Class);//recuperer repisotory
        $cats = $repository->findBy(array('isDeleted' => '0'));//affichage

        return $this->render('FrontOffice/book/bookByCat.html.twig', [
            'books' => $books,'cats' => $cats,
        ]);
    }



    /**
     * @Route("/bookfilterBack/{catId}", name="bookfilterBack")
     */
    public function listefiltersBackoffice($catId)
    {  $repository = $this->getDoctrine()->getrepository(Book::Class);//recuperer repisotory

        $books = $repository->findBy(
            ['cat' => $catId ,
                'isDeleted' => '0']
        );
         $nbr=0;
        foreach ($books as $b ){

            $nbr++;
        }

        return $this->render('BackOffice/book/bookByCatBack.html.twig', [
            'books' => $books, 'nbr' =>$nbr
        ]);
    }


    /**
     * @Route("/supp/{bookId}", name="d")
     */
    public function supprimer ($bookId)
    {
        $book=$this->getDoctrine()->getRepository(Book::class)->find($bookId);
        $book->setIsDeleted('1');
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        return $this->redirectToRoute('book');

    }





    /**
     * @Route("/modifier/{bookId}", name="u")
     */
    function modifier(BookRepository  $repository,Request $request,$bookId)
    {
        $Book=$repository->find($bookId);
        $form = $this->createForm(BookType::class, $Book);
        $form->remove('cat');
        $form->remove('filePath');
        $form->remove('imgUrl');

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('book');
        }
        return $this->render('FrontOffice/book/modifier.html.twig',[
            'form'=>$form->createView()
        ]);


    }




    /**
     *
     * @Route("/SearchBook ", name="SearchBook")
     */
    public function searchBook(Request $request,NormalizerInterface $Normalizer)
    {

        $repository = $this->getDoctrine()->getRepository(Book::class);
        $requestString=$request->get('searchValue');

        $books = $repository->findByTitle($requestString);


        $jsonContent = $Normalizer->normalize($books, 'json',['groups'=>'BS']);
        $retour=json_encode($jsonContent);
        return new Response($retour);

    }


    /**
     *
     * @Route("/SearchBookBack ", name="SearchBookBack")
     */
    public function searchBookBackOffice(Request $request,NormalizerInterface $Normalizer)
    {

        $repository = $this->getDoctrine()->getRepository(Book::class);
        $requestString=$request->get('searchValueBack');

        $books = $repository->findByTitle($requestString);


        $jsonContent = $Normalizer->normalize($books, 'json',['groups'=>'BS']);
        $retour=json_encode($jsonContent);
        return new Response($retour);

    }



    /**
     * @Route("/AjouterBook", name="Ajoute")
     */
    public function ajouter(Request $request)
    {
        $book = new Book();//creation instance

        $book->setCreatedAt(new \DateTime());
        $book->setModifiedAt(new \DateTime());
        $bc=$this->getDoctrine()->getRepository(BookCategory::Class)->find(1);
        $book->setcat($bc);
        $user=$this->getDoctrine()->getRepository(User::Class)->find($this->getUser());
        $book->setUser($user);
        $form = $this->createForm(BookType::class, $book);//Récupération du formulaire dans le contrôleur:

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $book->getImgUrl();
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            echo  $filename;
            try {
                $file->move(
                    $this->getParameter('images_directory_book'),
                    $filename
                );
            } catch (FileException $e) {
            }
            $fs=new Filesystem();
            $fs->mirror($this->getParameter('images_directory_book'), '../../CoHeal-Desktop/src/coheal/resources/images/books');

            $em = $this->getDoctrine()->getManager();//recupuration entity manager
            $book->setImgUrl($filename);
            $em->persist($book);//l'ajout de la objet cree
            $em->flush();
            return $this->redirectToRoute('book');//redirecter la pagee la page dafichage
        }

        return $this->render('FrontOffice/book/ajoutebBook.html.twig', [
            'form' => $form->createview()
        ]);

    }

}
