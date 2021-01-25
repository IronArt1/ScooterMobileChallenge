<?php

namespace App\Repository;

use App\Entity\Scooter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * @method Scooter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Scooter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Scooter[]    findAll()
 * @method Scooter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScooterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Scooter::class);
    }

    /**
     * @return Scooter[]
     */
    public function scooterUpdateStatus(Boolean $status)
    {
        return $this->createQueryBuilder('s')
            ->update()
            ->set('s.occupied', ':occupied')
            ->setParameter(':occupied', $status)
            ->getQuery()
            ->execute()
        ;
    }

    /**
     * @return Scooter[]
     */
    public function findAllMatching(?string $query, int $limit = 5)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('u.email LIKE :query')
            ->setParameter('query', '%'.$query.'%')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Scooter[]
     */
    public function findAllSubscribedToNewsletter(): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('u.subscribeToNewsletter = 1')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Scooter[] Returns an array of User objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Scooter
    {
        return $this->createQueryBuilder('s')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
