<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Event;
use App\Entity\Favorite;
use App\Entity\Recipe;
use App\Entity\Session;
use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class FavoriteController extends AbstractController
{

    /**
     * @Route("/favorite/{type}/{itemId}", name="favorite")
     */
    public function favorite($type, $itemId)
    {
        $favorite=new Favorite();

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $favorite->setUser($user);
        switch ($type){
            case 'book':$item=$this->getDoctrine()->getRepository(Book::class)->find($itemId);
                $favorite->setBook($item);
                break;
            case 'event':$item=$this->getDoctrine()->getRepository(Event::class)->find($itemId);
                $favorite->setEvent($item);
                break;
            case 'task':$item=$this->getDoctrine()->getRepository(Task::class)->find($itemId);
                $favorite->setTask($item);
                break;
            case 'session':$item=$this->getDoctrine()->getRepository(Session::class)->find($itemId);
                $favorite->setSession($item);
                break;
            case 'recipe':$item=$this->getDoctrine()->getRepository(Recipe::class)->find($itemId);
                $favorite->setRecipe($item);
                break;
        }

        $favorite->setType($type);
        $favorite->setCreatedAt(new \DateTime());
        $em=$this->getDoctrine()->getManager();
        $em->persist($favorite);
        $em->flush();

        $this->addFlash("success", "Added to favorites");

        return new JsonResponse([
            'fav'=>$favorite
        ]);
    }

    /**
     * @Route("/unfavorite/{type}/{itemId}", name="unfavorite")
     */
    public function unfavorite($type, $itemId)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $favorite=$this->getDoctrine()->getRepository(Favorite::class)->findOneBy([
            'user' => $user,
            $type => $itemId,
        ]);
        $em=$this->getDoctrine()->getManager();
        $em->remove($favorite);
        $em->flush();

        $this->addFlash("success", "Removed from favorites");

        return new JsonResponse([
            'fav'=>'done'
        ]);
    }

    /**
     * @Route("/myfavorites", name="myfavorites")
     */
    public function myFavorites()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $myFavorite=$this->getDoctrine()->getRepository(Favorite::class)->findBy([
            'user' => $user,
        ]);

        return $this->render('FrontOffice/my_favorites.html.twig',['myfav'=>$myFavorite]);
    }
}
