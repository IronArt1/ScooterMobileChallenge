<?php

namespace App\Repository;

use App\Entity\Scooter;
use App\Interfaces\Repository\ScooterRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Scooter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Scooter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Scooter[]    findAll()
 * @method Scooter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScooterRepository extends ServiceEntityRepository implements ScooterRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Scooter::class);
    }

    /**
     * @return Scooter[]
     */
    public function scooterUpdateStatus(
        bool $status,
        string $id
    ) {
        return $this->createQueryBuilder('s')
            ->update()
            ->andWhere('s.id = :id')
            ->setParameter('id', $id)
            ->set('s.occupied', ':occupied')
            ->setParameter('occupied', $status)
            ->getQuery()
            ->execute()
        ;
    }

    /**
     * @return Scooter[]
     */
    public function findAllMatching(array $params, string $status, int $limit = 10)
    {
        switch ($status) {
            case self::OCCUPIED_STATUS:
                $status = 1;
                break;
            case self::AVAILABLE_STATUS:
                $status = 0;
                break;
            default:
                $status = '%';
        }

        return $this->createQueryBuilder('s')
            ->leftJoin('s.location', 'l')
            ->andWhere('s.occupied LIKE :status')
            ->andWhere('l.latitude <= :startLatitude')
            ->andWhere('l.latitude >= :endLatitude')
            ->andWhere('l.longitude >= :startLongitude')
            ->andWhere('l.longitude <= :endLongitude')
            ->setParameter('status', $status)
            ->setParameter('startLatitude', $params['startLatitude'])
            ->setParameter('endLatitude', $params['endLatitude'])
            ->setParameter('startLongitude', $params['startLongitude'])
            ->setParameter('endLongitude', $params['endLongitude'])
            ->setMaxResults($limit)
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
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
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
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
