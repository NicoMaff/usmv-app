<?php

namespace App\Controller\API;

use App\Entity\Result;
use App\Repository\ResultRepository;
use App\Repository\TournamentRegistrationRepository;
use Exception;
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
     * GET
     * A MEMBER can read one of his results
     */

    /**
     * GET
     * A MEMBER can read all of his results
     */
    #[Route("/results", name: "api_results_readAllResults", methods: ["GET"])]
    public function readAllResults(ResultRepository $repository): JsonResponse
    {
        return $this->json($repository->findAll(), 200, context: ["groups" => "result:read"]);
    }


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
        return $this->json($result, 201, context: ["groups" => "result:update"]);
    }

    /**
     * PATCH
     * A MEMBER can update one of his results
     */
    #[Route("/result/{id}", name: "api_result_updateOneResult", methods: ["PATCH"])]
    public function updateOneResult(ResultRepository $repository, int $id, Request $request, SerializerInterface $serializer): JsonResponse
    {
        // $result = $repository->findOneBy(["user" => $this->getUser(), "id" => $id]);
        $result = $repository->find($id);
        $jsonReceived = $request->getContent();
        $updatedResult = $serializer->deserialize($jsonReceived, Result::class, "json");

        if ($result === null) {
            throw new Exception("The result's id selected does not belong to this user.");
        }

        if ($result->getSingleStageReached() !== $updatedResult->getSingleStageReached()) $result->setSingleStageReached($updatedResult->getSingleStageReached());
        if ($result->getDoubleStageReached() !== $updatedResult->getDoubleStageReached()) $result->setDoubleStageReached($updatedResult->getDoubleStageReached());
        if ($result->getMixedStageReached() !== $updatedResult->getMixedStageReached()) $result->setMixedStageReached($updatedResult->getMixedStageReached());
        if ($result->getComment() !== $updatedResult->getComment()) $result->setComment($updatedResult->getComment());

        if (
            $result->getSingleStageReached() !== $updatedResult->getSingleStageReached() ||
            $result->getDoubleStageReached() !== $updatedResult->getDoubleStageReached() ||
            $result->getMixedStageReached() !== $updatedResult->getMixedStageReached()
        ) {
            $result->setAreResultsValidated(false);
        }
        // dd($result);
        $result->setUpdatedAt(new \DateTime());
        $repository->add($result, true);
        return $this->json($result, 201, context: ["groups" => "result:update"]);
    }
}
