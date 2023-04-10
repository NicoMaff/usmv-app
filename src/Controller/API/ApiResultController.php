<?php

namespace App\Controller\API;

use App\Entity\Result;
use App\Repository\ResultRepository;
use App\Repository\UserRepository;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route("/api")]
class ApiResultController extends AbstractController
{
    /**
     * GET
     * An ADMIN can read all results
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route("/admin/results", name: "api_result_readAllMembersResults", methods: ["GET"])]
    public function readAllMembersResults(ResultRepository $repository): JsonResponse
    {
        return $this->json($repository->findAll(), 200, context: ["groups" => "result:read"]);
    }

    /**
     * GET
     * An ADMIN can read all results of one member.
     * The member is identified by his ID in params.
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route("/admin/results/{memberId}", name: "api_result_readAllResultsOfOneMember", methods: "GET")]
    public function readAllResultsOfOneMember(ResultRepository $resultRepo, UserRepository $userRepo, int $memberId): JsonResponse
    {
        return $this->json($resultRepo->findBy(["User" => $userRepo->find($memberId)]), 200, context: ["groups" => "result:read"]);
    }

    /**
     * GET
     * An ADMIN can read one member result
     * The result is identified by its id in params
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route("/admin/result/{resultId}", name: "api_result_readOneMemberResult", methods: "GET")]
    public function readOneMemberResult(ResultRepository $repository, int $resultId): JsonResponse
    {
        return $this->json($repository->find($resultId), 200, context: ["groups" => "result:read"]);
    }

    /**
     * GET
     * A MEMBER can read one of his results
     */
    #[IsGranted("ROLE_MEMBER")]
    #[Route("/result/{resultId}", name: "api_result_readOneResult", methods: "GET")]
    public function readOneResult(ResultRepository $repository, int $resultId): JsonResponse
    {
        $result = $repository->findOneBy(["id" => $resultId, "User" => $this->getUser()]);
        // dd($repository->findBy(["User" => $this->getUser()]));

        if ($result === null) {
            throw new Exception("The selected result does not belong to this user.");
        }

        return $this->json($result, 200, context: ["groups" => "result:read"]);
    }

    /**
     * GET
     * A MEMBER can read all of his results
     */
    #[IsGranted("ROLE_MEMBER")]
    #[Route("/results", name: "api_result_readAllResults", methods: ["GET"])]
    public function readAllResults(ResultRepository $repository): JsonResponse
    {

        return $this->json($repository->findBy(["User" => $this->getUser()]), 200, context: ["groups" => "result:read"]);
    }

    /**
     * PATCH
     * An ADMIN can modify one member result.
     * The result is identified by its ID.
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route("/admin/result/{resultId}", name: "api_result_updateOneMemberResult", methods: "PATCH")]

    public function updateOneMemberResult(ResultRepository $repository, int $resultId, Request $request, SerializerInterface $serializer): JsonResponse
    {
        $result = $repository->find($resultId);
        $updatedResult = $serializer->deserialize($request->getContent(), Result::class, "json");

        if ($updatedResult->getSingleStageReached() !== NULL) $result->setSingleStageReached($updatedResult->getSingleStageReached());
        if ($updatedResult->getDoubleStageReached() !== NULL) $result->setDoubleStageReached($updatedResult->getDoubleStageReached());
        if ($updatedResult->getMixedStageReached() !== NULL) $result->setMixedStageReached($updatedResult->getMixedStageReached());
        if ($updatedResult->getComment() !== NULL) $result->setComment($updatedResult->getComment());

        $result->setAreResultsValidated(true);
        $result->setUpdatedAt(new \DateTime());
        $repository->add($result, true);
        return $this->json($result, 201, context: ["groups" => "result:update"]);
    }

    /**
     * PATCH 
     * An ADMIN can toggle one user's result validation
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route("/admin/result/toggle_validation/{id}", name: "api_result_toggleOneMemberResultValidation", methods: ["PATCH"])]
    public function toggleOneMemberResultValidation(ResultRepository $repository, int $id): JsonResponse
    {
        $result = $repository->find($id);

        if ($result->getAreResultsValidated() === NULL) {
            $result->setAreResultsValidated(true);
        } else {
            $result->setAreResultsValidated(!$result->getAreResultsValidated());
        }

        $result->setUpdatedAt(new \DateTime());
        $repository->add($result, true);
        return $this->json($result, 201, context: ["groups" => "result:update"]);
    }

    /**
     * PATCH
     * A MEMBER can update one of his results
     */
    #[IsGranted("ROLE_MEMBER")]
    #[Route("/result/{id}", name: "api_result_updateOneResult", methods: ["PATCH"])]
    public function updateOneResult(ResultRepository $repository, int $id, Request $request, SerializerInterface $serializer): JsonResponse
    {
        $result = $repository->findOneBy(["User" => $this->getUser(), "id" => $id]);
        $updatedResult = $serializer->deserialize($request->getContent(), Result::class, "json");

        if ($result === null) {
            throw new Exception("The selected result does not belong to this user.");
        }

        if ($updatedResult->getSingleStageReached() !== NULL) $result->setSingleStageReached($updatedResult->getSingleStageReached());
        if ($updatedResult->getDoubleStageReached() !== NULL) $result->setDoubleStageReached($updatedResult->getDoubleStageReached());
        if ($updatedResult->getMixedStageReached() !== NULL) $result->setMixedStageReached($updatedResult->getMixedStageReached());
        if ($updatedResult->getComment() !== NULL) $result->setComment($updatedResult->getComment());

        $result->setAreResultsValidated(false);

        $result->setUpdatedAt(new \DateTime());
        $repository->add($result, true);
        return $this->json($result, 201, context: ["groups" => "result:update"]);
    }
}
