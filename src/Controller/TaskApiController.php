<?php

namespace App\Controller;

use App\Entity\PaidTask;
use App\Entity\Task;
use App\Entity\TaskCategory;
use App\Entity\User;
use Hshn\Base64EncodedFile\HttpFoundation\File\Base64EncodedFile;
use Hshn\Base64EncodedFile\HttpFoundation\File\UploadedBase64EncodedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class TaskApiController extends AbstractController
{
    /**
     * @Route("/api/tasks", name="task_list_api")
     */
    public function list(NormalizerInterface $normalizer)
    {
        $paid=$this->getDoctrine()->getRepository(PaidTask::class)->findAll();
        $tasks = $this->getDoctrine()->getRepository(Task::class)->findBy(['isDeleted'=>0],['createdAt'=>'desc']);
        foreach ( $tasks as $t ){
            foreach ( $paid as $p ){
                if($p->getTask()==$t){
                    $key = array_search($t, $tasks);
                    if ($key !== false) {
                        unset($tasks[$key]);
                    }
                }
            }
        }

        $all=array_merge($paid,$tasks);
        $data=$normalizer->normalize($all, 'json',['groups'=>'post:read']);

        return new Response(json_encode($data));
    }


    /**
     * @Route("/api/addTask", name="task_add_api")
     */
    public function add(Request $request,SerializerInterface $serializer)
    {

        $content=$request->getContent();
        $data=$serializer->deserialize($content,Task::class,'json');
        $parameters = json_decode($content, true);

        $uploads_directory = $this->getParameter('images_directory_task');
        $filename = md5(uniqid()) . '.' .$parameters['ext'];
        $file=new UploadedBase64EncodedFile(new Base64EncodedFile($parameters['file']));
        $file->move(
            $uploads_directory,
            $filename
        );
        $fs=new Filesystem();
        $fs->mirror($this->getParameter('images_directory_task'), '../../CoHeal-Desktop/src/coheal/resources/images/tasks');
        $user=$this->getDoctrine()->getRepository(User::class)->find($parameters['u']);
        $cat=$this->getDoctrine()->getRepository(TaskCategory::class)->find($parameters['cat']);
        $data->setU($user);
        $data->setCat($cat);
        $data->setCreatedAt(new \DateTime());
        $data->setModifiedAt(new \DateTime());
        $data->setImgUrl($filename);
        $em=$this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();

        return new Response("Task added successfully");
    }

    /**
     * @Route("/api/updateTask/{id}", name="task_update_api")
     */
    public function update(Request $request,int $id)
    {
        $task=$this->getDoctrine()->getRepository(Task::class)->find($id);
        $content=$request->getContent();
        $parameters = json_decode($content, true);

        /*$user=$this->getDoctrine()->getRepository(User::class)->find($parameters['u']);
        $cat=$this->getDoctrine()->getRepository(TaskCategory::class)->find($parameters['cat']);
        $task->setU($user);
        $task->setCat($cat);*/
        $task->setTitle($parameters['title']);
        $task->setDescription($parameters['description']);
        $task->setNumOfDays($parameters['numOfDays']);
        $task->setType("free");
        $task->setModifiedAt(new \DateTime());
        $em=$this->getDoctrine()->getManager();
        $em->persist($task);
        $em->flush();

        return new Response("Task updated successfully");
    }

    /**
     * @Route("/api/deleteTask/{id}", name="task_delete_api")
     */
    public function delete(Request $request,NormalizerInterface $normalizer,int $id)
    {
        $task=$this->getDoctrine()->getRepository(Task::class)->createQueryBuilder('t')->update(Task::class,'t')->set('t.isDeleted','?1')->where('t.taskId = ?2')->setParameter(1, 1)
            ->setParameter(2, $id)->getQuery()->execute();
        return new Response("Task deleted successfully");
    }


    /**
     * @Route("/api/taskDetails/{id}", name="task_details_api")
     */
    public function details(NormalizerInterface $normalizer,int $id)
    {
        $task=$this->getDoctrine()->getRepository(Task::class)->findBy(['taskId'=>$id]);
        $jsonContent=$normalizer->normalize($task, 'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/api/getTask/{title}", name="task_p_api")
     */
    public function task(NormalizerInterface $normalizer,string $title)
    {
        $task=$this->getDoctrine()->getRepository(Task::class)->findBy(['title'=>$title]);
        $jsonContent=$normalizer->normalize($task, 'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/api/participateTask/{id}", name="participate")
     */
    public function success(Request $request,int $id): Response
    {
        $task=$this->getDoctrine()->getRepository(Task::class)->find( $id);
        $content=$request->getContent();
        $parameters = json_decode($content, true);
        $u=$this->getDoctrine()->getRepository(User::class)->find($parameters['u']);

        $u->addTask($task);
        $task->addUser($u);
        $em = $this->getDoctrine()->getManager();
        $em->persist($task);
        $em->flush();
        $em->persist($u);
        $em->flush();
        return new Response("participate");

    }

    /**
     * @Route("/api/getParticipateTask/{id}", name="task_participate_api")
     */
    public function participate(NormalizerInterface $normalizer,int $id)
    {
        $user=$this->getDoctrine()->getRepository(User::class)->find($id);
        $jsonContent=$normalizer->normalize($user->getTask(), 'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }
}
