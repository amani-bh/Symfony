<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\EventCategory;
use App\Entity\Event;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Repository\EventCategoryRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;



class EventCategoryController extends AbstractController
{
    /**
     * @Route("/listEventCategory", name="Event_category_list")
     */
    public function EventCategoryList()
    {

        $categories = $this->getDoctrine()->getRepository(Event::class)->createQueryBuilder(' t ')
            ->addSelect('tc.catId, tc.name, tc.imgUrl,count(t) as totalEvents')->join('t.cat','tc')
            ->where('t.cat=tc.catId')
            ->andWhere('tc.isDeleted=0')->andWhere('t.isDeleted=0')->groupBy('tc.catId ')->orderBy('tc.createdAt', 'desc')
            ->getQuery()->getResult();

        return $this->render('/event_category/all_categories.html.twig', ['categories' => $categories]);
    }

    /**
     * @Route("/EventByCategory/{id}", name="events_by_category")
     */
    public function eventsByCategory(int $id)
    {
        $events = $this->getDoctrine()->getRepository(Event::class)->createQueryBuilder('t')->where('t.isDeleted=0')->andWhere('t.cat=?1')->setParameter(1, $id)->orderBy('t.createdAt', 'desc')->getQuery()->getResult();
        return $this->render('/event_category/events_by_category.html.twig', ['events' => $events]);
    }

    /**
     * @Route("/event/category", name="event_category")
     */
    public function index(): Response
    {
        return $this->render('event_category/index.html.twig', [
            'controller_name' => 'EventCategoryController',
        ]);
    }
    /**
     * @Route("/addEventCategory", name="addEventCategory")
     */
    public function addEventCategory(Request $request)
    {
        $category = new EventCategory();
        $form = $this->createFormBuilder($category)
            ->add('name', TextType::class)
            ->add('ImgUrl', FileType::class, ['required' => true])
            ->add('add', SubmitType::class, ['label' => 'Add new Event Category'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $file = $category->getImgUrl();
            $filename = md5(uniqid()) . '.' .$file->guessExtension();
            try{
                $file->move(
                    $this->getParameter('images_directory_event'),
                    $filename

                );
            } catch (FileException $e){
                // ...handle exception if something happense during file upload
            }
            $fs=new Filesystem();
            $fs->mirror($this->getParameter('images_directory_event'), '../../CoHeal-Desktop/src/coheal/resources/images/events');
            $em = $this->getDoctrine()->getManager();
            $category->setImgUrl("$filename");
            $category->setCreatedAt(new \DateTime());
            $category->setModifiedAt(new \DateTime());
            var_dump($category);
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('events');
        }
        return $this->render('/BackOffice/add_EventCategory.html.twig', ['form'=>$form->createView()]);
    }


    /**
     * @Route("/updateEventCategory/{id}", name="update_eventCategory")
     */
    public function update(Request $request,int $id): Response
    {
        $category=$this->getDoctrine()->getRepository(EventCategory::class)->find($id);
        $form = $this->createFormBuilder($category)
            ->add('name', TextType::class)
            ->add('update', SubmitType::class, ['label' => 'update Event Category'])
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('events');
        }
        return $this->render('/BackOffice/update_EventCategory.html.twig', ['form'=>$form->createView()]);
    }

    /**
     * @Route("/deleteEventCategory/{id}", name="delete_eventCategory")
     */
    public function delete(int $id)
    {
        $tasks=$this->getDoctrine()->getRepository(EventCategory::class)->createQueryBuilder('ec')->update(EventCategory::class,'ec')->set('ec.isDeleted','?1')->where('ec.catId = ?2')->setParameter(1, 1)
            ->setParameter(2, $id)->getQuery()->execute();
        return $this->redirectToRoute("events");
    }
}
