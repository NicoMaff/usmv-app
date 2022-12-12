<?php

namespace App\Controller\API;

use ApiPlatform\Validator\ValidatorInterface as ValidatorValidatorInterface;
use App\Entity\Tournament;
use App\Entity\TournamentRegistration;
use App\Entity\User;
use App\Repository\TournamentRegistrationRepository;
use App\Repository\TournamentRepository;
use App\Repository\UserRepository;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route("/api")]
class ApiTournamentRegistrationController extends AbstractController
{
    /**
     * CREATE
     * An ADMIN can make a tournament registration for a member.
     * The member may already exist or he can be created. 
     * To create a member, his lastName, firstName and his email have to be filled out. The password will be set automatically.
     * The userId property will be set by the admin.
     * The tournament may already exist or he can be created.
     * To create a tournament, the city and the startDate have to be filled out. The name can be filled out but it is not required.
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route("/admin/tournament-registration", name: "api_tournamentRegistration_createMemberRegistration", methods: "POST")]
    public function createMemberRegistration(Request $request, TournamentRegistrationRepository $tournamentRegistrationRepo, TournamentRepository $tournamentRepo, UserRepository $userRepo, SerializerInterface $serializer, UserPasswordHasherInterface $hasher, ValidatorInterface $validator): JsonResponse
    {
        $jsonReceived = $request->getContent();
        $registration = $serializer->deserialize($jsonReceived, TournamentRegistration::class, "json");

        if ($registration->getUserId() === null && $registration->getUserLastName() && $registration->getUserFirstName() && $registration->getUserEmail()) {
            $user = new User();

            $password = "";
            for ($i = 0; $i < 6; $i++) {
                $alpha = mt_rand(97, 122);
                $alphaMaj = mt_rand(65, 90);
                $char = mt_rand(1, 2) === 1 ? mt_rand(0, 9) : (mt_rand(1, 2) === 1 ? chr($alpha) : chr($alphaMaj));
                $password .= $char;
            }

            $user
                ->setLastName($registration->getUserLastName())
                ->setFirstName($registration->getUserFirstName())
                ->setEmail($registration->getUserEmail())
                ->setPassword($password)
                ->setConfirmPassword($password);

            $errors = $validator->validate($user);
            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }
            $user->setPassword($hasher->hashPassword($user, $password));

            $userRepo->add($user, true);
            $registration->setUserId($user->getId());
        } else {
            throw new Exception("At least one user's information is missing");
        }

        if ($registration->getTournamentId() === null && $registration->getTournamentCity() && $registration->getTournamentStartDate()) {
            $tournament = new Tournament();
            $tournament
                ->setCity($registration->getTournamentCity())
                ->setStartDate($registration->getTournamentStartDate());

            if ($registration->getTournamentName()) {
                $tournament->setName($registration->getTournamentName());
            }

            if (in_array($tournament->getStartDate()->format("m"), ["09", "10", "11", "12"])) {
                $tournament->setSeason("20" . $tournament->getStartDate()->format("y") . "/20" . $tournament->getStartDate()->format("y") + 1);
            } else if (in_array($tournament->getStartDate()->format("m"), ["01", "02", "03", "04", "05", "06", "07", "08"])) {
                $tournament->setSeason("20" . $tournament->getStartDate()->format("y") - 1 . "/20" . $tournament->getStartDate()->format("y"));
            }

            $errors = $validator->validate($tournament);
            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }

            $tournamentRepo->add($tournament, true);
            $registration->setTournamentId($tournament->getId());
        } else {
            throw new Exception("At least one tournament's information is missing");
        }

        $registration->setUser($userRepo->find($registration->getUserId()));
        $registration->setTournament($tournamentRepo->find($registration->getTournamentId()));

        $tournamentRegistrationRepo->add($registration, true);

        return $this->json($registration, 201, context: ["groups" => "registration:create"]);
    }

    /**
     * CREATE
     * A MEMBER can create a tournament registration.
     * The userId property is set from the member id only when he is authenticated.
     * The tournament may already exist or he can be created.
     * To create a tournament, the city and the startDate have to be filled out. The name can be filled out but it is not required.
     */
    #[IsGranted("ROLE_MEMBER")]
    #[Route("/tournament-registration", "api_tournamentRegistration_createRegistration", methods: "POST")]
    public function createRegistration(Request $request, UserRepository $userRepo, TournamentRegistrationRepository $tournamentRegistrationRepo, TournamentRepository $tournamentRepo, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $user = $userRepo->findBy(["email" => $this->getUser()->getUserIdentifier()])[0];
        $jsonReceived = $request->getContent();
        $registration = $serializer->deserialize($jsonReceived, TournamentRegistration::class, "json");

        if ($registration->getTournamentId() === null && $registration->getTournamentCity() && $registration->getTournamentStartDate()) {
            $tournament = new Tournament();
            $tournament
                ->setCity($registration->getTournamentCity())
                ->setStartDate($registration->getTournamentStartDate());

            if ($registration->getTournamentName()) {
                $tournament->setName($registration->getTournamentName());
            }

            if (in_array($tournament->getStartDate()->format("m"), ["09", "10", "11", "12"])) {
                $tournament->setSeason("20" . $tournament->getStartDate()->format("y") . "/20" . $tournament->getStartDate()->format("y") + 1);
            } else if (in_array($tournament->getStartDate()->format("m"), ["01", "02", "03", "04", "05", "06", "07", "08"])) {
                $tournament->setSeason("20" . $tournament->getStartDate()->format("y") - 1 . "/20" . $tournament->getStartDate()->format("y"));
            }

            $errors = $validator->validate($tournament);
            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }

            $tournamentRepo->add($tournament, true);
            $registration->setTournamentId($tournament->getId());
        } else {
            throw new Exception("At least one tournament's information is missing");
        }

        $registration->setUser($user->getId());
        $registration->setTournament($registration->find($registration->getTournamentId()));

        $tournamentRegistrationRepo->add($registration, true);

        return $this->json($registration, 201, context: ["groups" => "registration:create"]);
    }

    /**
     * CREATE
     * An user unauthenticated can create a tournament registration.
     * He must will fill his last-name, his first-name and his email.
     * The tournament may already exist or he can be created.
     * To create a tournament, the city and the startDate have to be filled out. The name can be filled out but it is not required.
     */
    #[Route("/tournament-registration-unauthenticated", "api_tournamentRegistration_createRegistrationForUnauthenticated", methods: "POST")]
    public function createRegistrationForUnauthenticated(Request $request, UserRepository $userRepo, TournamentRegistrationRepository $tournamentRegistrationRepo, TournamentRepository $tournamentRepo, SerializerInterface $serializer, UserPasswordHasherInterface $hasher, ValidatorInterface $validator): JsonResponse
    {
        $jsonReceived = $request->getContent();
        $registration = $serializer->deserialize($jsonReceived, TournamentRegistration::class, "json");

        if ($registration->getUserLastName() && $registration->getUserFirstName() && $registration->getUserEmail()) {
            $user = new User();

            $password = "";
            for ($i = 0; $i < 6; $i++) {
                $alpha = mt_rand(97, 122);
                $alphaMaj = mt_rand(65, 90);
                $char = mt_rand(1, 2) === 1 ? mt_rand(0, 9) : (mt_rand(1, 2) === 1 ? chr($alpha) : chr($alphaMaj));
                $password .= $char;
            }

            $user
                ->setLastName($registration->getUserLastName())
                ->setFirstName($registration->getUserFirstName())
                ->setEmail($registration->getUserEmail())
                ->setPassword($password)
                ->setConfirmPassword($password);

            $errors = $validator->validate($user);
            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }

            $user->setPassword($hasher->hashPassword($user, $password));

            $userRepo->add($user, true);
            $registration->setUserId($user->getId());
        } else {
            throw new Exception("At least one user's information is missing");
        }

        if ($registration->getTournamentId() === null && $registration->getTournamentCity() && $registration->getTournamentStartDate()) {
            $tournament = new Tournament();
            $tournament
                ->setCity($registration->getTournamentCity())
                ->setStartDate($registration->getTournamentStartDate());

            if ($registration->getTournamentName()) {
                $tournament->setName($registration->getTournamentName());
            }

            if (in_array($tournament->getStartDate()->format("m"), ["09", "10", "11", "12"])) {
                $tournament->setSeason("20" . $tournament->getStartDate()->format("y") . "/20" . $tournament->getStartDate()->format("y") + 1);
            } else if (in_array($tournament->getStartDate()->format("m"), ["01", "02", "03", "04", "05", "06", "07", "08"])) {
                $tournament->setSeason("20" . $tournament->getStartDate()->format("y") - 1 . "/20" . $tournament->getStartDate()->format("y"));
            }
            $tournamentRepo->add($tournament, true);
            $registration->setTournamentId($tournament->getId());
        }

        $registration->setUser($userRepo->find($registration->getUserId()));
        $registration->setTournament($tournamentRepo->find($registration->getTournamentId()));

        $tournamentRegistrationRepo->add($registration, true);

        return $this->json($registration, 201, context: ["groups" => "registration:create"]);
    }


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
