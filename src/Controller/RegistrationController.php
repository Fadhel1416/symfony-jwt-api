<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api", name="api_")
 */
class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="register", methods={"POST"})
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
         
        $em = $this->getDoctrine()->getManager();
        $decoded = json_decode($request->getContent());
        $email = $decoded->email;
        $password = $decoded->password;
 
        $user = new User();
        $user->setPassword($encoder->encodePassword($user, $password));
        $user->setEmail($email);
        $em->persist($user);
        $em->flush();
 
        return $this->json(['message' => 'Registered Successfully']);
    }
}
