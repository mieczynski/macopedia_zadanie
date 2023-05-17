<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    const PASSWORD = 'macopedia';

    #[Route('/', name: 'app_auth')]
    public function auth()
    {
        return $this->render('auth.html.twig', []);
    }

    #[Route('/check_password', name: 'app_auth_check_password')]
    public function checkPassword(Request $request, Session $session): Response
    {
        if ($this->getPassword($request) == self::PASSWORD) {
            $session->set('auth', true);
            return $this->redirect('/upload');
        }
        return $this->auth();
    }

    private function getPassword(Request $request)
    {
        return $request->get('password');
    }


}