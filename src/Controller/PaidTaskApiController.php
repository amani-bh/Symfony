<?php

namespace App\Controller;

use App\Entity\PaidTask;
use App\Entity\Task;
use App\Entity\TaskCategory;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class PaidTaskApiController extends AbstractController
{
    /**
     * @Route("/api/paidTasks", name="paid_task_api")
     */
    public function paidDetails(Request $request,NormalizerInterface $normalizer)
    {

        $paid=$this->getDoctrine()->getRepository(PaidTask::class)->findAll();
        $jsonContent=$normalizer->normalize($paid, 'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/api/addPaidTask", name="paid_task_add_api")
     */
    public function add(Request $request,SerializerInterface $serializer)
    {
        /*$em=$this->getDoctrine()->getManager();
        $task=new Task();

        $task->setTitle($request->get('title'));
        $task->setCat($request->get('cat'));
        $task->setDescription($request->get('description'));
        $task->setNumOfDays($request->get('numOfDays'));
        $task->setU($request->get('user'));
        $task->setImgUrl($request->get('imgUrl'));
        $task->setCreatedAt(new \DateTime());
        $task->setModifiedAt(new \DateTime());
        $em->persist($task);
        $em->flush();

        $paid=new PaidTask();
        $t=$this->getDoctrine()->getRepository(Task::class)->findOneBy(['title'=> $task->getTitle()]);
        $paid->setTask($t);
        $paid->setPrice($request->get('price'));

        $em->persist($paid);
        $em->flush();
        $jsonContent=$normalizer->normalize($paid, 'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));*/
        $content=$request->getContent();
        $data=$serializer->deserialize($content,PaidTask::class,'json');
        $parameters = json_decode($content, true);

        $task=$this->getDoctrine()->getRepository(Task::class)->find($parameters['task']);
        $data->setTask($task);
        $data->setPrice($parameters['price']);
        $em=$this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();

        return new Response("Paid Task added successfully");
    }

    /**
     * @Route("/api/updatePaidTask/{id}", name="paid_task_update_api")
     */
    public function update(Request $request,NormalizerInterface $normalizer,int $id)
    {
        /*$em=$this->getDoctrine()->getManager();
        $paid=$this->getDoctrine()->getRepository(PaidTask::class)->find($id);

        $paid->setTask()->setTitle($request->get('title'));
        $paid->setTask()->setCat($request->get('cat'));
        $paid->setTask()->setDescription($request->get('description'));
        $paid->setTask()->setNumOfDays($request->get('numOfDays'));
        $paid->setTask()->setU($request->get('user'));
        $paid->setTask()->setImgUrl($request->get('imgUrl'));
        $paid->setTask()->setModifiedAt(new \DateTime());
        $paid->setPrice($request->get('price'));
        $em->flush();
        $jsonContent=$normalizer->normalize($paid, 'json',['groups'=>'post:read']);
        return new Response("updated".json_encode($jsonContent));*/
        $paid=$this->getDoctrine()->getRepository(PaidTask::class)->find($id);
        $content=$request->getContent();
        $parameters = json_decode($content, true);

        /* $user=$this->getDoctrine()->getRepository(User::class)->find($parameters['u']);
         $cat=$this->getDoctrine()->getRepository(TaskCategory::class)->find($parameters['cat']);
         $paid->setTask()->setU($user);
         $paid->setTask()->setCat($cat);*/
        $paid->setTask()->setTitle($parameters['title']);
        $paid->setTask()->setDescription($parameters['description']);
        $paid->setTask()->setType("free");
        $paid->setTask()->setModifiedAt(new \DateTime());
        $paid->setPrice($parameters['price']);
        $em=$this->getDoctrine()->getManager();
        $em->persist($paid);
        $em->flush();

        return new Response("Paid Task updated successfully");
    }



    /**
     * @Route("/api/paidTaskDetails/{id}", name="paid_task_delete_api")
     */
    public function details(Request $request,NormalizerInterface $normalizer,int $id)
    {
        $task=$this->getDoctrine()->getRepository(Task::class)->createQueryBuilder('t')
            ->where('t.taskId=?1')->setParameter(1,$id)->getQuery()->getSingleResult();
        $paid=new PaidTask();
        if($task->getType()=="paid"){
            $p=$this->getDoctrine()->getRepository(PaidTask::class)->find($task->getId());
            $paid=$p;
        }
        $jsonContent=$normalizer->normalize($paid, 'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }
}
