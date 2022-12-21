<?php

namespace App\Controller;

use App\Entity\Recipe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\RecipeCategory;

class RecipeCategoryController extends AbstractController
{
    /**
     * @Route("/recipe/category", name="recipe_category")
     */
    public function index(): Response
    {
        return $this->render('recipe_category/index.html.twig', [
            'controller_name' => 'RecipeCategoryController',
        ]);
    }

    /**
     * @Route("/addRecipeCategory", name="addRecipeCategory")
     */
    public function addRecipeCategory(Request $request)
    {
        $category = new RecipeCategory();
        $form = $this->createFormBuilder($category)
            ->add('Name', TextType::class)
            ->add('ImgUrl', FileType::class, ['label'=>'Image','required' => true])
            ->add('Add', SubmitType::class, ['label' => 'Add new Recipe Category'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //$file = $category->getImgUrl();
            $file = $form->get('ImgUrl')->getData();
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images_directory_recipe'),
                    $filename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $fs=new Filesystem();
            $fs->mirror($this->getParameter('images_directory_recipe'), '../../CoHeal-Desktop/src/coheal/resources/images/recipes');
            $em = $this->getDoctrine()->getManager();
            $category->setImgUrl($filename);
            $category->setCreatedAt(new \DateTime());
            $category->setModifiedAt(new \DateTime());
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('recipesPage');
        }
        return $this->render('/BackOffice/addRecipeCategory.html.twig', ['form'=>$form->createView()]);
    }

    /**
     * @Route("/allRecipeCategories", name="allRecipeCategories")
     */
    public function allCategories()
    {
        $categories = $this->getDoctrine()->getRepository(Recipe::class)->createQueryBuilder(' r ')
            ->addSelect('rc.catId, rc.name, rc.imgUrl,count(r) as totalRecipes')->join('r.cat','rc')
            ->where('r.cat=rc.catId')
            ->andWhere('rc.isDeleted=0')->andWhere('r.isDeleted=0')->groupBy('rc.catId ')->orderBy('rc.createdAt', 'desc')
            ->getQuery()->getResult();
        return $this->render('/recipe_category/allRecipeCategories.html.twig', ['categories' => $categories]);
    }

    /**
     * @Route("/recipesByCategory/{id}", name="recipesByCategory")
     */
    public function recipesByCategory(int $id)
    {
        //$recipes = $this->getDoctrine()->getRepository(Recipe::class)->createQueryBuilder('r')->where('r.isDeleted=0')->andWhere('r.cat=?1')->setParameter(1, $id)->orderBy('r.createdAt', 'desc')->getQuery()->getResult();
        $recipes = $this->getDoctrine()->getRepository(Recipe::class)->findBy(['isDeleted'=>0,'cat'=>$id]);
        return $this->render('/recipe_category/recipesByCategory.html.twig', ['recipes' => $recipes]);
    }

    /**
     * @Route("/updateRecipeCategory/{id}", name="updateRecipeCategory")
     */
    public function updateRecipeCategory(Request $request,int $id): Response
    {
        $category=$this->getDoctrine()->getRepository(RecipeCategory::class)->find($id);
        $form = $this->createFormBuilder($category)
            ->add('Name', TextType::class)
            ->add('Update', SubmitType::class, ['label' => 'Update Recipe Category'])
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('recipesPage');
        }
        return $this->render('/BackOffice/updateRecipeCategory.html.twig', ['form'=>$form->createView()]);
    }

    /**
     * @Route("/deleteRecipeCategory/{id}", name="deleteRecipeCategory")
     */
    public function deleteRecipeCategory(int $id)
    {
        $recipes=$this->getDoctrine()->getRepository(RecipeCategory::class)->createQueryBuilder('rc')->update(RecipeCategory::class,'rc')->set('rc.isDeleted','?1')->where('rc.catId = ?2')->setParameter(1, 1)
            ->setParameter(2, $id)->getQuery()->execute();
        return $this->redirectToRoute("recipesPage");
    }
}
