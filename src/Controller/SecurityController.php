<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    #[Route('/login', name: 'login')]
    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {

        if ($this->getUser()) {
            return $this->redirectToRoute('homepage');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    #[Route('/login_check', name: 'login_check', methods: ['POST'])]
    public function loginCheck() : void
    {
        // This code is never executed.
    }


    #[Route('/logout', name: 'logout')]
    public function logoutCheck() : void
    {
        // This code is never executed.
    }
}