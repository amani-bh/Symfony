<?php

namespace App\Controller;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;



use App\Entity\Session;
use App\Entity\SessionChat;
use App\Entity\Task;
use App\Entity\TaskCategory;
use App\Entity\User;
use Doctrine\ORM\Tools\Pagination\Paginator;
use phpDocumentor\Reflection\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Validator\Constraints as Assert;

class SessionController extends AbstractController
{
    /**
     * @Route("/session", name="session")
     */
    public function index(): Response
    {


        return $this->render('session/index.html.twig', [
            'controller_name' => 'SessionController',

        ]);
    }

    /**
     * @Route("/addSession", name="add_session")
     */
    public function addSession(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $session = new Session();
        $session->setIsDeleted(false);

        $form = $this->createFormBuilder($session)
            ->add('Title', TextType::class, ['required' => true, 'attr' => ['placeholder' => 'Write Title']])
            ->add('Description', TextareaType::class, ['required' => true, 'attr' => ['placeholder' => 'Write Description']])
            ->add('numOfDays', IntegerType::class, ['required' => true])
            ->add('add', SubmitType::class, ['label' => 'Add new session'])
            ->getForm();

        $form->handleRequest($request);
        //$user = $this->getDoctrine()->getRepository(User::class)->find(7);
        $session->setTherp($user);
        $session->setCreatedAt(new \DateTime());
        $session->setModifiedAt(new \DateTime());
        if ($form->isSubmitted() && $form->isValid()) {
            // $file=$session->getImgUrl();
            //  $filename=md5(uniqid()).'.'.$file->guessExtension();
            $em = $this->getDoctrine()->getManager();


            $em->persist($session);
            $em->flush();
            return $this->redirectToRoute('Session');
        }

        return $this->render('/session/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/listSession", name="Session")
     */
    public function list(Request $request)

    {


        $repository = $this->getDoctrine()->getrepository(Session::Class);//recuperer repisotory
        $session = $repository->findBy(array('isDeleted' => '0'));//affichage
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $u= $this->getUser();



        return $this->render('session/index.html.twig', [
                'session' => $session,'user'=>$u]
        );


    }

    /**
     * @Route("/deletesesssion/{id}", name="delete_Session")
     */
    public function delete(int $id)
    {
        $Session = $this->getDoctrine()->getRepository(Session::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($Session);
        $em->flush();
        return $this->redirectToRoute("Session");
    }


    /**
     * @Route("/modifierSession/{id}", name="session_modif", methods={"GET","POST"})
     */
    public function modifier(Request $request, $id): Response
    {

        $session = $this->getDoctrine()->getRepository(Session::class)->find($id);
        $form = $this->createFormBuilder($session)
            ->add('Title', TextType::class, ['required' => true, 'attr' => ['placeholder' => 'Write Title']])
            ->add('Description', TextareaType::class, ['required' => true, 'attr' => ['placeholder' => 'Write Description']])
            ->add('numOfDays', IntegerType::class, ['required' => true])
            ->add('add', SubmitType::class, ['label' => 'Add new session'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($session);
            $em->flush();
            return $this->redirectToRoute('Session');
        }
        return $this->render('/session/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/listSessionuser", name="Sessionuser")
     */
    public function listuser()
    {

        $repository = $this->getDoctrine()->getrepository(Session::Class);//recuperer repisotory
        $sessionuser = $repository->findBy(array('isDeleted' => '0', 'user_id' != '0'));//affichage
        return $this->render('session/index.html.twig', [
            'Session' => $sessionuser,
        ]);//liasion twig avec le controller
    }

    /**
     * @Route("/RchercheSession", name="rechercheSession")
     */
    public function recherche($idt)
    {
        $recherche = $this->getDoctrine()->getRepository(Session::class)->createQueryBuilder('s')->where("s.title LIKE :1")->setParameter('1', $idt)->getQuery()->getResult();
        return $this->render('session/listbyrecherche.html.twig', ['recherche' => $recherche]);
    }

    function filterwords($text)
    {
        $filterWords = array('gosh', 'darn', 'poo');
        $filterCount = sizeof($filterWords);
        for ($i = 0; $i < $filterCount; $i++) {
            $text = preg_replace_callback('/\b' . $filterWords[$i] . '\b/i', function ($matches) {
                return str_repeat('*', strlen($matches[0]));
            }, $text);
        }
        return $text;
    }

    /**
     * @Route("/participateSession/{id}", name="participate_session")
     */
    public function participate(int $id)
    {
        $session = $this->getDoctrine()->getRepository(Session::class)->find($id);
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $u = $this->getUser();
        $session->setUser($u);
        $sessionchat=new SessionChat();
        $sessionchat->setSession($session);
        //$userid=$this->getDoctrine()->getRepository(Session::class)->createQueryBuilder('s')->update(SessionChat::class,'s')->set('s.user','?1')->where('s.sessionId = ?2')->setParameter(1, $u)
           // ->setParameter(2, $id)->getQuery()->execute();

        $em = $this->getDoctrine()->getManager();
        $em->persist($sessionchat);
        $em->flush();
        //$um = $this->getDoctrine()->getManager();
        //$um->persist($userid);
        //$um->flush();


        return $this->redirectToRoute("Session");

    }
    /**
     * @Route("/test", name="test")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function statAction()
    {
        $sessions = $this->getDoctrine()->getRepository(Session::class)->createQueryBuilder('s')
            ->select('count(s) as nbr')->join('s.user','u')
            ->where('s.user=u.userId')->andWhere('u.userId!=0')->getQuery()->getSingleScalarResult();
        $session = $this->getDoctrine()->getRepository(Session::class)->createQueryBuilder('s')
            ->select('count(s) as nbr')->where('s.user is null')->getQuery()->getSingleScalarResult();

        $pieChart = new PieChart();

        $pieChart->getData()->setArrayToDataTable( array(
            ['Task', 'Hours per Day'],
            ['session participer',    (int)$sessions ],
            ['session non participer', (int)    $session],
        ));

        $pieChart->getOptions()->setTitle('You still in work');
        $pieChart->getOptions()->setHeight(400);
        $pieChart->getOptions()->setWidth(400);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#07600');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(25);


        return $this->render('BackOffice/stat.html.twig', array(
                'piechart' => $pieChart,
            )

        );
    }
}

