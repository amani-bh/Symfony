<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\TaskActions;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskActionsController extends AbstractController
{
    /**
     * @Route("/task/actions", name="task_actions")
     */
    public function index(): Response
    {
        return $this->render('task_actions/index.html.twig', [
            'controller_name' => 'TaskActionsController',
        ]);
    }

    /**
     * @Route("/addtaskAction/{id}", name="add_task_action")
     */
    public function add(Request $request,int $id)
    {
        $action=new TaskActions();
        $form=$this->createFormBuilder($action)
            ->add('title', TextType::class)
            ->add('description', TextareaType::class, )
            ->add('add', SubmitType::class, ['label'=>'Add new task action'])
            ->getForm();
        $task=$this->getDoctrine()->getRepository(Task::class)->find($id);

        $action->setTask($task);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $em=$this->getDoctrine()->getManager();
            $em->persist($action);
            $em->flush();
            return $this->redirectToRoute('task_actions_by_task', array('id' => $id));
        }

        return $this->render('task_actions/add_task_action.html.twig', ['form'=>$form->createView()]);
    }



    /**
     * @Route("/updateTaskAction/{id}", name="update_task_action")
     */
    public function update(Request $request,int $id): Response
    {
        $action=$this->getDoctrine()->getRepository(TaskActions::class)->find($id);
        $form=$this->createFormBuilder($action)
            ->add('title', TextType::class, ['required'=>true, 'attr'=>['class' => 'form-control']])
            ->add('description', TextareaType::class, ['required'=>true, 'attr'=>['placeholder'=>'Write Description']])
            ->add('update', SubmitType::class, ['label'=>'Save', 'attr'=>['class' => 'btn btn-primary']])
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($action);
            $em->flush();
            return $this->redirectToRoute('task_details', array('idt' => $action->getTask()->getId()));
        }
        return $this->render('task_actions/update_task_action.html.twig', ['form'=>$form->createView()]);
    }

    /**
     * @Route("/updateTaskActionDone/{id}", name="update_task_action_done")
     */
    public function updateDone(Request $request,int $id): Response
    {
        $action=$this->getDoctrine()->getRepository(TaskActions::class)->find($id);
        $action->setDone(1);
        $em = $this->getDoctrine()->getManager();
        $em->persist($action);
        $em->flush();
        // return $this->redirect($request->getUri());
        /*  $action=$this->getDoctrine()->getRepository(TaskActions::class)->find($id);
          $form=$this->createFormBuilder($action)
              ->add('done', CheckboxType::class, ['required'=>true, 'attr'=>['placeholder'=>'Write Description']])
             ->getForm();

          $form->handleRequest($request);
          if($form->isSubmitted() && $form->isValid()) {
              $em = $this->getDoctrine()->getManager();
              $em->persist($action);
              $em->flush();
              return $this->redirectToRoute('task_actions_by_task', array('id' => $action->getTask()->getTaskId()));
          }
          return $this->render('task_actions/list_task_actions.html.twig', ['form'=>$form->createView()]);*/
        return $this->redirectToRoute('task_actions_by_task', array('id' => $action->getTask()->getId()));
    }
    /**
     * @Route("/deleteTaskAction/{id}", name="delete_task_action")
     */
    public function delete(int $id)
    {
        $a=$this->getDoctrine()->getRepository(TaskActions::class)->find($id);
        $idt=$a->getTask()->getId();
        $action=$this->getDoctrine()->getRepository(TaskActions::class)->createQueryBuilder('t')->delete(TaskActions::class,'ta')->where('ta.actionId = ?1')->setParameter(1, $id)->getQuery()->execute();
        return $this->redirectToRoute("task_actions_by_task", array('id' =>$idt));
    }

    /**
     * @Route("/TaskActionsByTask/{id}", name="task_actions_by_task")
     */
    public function taskActionsByCategory(int $id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $u = $this->getUser();
        $actions = $this->getDoctrine()->getRepository(TaskActions::class)->createQueryBuilder('ta')->where('ta.task=?1')->setParameter(1, $id)->getQuery()->getResult();
        $task=$this->getDoctrine()->getRepository(Task::class)->find($id);
        return $this->render('task_actions/list_task_actions.html.twig', ['actions' => $actions,'id'=>$id,'user'=>$u,'task'=>$task]);
    }

    /**
     * @Route("/doneTaskAction/{id}", name="done_task_action")
     */
    public function done(Request $request,int $id): Response
    {
        $action=$this->getDoctrine()->getRepository(TaskActions::class)->find($id);
        $form=$this->createFormBuilder($action)
            ->add('done', CheckboxType::class, ['required'=>true, 'attr'=>['class' => 'form-control']])
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($action);
            $em->flush();
            return $this->redirectToRoute('task_actions_by_task', array('idt' => $action->getTask()->getId()));
        }
        return $this->redirectToRoute('task_actions_by_task', array('idt' => $action->getTask()->getId()));
    }
}
