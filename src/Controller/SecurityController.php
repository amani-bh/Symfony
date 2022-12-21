<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/loginClient", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils,UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $listusers=$this->getDoctrine()->getRepository(User::class)->findAll();

        foreach($listusers as $i => $i_value) {
            dump( $i_value->getPassword());
            if ($i_value->getPassword() == ""){
                $user=$this->getDoctrine()->getRepository(User::class)->find($i_value->getUserId());

                $password = $passwordEncoder ->encodePassword(
                    $user,
                    $user->getPassword2()
                );
                $user->setPassword($password);
                $entityManager =$this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            }

        }

         if ($this->getUser()) {
             return $this->redirectToRoute('home');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
