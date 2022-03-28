<?php

namespace App\Service;

use App\Entity\UserLog;
use DivineOmega\SSHConnection\SSHConnection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;

class UserLogService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;
    private ParameterBagInterface $parameterBag;

    public function __construct(EntityManagerInterface $em, ParameterBagInterface $parameterBag)
    {
        $this->em = $em;
        $this->parameterBag = $parameterBag;
    }

    public function userLogControl(Request $request)
    {
        $userLog = $this->em->getRepository(UserLog::class)->findBy(['ip' => $request->getClientIp(), 'is_what' => true]);
        // kullanıcı reklamdan 1 den fazla girmişse
        if (count($userLog) > 1){
            //banla
            $connection = (new SSHConnection())
                ->to($this->parameterBag->get('ssh_url'))
                ->as($this->parameterBag->get('ssh_as'))
                ->withPassword($this->parameterBag->get('ssh_pass'))
                ->connect();
            $connection->run("firewall-cmd --permanent --add-rich-rule=\"rule family='ipv4' source address='{$request->getClientIp()}' reject\"");
            $connection->run('firewall-cmd --reload');
            // banladığını bildir log'a
            foreach ($userLog as $log){
                $log->setIsBanned(true);
            }
        }else {
            $log = new UserLog();
            $log->setIp($request->getClientIp());
            $log->setCreatedAt(new \DateTime());
            $log->setAgent($_SERVER['HTTP_USER_AGENT']);
            $log->setIsBanned(false);
            if ($request->query->get('ads') === "1")
            {
                $log->setIsWhat(true);
            } else {
                $log->setIsWhat(false);
            }
            $this->em->persist($log);
        }

        $this->em->flush();
        return true;
    }
}