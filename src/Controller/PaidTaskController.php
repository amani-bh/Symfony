<?php

namespace App\Controller;

use App\Entity\PaidTask;
use App\Entity\Task;
use App\Entity\TaskCategory;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaidTaskController extends AbstractController
{
    /**
     * @Route("/paid/task", name="paid_task")
     */
    public function index(): Response
    {
        return $this->render('paid_task/index.html.twig', [
            'controller_name' => 'PaidTaskController',
        ]);
    }
    /**
     * @Route("/paid/addpaidtask", name="add_paid_task")
     */
    public function addPaidTask(Request $request)
    {
        $task=new PaidTask();
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
            ->add('add', SubmitType::class, ['label'=>'Add new task'])
            ->getForm();
        $user=$this->getDoctrine()->getRepository(User::class)->find(6);

        $task->setU($user);
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
                // ... handle exception if something happens during file upload
            }
            $em=$this->getDoctrine()->getManager();
            $task->setImgUrl($filename);
            $task->setCreatedAt(new \DateTime());
            $task->setModifiedAt(new \DateTime());
            $em->persist($task);
            $em->flush();
            return $this->redirectToRoute('task_category_list');
        }

        return $this->render('/task/add_task.html.twig', ['form'=>$form->createView()]);
    }

    /**
     * @Route("/taskDtails/{idt}", name="task_details")
     */
    public function details(int $idt)
    {
        $tasks=$this->getDoctrine()->getRepository(Task::class)->createQueryBuilder('t')
            ->where('t.taskId=?1')->setParameter(1,$idt)->getQuery()->getResult();
        return $this->render('task/task_details.html.twig', ['tasks'=>$tasks]);
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
     * @Route("/allTasks", name="all_task")
     */
    public function all()
    {
        $tasks=$this->getDoctrine()->getRepository(Task::class)->createQueryBuilder('t')->where('t.isDeleted=0')->orderBy('t.createdAt','desc')->getQuery()->getResult();
        return $this->render('/task/all_tasks.html.twig', ['tasks'=>$tasks]);
    }
}
