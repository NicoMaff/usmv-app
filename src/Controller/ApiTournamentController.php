<?php

namespace App\Controller;

use App\Entity\Tournament;
use App\Repository\TournamentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route("/api")]
// #[IsGranted("ROLE_MEMBER")]
class ApiTournamentController extends AbstractController
{
    /**
     * CREATE
     * An Admin can create a new tournament
     */
    #[Route('/tournament', name: 'api_tournament_createOne', methods: "POST")]
    public function createOne(Request $request, TournamentRepository $repository, SerializerInterface $serializer): JsonResponse
    {
        $jsonReceived = $request->getContent();
        $tournament = $serializer->deserialize($jsonReceived, Tournament::class, "json");

        if (in_array($tournament->getStartDate()->format("m"), ["09", "10", "11", "12"])) {
            $tournament->setSeason("20" . $tournament->getStartDate()->format("y") . "/20" . $tournament->getStartDate()->format("y") + 1);
        } else if (in_array($tournament->getStartDate()->format("m"), ["01", "02", "03", "04", "05", "06", "07", "08"])) {
            $tournament->setSeason("20" . $tournament->getStartDate()->format("y") - 1 . "/20" . $tournament->getStartDate()->format("y"));
        }

        $repository->add($tournament, true);

        return $this->json($tournament, 201);
    }

    /**
     * READ
     * A member can access to all tournaments available
     */
    #[Route("/tournaments", "api_tournaments_readAll", methods: "GET")]
    public function readAll(TournamentRepository $repository): JsonResponse
    {
        return $this->json($repository->findAll(), 200);
    }
}
