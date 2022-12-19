<?php

namespace App\Controller\API;

use App\Entity\Tournament;
use App\Repository\TournamentRepository;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route("/api")]
class ApiTournamentController extends AbstractController
{
    /**
     * CREATE
     * An Admin can create a new tournament.
     * Only one file can be stored by tournament.
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route('/admin/tournament', name: 'api_tournament_createTournament', methods: "POST")]
    public function createTournament(Request $request, TournamentRepository $repository, SerializerInterface $serializer, ValidatorInterface $validator, SluggerInterface $slugger): JsonResponse
    {
        if ($request->request->get("data")) {
            $jsonReceived = $request->request->get("data");
        } else {
            $jsonReceived = $request->getContent();
        }

        if ($request->files->get("file")) {
            $uploadedFile = $request->files->get("file");
        }

        $tournament = $serializer->deserialize($jsonReceived, Tournament::class, "json");

        if (in_array($tournament->getStartDate()->format("m"), ["09", "10", "11", "12"])) {
            $tournament->setSeason("20" . $tournament->getStartDate()->format("y") . "/20" . $tournament->getStartDate()->format("y") + 1);
        } else if (in_array($tournament->getStartDate()->format("m"), ["01", "02", "03", "04", "05", "06", "07", "08"])) {
            $tournament->setSeason("20" . $tournament->getStartDate()->format("y") - 1 . "/20" . $tournament->getStartDate()->format("y"));
        }

        if ($tournament->getStartDate()->diff($tournament->getEndDate())->days >= 10) {
            throw new Exception("The interval between the start and the end of the tournament is too long");
        }

        if (isset($uploadedFile)) {
            // File settings
            $originalFileName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFileName = $slugger->slug($originalFileName);
            $destination = $this->getParameter("kernel.project_dir") . "/src/data/tournamentsRegulations/";
            $newFileName = $safeFileName . "-" . uniqid() . "." . $uploadedFile->guessExtension();

            try {
                $uploadedFile->move($destination, $newFileName);
            } catch (FileException $e) {
                echo $e->getMessage();
            }

            $tournament->setRegulationFileName($newFileName);
            $tournament->setRegulationFileUrl($destination . $newFileName);
        }

        $errors = $validator->validate($tournament);
        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        $repository->add($tournament, true);
        return $this->json($tournament, 201, context: ["groups" => "tournament:read"]);
    }

    /**
     * READ
     * An user can access to one tournament from its id.
     */
    #[Route("/tournament/{id}", "api_tournament_readTournament", methods: "GET")]
    public function readTournament(TournamentRepository $repository, int $id): JsonResponse
    {
        return $this->json($repository->find($id), 200, context: ["groups" => "tournament:read"]);
    }

    /**
     * READ
     * An user can access to all tournaments available.
     */
    #[Route("/tournaments", "api_tournaments_readAllTournaments", methods: "GET")]
    public function readAllTournaments(TournamentRepository $repository): JsonResponse
    {
        return $this->json($repository->findAll(), 200, context: ["groups" => "tournament:read"]);
    }

    /**
     * UPDATE
     * An admin can update a tournament or a part of tournament from its id
     * WARNING : if the user send file, the method POST is required because multipart/form-data only support POST method (and no PATCH).
     * Only one file is stored by tournament.
     * If a new file is uploaded, it will replace the older.
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route("/admin/tournament/{id}", "api_tournament_updateTournament", methods: ["PATCH", "POST"])]
    public function updateTournament(TournamentRepository $repository, Request $request, int $id, ValidatorInterface $validator, SerializerInterface $serializer, SluggerInterface $slugger): JsonResponse
    {
        if ($request->request->get("data")) {
            $jsonReceived = $request->request->get("data");
        } else {
            $jsonReceived = $request->getContent();
        }

        if ($request->files->get("file")) {
            $uploadedFile = $request->files->get("file");
        }

        if ($request->request->get("deleteFile")) {
            $deleteFile = true;
        } else {
            $deleteFile = false;
        }

        $tournament = $repository->find($id);
        $updatedTournament = $serializer->deserialize($jsonReceived, Tournament::class, "json");

        if ($updatedTournament->getName()) {
            $tournament->setName($updatedTournament->getName());
        }
        if ($updatedTournament->getCity()) {
            $tournament->setCity($updatedTournament->getCity());
        }
        if ($updatedTournament->getStartDate()) {
            $tournament->setStartDate($updatedTournament->getStartDate());
        }
        if ($updatedTournament->getEndDate()) {
            $tournament->setEndDate($updatedTournament->getEndDate());
        }
        if ($updatedTournament->getStandardPrice1()) {
            $tournament->setStandardPrice1($updatedTournament->getStandardPrice1());
        }
        if ($updatedTournament->getStandardPrice2()) {
            $tournament->setStandardPrice2($updatedTournament->getStandardPrice2());
        }
        if ($updatedTournament->getStandardPrice3()) {
            $tournament->setStandardPrice3($updatedTournament->getStandardPrice1());
        }
        if ($updatedTournament->getElitePrice1()) {
            $tournament->setElitePrice1($updatedTournament->getElitePrice1());
        }
        if ($updatedTournament->getElitePrice2()) {
            $tournament->setElitePrice2($updatedTournament->getElitePrice2());
        }
        if ($updatedTournament->getElitePrice3()) {
            $tournament->setElitePrice3($updatedTournament->getElitePrice3());
        }
        if ($updatedTournament->getPriceSingle()) {
            $tournament->setPriceSingle($updatedTournament->getPriceSingle());
        }
        if ($updatedTournament->getPriceDouble()) {
            $tournament->setPriceDouble($updatedTournament->getPriceDouble());
        }
        if ($updatedTournament->getPriceMixed()) {
            $tournament->setPriceMixed($updatedTournament->getPriceMixed());
        }
        if ($updatedTournament->getRegistrationClosingDate()) {
            $tournament->setRegistrationClosingDate($updatedTournament->getRegistrationClosingDate());
        }
        if ($updatedTournament->getRandomDraw()) {
            $tournament->setRandomDraw($updatedTournament->getRandomDraw());
        }
        if ($updatedTournament->getEmailContact()) {
            $tournament->setEmailContact($updatedTournament->getEmailContact());
        }
        if ($updatedTournament->getTelContact()) {
            $tournament->setTelContact($updatedTournament->getTelContact());
        }
        if ($updatedTournament->getRegistrationMethod()) {
            $tournament->setRegistrationMethod($updatedTournament->getRegistrationMethod());
        }
        if ($updatedTournament->getPaymentMethod()) {
            $tournament->setPaymentMethod($updatedTournament->getPaymentMethod());
        }
        if ($updatedTournament->getComment()) {
            $tournament->setComment($updatedTournament->getComment());
        }

        if (in_array($tournament->getStartDate()->format("m"), ["09", "10", "11", "12"])) {
            $tournament->setSeason("20" . $tournament->getStartDate()->format("y") . "/20" . $tournament->getStartDate()->format("y") + 1);
        } else if (in_array($tournament->getStartDate()->format("m"), ["01", "02", "03", "04", "05", "06", "07", "08"])) {
            $tournament->setSeason("20" . $tournament->getStartDate()->format("y") - 1 . "/20" . $tournament->getStartDate()->format("y"));
        }

        if (isset($uploadedFile)) {

            // File settings
            $originalFileName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFileName = $slugger->slug($originalFileName);
            $destination = $this->getParameter("kernel.project_dir") . "/src/data/tournamentsRegulations/";
            $newFileName = $safeFileName . "-" . uniqid() . "." . $uploadedFile->guessExtension();

            if ($tournament->getRegulationFileName() && file_exists($tournament->getRegulationFileUrl())) {
                unlink($tournament->getRegulationFileUrl());
            }

            try {
                $uploadedFile->move($destination, $newFileName);
            } catch (FileException $e) {
                echo $e->getMessage();
            }

            $tournament->setRegulationFileName($newFileName);
            $tournament->setRegulationFileUrl($destination . $newFileName);
        }

        if ($deleteFile) {
            if ($tournament->getRegulationFileName() && file_exists($tournament->getRegulationFileUrl())) {
                unlink($tournament->getRegulationFileUrl());
                $tournament->setRegulationFileName(null);
                $tournament->setRegulationFileUrl(null);
            }
        }

        $tournament->setUpdatedAt(new \DateTime());

        $errors = $validator->validate($tournament);
        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        $repository->add($tournament, true);
        return $this->json($tournament, 201);
    }

    /**
     * DELETE
     * An admin can delete a tournament from its id.
     * If the tournament have a stored file, it will be remove at the same time than the tournament instance.
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route("/admin/tournament/{id}", "api_tournament_deleteTournament", methods: "DELETE")]
    public function deleteTournament(TournamentRepository $repository, int $id): JsonResponse
    {
        $tournament = $repository->find($id);
        if ($tournament->getRegulationFileName() && file_exists($tournament->getRegulationFileUrl())) {
            unlink($tournament->getRegulationFileUrl());
        }
        $repository->remove($tournament, true);

        return $this->json([
            "status" => 200,
            "message" => "The tournament with the id #$id has been correctly deleted!"
        ], 200);
    }
}
