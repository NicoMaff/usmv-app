<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_security_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route("/api/login", "api_security_login")]
    public function apiLogin(#[CurrentUser] ?User $user): Response
    {
        if ($user === null) {
            return $this->json([
                "message" => "missing credentials",
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = "10";

        return $this->json([
            "user" => $user->getUserIdentifier(),
            "token" => $token
        ]);
    }

    #[Route(path: '/logout', name: 'app_security_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
