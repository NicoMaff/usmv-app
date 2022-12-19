<?php

namespace App\Controller\API;

use App\Entity\ResetPassword;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

#[Route('/api/reset_password')]
class ApiResetPasswordController extends AbstractController
{
    use ResetPasswordControllerTrait;

    public function __construct(
        private ResetPasswordHelperInterface $resetPasswordHelper,
    ) {
    }

    /**
     * Display & process form to request a password reset.
     */
    #[Route('', name: 'api_forgot_password_request', methods: "POST")]
    public function request(Request $request, SerializerInterface $serializer, MailerInterface $mailer, UserRepository $repository): JsonResponse
    {
        $jsonReceived = $request->getContent();
        $email = $serializer->deserialize($jsonReceived, ResetPassword::class, "json")->getEmail();

        $user = $repository->findOneBy(["email" => $email]);

        if (!$user) {
            return $this->json([
                "status" => 404,
                "message" => "The email does not exist."
            ], 404);
        }

        try {
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        } catch (ResetPasswordExceptionInterface $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getReason()
            ], 400);
        }

        $email = (new TemplatedEmail())
            ->from(new Address("no-reply@villeparisisbadminton77.fr', 'No Reply - USMV App"))
            ->to($user->getEmail())
            ->subject("Réinitialisation de mon mot de passe")
            ->htmlTemplate("reset_password/email.html.twig")
            ->context([
                "resetToken" => $resetToken,
            ]);

        $mailer->send($email);

        return $this->json([
            "status" => 200,
            "message" => "The email with the reset link has been correctly send."
        ], 200);
    }

    /**
     * Validates and process the reset URL that the user clicked in their email.
     */
    #[Route('/reset', name: 'api_reset_password', methods: "POST")]
    public function reset(Request $request, UserRepository $repository, SerializerInterface $serializer, UserPasswordHasherInterface $hasher): JsonResponse
    {
        $jsonReceived = $request->getContent();
        $reset = $serializer->deserialize($jsonReceived, ResetPassword::class, "json");

        if (null === $reset->getResetToken()) {
            throw $this->createNotFoundException('No reset password token found in the URL or in the session.');
        }

        try {
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($reset->getResetToken());
        } catch (ResetPasswordExceptionInterface $e) {
            $this->resetPasswordHelper->removeResetRequest($reset->getResetToken());
            return $this->json([
                "status" => 404,
                "errorMessage" => $e->getReason()
            ], 404);
        }

        if ($reset->getPassword() === $reset->getConfirmPassword()) {
            $this->resetPasswordHelper->removeResetRequest($reset->getResetToken());
            $user->setPassword($hasher->hashPassword($user, $reset->getPassword()));
            $repository->add($user);
            return $this->json($user, 201, context: ["groups" => "user:read"]);
        } else {
            return $this->json([
                "status" => 400,
                "message" => "The password does not correspond to the confirm password."
            ], 400);
            // throw new Exception("The password does not correspond to the confirm password.");
        }
    }
}
