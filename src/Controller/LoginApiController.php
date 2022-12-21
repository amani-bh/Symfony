<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class LoginApiController extends AbstractController
{
    /**
     * @Route("/api/login", name="login_api")
     */

    public function login(Request $request,NormalizerInterface $normalizer)
    {
        $listusers=$this->getDoctrine()->getRepository(User::class)->findAll();
        $content=$request->getContent();
        $parameters = json_decode($content, true);

        $data=[];
        foreach($listusers as $i => $i_value) {

            $user=$this->getDoctrine()->getRepository(User::class)->find($i_value->getUserId());
            if ($user->getPassword2() === $parameters['password2'] && $user->getemail() === $parameters['email']){

                $data=$normalizer->normalize($user,'json',['groups'=>'post:read']);
                return new Response(json_encode($data));

            }
        }
        return new Response( json_encode($data) );
    }

}
