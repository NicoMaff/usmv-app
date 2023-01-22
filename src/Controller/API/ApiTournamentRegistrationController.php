<?php

namespace App\Controller\API;

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
        $jsonReceived = $request->getContent();
        $registration = $serializer->deserialize($jsonReceived, TournamentRegistration::class, "json");

        /** Set the user
         * Research by email because this property is unique in database */
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
        }

        /** Set the tournament
         * Research by the tournament's name because it's the only explicit property to identify a tournament  */
        $tournamentSearch = [
            "city" => $registration->getTournamentCity(),
            "startDate" => new \DateTime($registration->getTournamentStartDate())
        ];
        if ($tournamentRepo->findOneBy($tournamentSearch)) {
            $registration->setTournament($tournamentRepo->findOneBy($tournamentSearch));
            $registration->setTournamentName(null);
            $registration->setTournamentCity(null);
            $registration->setTournamentStartDate(null);
            $registration->setTournamentEndDate(null);
        }

        /** Create User if data don't correspond to an instance in bdd */
        if (
            $registration->getHaveToCreateUser()
            && $registration->getUser() === null
            && $registration->getUserLastName()
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

        /** Create Tournament if data don't correspond to an instance in bdd */
        if (
            $registration->getHaveToCreateTournament()
            && $registration->getTournament() === null
            && $registration->getTournamentCity()
            && $registration->getTournamentStartDate()
        ) {
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
            } else if (in_array($tournament->getStartDate()->format("m"), ["01", "02", "03", "04", "05", "06", "07", "08"])) {
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

        /** Set the tournament
         * Research by the tournament's name because it's the only explicit property to identify a tournament  */
        $tournamentSearch = [
            "city" => $registration->getTournamentCity(),
            "startDate" => new \DateTime($registration->getTournamentStartDate())
        ];
        if ($tournamentRepo->findOneBy([$tournamentSearch])) {
            $registration->setTournament([$tournamentSearch]);
            $registration->setTournamentName(null);
            $registration->setTournamentCity(null);
            $registration->setTournamentStartDate(null);
            $registration->setTournamentEndDate(null);
        }

        $registration->setUser($this->getUser());

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

        /** Set the user
         * Research by email because this property is unique in database */
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
        }

        /** Set the tournament
         * Research by the tournament's name because it's the only explicit property to identify a tournament  */
        $tournamentSearch = [
            "city" => $registration->getTournamentCity(),
            "startDate" => new \DateTime($registration->getTournamentStartDate())
        ];
        if ($tournamentRepo->findOneBy([$tournamentSearch])) {
            $registration->setTournament([$tournamentSearch]);
            $registration->setTournamentName(null);
            $registration->setTournamentCity(null);
            $registration->setTournamentStartDate(null);
            $registration->setTournamentEndDate(null);
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
    #[Route("/admin/tournament-registration/{id}", "api_tournamentRegistration_readOneMemberRegistration", methods: "GET")]
    public function readOneMemberRegistration(TournamentRegistrationRepository $repository, int $id): JsonResponse
    {
        $registration = $repository->find($id);

        if ($registration === null) {
            throw new Exception("This id does not correspond to none of the tournament registrations.");
        } else {
            $registration
                ->setUserId($registration->getUser()->getId())
                ->setTournamentId($registration->getTournament()->getId());
            return $this->json($registration, 200, context: ["groups" => "registration:read"]);
        }
    }

    /**
     * READ
     * An ADMIN can get details of all member's registrations.
     * The selection only concern the registration (and not the user). 
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route("/admin/tournament-registrations", "api_tournamentRegistration_readOneMemberRegistration", methods: "GET")]
    public function readAllMembersRegistrations(TournamentRegistrationRepository $repository): JsonResponse
    {
        $registrations = $repository->findAll();

        $registrationsToReturn = [];
        foreach ($registrations as $registration) {
            $registration
                ->setUserId($registration->getUser()->getId())
                ->setTournamentId($registration->getTournament()->getId());
            array_push($registrationsToReturn, $registration);
        }
        return $this->json($registrationsToReturn, 200, context: ["groups" => "registration:read"]);
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
        $registration = $repository->findOneBy(["user" => $this->getUser(), "id" => $id]);

        if ($registration === null) {
            throw new Exception("The registration's id selected does not belong to this user.");
        } else {
            $registration
                ->setUserId($registration->getUser()->getId())
                ->setTournamentId($registration->getTournament()->getId());
            return $this->json($registration, 200, context: ["groups" => "registration:read"]);
        }
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
        $registrations = $repository->findBy(["user" => $this->getUser()]);

        $registrationsToReturn = [];
        foreach ($registrations as $registration) {
            $registration
                ->setUserId($registration->getUser()->getId())
                ->setTournamentId($registration->getTournament()->getId());
            array_push($registrationsToReturn, $registration);
        }
        return $this->json($registrationsToReturn, 200, context: ["groups" => "registration:read"]);
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
        $jsonReceived = $request->getContent();
        $updatedRegistration = $serializer->deserialize($jsonReceived, TournamentRegistration::class, "json");

        if ($updatedRegistration->getUserId()) {
            $registration->setUserId($updatedRegistration->getUserId());
        }

        if ($updatedRegistration->getUserId() === null && $updatedRegistration->getUserLastName() && $updatedRegistration->getUserFirstName() && $updatedRegistration->getUserEmail()) {
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
            $registration->setUserId($user->getId());
        }

        if ($updatedRegistration->getTournamentId()) {
            $registration->setTournamentId($updatedRegistration->getTournamentId());
        }

        if ($registration->getTournamentId() === null && $updatedRegistration->getTournamentCity() && $updatedRegistration->getTournamentStartDate()) {
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
            $registration->setTournamentId($tournament->getId());
        }

        if ($updatedRegistration->getRequestState()) {
            $registration->setRequestState($updatedRegistration->getRequestState());
        }
        if ($updatedRegistration->isHasParticipated()) {
            $registration->setHasParticipated($updatedRegistration->getHasParticipated());
        }
        if ($updatedRegistration->isParticipationSingle()) {
            $registration->setParticipationSingle($updatedRegistration->isParticipationSingle());
        }
        if ($updatedRegistration->isParticipationDouble()) {
            $registration->setParticipationDouble($updatedRegistration->isParticipationDouble());
        }
        if ($updatedRegistration->isParticipationMixed()) {
            $registration->setParticipationMixed($updatedRegistration->isParticipationMixed());
        }
        if ($updatedRegistration->getSingleStageReached()) {
            $registration->setSingleStageReached($updatedRegistration->getSingleStageReached());
        }
        if ($updatedRegistration->getDoubleStageReached()) {
            $registration->setDoubleStageReached($updatedRegistration->getDoubleStageReached());
        }
        if ($updatedRegistration->getMixedStageReached()) {
            $registration->setMixedStageReached($updatedRegistration->getMixedStageReached());
        }
        if ($updatedRegistration->getDoublePartnerName()) {
            $registration->setDoublePartnerName($updatedRegistration->getDoublePartnerName());
        }
        if ($updatedRegistration->getDoublePartnerClub()) {
            $registration->setDoublePartnerClub($updatedRegistration->getDoublePartnerClub());
        }
        if ($updatedRegistration->getMixedPartnerName()) {
            $registration->setMixedPartnerName($updatedRegistration->getMixedPartnerName());
        }
        if ($updatedRegistration->getMixedPartnerClub()) {
            $registration->setMixedPartnerClub($updatedRegistration->getMixedPartnerClub());
        }
        if ($updatedRegistration->getComment()) {
            $registration->setComment($updatedRegistration->getComment());
        }

        $registration->setUser($userRepo->find($registration->getUser()->getId()));
        $registration->setTournament($tournamentRepo->find($registration->getTournamentId()));
        $registration->setUpdatedAt(new \DateTime());
        $tournamentRegistrationRepo->add($registration, true);
        return $this->json($registration, 201, context: ["groups" => "registration:create"]);
    }

    /**
     * UPDATE
     * A MEMBER can modify some properties of his registration.
     * The user and tournament entities can be modified by creating a new instance.
     * Every property can be modified independently.
     */
    #[IsGranted("ROLE_MEMBER")]
    #[Route("/tournament-registration/{id}", "api_tournamentRegistration_updateRegistration", methods: "PATCH")]
    public function updateRegistration(Request $request, TournamentRegistrationRepository $tournamentRegistrationRepo, TournamentRepository $tournamentRepo, SerializerInterface $serializer, ValidatorInterface $validator, int $id): JsonResponse
    {
        $registration = $tournamentRegistrationRepo->findOneBy(["user" => $this->getUser(), "id" => $id]);
        $jsonReceived = $request->getContent();
        $updatedRegistration = $serializer->deserialize($jsonReceived, TournamentRegistration::class, "json");

        if ($registration === null) {
            throw new Exception("The registration's id selected does not belong to this user.");
        }

        if ($updatedRegistration->getTournamentId()) {
            $registration->setTournamentId($updatedRegistration->getTournamentId());
        }

        if ($registration->getTournamentId() === null && $updatedRegistration->getTournamentCity() && $updatedRegistration->getTournamentStartDate()) {
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
            $registration->setTournamentId($tournament->getId());
        }

        if ($updatedRegistration->isParticipationSingle()) {
            $registration->setParticipationSingle($updatedRegistration->isParticipationSingle());
        }
        if ($updatedRegistration->isParticipationDouble()) {
            $registration->setParticipationDouble($updatedRegistration->isParticipationDouble());
        }
        if ($updatedRegistration->isParticipationMixed()) {
            $registration->setParticipationMixed($updatedRegistration->isParticipationMixed());
        }
        if ($updatedRegistration->getSingleStageReached()) {
            $registration->setSingleStageReached($updatedRegistration->getSingleStageReached());
        }
        if ($updatedRegistration->getDoubleStageReached()) {
            $registration->setDoubleStageReached($updatedRegistration->getDoubleStageReached());
        }
        if ($updatedRegistration->getMixedStageReached()) {
            $registration->setMixedStageReached($updatedRegistration->getMixedStageReached());
        }
        if ($updatedRegistration->getDoublePartnerName()) {
            $registration->setDoublePartnerName($updatedRegistration->getDoublePartnerName());
        }
        if ($updatedRegistration->getDoublePartnerClub()) {
            $registration->setDoublePartnerClub($updatedRegistration->getDoublePartnerClub());
        }
        if ($updatedRegistration->getMixedPartnerName()) {
            $registration->setMixedPartnerName($updatedRegistration->getMixedPartnerName());
        }
        if ($updatedRegistration->getMixedPartnerClub()) {
            $registration->setMixedPartnerClub($updatedRegistration->getMixedPartnerClub());
        }
        if ($updatedRegistration->getComment()) {
            $registration->setComment($updatedRegistration->getComment());
        }

        $registration->setTournament($tournamentRepo->find($registration->getTournamentId()));
        $registration->setUpdatedAt(new \DateTime());
        $tournamentRegistrationRepo->add($registration, true);
        return $this->json($registration, 201, context: ["groups" => "registration:create"]);
    }

    /**
     * UPDATE (Delete for a member)
     * A MEMBER can cancel a registration (instead of delete it).
     */
    #[IsGranted("ROLE_MEMBER")]
    #[Route("/tournament-registration/cancel/{id}", "api_tournamentRegistration_cancelMemberRegistration", methods: "PATCH")]
    public function cancelMemberRegistration(TournamentRegistrationRepository $repository, int $id): JsonResponse
    {
        $registration = $repository->findOneBy(["user" => $this->getUser(), "id" => $id]);

        if ($registration === null) {
            throw new Exception("The registration's id selected does not belong to this user.");
        } else {
            $registration->setRequestState("cancelled");
            $registration->setUpdatedAt(new \DateTime());
            $repository->add($registration, true);
            return $this->json($registration, 200, context: ["groups" => "registration:read"]);
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
