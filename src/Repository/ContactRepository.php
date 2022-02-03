<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    public function getWhatsApp()
    {
        $qb = $this->createQueryBuilder('c');
        $qb->leftJoin('c.type', 'type');
        $qb->leftJoin('type.value', 'typeValue');
        $qb->andWhere('typeValue.id = :id')->setParameter('id', 1);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getPhones()
    {
        $qb = $this->createQueryBuilder('c');
        $qb->leftJoin('c.type', 'type');
        $qb->leftJoin('type.value', 'typeValue');
        $qb->andWhere('typeValue.id != :id')->setParameter('id', 1);

        return $qb->getQuery()->getResult();
    }
}
