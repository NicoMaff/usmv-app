<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function add(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllFromToday(): array
    {
        return $this->createQueryBuilder("e")
            ->where('e.startDate > :yesterday')
            ->setParameter("yesterday", (new \DateTime())->modify("-1 day"))
            // by default, DateTime is set at 00:00:00, it now sets at yesterday to include "today"
            ->andWhere("e.isVisible = true")
            ->orderBy("e.startDate", "ASC")
            ->getQuery()
            ->getResult();
    }

    public function find400SortByStartDate(): array
    {
        return $this->createQueryBuilder("e")
            ->where("e.startDate > :date")
            ->setParameter("date", (new \DateTime())->modify("-401 days"))
            ->andWhere("e.isVisible = true")
            ->orderBy("e.startDate", "DESC")
            ->getQuery()
            ->getResult();
    }
}
