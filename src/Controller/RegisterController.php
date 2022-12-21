<?php

namespace App\Controller;
use App\Entity\Role;
use App\Entity\User;
use App\Form\RegisterFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(UserPasswordEncoderInterface $passwordEncoder,Request  $request): Response
    {
        $user =new User();
        $form =$this ->createForm(RegisterFormType::class,$user);
        $form->handleRequest($request);

        $role =$this->getDoctrine()->getRepository(Role::class)->find(4);
        $user->addRole($role);

        if($form->isSubmitted() && $form->isValid()){

            $password = $passwordEncoder ->encodePassword(
                $user,
                $user->getPlainPassword()
            );

            $plainPassword=$user->getPlainPassword();

            $user->setPassword2($plainPassword);

            $user ->setPassword($password);
            $user->setBalance(0);
            $user->setScore(0);
            $user->setIsLimited(0);
            $user->setIsDeleted(0);
            $user->setCreatedAt(new \DateTime());
            $user->setUpdatedAt(new \DateTime());
            $entityManager =$this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirect('loginClient');
        }


        return $this->render('register/register.html.twig', [
            'form' => $form->createView() 
        ]);
    }
}
