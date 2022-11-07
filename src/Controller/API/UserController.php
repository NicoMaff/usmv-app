<?php

namespace App\Controller\API;

use App\Entity\User;
use App\Repository\UserRepository;
use Exception;
use ProxyManager\Factory\RemoteObject\Adapter\JsonRpc;
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
     * MEMBER
     * Create an account
     */
    #[Route("user", name: "api_user_createAccount", methods: "POST")]
    public function createAccount(UserRepository $repository, UserPasswordHasherInterface $hasher, Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $jsonReceived = $request->getContent();

        $user = $serializer->deserialize($jsonReceived, User::class, "json");

        $hashedPassword = $hasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);
        $user->setState("pending");
        $user->setCreatedAt(new \DateTimeImmutable());

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        $repository->add($user, true);

        return $this->json($user, 201, [], ["groups" => "user:create"]);
    }

    /**
     * CREATE
     * ADMIN
     * Create a new member
     */
    #[Route("admin/user/new-member", name: "api_user_createMemberAccount", methods: "POST")]
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
     * ADMIN
     * Create a new admin
     */
    #[Route("admin/user/new-admin", name: "api_user_createAdminAccount", methods: "POST")]
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
     *  - Get all users
     */
    #[Route('users', 'api_user_readAll', methods: "GET")]
    public function readAll(UserRepository $repository): JsonResponse
    {
        return $this->json($repository->findAll(), 200, [], ["groups" => "user:read"]);
    }

    /**
     * READ - Get on user
     */
    #[Route("user/{id}", name: "api_user_readOne", methods: "GET")]
    public function readOne(UserRepository $repository, int $id): JsonResponse
    {
        return $this->json($repository->find($id), 200, [], ["groups" => "user:read"]);
    }

    /**
     * UPDATE
     * ADMIN
     * Update user infos
     */
    #[Route("admin/user/{id}", name: "api_user_updateMemberInfos", methods: "PATCH")]
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
     * MEMBER
     * Update user infos
     */
    #[Route("user/{id}", name: "api_user_updatePersonalInfos", methods: "PATCH")]
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
     * ADMIN
     * Validate member's account
     */
    #[Route("admin/user/{id}/validate_account", "api_user_validateAccount", methods: "PATCH")]
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
     * ADMIN
     * Move a member's state account to inactive
     */
    #[Route("admin/user/{id}/inactivate", "api_user_inactivateAccount", methods: "PATCH")]
    public function inactivateAccount(UserRepository $repository, int $id): JsonResponse
    {
        $user = $repository->find($id)->setState("inactive");
        $repository->add($user, true);
        return $this->json($user, 201, [], ["groups" => "user:update"]);
    }

    /**
     * UPDATE
     * ADMIN
     * Reactivate a member account
     */
    #[Route("admin/user/{id}/activate", "api_user_activateAccount", methods: "PATCH")]
    public function activateAccount(UserRepository $repository, int $id): JsonResponse
    {
        $user = $repository->find($id)->setState("active");
        $repository->add($user, true);
        return $this->json($user, 201, [], ["groups" => "user:update"]);
    }

    /**
     * UPDATE
     * ADMIN
     * Promote Member to Admin
     */
    #[Route("admin/user/{id}/promote", "api_user_promoteMember", methods: "PATCH")]
    public function promoteMember(UserRepository $repository, int $id): JsonResponse
    {
        $user = $repository
            ->find($id)
            ->setRoles(["ROLE_ADMIN", "ROLE_MEMBER"]);
        $repository->add($user, true);
        return $this->json($user, 201, []);
    }

    /**
     * UPDATE
     * ADMIN
     * Demote Admin to Member
     */
    #[Route("admin/user/{id}/demote", "api_user_demoteAdmin", methods: "PATCH")]
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
     * ADMIN
     * Delete one member
     */
    #[Route("admin/user/{id}", "api_user_deleteOneMember", methods: "DELETE")]
    public function deleteOneMember(UserRepository $repository, int $id): JsonResponse
    {
        $repository->remove($repository->find($id), true);
        return $this->json("L'utilisateur avec l'id n°$id a bien été supprimé.", 200);
    }

    /**
     * DELETE
     * ADMIN
     * Delete multiple members
     */
    // #[Route("admin/users", 'api_user_deleteMultipleMembers', methods: "DELETE")]
    // public function deleteMultipleMembers(UserRepository $repository, Request $request): JsonResponse
    // {
    //     $idsArray = $request->getContent();
    //     for ($i = 0; $i < count($idsArray) - 1; $i++) {
    //         $repository->remove($repository->find($i), true);
    //     }
    //     return $this->json("Les utilisateurs avec les ids { " . implode(",", $idsArray) . " } ont bien été supprimés.", 200);
    // }
}
