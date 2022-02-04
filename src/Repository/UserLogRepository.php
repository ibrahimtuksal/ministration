<?php

namespace App\Repository;

use App\Entity\UserLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method UserLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserLog[]    findAll()
 * @method UserLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserLog::class);
    }


    public function findByNowDay(Request $request)
    {
        $first_time = "00:00:00";
        $last_time = "23:59:00";
        if ($request->get('first_time'))
        {
            $first_time = $request->get('first_time');
        }
        if ($request->get('last_time'))
        {
            $first_time = $request->get('last_time');
        }

        $startDate = new \DateTime();
        $endDate = new \DateTime();
        $qb = $this->createQueryBuilder('u')
            ->orderBy('u.id', 'desc');

        if (! $request->get('first') || ! $request->get('last'))
        {
            $qb->andWhere('u.created_at >= :startDate')
                ->andWhere('u.created_at <= :endDate')
                ->setParameter('startDate',$startDate->format('Y-m-d')." $first_time")
                ->setParameter('endDate',$endDate->format('Y-m-d')." $last_time");
        }

        if($request->get('first') && $request->get('last'))
        {
            $qb->andWhere('u.created_at >= :startDate')
                ->andWhere('u.created_at <= :endDate')
                ->setParameter('startDate',$request->get('first')." $first_time")
                ->setParameter('endDate',$request->get('last')." $last_time");

        } else if($request->get('first') && ! $request->get('last')){
            $qb->andWhere('u.created_at >= :startDate')->setParameter('startDate',$request->get('first')." $first_time");
        } else if($request->get('last') && ! $request->get('first')){
            $qb->andWhere('u.created_at >= :startDate')->setParameter('startDate',$request->get('last')." $last_time");
        }

        if ($request->get('isWhat') == 'on')
        {
            $qb->andWhere('u.is_what = :isWhat')->setParameter('isWhat', true);
        }

        if ($request->get('count') == 'on')
        {
            $qb->andWhere('u.count > :count')->setParameter('count', 1);
        }

        return $qb->getQuery()->getResult();
    }


    /*
    public function findOneBySomeField($value): ?UserLog
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
