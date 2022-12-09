<?php

namespace App\Controller\API;

use App\Entity\TournamentRegistration;
use App\Repository\TournamentRegistrationRepository;
use App\Repository\TournamentRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route("/api")]
class ApiTournamentRegistrationController extends AbstractController
{
    /**
     * CREATE
     * An user (admin or member) can make a tournament registration request from an empty form
     */
    #[Route('/tournament-registration', name: 'api_tournamentRegistration_createMemberRequest', methods: "POST")]
    public function createMemberRequest(Request $request, TournamentRegistrationRepository $tournamentRegistrationRepo, TournamentRepository $tournamentRepo, UserRepository $userRepo, SerializerInterface $serializer): JsonResponse
    {
        $jsonReceived = $request->getContent();
        $registration = $serializer->deserialize($jsonReceived, TournamentRegistration::class, "json");

        $registration->setUser($userRepo->find($registration->getUserId()));
        $registration->setTournament($tournamentRepo->find($registration->getTournamentId()));

        $tournamentRegistrationRepo->add($registration, true);

        return $this->json($registration, 201, context: ["groups" => "registration:create"]);
    }
}
