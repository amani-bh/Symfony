<?php

namespace App\Controller;

use App\Entity\PaidTask;
use App\Entity\Task;
use App\Entity\TaskCategory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskCategoryController extends AbstractController
{
    /**
     * @Route("/task/category", name="task_category")
     */
    public function index(): Response
    {
        return $this->render('task_category/index.html.twig', [
            'controller_name' => 'TaskCategoryController',
        ]);
    }

    /**
     * @Route("/listTaskCategory", name="task_category_list")
     */
    public function taskCategoryList()
    {
        $categories = $this->getDoctrine()->getRepository(Task::class)->createQueryBuilder(' t ')
            ->addSelect('tc.catId, tc.name, tc.imgUrl,count(t) as totalTasks')->join('t.cat','tc')
            ->where('t.cat=tc.catId')
            ->andWhere('tc.isDeleted=0')->andWhere('t.isDeleted=0')->groupBy('tc.catId ')->orderBy('tc.createdAt', 'desc')
            ->getQuery()->getResult();

        //$categories = $this->getDoctrine()->getRepository(TaskCategory::class)->createQueryBuilder('tc')->where('tc.isDeleted=0')->orderBy('tc.createdAt', 'desc')->getQuery()->getResult();
        return $this->render('/task_category/all_categories.html.twig', ['categories' => $categories]);
    }

    /**
     * @Route("/TasksByCategory/{id}", name="tasks_by_category")
     */
    public function tasksByCategory(int $id)
    {
        $paid=$this->getDoctrine()->getRepository(PaidTask::class)->findAll();
        $tasks = $this->getDoctrine()->getRepository(Task::class)->findBy(['isDeleted'=>0,'cat'=>$id]);
        /* $tasks = $this->getDoctrine()->getRepository(Task::class)->createQueryBuilder(' t ')
             ->where('  t.isDeleted=0 and t.cat=?1  order by t.createdAt DESC ')
             ->setParameter(1, $id)->getQuery()->getResult();*/
        /*   $paid = $this->getDoctrine()->getRepository(PaidTask::class)->createQueryBuilder('pt')
               ->join('pt.task','t')->addSelect('t')
               ->where('t.isDeleted=0')->andWhere('t.cat=?1')->setParameter(1, $id)
               ->orderBy('t.createdAt', 'desc')->getQuery()->getResult();*/
        $all=array_merge($paid,$tasks);
        return $this->render('/task_category/tasks_by_category.html.twig', ['tasks'=>$tasks,'paid'=>$paid]);
    }



    /**
     * @Route("/addTaskCategory", name="addTaskCategory")
     */
    public function addTaskCategory(Request $request)
    {

        $category = new TaskCategory();
        $form = $this->createFormBuilder($category)
            ->add('name', TextType::class)
            ->add('ImgUrl', FileType::class, ['required' => true])
            ->add('add', SubmitType::class, ['label' => 'Add new Task Category'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $category->getImgUrl();
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images_directory_task'),
                    $filename
                );
                $fs=new Filesystem();
                $fs->mirror($this->getParameter('images_directory_task'), '../../CoHeal-Desktop/src/coheal/resources/images/tasks');

            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $em = $this->getDoctrine()->getManager();
            $category->setImgUrl($filename);
            $category->setCreatedAt(new \DateTime());
            $category->setModifiedAt(new \DateTime());
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('tasks');
        }
        return $this->render('/BackOffice/add_TaskCategory.html.twig', ['form'=>$form->createView()]);
    }

    /**
     * @Route("/updateTaskCategory/{id}", name="update_taskCategory")
     */
    public function update(Request $request,int $id): Response
    {
        $category=$this->getDoctrine()->getRepository(TaskCategory::class)->find($id);
        $form = $this->createFormBuilder($category)
            ->add('name', TextType::class)
            ->add('update', SubmitType::class, ['label' => 'update Task Category'])
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('tasks');
        }
        return $this->render('/BackOffice/update_TaskCategory.html.twig', ['form'=>$form->createView()]);
    }

    /**
     * @Route("/deleteTaskCategory/{id}", name="delete_taskCategory")
     */
    public function delete(int $id)
    {
        $tasks=$this->getDoctrine()->getRepository(TaskCategory::class)->createQueryBuilder('tc')->update(TaskCategory::class,'tc')->set('tc.isDeleted','?1')->where('tc.catId = ?2')->setParameter(1, 1)
            ->setParameter(2, $id)->getQuery()->execute();
        return $this->redirectToRoute("tasks");
    }


}
