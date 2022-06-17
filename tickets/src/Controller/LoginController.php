<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RequestStack;

class LoginController extends AbstractController
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    /**
     * @Route("/login", name="login")
     */
    public function index(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        //$session= new Session;
        //$session->start();
        $error=$authenticationUtils->getLastAuthenticationError();
        if($error!=""){
            $error="Usuario o contraseña incorrecta";
        }else{
            $error="";
        }
        $lastUsername = $authenticationUtils->getLastUsername();
        $session = $this->requestStack->getSession();
        //$session=$request->getSession();
        $session->set('usuario', $lastUsername);
        
        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
            'last_username' => $lastUsername,
            'error'=> $error,
        ]);
    }
}
