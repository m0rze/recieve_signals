<?php

namespace App\Repository;

use App\Entity\SignalsData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SignalsData|null find($id, $lockMode = null, $lockVersion = null)
 * @method SignalsData|null findOneBy(array $criteria, array $orderBy = null)
 * @method SignalsData[]    findAll()
 * @method SignalsData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SignalsDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SignalsData::class);
    }

    // /**
    //  * @return SignalsData[] Returns an array of SignalsData objects
    //  */
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
    public function findOneBySomeField($value): ?SignalsData
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
