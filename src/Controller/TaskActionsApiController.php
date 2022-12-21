<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\TaskActions;
use App\Entity\TaskCategory;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class TaskActionsApiController extends AbstractController
{
    /**
     * @Route("/api/taskActions/{id}", name="task_actions_api")
     */
    public function taskActions(int $id,NormalizerInterface $normalizer)
    {

        $actions = $this->getDoctrine()->getRepository(TaskActions::class)->createQueryBuilder('ta')->where('ta.task=?1')->setParameter(1, $id)->getQuery()->getResult();
        $jsonContent=$normalizer->normalize($actions, 'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/api/addTaskActions/{id}", name="task_actions_add_api")
     */
    public function addActions(Request $request,SerializerInterface $serializer,$id)
    {
        $content=$request->getContent();
        $data=$serializer->deserialize($content,TaskActions::class,'json');
        $parameters = json_decode($content, true);

        $task=$this->getDoctrine()->getRepository(Task::class)->find($id);
        $data->setTask($task);
        $em=$this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();

        return new Response("Task action added successfully");
    }
    /**
     * @Route("/api/updateTaskActions/{id}", name="task_actions_update_api")
     */
    public function updateActions(Request $request,$id)
    {
        $action=$this->getDoctrine()->getRepository(TaskActions::class)->find($id);
        $content=$request->getContent();
        $parameters = json_decode($content, true);
        $action->setTitle($parameters['title']);
        $action->setDescription($parameters['description']);
        $em=$this->getDoctrine()->getManager();
        $em->persist($action);
        $em->flush();

        return new Response("Task action updated successfully");
    }

    /**
     * @Route("/api/deleteTaskAction/{id}", name="task_actions_delete_api")
     */
    public function delete(Request $request,NormalizerInterface $normalizer,int $id)
    {
        $action=$this->getDoctrine()->getRepository(TaskActions::class)->createQueryBuilder('t')->delete(TaskActions::class,'ta')->where('ta.actionId = ?1')->setParameter(1, $id)->getQuery()->execute();
        return new Response("Task action deleted successfully");
    }


    /**
     * @Route("/api/getTaskActions/{id}", name="task_action_get_api")
     */
    public function ac(NormalizerInterface $normalizer,string $id)
    {
        $action=$this->getDoctrine()->getRepository(TaskActions::class)->findBy(['actionId'=>$id]);
        $jsonContent=$normalizer->normalize($action, 'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }
}
