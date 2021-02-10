<?php

namespace App\Repository;

use App\Entity\Mobile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Mobile|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mobile|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mobile[]    findAll()
 * @method Mobile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MobileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mobile::class);
    }

    /**
     * @return Mobile[]
     */
    public function findAllEmailAlphabetical()
    {
        return $this->createQueryBuilder('m')
            ->orderBy('u.email', 'ASC')
            ->getQuery()
            ->execute()
        ;
    }

    /**
     * @return Mobile[]
     */
    public function findAllMatching(?string $query, int $limit = 5)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('u.email LIKE :query')
            ->setParameter('query', '%'.$query.'%')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Mobile[] Returns an array of User objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
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
    public function findOneBySomeField($value): ?Mobile
    {
        return $this->createQueryBuilder('m')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
