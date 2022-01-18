<?php

namespace App\Repository;

use App\Entity\CityWithCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CityWithCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method CityWithCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method CityWithCategory[]    findAll()
 * @method CityWithCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityWithCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CityWithCategory::class);
    }

    // /**
    //  * @return CityWithCategory[] Returns an array of CityWithCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CityWithCategory
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
