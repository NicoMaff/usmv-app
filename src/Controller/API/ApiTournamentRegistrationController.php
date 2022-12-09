<?php

namespace App\Controller\API;

use App\Entity\TournamentRegistration;
use App\Repository\TournamentRegistrationRepository;
use App\Repository\TournamentRepository;
use App\Repository\UserRepository;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * An admin can make a tournament registration for a member
     * The usedId property will be set by the admin 
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route("/tournament-registration", name: "api_tournamentRegistration_createMemberRegistration", methods: "POST")]
    public function createMemberRegistration(Request $request, TournamentRegistrationRepository $tournamentRegistrationRepo, TournamentRepository $tournamentRepo, UserRepository $userRepo, SerializerInterface $serializer): JsonResponse
    {
        $jsonReceived = $request->getContent();
        $registration = $serializer->deserialize($jsonReceived, TournamentRegistration::class, "json");

        $registration->setUser($userRepo->find($registration->getUserId()));
        $registration->setTournament($tournamentRepo->find($registration->getTournamentId()));

        $tournamentRegistrationRepo->add($registration, true);

        return $this->json($registration, 201, context: ["groups" => "registration:create"]);
    }

    /**
     * CREATE
     * A member can create a tournament registration
     * The userId property is set from the member id only when he is authenticated
     * createRegistration
     */

    /**
     * readOneMemberRegistration
     * readAllMemberRegistrations
     */

    /**
     * READ
     * A member can get details of his tournament registration that he is registered
     */
    #[Route("/tournament-registration/{id}", "api_tournamentRegistration_readOneRegistration", methods: "GET")]
    public function readOneRegistration(UserRepository $userRepo, TournamentRegistrationRepository $repository, int $id): JsonResponse
    {
        $user = $userRepo->findBy(["email" => $this->getUser()->getUserIdentifier()])[0];
        $registration = $repository->find($id);

        if ($user->getId() !== $registration->getUser()->getId()) {
            throw new Exception("You don't have permission to access this tournament registration.");
        } else {
            $registration->setUserId($registration->getUser()->getId());
            $registration->setTournamentId($registration->getTournament()->getId());
            return $this->json($registration, 200, context: ["groups" => "registration:read"]);
        }
    }

    /**
     * READ
     * A member can get details of all his tournaments registrations that he is registered
     */
    #[Route("/tournament-registrations", "api_tournamentRegistration_readAllRegistrations", methods: "GET")]
    public function readAllRegistrations(UserRepository $userRepo, TournamentRegistrationRepository $repository): JsonResponse
    {
        $user = $userRepo->findBy(["email" => $this->getUser()->getUserIdentifier()])[0];
        $registrations = $repository->findAll();

        $registrationsToReturn = [];
        foreach ($registrations as $registration) {
            if ($user->getId() !== $registration->getUser()->getId()) {
                throw new Exception("You don't have permission to access one or some tournament registrations.");
            } else {
                $registration->setUserId($registration->getUser()->getId());
                $registration->setTournamentId($registration->getTournament()->getId());
                array_push($registrationsToReturn, $registration);
            }
        }
        return $this->json($registrationsToReturn, 200, context: ["groups" => "registration:read"]);
    }
}
