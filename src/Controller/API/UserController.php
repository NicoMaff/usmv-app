<?php

namespace App\Controller\API;

use App\Entity\User;
use App\Repository\UserRepository;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route("api/")]
class UserController extends AbstractController
{
    /**
     * CREATE
     * Create an account
     */
    #[Route("user/account", name: "api_user_createAccount", methods: "POST")]
    // #[IsGranted("ROLE_MEMBER")]
    public function createAccount(UserRepository $repository, UserPasswordHasherInterface $hasher, Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $jsonReceived = $request->getContent();

        $user = $serializer->deserialize($jsonReceived, User::class, "json");

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        $hashedPassword = $hasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);
        $user->setState("pending");
        $user->setCreatedAt(new \DateTimeImmutable());


        $repository->add($user, true);

        return $this->json($user, 201, [], ["groups" => "user:create"]);
    }

    /**
     * CREATE
     * Create a new member
     */
    #[Route("user/member", name: "api_user_createMemberAccount", methods: "POST")]
    // #[IsGranted("ROLE_ADMIN")]
    public function createMemberAccount(UserRepository $repository, UserPasswordHasherInterface $hasher, Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $jsonReceived = $request->getContent();

        $user = $serializer->deserialize($jsonReceived, User::class, "json");

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        $hashedPassword = $hasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);
        $user->setState("active");
        $user->setValidatedAccount(true);
        $user->setCreatedAt(new \DateTimeImmutable());


        $repository->add($user, true);

        return $this->json($user, 201, [], ["groups" => "user:create"]);
    }

    /**
     * CREATE
     * Create a new admin
     */
    #[Route("user/admin", name: "api_user_createAdminAccount", methods: "POST")]
    // #[IsGranted("ROLE_SUPERADMIN")]
    public function createAdminAccount(UserRepository $repository, UserPasswordHasherInterface $hasher, Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $jsonReceived = $request->getContent();

        $user = $serializer->deserialize($jsonReceived, User::class, "json");

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        $hashedPassword = $hasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setState("active");
        $user->setValidatedAccount(true);
        $user->setCreatedAt(new \DateTimeImmutable());


        $repository->add($user, true);

        return $this->json($user, 201, [], ["groups" => "user:create"]);
    }

    /**
     * READ
     * Get on user
     */
    #[Route("user/{id}", name: "api_user_readOne", methods: "GET")]
    #[IsGranted("ROLE_ADMIN")]
    public function readOne(UserRepository $repository, int $id): JsonResponse
    {
        return $this->json($repository->find($id), 200, [], ["groups" => "user:read"]);
    }

    /**
     * READ
     * Get all users
     */
    #[Route('users', 'api_user_readAll', methods: "GET")]
    public function readAll(UserRepository $repository): JsonResponse
    {
        return $this->json($repository->findAll(), 200, [], ["groups" => "user:read"]);
    }

    /**
     * UPDATE
     * A member can update his personal infos
     */
    #[Route("user/{id}/personal-infos", name: "api_user_updatePersonalInfos", methods: "PATCH")]
    #[IsGranted("ROLE_MEMBER")]
    public function updatePersonalInfosOne(UserRepository $repository, SerializerInterface $serializer, UserPasswordHasherInterface $hasher, Request $request, int $id): JsonResponse
    {
        $user = $repository->find($id);
        $jsonReceived = $request->getContent();
        $newUserInfos = $serializer->deserialize($jsonReceived, User::class, "json");

        if ($newUserInfos->getFirstName()) {
            $user->setFirstName($newUserInfos->getFirstName());
        }

        if ($newUserInfos->getLastName()) {
            $user->setLastName($newUserInfos->getLastName());
        }

        if ($newUserInfos->getEmail()) {
            $user->setEmail($newUserInfos->getEmail());
        }

        if ($newUserInfos->getPassword()) {
            $validPassword = $hasher->isPasswordValid($user, $newUserInfos->previousPassword);

            if ($validPassword) {
                $hashedPassword = $hasher->hashPassword($user, $newUserInfos->getPassword());
                $user->setPassword($hashedPassword);
            } else {
                throw new Exception("Votre précédent mot de passe n'est pas correct !");
            }
        }

        if ($newUserInfos->getAvatarUrl()) {
            $user->setAvatarUrl($newUserInfos->getAvatarUrl());
        }

        $user->setUpdatedAt(new \DateTimeImmutable());

        $repository->add($user, true);

        return $this->json($user, 201, []);
    }

    /**
     * UPDATE
     * An admin can update member infos
     */
    #[Route("user/{id}/member-infos", name: "api_user_updateMemberInfos", methods: "PATCH")]
    // #[IsGranted("ROLE_ADMIN")]
    public function updateMemberInfos(UserRepository $repository, SerializerInterface $serializer, Request $request, int $id): JsonResponse
    {
        $user = $repository->find($id);

        $jsonReceived = $request->getContent();

        $newUserInfos = $serializer->deserialize($jsonReceived, User::class, "json");

        if ($newUserInfos->getFirstName()) {
            $user->setFirstName($newUserInfos->getFirstName());
        }

        if ($newUserInfos->getLastName()) {
            $user->setLastName($newUserInfos->getLastName());
        }

        if ($newUserInfos->getGender()) {
            $user->setGender($newUserInfos->getGender());
        }

        if ($newUserInfos->getEmail()) {
            $user->setEmail($newUserInfos->getEmail());
        }

        if ($newUserInfos->getBirthDate()) {
            $user->setBirthDate($newUserInfos->getBirthDate());
        }

        if ($newUserInfos->getAvatarUrl()) {
            $user->setAvatarUrl($newUserInfos->getAvatarUrl());
        }

        if ($newUserInfos->getRoles()) {
            $user->setRoles($newUserInfos->getRoles());
        }

        if ($newUserInfos->isValidatedAccount()) {
            $user->setValidatedAccount($newUserInfos->isValidatedAccount());
        }

        if ($newUserInfos->getState()) {
            $user->setState($newUserInfos->getState());
        }

        $user->setUpdatedAt(new \DateTimeImmutable());

        $repository->add($user, true);

        return $this->json($user, 201, [], ["groups" => "user:update",]);
    }

    /**
     * UPDATE
     * Validate member's account
     */
    #[Route("user/{id}/account-validation", "api_user_validateAccount", methods: "PATCH")]
    #[IsGranted("ROLE_ADMIN")]
    public function validateAccount(UserRepository $repository, int $id): JsonResponse
    {
        $user = $repository
            ->find($id)
            ->setValidatedAccount(true)
            ->setState("active");
        $repository->add($user, true);
        return $this->json($user, 201, [], ["groups" => "user:update"]);
    }

    /**
     * UPDATE
     * Move a member's state account to inactive
     */
    #[Route("user/{id}/inactivation", "api_user_inactivateAccount", methods: "PATCH")]
    #[IsGranted("ROLE_ADMIN")]
    public function inactivateAccount(UserRepository $repository, int $id): JsonResponse
    {
        $user = $repository
            ->find($id)
            ->setState("inactive");
        $repository->add($user, true);
        return $this->json($user, 201, [], ["groups" => "user:update"]);
    }

    /**
     * UPDATE
     * Reactivate a member account
     */
    #[Route("user/{id}/activation", "api_user_activateAccount", methods: "PATCH")]
    #[IsGranted("ROLE_ADMIN")]
    public function activateAccount(UserRepository $repository, int $id): JsonResponse
    {
        $user = $repository
            ->find($id)
            ->setState("active");
        $repository->add($user, true);
        return $this->json($user, 201, [], ["groups" => "user:update"]);
    }

    /**
     * UPDATE
     * Promote Member to Admin
     */
    #[Route("user/{id}/promotion", "api_user_promoteMember", methods: "PATCH")]
    #[IsGranted("ROLE_ADMIN")]
    public function promoteMember(UserRepository $repository, int $id): JsonResponse
    {
        $user = $repository
            ->find($id)
            ->setRoles(["ROLE_ADMIN"]);
        $repository->add($user, true);
        return $this->json($user, 201, []);
    }

    /**
     * UPDATE
     * Demote Admin to Member
     */
    #[Route("user/{id}/demotion", "api_user_demoteAdmin", methods: "PATCH")]
    #[IsGranted("ROLE_ADMIN")]
    public function demoteAdmin(UserRepository $repository, int $id): JsonResponse
    {
        $user = $repository
            ->find($id)
            ->setRoles(["ROLE_MEMBER"]);
        $repository->add($user, true);
        return $this->json($user, 201, []);
    }

    /**
     * DELETE
     * Remove one member
     */
    #[Route("/user/member/{id}", "api_user_deleteOneMember", methods: "DELETE")]
    // #[IsGranted("ROLE_ADMIN")]
    public function deleteOneMember(UserRepository $repository, int $id): JsonResponse
    {
        $user = $repository->find($id);

        if (in_array("ROLE_SUPERADMIN", $user->getRoles())) {
            throw new Exception("Vous n'avez pas les droits pour supprimer le Grand CHEF 😝");
        } else if (in_array("ROLE_ADMIN", $user->getRoles())) {
            throw new Exception("Vous n'avez pas les droits pour supprimer un administrateur");
        } else {
            $repository->remove($user, true);
            return $this->json([
                "status" => 200,
                "message" => "L'utilisateur avec l'id #$id a bien été supprimé."
            ], 200);
        }
    }

    /**
     * DELETE
     * Remove one user (admin or member)
     */
    #[Route("user/{id}", "api_user_deleteOneAdminOrMember", methods: "DELETE")]
    // #[IsGranted("ROLE_SUPERADMIN")]
    public function deleteOneAdminOrMember(UserRepository $repository, int $id): JsonResponse
    {
        $user = $repository->find($id);

        if (in_array("ROLE_SUPERADMIN", $user->getRoles())) {
            throw new Exception("Vous n'avez pas les droits pour supprimer le Grand CHEF 😝");
        } else {
            $repository->remove($user, true);
        }

        if (in_array("ROLE_ADMIN", $user->getRoles())) {
            return $this->json([
                "status" => 200,
                "message" => "L'administrateur avec l'id #$id a bien été supprimé."
            ], 200);
        } else {
            return $this->json([
                "status" => 200,
                "message" => "Le membre avec l'id #$id a bien été supprimé."
            ], 200);
        }
    }
}
