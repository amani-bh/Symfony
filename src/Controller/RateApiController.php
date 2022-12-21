<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\BookRate;
use App\Entity\Event;
use App\Entity\EventRate;
use App\Entity\Favorite;
use App\Entity\Rate;
use App\Entity\Recipe;
use App\Entity\RecipeRate;
use App\Entity\Session;
use App\Entity\SessionRate;
use App\Entity\Task;
use App\Entity\TaskRate;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RateApiController extends AbstractController
{
    /**
     * @Route("/api/rate/{id}/{type}/{score}/{user}", name="rate_api")
     */
    public function add(Request $request, $id, $type,$score,User $user)
    {
        $rate=new Rate();
        $rate->setType($type);
        $rate->setCreatedAt(new \DateTime());
        $rate->setScore($score);
        $rate->setUser($user);

        switch ($type){
            case 'book':$rated = $this->getDoctrine()->getRepository(Book::class)->find($id);
                $itemRate=new BookRate();
                $itemRate->setBook($rated);
                break;
            case 'event':$rated = $this->getDoctrine()->getRepository(Event::class)->find($id);
                $itemRate=new EventRate();
                $itemRate->setEvent($rated);
                break;
            case 'task':$rated = $this->getDoctrine()->getRepository(Task::class)->find($id);
                $itemRate=new TaskRate();
                $itemRate->setTask($rated);
                break;
            case 'session':$rated = $this->getDoctrine()->getRepository(Session::class)->find($id);
                $itemRate=new SessionRate();
                $itemRate->setSession($rated);
                break;
            case 'recipe':$rated = $this->getDoctrine()->getRepository(Recipe::class)->find($id);
                $itemRate=new RecipeRate();
                $itemRate->setRecipe($rated);
                break;
        }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rate);
            $entityManager->flush();
            $itemRate->setRate($rate);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($itemRate);
            $entityManager->flush();

        switch ($type){
            case 'book':$item=$this->getDoctrine()->getRepository(Book::class)->find($id);
                break;
            case 'event':$item=$this->getDoctrine()->getRepository(Event::class)->find($id);
                break;
            case 'task':$item=$this->getDoctrine()->getRepository(Task::class)->find($id);
                break;
            case 'session':$item=$this->getDoctrine()->getRepository(Session::class)->find($id);
                break;
            case 'recipe':$item=$this->getDoctrine()->getRepository(Recipe::class)->find($id);
                break;
        }
        return new Response("Rate added");
    }

    /**
     * @Route("/api/fav/{type}/{itemId}/{user}", name="fav_api")
     */
    public function fav(Request $request, $type, $itemId,User $user)
    {
        $favorite=new Favorite();
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
        return new Response("favorite added");
    }
    /**
     * @Route("/api/unfav/{type}/{itemId}/{user}", name="unfav_api")
     */
    public function unfav(Request $request, $type, $itemId,User $user)
    {
        $favorite=$this->getDoctrine()->getRepository(Favorite::class)->findOneBy([
            'user' => $user,
            $type => $itemId,
        ]);
        $em=$this->getDoctrine()->getManager();
        $em->remove($favorite);
        $em->flush();
        return new Response("favorite removed");
    }
}
