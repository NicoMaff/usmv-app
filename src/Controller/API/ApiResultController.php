<?php

namespace App\Controller\API;

use App\Entity\Result;
use App\Repository\ResultRepository;
use App\Repository\TournamentRegistrationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route("/api")]
class ApiResultController extends AbstractController
{
    // /**
    //  * POST
    //  * An ADMIN can create the results of one user's for one tournament
    //  */
    // #[Route("/admin/result/{memberId}", name: "api_result_createOneMemberResult", methods: ["POST"])]
    // public function createOneMemberResult(ResultRepository $resultRepo,TournamentRegistrationRepository $registrationRepo, Request $request, SerializerInterface $serializer): JsonResponse
    // {   
    //     $jsonReceived = $request->getContent();
    //     $result = $serializer->deserialize($jsonReceived, Result::class, "json");

    //     $result->setTournamentRegistration($registrationRepo->find())
    // }

    /**
     * GET
     * An ADMIN can read all results
     */

    /**
     * GET
     * An ADMIN can read all results of one member
     */

    /**
     * GET
     * An ADMIN can read a result of one member tournament
     */



    /**
     * UPDATE 
     * An ADMIN can toggle one user's result validation
     */
    #[Route("/admin/tournament-registration/result/{id}", name: "api_result_toggleOneMemberResultValidation", methods: ["PATCH"])]
    public function toggleOneMemberResultValidation(ResultRepository $repository, int $id): JsonResponse
    {
        $result = $repository->find($id);
        if ($result->getAreResultsValidated() === NULL) {
            $result->setAreResultsValidated(true);
        } else {
            $result->setAreResultsValidated(!$result->getAreResultsValidated());
        }
        return $this->json($result, 201, context: ["groups" => "registration:update"]);
    }
}
