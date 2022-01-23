<?php

namespace App\Repository;

use App\Entity\BrandWithCity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BrandWithCity|null find($id, $lockMode = null, $lockVersion = null)
 * @method BrandWithCity|null findOneBy(array $criteria, array $orderBy = null)
 * @method BrandWithCity[]    findAll()
 * @method BrandWithCity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BrandWithCityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BrandWithCity::class);
    }

    // /**
    //  * @return BrandWithCity[] Returns an array of BrandWithCity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BrandWithCity
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
