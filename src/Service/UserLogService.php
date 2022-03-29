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
            $this->smsService->sendSms("Birileri ban yedi hemen ads paneline ip'yi ekle! ip: ".$_SERVER['HTTP_USER_AGENT']);
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

    function get_browser_name($user_agent)
    {

        $t = strtolower($user_agent);

        $t = " " . $t;

        if     (strpos($t, 'opera'     ) || strpos($t, 'opr/')     ) return 'Opera'            ;
        elseif (strpos($t, 'edge'      )                           ) return 'Edge'             ;
        elseif (strpos($t, 'chrome'    )                           ) return 'Chrome'           ;
        elseif (strpos($t, 'safari'    )                           ) return 'Safari'           ;
        elseif (strpos($t, 'firefox'   )                           ) return 'Firefox'          ;
        elseif (strpos($t, 'msie'      ) || strpos($t, 'trident/7')) return 'Internet Explorer';

        elseif (strpos($t, 'google'    )                           ) return '[Bot] Googlebot'   ;
        elseif (strpos($t, 'bing'      )                           ) return '[Bot] Bingbot'     ;
        elseif (strpos($t, 'slurp'     )                           ) return '[Bot] Yahoo! Slurp';
        elseif (strpos($t, 'duckduckgo')                           ) return '[Bot] DuckDuckBot' ;
        elseif (strpos($t, 'baidu'     )                           ) return '[Bot] Baidu'       ;
        elseif (strpos($t, 'yandex'    )                           ) return '[Bot] Yandex'      ;
        elseif (strpos($t, 'sogou'     )                           ) return '[Bot] Sogou'       ;
        elseif (strpos($t, 'exabot'    )                           ) return '[Bot] Exabot'      ;
        elseif (strpos($t, 'msn'       )                           ) return '[Bot] MSN'         ;

        elseif (strpos($t, 'mj12bot'   )                           ) return '[Bot] Majestic'     ;
        elseif (strpos($t, 'ahrefs'    )                           ) return '[Bot] Ahrefs'       ;
        elseif (strpos($t, 'semrush'   )                           ) return '[Bot] SEMRush'      ;
        elseif (strpos($t, 'rogerbot'  ) || strpos($t, 'dotbot')   ) return '[Bot] Moz or OpenSiteExplorer';
        elseif (strpos($t, 'frog'      ) || strpos($t, 'screaming')) return '[Bot] Screaming Frog';

        elseif (strpos($t, 'facebook'  )                           ) return '[Bot] Facebook'     ;
        elseif (strpos($t, 'pinterest' )                           ) return '[Bot] Pinterest'    ;

        elseif (strpos($t, 'crawler' ) || strpos($t, 'api'    ) ||
            strpos($t, 'spider'  ) || strpos($t, 'http'   ) ||
            strpos($t, 'bot'     ) || strpos($t, 'archive') ||
            strpos($t, 'info'    ) || strpos($t, 'data'   )    ) return '[Bot] Other'   ;

        return 'Other (Unknown)';
    }

    function getOS($user_agent) {

        $os_platform =   "Bilinmeyen";
        $os_array =   array(
            '/windows nt 10/i'      =>  'Windows 10',
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/windows nt 5.0/i'     =>  'Windows 2000',
            '/windows me/i'         =>  'Windows ME',
            '/win98/i'              =>  'Windows 98',
            '/win95/i'              =>  'Windows 95',
            '/win16/i'              =>  'Windows 3.11',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        );

        foreach ( $os_array as $regex => $value ) {
            if ( preg_match($regex, $user_agent ) ) {
                $os_platform = $value;
            }
        }
        return $os_platform;
    }
}