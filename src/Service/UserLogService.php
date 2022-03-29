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
    private SmsService $smsService;

    public function __construct(EntityManagerInterface $em, ParameterBagInterface $parameterBag, SmsService $smsService)
    {
        $this->em = $em;
        $this->parameterBag = $parameterBag;
        $this->smsService = $smsService;
    }

    public function userLogControl(Request $request)
    {
        $userLog = $this->em->getRepository(UserLog::class)->findOneBy(['ip' => $request->getClientIp(), 'is_what' => true], ['id' => 'desc']);
        // kullanıcı reklamdan daha önce girmişse
        if ($userLog instanceof UserLog && $userLog->getIp() == $request->getClientIp() && $request->query->get('ads') === "1"){
            //ip son haneyi sıfır yap
            $ip = explode(".", $request->getClientIp());
            $ip[3] = "0";
            $ip = implode(".", $ip);
            //ssh bağlan
            $connection = (new SSHConnection())
                ->to($this->parameterBag->get('ssh_url'))
                ->as($this->parameterBag->get('ssh_as'))
                ->withPassword($this->parameterBag->get('ssh_pass'))
                ->connect();
            // ban yetkisi aç
            $connection->run('service firewalld start');
            // banla
            $connection->run("firewall-cmd --permanent --add-rich-rule=\"rule family='ipv4' source address='$ip/24' reject\"");
            // onayla
            $connection->run('firewall-cmd --reload');
            // banladığını log'a bildir
            $userLog->setIsBanned(true);
            $this->smsService->sendSms("Birileri ban yedi hemen ads paneline ip'yi ekle!");
        }else {
            $log = new UserLog();
            $log->setIp($request->getClientIp());
            $log->setCreatedAt(new \DateTime());
            $log->setAgent($_SERVER['HTTP_USER_AGENT']);
            $log->setIsBanned(false);
            if ($request->query->get('ads') === "1")
            {
                $log->setIsWhat(true);
                $this->smsService->sendSms("Reklamdan Giren bir kullanıcı var!");
            } else {
                $log->setIsWhat(false);
            }
            $this->em->persist($log);
        }

        $this->em->flush();
        return true;
    }
}