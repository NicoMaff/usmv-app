<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
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

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
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
        // throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
        // return $this->redirectToRoute("app_homepage_index");
    }

    #[Route("/api/registration", "api_security_registration")]
    public function registration(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, UserRepository $repository, UserPasswordHasherInterface $hasher): Response
    {
        $jsonReceived = $request->getContent();
        // dd($jsonReceived);

        $user = $serializer->deserialize($jsonReceived, USER::class, "json");
        // dd($user);
        $user->setCreatedAt(new \DateTimeImmutable());

        $hashedPassword = $hasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);

        $user->setValidatedAccount(false);

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        $repository->add($user, true);

        return $this->json($user, 201, [], []);
    }
}
