<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function add(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->add($user, true);
    }

    public function findAllUsersExceptAdmins(): array
    {
        return $this->createQueryBuilder("user")
            ->where("user.roles = :val")
            ->setParameter("val", "ROLE_USER")
            ->orderBy("user.id", "ASC")
            ->getQuery()
            ->getResult();
    }

    // add implements UserLoaderInterface
    // /**
    //  * This method allow to identify an user either by his email or his id.
    //  */
    // public function loadUserByIdentifier(string $emailOrId): ?User
    // {
    //     $entityManager = $this->getEntityManager();

    //     return $entityManager->createQuery(
    //         'SELECT u
    //         FROM APP\ENTITY\USER u
    //         WHERE u.email = :query 
    //         OR u.id = :query'
    //     )
    //         ->setParameter("query", $emailOrId)
    //         ->getOneOrNullResult();
    // }
}
