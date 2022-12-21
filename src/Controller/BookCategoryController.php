<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Form\BookCategorieType;
use App\Repository\BookCategoryRepository;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\FileType;

class BookCategoryController extends AbstractController
{
    /**
     * @Route("/book/category", name="book_category")
     */
    public function index(): Response
    {
        return $this->render('book_category/index.html.twig', [
            'controller_name' => 'BookCategoryController',
        ]);
    }


    /**
     * @Route("/bookBack", name="bookBack")
     */
    public function liste()
    {$repository = $this->getDoctrine()->getrepository(Book::Class);//recuperer repisotory
        $books = $repository->findBy(array('isDeleted' => '0'));//affichage
        $rep= $this->getDoctrine()->getrepository(Book::Class);
        $views=$rep->findBy(
            array('isDeleted' => '0'),
            array('views' => 'DESC'),
            5,
            0

        );

        $repository = $this->getDoctrine()->getrepository(BookCategory::Class);//recuperer repisotory
        $cats = $repository->findBy(array('isDeleted' => '0'));//affichage
        return $this->render('BackOffice/book/bookBack.html.twig', [
            'cats' => $cats, 'books' =>$books,'views' => $views,
        ]);
    }


    /**
     * @Route("/supp/{catId}", name="dc")
     */
    public function supprimer ($catId)
    {
        $bookcat=$this->getDoctrine()->getRepository(BookCategory::class)->find($catId);
        $bookcat->setIsDeleted(true);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        return $this->redirectToRoute('bookBack');

    }
    /**
     * @Route("/modifierCat/{catId}", name="uc")
     */
    function modifier(BookCategoryRepository $repository,Request $request,$catId)
    {
        $cat=$repository->find($catId);
        $form = $this->createForm(BookCategorieType::class, $cat);

        $form->remove('imgUrl');

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('bookBack');
        }
        return $this->render('BackOffice/book/modifierCat.html.twig',[
            'form'=>$form->createView()
        ]);


    }
    /**
     * @Route("/AjouterBookCat", name="AjouterCat")
     */
    public function ajouter(Request $request)
    {
        $bookCat = new BookCategory();//creation instance

        $bookCat->setCreatedAt(new \DateTime());
        $bookCat->setModifiedAt(new \DateTime());

        $form = $this->createForm(BookCategorieType::class, $bookCat);//Récupération du formulaire dans le contrôleur:

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $bookCat->getImgUrl();
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            echo  $filename;
            try {
                $file->move(
                    $this->getParameter('images_directory_bookCat'),
                    $filename
                );
            } catch (FileException $e) {
            }
            $fs=new Filesystem();
            $fs->mirror($this->getParameter('images_directory_bookCat'), '../../CoHeal-Desktop/src/coheal/resources/images/bookCat');
            $em = $this->getDoctrine()->getManager();//recupuration entity manager
            $bookCat->setImgUrl($filename);
            $em->persist($bookCat);//l'ajout de la objet cree
            $em->flush();
            return $this->redirectToRoute('bookBack');//redirecter la pagee la page dafichage
        }

        return $this->render('BackOffice/book/ajouterBookCate.html.twig', [
            'form' => $form->createview()
        ]);

    }

}
