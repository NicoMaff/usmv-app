<?php

namespace App\Controller\API;

use App\Entity\User;
use App\Repository\UserRepository;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

#[Route('/api/reset-password')]
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
  #[Route('', name: 'api_forgot_password_request')]
  public function request(Request $request, SerializerInterface $serializer, MailerInterface $mailer, UserRepository $repository): JsonResponse
  {
    $jsonReceived = $request->getContent();
    $email = $serializer->deserialize($jsonReceived, User::class, "json")->getEmail();

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

      $email = (new TemplatedEmail())
        ->from(new Address('no-reply@villeparisisbadminton77.fr', 'Reset Password - VillepaBoard'))
        ->to($user->getEmail())
        ->subject('Your password reset request')
        ->htmlTemplate('reset_password/email.html.twig')
        ->context([
          'resetToken' => $resetToken,
        ]);

      $mailer->send($email);

      return $this->json([
        "status" => 200,
        "message" => "The email with the reset link has been correctly send."
      ], 200);
    }
  }

  /**
   * Validates and process the reset URL that the user clicked in their email.
   */
  #[Route('/reset/{token}', name: 'api_reset_password')]
  public function reset(Request $request, UserRepository $repository, SerializerInterface $serializer, UserPasswordHasherInterface $hasher, TranslatorInterface $translator, string $token = null): Response
  {
    if (null === $token) {
      throw $this->createNotFoundException('No reset password token found in the URL or in the session.');
    }

    try {
      $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
    } catch (ResetPasswordExceptionInterface $e) {
      return $this->json([
        "status" => 404,
        "errorMessage" => $e->getReason()
      ], 404);
    }

    $jsonReceived = $request->getContent();
    $newPassword = $serializer->deserialize($jsonReceived, User::class, "json");

    $this->resetPasswordHelper->removeResetRequest($token);

    if ($newPassword->password() === $newPassword->getConfirmPassword()) {
      $user->setPassword($hasher->hashPassword($user, $newPassword->getPassword()));
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
