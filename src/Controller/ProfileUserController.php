<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfileUserController extends AbstractController
{
    /**
     * @Route("/profileUser" , name="profile_user" )
     */
    public function profile(): Response
    {
        // usually you'll want to make sure the user is authenticated first
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // returns your User object, or null if the user is not authenticated
        // use inline documentation to tell your editor your exact User class
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
         
        return $this->render('profile_user/profile.html.twig', ['user'=>$user]);
    }

    /**
     * @Route("/updateUser" , name="update_user" )
     */
    public function updateUser(Request $request): Response
    {
        $userId =$request->request->get('userId');
        $email = $request->request->get('emailUpdated');
        $firstName = $request->request->get('firstNameUpdated');
        $lastName = $request->request->get('lastNameUpdated');
        $date=new \DateTime($request->request->get('dateUpdated'));

        $newuser=$this->getDoctrine()->getRepository(User::class)->find($userId);
        $newuser->setEmail($email);
        $newuser->setFirstName($firstName);
        $newuser->setLastName($lastName);
        $newuser->setDateOfBirth($date);
        $em=$this->getDoctrine()->getManager();
        $em->persist($newuser);
        $em->flush();


        return $this->redirectToRoute('profile_user');
    }

    /**
     * @Route("/updatePassword" , name="update_Password" )
     */
    public function updatePassword(Request $request,UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $userId =$request->request->get('userId');
        $user=$this->getDoctrine()->getRepository(User::class)->find($userId);

        $OldPassword = $request->request->get('OldPassword');
        $password = $request->request->get('password');
          
        if(password_verify($OldPassword, $password)){

            $NewPassword = $request->request->get('NewPassword');
            $ConfirmNewPassword = $request->request->get('ConfirmNewPassword');
            if($NewPassword == $ConfirmNewPassword){

                $password = $passwordEncoder ->encodePassword($user,$NewPassword);
                $user ->setPassword($password);
                
                $em=$this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
            }
            
        }

        return $this->render('FrontOffice/index.html.twig');
    }
}
