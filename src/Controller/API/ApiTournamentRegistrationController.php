<?php

namespace App\Controller\API;

use App\Entity\Result;
use App\Entity\Tournament;
use App\Entity\TournamentRegistration;
use App\Entity\User;
use App\Repository\ResultRepository;
use App\Repository\TournamentRegistrationRepository;
use App\Repository\TournamentRepository;
use App\Repository\UserRepository;
use Exception;
use phpDocumentor\Reflection\Types\Null_;
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
     * An ADMIN can create a tournament registration for a member.
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
        $registration = $serializer->deserialize($request->getContent(), TournamentRegistration::class, "json");

        /** Set the User */
        if ($registration->getUserLastName() !== null && $registration->getUserFirstName() !== null && $registration->getUserEmail() !== null) {
            $registeredUser = $userRepo->findOneBy([
                "email" => $registration->getUserEmail(),
                "firstName" => $registration->getUserFirstName(),
                "lastName" => $registration->getUserLastName()
            ]);

            if ($registeredUser !== null) {
                $registration->setUser($registeredUser);
                $registration->setUserEmail(null);
                $registration->setUserLastName(null);
                $registration->setUserFirstName(null);
            } else {
                $registration->setUser(null);
            }
        } else {
            throw new Exception("At least, one information is missing about user.");
        }

        /** Create User if data don't correspond to an instance in bdd and if the property haveToCreateUser is true */
        if ($registration->getHaveToCreateUser()) {
            if (
                $registration->getUserLastName()
                && $registration->getUserFirstName()
                && $registration->getUserEmail()
            ) {
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

                $registration->setUser($user);
                $registration->setUserEmail(null);
                $registration->setUserLastName(null);
                $registration->setUserFirstName(null);
            } else {
                throw new Exception("At least one user's information is missing");
            }
        }

        /** Set the tournament */
        if ($registration->getTournamentCity() !== null && $registration->getTournamentStartDate() !== null) {
            $registeredTournament = $tournamentRepo->findOneBy([
                "city" => $registration->getTournamentCity(),
                "startDate" => $registration->getTournamentStartDate(),
            ]);

            if ($registeredTournament !== null) {
                $registration->setTournament($registeredTournament);
                $registration->setTournamentName(null);
                $registration->setTournamentCity(null);
                $registration->setTournamentStartDate(null);
                $registration->setTournamentEndDate(null);
            } else {
                $registration->setTournament(null);
            }
        } else {
            throw new Exception("At least one user's information is missing about the tournament");
        }

        /** Create Tournament if data don't correspond to an instance in bdd and if haveToCreateTournament is true */
        if ($registration->getHaveToCreateTournament()) {
            if ($registration->getTournamentCity() && $registration->getTournamentStartDate()) {
                $tournament = new Tournament();
                $tournament
                    ->setCity($registration->getTournamentCity())
                    ->setStartDate($registration->getTournamentStartDate());

                if ($registration->getTournamentName()) {
                    $tournament->setName($registration->getTournamentName());
                }
                if ($registration->getTournamentEndDate()) {
                    $tournament->setEndDate($registration->getTournamentEndDate());
                }

                if (in_array($tournament->getStartDate()->format("m"), ["09", "10", "11", "12"])) {
                    $tournament->setSeason("20" . $tournament->getStartDate()->format("y") . "/20" . $tournament->getStartDate()->format("y") + 1);
                } elseif (in_array($tournament->getStartDate()->format("m"), ["01", "02", "03", "04", "05", "06", "07", "08"])) {
                    $tournament->setSeason("20" . $tournament->getStartDate()->format("y") - 1 . "/20" . $tournament->getStartDate()->format("y"));
                }

                $errors = $validator->validate($tournament);
                if (count($errors) > 0) {
                    return $this->json($errors, 400);
                }

                $tournamentRepo->add($tournament, true);
                $registration->setTournament($tournament);
                $registration->setTournamentName(null);
                $registration->setTournamentCity(null);
                $registration->setTournamentStartDate(null);
                $registration->setTournamentEndDate(null);
            } else {
                throw new Exception("At least one tournament's information is missing");
            }
        }

        $registration->setResult(new Result());
        $tournamentRegistrationRepo->add($registration, true);

        return $this->json($registration, 201, context: ["groups" => "registration:create"]);
    }


    /**
     * CREATE
     * A MEMBER can create a tournament registration.
     * The user is set from the authentication session.
     * The tournament may already exist. It will be selected if the city and the start date exist and exactly correspond to the saved tournament.
     * Otherwise, the tournament property will be set on null.
     */
    #[IsGranted("ROLE_MEMBER")]
    #[Route("/tournament-registration", "api_tournamentRegistration_createRegistration", methods: "POST")]
    public function createRegistration(Request $request, TournamentRegistrationRepository $tournamentRegistrationRepo, TournamentRepository $tournamentRepo, SerializerInterface $serializer): JsonResponse
    {
        // $user = $userRepo->findBy(["email" => $this->getUser()->getUserIdentifier()])[0];
        $jsonReceived = $request->getContent();
        $registration = $serializer->deserialize($jsonReceived, TournamentRegistration::class, "json");

        /** Set the user */
        $registration->setUser($this->getUser());
        $registration->setUserEmail(null);
        $registration->setUserLastName(null);
        $registration->setUserFirstName(null);

        /**
         * Check if the tournament's data correspond to an instance of tournament.
         * If true, set the tournament's instance to the registration.
         * Otherwise, use the tournament's data without create a new instance.
         * The front have to send only the data and not the tournament id.
         */
        $tournamentSearch = [
            "name" => $registration->getTournamentName(),
            "city" => $registration->getTournamentCity(),
            "startDate" => $registration->getTournamentStartDate(),
            "endDate" => $registration->getTournamentEndDate()
        ];
        if ($tournamentRepo->findOneBy($tournamentSearch)) {
            $registration->setTournament($tournamentRepo->findOneBy($tournamentSearch));
            $registration->setTournamentName(null);
            $registration->setTournamentCity(null);
            $registration->setTournamentStartDate(null);
            $registration->setTournamentEndDate(null);
        } else {
            $registration->setTournament(null);
        }

        $registration->setResult(new Result());
        $tournamentRegistrationRepo->add($registration, true);

        return $this->json($registration, 201, context: ["groups" => "registration:create"]);
    }

    /**
     * CREATE
     * An USER UNAUTHENTICATED can create a tournament registration.
     * He must will fill his last-name, his first-name and his email.
     * If these three properties exist and correspond to a saved user, the user property will be set.
     * The tournament may already exist. It will be selected if the city and the start date exist and exactly correspond to the saved tournament.
     * Otherwise, the tournament property will be set on null.
     */
    #[Route("/tournament-registration-unauthenticated", "api_tournamentRegistration_createRegistrationForUnauthenticated", methods: "POST")]
    public function createRegistrationForUnauthenticated(Request $request, UserRepository $userRepo, TournamentRegistrationRepository $tournamentRegistrationRepo, TournamentRepository $tournamentRepo, SerializerInterface $serializer): JsonResponse
    {
        $jsonReceived = $request->getContent();
        $registration = $serializer->deserialize($jsonReceived, TournamentRegistration::class, "json");

        /** Set the user if these three properties correspond to an existing user */
        $userSearch = [
            "email" => $registration->getUserEmail(),
            "lastName" => $registration->getUserLastName(),
            "firstName" => $registration->getUserFirstName()
        ];
        if ($userRepo->findOneBy($userSearch)) {
            $registration->setUser($userRepo->findOneBy($userSearch));
            $registration->setUserEmail(null);
            $registration->setUserLastName(null);
            $registration->setUserFirstName(null);
            $result = new Result();
        }

        /**
         * Check if the tournament's data correspond to an instance of tournament.
         * If true, set the tournament's instance to the registration.
         * Otherwise, use the tournament's data without create a new instance.
         * The front have to send only the data and not the tournament id.
         */
        $tournamentSearch = [
            "name" => $registration->getTournamentName(),
            "city" => $registration->getTournamentCity(),
            "startDate" => $registration->getTournamentStartDate(),
            "endDate" => $registration->getTournamentEndDate()
        ];
        if ($tournamentRepo->findOneBy($tournamentSearch)) {
            $registration->setTournament($tournamentRepo->findOneBy($tournamentSearch));
            $registration->setTournamentName(null);
            $registration->setTournamentCity(null);
            $registration->setTournamentStartDate(null);
            $registration->setTournamentEndDate(null);
        }

        if ($result) {
            $registration->setResult($result);
        }
        $tournamentRegistrationRepo->add($registration, true);

        return $this->json($registration, 201, context: ["groups" => "registration:create"]);
    }


    /**
     * READ
     * An ADMIN can get details of one member registration from registration's id.
     * The selection only concern the registration (and not the user). 
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route("/admin/tournament-registration/{id}", "api_tournamentRegistration_readOneMemberRegistration", methods: ["GET"])]
    public function readOneMemberRegistration(TournamentRegistrationRepository $repository, int $id): JsonResponse
    {
        return $this->json($repository->find($id), 200, context: ["groups" => "registration:read"]);
    }

    /**
     * READ
     * An ADMIN can get details of all member's registrations.
     * The selection only concern the registration (and not the user). 
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route("/admin/tournament-registrations", "api_tournamentRegistration_readAllMemberRegistrations", methods: "GET")]
    public function readAllMembersRegistrations(TournamentRegistrationRepository $repository): JsonResponse
    {
        return $this->json($repository->findAll(), 200, context: ["groups" => "registration:read"]);
    }

    /**
     * READ
     * A MEMBER can get details of a tournament registration when he is registered to this tournament.
     * The member needs to be authenticated to access one of his registrations.
     */
    #[IsGranted("ROLE_MEMBER")]
    #[Route("/tournament-registration/{id}", "api_tournamentRegistration_readOneRegistration", methods: "GET")]
    public function readOneRegistration(TournamentRegistrationRepository $repository, int $id): JsonResponse
    {
        return $this->json($repository->findOneBy(["user" => $this->getUser(), "id" => $id]), 200, context: ["groups" => "registration:read"]);
    }

    /**
     * READ
     * A MEMBER can get details of a tournament registration when he is registered to this tournament.
     * The member needs to be authenticated to access one of his registrations.
     */
    #[IsGranted("ROLE_MEMBER")]
    #[Route("/tournament-registrations", "api_tournamentRegistration_readAllRegistrations", methods: "GET")]
    public function readAllRegistrations(TournamentRegistrationRepository $repository): JsonResponse
    {
        return $this->json($repository->findBy(["user" => $this->getUser()]), 200, context: ["groups" => "registration:read"]);
    }

    /**
     * UPDATE
     * An ADMIN can modify some properties of one member's registration.
     * The user and tournament entities can be modified by creating a new instance.
     * Every property can be modified independently.
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route("/admin/tournament-registration/{id}", "api_tournamentRegistration_updateMemberRegistration", methods: "PATCH")]
    public function updateMemberRegistration(Request $request, TournamentRegistrationRepository $tournamentRegistrationRepo, TournamentRepository $tournamentRepo, UserRepository $userRepo, SerializerInterface $serializer, UserPasswordHasherInterface $hasher, ValidatorInterface $validator, int $id): JsonResponse
    {
        $registration = $tournamentRegistrationRepo->find($id);
        $updatedRegistration = $serializer->deserialize($request->getContent(), TournamentRegistration::class, "json");

        if (
            $updatedRegistration->getUserLastName() !== NULL
            && $updatedRegistration->getUserFirstName() !== NULL
            && $updatedRegistration->getUserEmail() !== NULL
        ) {
            $registeredUser = $userRepo->findOneBy([
                "email" => $updatedRegistration->getUserEmail(),
                "firstName" => $updatedRegistration->getUserFirstName(),
                "lastName" => $updatedRegistration->getUserLastName()
            ]);

            if ($registeredUser !== NULL) {
                $registration->setUser($registeredUser);
                $registration->setUserEmail(null);
                $registration->setUserLastName(null);
                $registration->setUserFirstName(null);
            } else {
                $registration->setUser(null);
                if ($updatedRegistration->getUserLastName() !== NULL) $registration->setUserLastName($updatedRegistration->getUserLastName());
                if ($updatedRegistration->getUserFirstName() !== NULL) $registration->setUserFirstName($updatedRegistration->getUserFirstName());
                if ($updatedRegistration->getUserEmail() !== NULL) $registration->setUserEmail($updatedRegistration->getUserEmail());
            }
        }

        /** Create a new instance of user */
        if (
            $registration->getHaveToCreateUser()
            && $updatedRegistration->getUserLastName()
            && $updatedRegistration->getUserFirstName()
            && $updatedRegistration->getUserEmail()
        ) {
            $user = new User();

            $password = "";
            for ($i = 0; $i < 6; $i++) {
                $alpha = mt_rand(97, 122);
                $alphaMaj = mt_rand(65, 90);
                $char = mt_rand(1, 2) === 1 ? mt_rand(0, 9) : (mt_rand(1, 2) === 1 ? chr($alpha) : chr($alphaMaj));
                $password .= $char;
            }

            $user
                ->setLastName($updatedRegistration->getUserLastName())
                ->setFirstName($updatedRegistration->getUserFirstName())
                ->setEmail($updatedRegistration->getUserEmail())
                ->setPassword($password)
                ->setConfirmPassword($password);

            $errors = $validator->validate($user);
            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }
            $user->setPassword($hasher->hashPassword($user, $password));

            $userRepo->add($user, true);
            $registration->setUser($userRepo->find($user->getId()));
        }

        if ($updatedRegistration->getTournamentCity() !== NULL && $updatedRegistration->getTournamentStartDate() !== NULL) {
            $registeredTournament = $tournamentRepo->findOneBy([
                "city" => $updatedRegistration->getTournamentCity(),
                "startDate" => $updatedRegistration->getTournamentStartDate(),
            ]);

            if ($registeredTournament !== NULL) {
                $registration->setTournament($registeredTournament);
                $registration->setTournamentName(null);
                $registration->setTournamentCity(null);
                $registration->setTournamentStartDate(null);
                $registration->setTournamentEndDate(null);
            } else {
                $registration->setTournament(null);
                if ($updatedRegistration->getTournamentName()) $registration->setTournamentName($updatedRegistration->getTournamentName());
                if ($updatedRegistration->getTournamentCity()) $registration->setTournamentCity($updatedRegistration->getTournamentCity());
                if ($updatedRegistration->getTournamentStartDate()) $registration->setTournamentStartDate($updatedRegistration->getTournamentStartDate());
                if ($updatedRegistration->getTournamentEndDate()) $registration->setTournamentEndDate($updatedRegistration->getTournamentEndDate());
            }
        }

        /** Create a new instance of tournament */
        if (
            $registration->getHaveToCreateTournament()
            && $updatedRegistration->getTournamentCity()
            && $updatedRegistration->getTournamentStartDate()
        ) {
            $tournament = new Tournament();
            $tournament
                ->setCity($updatedRegistration->getTournamentCity())
                ->setStartDate($updatedRegistration->getTournamentStartDate());

            if ($updatedRegistration->getTournamentName()) {
                $tournament->setName($updatedRegistration->getTournamentName());
            }
            if ($updatedRegistration->getTournamentEndDate()) {
                $tournament->setEndDate($updatedRegistration->getTournamentEndDate());
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
            $registration->setTournament($tournamentRepo->find($tournament->getId()));
        }

        if ($updatedRegistration->getRequestState() !== NULL) {
            $registration->setRequestState($updatedRegistration->getRequestState());
        }
        if ($updatedRegistration->isParticipationSingle() !== NULL) {
            $registration->setParticipationSingle($updatedRegistration->isParticipationSingle());
        }
        if ($updatedRegistration->isParticipationDouble() !== NULL) {
            $registration->setParticipationDouble($updatedRegistration->isParticipationDouble());
        }
        if ($updatedRegistration->isParticipationMixed() !== NULL) {
            $registration->setParticipationMixed($updatedRegistration->isParticipationMixed());
        }
        if ($updatedRegistration->getDoublePartnerName() !== NULL) {
            $registration->setDoublePartnerName($updatedRegistration->getDoublePartnerName());
        }
        if ($updatedRegistration->getDoublePartnerClub() !== NULL) {
            $registration->setDoublePartnerClub($updatedRegistration->getDoublePartnerClub());
        }
        if ($updatedRegistration->getMixedPartnerName() !== NULL) {
            $registration->setMixedPartnerName($updatedRegistration->getMixedPartnerName());
        }
        if ($updatedRegistration->getMixedPartnerClub() !== NULL) {
            $registration->setMixedPartnerClub($updatedRegistration->getMixedPartnerClub());
        }
        if ($updatedRegistration->getComment() !== NULL) {
            $registration->setComment($updatedRegistration->getComment());
        }

        $registration->setUpdatedAt(new \DateTime());
        $tournamentRegistrationRepo->add($registration, true);
        return $this->json($registration, 201, context: ["groups" => "registration:update"]);
    }

    /**
     * UPDATE
     * A MEMBER can modify some properties of his registration.
     * The user and tournament entities can be modified by creating a new instance.
     * Every property can be modified independently.
     */
    #[IsGranted("ROLE_MEMBER")]
    #[Route("/tournament-registration/{id}", "api_tournamentRegistration_updateRegistration", methods: "PATCH")]
    public function updateRegistration(Request $request, TournamentRegistrationRepository $tournamentRegistrationRepo, TournamentRepository $tournamentRepo, SerializerInterface $serializer, int $id): JsonResponse
    {
        $registration = $tournamentRegistrationRepo->findOneBy(["user" => $this->getUser(), "id" => $id]);
        $jsonReceived = $request->getContent();
        $updatedRegistration = $serializer->deserialize($jsonReceived, TournamentRegistration::class, "json");

        if ($registration === null) {
            throw new Exception("The selected registration does not belong to this user.");
        }

        if ($updatedRegistration->getTournamentCity() !== NULL && $updatedRegistration->getTournamentStartDate() !== NULL) {
            $registeredTournament = $tournamentRepo->findOneBy([
                "city" => $updatedRegistration->getTournamentCity(),
                "startDate" => $updatedRegistration->getTournamentStartDate(),
            ]);

            if ($registeredTournament !== NULL) {
                $registration->setTournament($registeredTournament);
                $registration->setTournamentName(null);
                $registration->setTournamentCity(null);
                $registration->setTournamentStartDate(null);
                $registration->setTournamentEndDate(null);
            } else {
                $registration->setTournament(null);
                if ($updatedRegistration->getTournamentName()) $registration->setTournamentName($updatedRegistration->getTournamentName());
                if ($updatedRegistration->getTournamentCity()) $registration->setTournamentCity($updatedRegistration->getTournamentCity());
                if ($updatedRegistration->getTournamentStartDate()) $registration->setTournamentStartDate($updatedRegistration->getTournamentStartDate());
                if ($updatedRegistration->getTournamentEndDate()) $registration->setTournamentEndDate($updatedRegistration->getTournamentEndDate());
            }
        }

        if ($updatedRegistration->isParticipationSingle() !== NULL) {
            $registration->setParticipationSingle($updatedRegistration->isParticipationSingle());
        }
        if ($updatedRegistration->isParticipationDouble() !== NULL) {
            $registration->setParticipationDouble($updatedRegistration->isParticipationDouble());
        }
        if ($updatedRegistration->isParticipationMixed() !== NULL) {
            $registration->setParticipationMixed($updatedRegistration->isParticipationMixed());
        }
        if ($updatedRegistration->getDoublePartnerName() !== NULL) {
            $registration->setDoublePartnerName($updatedRegistration->getDoublePartnerName());
        }
        if ($updatedRegistration->getDoublePartnerClub() !== NULL) {
            $registration->setDoublePartnerClub($updatedRegistration->getDoublePartnerClub());
        }
        if ($updatedRegistration->getMixedPartnerName() !== NULL) {
            $registration->setMixedPartnerName($updatedRegistration->getMixedPartnerName());
        }
        if ($updatedRegistration->getMixedPartnerClub() !== NULL) {
            $registration->setMixedPartnerClub($updatedRegistration->getMixedPartnerClub());
        }
        if ($updatedRegistration->getComment() !== NULL) {
            $registration->setComment($updatedRegistration->getComment());
        }

        $registration->setRequestState("pending");
        $registration->setUpdatedAt(new \DateTime());
        $tournamentRegistrationRepo->add($registration, true);
        return $this->json($registration, 201, context: ["groups" => "registration:update"]);
    }

    /**
     * PATCH
     * An ADMIN can validate one member registration from the registration's id
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route("/admin/tournament-registration/validate/{id}", name: "api_tournamentRegistration_validateMemberRegistration", methods: "PATCH")]
    public function validateMemberRegistration(TournamentRegistrationRepository $repository, int $id): JsonResponse
    {
        $registration = $repository->find($id);
        $registration
            ->setRequestState("validated")
            ->setUpdatedAt(new \DateTime());
        $repository->add($registration, true);
        return $this->json($registration, 200, context: ["groups" => "registration:update"]);
    }

    /**
     * PATCH
     * An ADMIN can cancel a registration.
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route("/admin/tournament-registration/cancel/{id}", "api_tournamentRegistration_cancelMemberRegistration", methods: "PATCH")]
    public function cancelMemberRegistration(TournamentRegistrationRepository $repository, int $id): JsonResponse
    {
        $registration = $repository->find($id);
        $registration
            ->setRequestState("cancelled")
            ->setUpdatedAt(new \DateTime());
        $repository->add($registration, true);
        return $this->json($registration, 200, context: ["groups" => "registration:update"]);
    }

    /**
     * UPDATE (Delete for a member)
     * A MEMBER can cancel a registration (instead of delete it).
     */
    #[IsGranted("ROLE_MEMBER")]
    #[Route("/tournament-registration/cancel/{id}", "api_tournamentRegistration_cancelRegistration", methods: "PATCH")]
    public function cancelRegistration(TournamentRegistrationRepository $repository, int $id): JsonResponse
    {
        $registration = $repository->findOneBy(["user" => $this->getUser(), "id" => $id]);

        if ($registration === null) {
            throw new Exception("The registration's id selected does not belong to this user.");
        } else {
            $registration->setRequestState("cancelled");
            $registration->setUpdatedAt(new \DateTime());
            $repository->add($registration, true);
            return $this->json($registration, 200, context: ["groups" => "registration:update"]);
        }
    }

    /**
     * DELETE
     * An ADMIN can delete one tournament.
     * To update the registration's state, the admin have to use the patch route
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route("/admin/tournament-registration/{id}", "api_tournamentRegistration_deleteRegistration", methods: "DELETE")]
    public function deleteRegistration(TournamentRegistrationRepository $repository, int $id): JsonResponse
    {
        $repository->remove($repository->find($id), true);
        return $this->json([
            "status" => 200,
            "message" => "The registration with the id #$id has been correctly deleted."
        ], 200);
    }
}
