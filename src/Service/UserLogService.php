<?php

namespace App\Service;

use App\Entity\UserLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class UserLogService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
// todo: aynı gün girilmişse 5 den fazla kayıt atma
// todo: reklam ile normali ayır
    public function userLogControl(Request $request)
    {

        $userLog = $this->em->getRepository(UserLog::class)->findOneBy(['ip' => $request->getClientIp()], ['id' => 'desc']);
        if ($userLog instanceof UserLog){
            $nowDate = new \DateTime();
            $nowDate = $nowDate->format('Y-m-d H:i:s');
            if ($this->dateDifference($userLog->getCreatedAt()->format('Y-m-d H:i:s'), $nowDate) < 24){
                if ($userLog->getCount() < 5){
                    $userLog->setCount($userLog->getCount()+1);
                    $this->em->flush();
                }else {
                    return false;
                }
            }else {
                $log = new UserLog();
                $log->setIp($request->getClientIp());
                $log->setCreatedAt(new \DateTime());
                $log->setAgent($_SERVER['HTTP_USER_AGENT']);
                if ($request->query->get('ads') === "1")
                {
                    $log->setIsWhat(true);
                } else {
                    $log->setIsWhat(false);
                }
                $this->em->persist($log);
                $this->em->flush();
                return true;
            }
        }else {
            $log = new UserLog();
            $log->setIp($request->getClientIp());
            $log->setCreatedAt(new \DateTime());
            $log->setAgent($_SERVER['HTTP_USER_AGENT']);
            if ($request->query->get('ads') === "1")
            {
                $log->setIsWhat(true);
            } else {
                $log->setIsWhat(false);
            }
            $this->em->persist($log);
            $this->em->flush();
            return true;
        }
        return false;
    }

    public function dateDifference($dateOne , $dateTwo , $differenceFormat = '%h' )
    {
        $datetime1 = date_create_from_format('Y-m-d H:i:s', $dateOne);
        $datetime2 = date_create_from_format('Y-m-d H:i:s', $dateTwo);

        $interval = date_diff($datetime1, $datetime2);

        return $interval->format($differenceFormat);
    }
}