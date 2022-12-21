<?php

namespace App\Controller;
use App\Entity\PaidTask;
use App\Entity\Task;
use App\Entity\TaskCategory;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TaskController extends AbstractController
{
    /**
     * @Route("/task", name="task")
     */
    public function index(): Response
    {
        return $this->render('task/index.html.twig', [
            'controller_name' => 'TaskController',
        ]);
    }
    /**
     * @Route("/addtask", name="add_task")
     */
    public function addTask(Request $request)
    {
        $task=new Task();
        $form=$this->createFormBuilder($task)
            ->add('Title', TextType::class)
            ->add('Description', TextareaType::class, )
            ->add('Cat', EntityType::class, ['class'=>TaskCategory::class,
                'query_builder' => function (EntityRepository   $er) {
                    return $er->createQueryBuilder('tc')
                        ->where('tc.isDeleted=0');}, 'choice_label'=>'name','required'=>true,
                'attr' => ['class' => 'form-control']])
            ->add('numOfDays', IntegerType::class)
            ->add('ImgUrl', FileType::class, ['required'=>true])
            ->add('type',ChoiceType::class, array(
                'choices' => array(
                    'Free Task' => 'free',
                    'Paid Task' => 'paid',
                ),
                'expanded' => true))
            ->add('price', IntegerType::class)
            ->add('add', SubmitType::class, ['label'=>'Add new task'])
            ->getForm();

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $u = $this->getUser();

        $task->setU($u);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $file = $task->getImgUrl();
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images_directory_task'),
                    $filename
                );
            } catch (FileException $e) {
            }
            $fs=new Filesystem();
            $fs->mirror($this->getParameter('images_directory_task'), '../../CoHeal-Desktop/src/coheal/resources/images/tasks');

            $em=$this->getDoctrine()->getManager();
            $task->setImgUrl($filename);
            $task->setCreatedAt(new \DateTime());
            $task->setModifiedAt(new \DateTime());
            $em->persist($task);
            $em->flush();
//////////////
            if($task->getType()=='paid'){
                $p=new PaidTask();
                $t=$this->getDoctrine()->getRepository(Task::class)->findOneBy(['title'=> $task->getTitle()]);
                $p->setTask($t);
                $p->setPrice($task->getPrice());
                $em->persist($p);
                $em->flush();
            }
            return $this->redirectToRoute('task_category_list');
        }

        return $this->render('/task/add_task.html.twig', ['form'=>$form->createView()]);
    }

    /**
     * @Route("/taskDtails/{idt}", name="task_details")
     */
    public function details(int $idt)
    {
        $task=$this->getDoctrine()->getRepository(Task::class)->createQueryBuilder('t')
            ->where('t.taskId=?1')->setParameter(1,$idt)->getQuery()->getSingleResult();
        $paid=new PaidTask();
        if($task->getType()=="paid"){
            $p=$this->getDoctrine()->getRepository(PaidTask::class)->find($task->getId());
            $paid=$p;
        }
        $lastTasks=$this->getDoctrine()->getRepository(Task::class)->createQueryBuilder(' t')
            ->join('t.cat','c')->where('t.isDeleted=0')->andWhere('c.isDeleted=0')
            ->orderBy('t.createdAt','desc')->setFirstResult(0)->setMaxResults(3)
            ->getQuery()->getResult();
        $categories = $this->getDoctrine()->getRepository(Task::class)->createQueryBuilder(' t ')
            ->addSelect('tc.catId, tc.name, tc.imgUrl,count(t) as totalTasks')->join('t.cat','tc')
            ->where('t.cat=tc.catId')
            ->andWhere('tc.isDeleted=0')->andWhere('t.isDeleted=0')->groupBy('tc.catId ')->orderBy('tc.createdAt', 'desc')
            ->setFirstResult(0)->setMaxResults(3)->getQuery()->getResult();
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $u = $this->getUser();
        $exist="";

        $user=$this->getDoctrine()->getRepository(User::class)->find($u);
        if($user->getTask()->contains($task)){;
            $exist="exist";
        }
        return $this->render('task/task_details.html.twig',
            ['task'=>$task,'lastTasks'=>$lastTasks,'categories'=>$categories,'paid'=>$paid,'user'=>$u,'part'=>$exist]);
    }

    /**
     * @Route("/updateTask/{id}", name="update_task")
     */
    public function update(Request $request,int $id): Response
    {
        $task=$this->getDoctrine()->getRepository(Task::class)->find($id);
        $form=$this->createFormBuilder($task)
            ->add('Title', TextType::class, ['required'=>true, 'attr'=>['class' => 'form-control']])
            ->add('Description', TextareaType::class, ['required'=>true, 'attr'=>['placeholder'=>'Write Description']])
            ->add('Cat', EntityType::class, ['class'=>TaskCategory::class,
                'query_builder' => function (EntityRepository   $er) {
                    return $er->createQueryBuilder('tc')
                        ->where('tc.isDeleted=0');}, 'choice_label'=>'name','required'=>true,
                'attr' => ['class' => 'form-control']])
            ->add('numOfDays', IntegerType::class, ['required'=>true])
            ->add('update', SubmitType::class, ['label'=>'Save', 'attr'=>['class' => 'btn btn-primary']])
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();
            return $this->redirectToRoute('task_details', array('idt' => $task->getId()));
        }
        return $this->render('/task/update_task.html.twig', ['form'=>$form->createView()]);
    }

    /**
     * @Route("/deleteTask/{id}", name="delete_task")
     */
    public function delete(int $id)
    {
        $tasks=$this->getDoctrine()->getRepository(Task::class)->createQueryBuilder('t')->update(Task::class,'t')->set('t.isDeleted','?1')->where('t.taskId = ?2')->setParameter(1, 1)
            ->setParameter(2, $id)->getQuery()->execute();
        return $this->redirectToRoute("all_task");
    }

    /**
     * @Route("/deleteAdminTask/{id}", name="delete_admin_task")
     */
    public function admiDelete(int $id)
    {
        $tasks=$this->getDoctrine()->getRepository(Task::class)->createQueryBuilder('t')
            ->update(Task::class,'t')->set('t.isDeleted','?1')->where('t.taskId = ?2')->setParameter(1, 1)
            ->setParameter(2, $id)->getQuery()->execute();
        return $this->redirectToRoute("tasks");
    }

    /**
     * @Route("/allTasks", name="all_task")
     */
    public function all()
    {
        // $tasks=$this->getDoctrine()->getRepository(Task::class)->createQueryBuilder('t')->where('t.isDeleted=0')->orderBy('t.createdAt','desc')->getQuery()->getResult();
        $paid=$this->getDoctrine()->getRepository(PaidTask::class)->findAll();
        $tasks = $this->getDoctrine()->getRepository(Task::class)->findBy(['isDeleted'=>0]);
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $u = $this->getUser();
        $yours=$u->getTask();
        return $this->render('/task/all_tasks.html.twig', ['tasks'=>$tasks,'paid'=>$paid,'yours'=>$yours]);
    }

    /**
     * @Route("/participateTask/{id}", name="participate_task")
     */
    public function participate(int $id)
    {
        $task=$this->getDoctrine()->getRepository(Task::class)->find( $id);
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $u = $this->getUser();

        $u->addTask($task);
        $task->addUser($u);
        $em = $this->getDoctrine()->getManager();
        $em->persist($task);
        $em->flush();
        $em->persist($u);
        $em->flush();
        $lastTasks=$this->getDoctrine()->getRepository(Task::class)->createQueryBuilder(' t')
            ->join('t.cat','c')->where('t.isDeleted=0')->andWhere('c.isDeleted=0')
            ->orderBy('t.createdAt','desc')->setFirstResult(0)->setMaxResults(3)
            ->getQuery()->getResult();
        $categories = $this->getDoctrine()->getRepository(Task::class)->createQueryBuilder(' t ')
            ->addSelect('tc.catId, tc.name, tc.imgUrl,count(t) as totalTasks')->join('t.cat','tc')
            ->where('t.cat=tc.catId')
            ->andWhere('tc.isDeleted=0')->andWhere('t.isDeleted=0')->groupBy('tc.catId ')->orderBy('tc.createdAt', 'desc')
            ->setFirstResult(0)->setMaxResults(3)->getQuery()->getResult();
        return $this->render('task/task_details.html.twig', ['task'=>$task,'lastTasks'=>$lastTasks,'categories'=>$categories]);

    }
    /**
     * @Route("/listParticipateTask/{id}", name="success")
     */
    public function success(int $id): Response
    {
        $task=$this->getDoctrine()->getRepository(Task::class)->find( $id);
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $u = $this->getUser();

        $u->addTask($task);
        $task->addUser($u);
        $em = $this->getDoctrine()->getManager();
        $em->persist($task);
        $em->flush();
        $em->persist($u);
        $em->flush();
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $u = $this->getUser();
        $user=$this->getDoctrine()->getRepository(User::class)->find($u);

        // $tasks=$this->getDoctrine()->getRepository(User::class)->find($u->getU)
        return $this->render('task/participate_user_task.html.twig', [
            'tasks' => $user->getTask(),
        ]);
    }
    /**
     * @Route("/yours", name="yours")
     */
    public function yours(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $u = $this->getUser();
        $user=$this->getDoctrine()->getRepository(User::class)->find($u);
        if( in_array("ROLE_Therapist", $u->getRoles())){
            $tasks=$this->getDoctrine()->getRepository(Task::class)->createQueryBuilder(' t')
                ->where('t.u=?1')->setParameter('1',$u)->andWhere('t.isDeleted=0')->getQuery()->getResult();

        }
        else{
            $tasks=$user->getTask();

        }


        // $tasks=$this->getDoctrine()->getRepository(User::class)->find($u->getU)
        return $this->render('task/participate_user_task.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    /**
     * @Route("/error", name="error")
     */
    public function error(): Response
    {
        return $this->render('task/error.html.twig', [
            'controller_name' => 'TaskController',
        ]);
    }
    /**
     * @Route("/create-checkout-session/{price}/{title}/{id}", name="checkout")
     */
    public function checkout(int $price,string $title,int $id): Response
    {
        \Stripe\Stripe::setApiKey('sk_test_51IjkvCBmeiBzIRGDyyarfCt1PkQ84C1qClt5fW2OoDI1C9kj5P0Cqfmxo9qhcVvomVSvZzPnu8gNURcNGwK3RO3M00YdjlLqWL');
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $title,
                    ],
                    'unit_amount' => $price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('success',['id'=>$id],UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('error',[],UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
        return new JsonResponse([ 'id' => $session->id ]);
    }

}
