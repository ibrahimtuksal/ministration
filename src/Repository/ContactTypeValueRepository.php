<?php

namespace App\Repository;

use App\Entity\ContactTypeValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ContactTypeValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactTypeValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactTypeValue[]    findAll()
 * @method ContactTypeValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactTypeValueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactTypeValue::class);
    }

    // /**
    //  * @return ContactTypeValue[] Returns an array of ContactTypeValue objects
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
    public function findOneBySomeField($value): ?ContactTypeValue
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
