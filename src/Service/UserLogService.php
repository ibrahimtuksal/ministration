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

/*
 * ip daha önce girmemişse veri eklenir
 * ip varsa ve 24 saat geçmemişse her girdiğinde sayıyı 1 arttırır girdiği sayıyı en fazla 5'e kadar yükseltir
 * ip varsa ve 24 saat geçmişse yeni veri eklenir
 */
    public function userLogControl(Request $request)
    {
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
}