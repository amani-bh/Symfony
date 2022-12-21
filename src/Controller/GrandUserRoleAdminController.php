<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GrandUserRoleAdminController extends AbstractController
{

    /**
     * @Route("/grandUserRoleAdmin", name="grand_user_role_admin")
     */
    public function grandUserRoleAdmin()
    {
        $user=$this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('/grand_user_role_admin/index.html.twig',['listUsers'=> $user ]);
    }

    /**
     * @Route("/AddRole/{userId}", name="AddRole")
     */
    public function addRole(Request $request,$userId)
    {
        $user=$this->getDoctrine()->getRepository(User::class)->find($userId);
        $type=$request->query->get('rid');
        $role=$this->getDoctrine()->getRepository(Role::class)->find($type);

        $user->addRole($role);
        $em=$this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return  $this->redirectToRoute('grand_user_role_admin');
    }

    /**
     * @Route("/RemoveRole/{userId}", name="RemoveRole")
     */
    public function removeRole(Request $request,$userId)
    {
        $user=$this->getDoctrine()->getRepository(User::class)->find($userId);
        $type=$request->query->get('rid');
        $role=$this->getDoctrine()->getRepository(Role::class)->find($type);

        $user->removeRole($role);
        $em=$this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return  $this->redirectToRoute('grand_user_role_admin');
    }

    /**
     * @Route("/ActivateAccount/{userId}", name="activateAccount")
     */
    public function activateAccount ($userId)
    {
        $user=$this->getDoctrine()->getRepository(User::class)->find($userId);

        $user->setIsDeleted(0);

        $em=$this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return  $this->redirectToRoute('grand_user_role_admin');
    }

    /**
     * @Route("/DesactivateAccount/{userId}", name="desactivateAccount")
     */
    public function DesactivateAccount ($userId)
    {
        $user=$this->getDoctrine()->getRepository(User::class)->find($userId);

        $user->setIsDeleted(1);

        $em=$this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return  $this->redirectToRoute('grand_user_role_admin');
    }

}
