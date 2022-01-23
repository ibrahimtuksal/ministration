<?php

namespace App\Repository;

use App\Entity\BrandContent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BrandContent|null find($id, $lockMode = null, $lockVersion = null)
 * @method BrandContent|null findOneBy(array $criteria, array $orderBy = null)
 * @method BrandContent[]    findAll()
 * @method BrandContent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BrandContentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BrandContent::class);
    }

    // /**
    //  * @return BrandContent[] Returns an array of BrandContent objects
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
    public function findOneBySomeField($value): ?BrandContent
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
