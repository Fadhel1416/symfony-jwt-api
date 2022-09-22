<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="app_dashboard")
     */
    public function index(Request $request): Response
    {
        if($request->headers->has('Authorization')){
            $token=$request->headers->get('Authorization');
            $token=str_replace("Bearer","",$token);
            $token=str_replace(" ","",$token);

        }
        else{
            $token="";
        }
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/DashboardController.php',
            "token"=>$token,
            "user"=>$this->getUser()
        ]);
    }
}
