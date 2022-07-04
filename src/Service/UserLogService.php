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

    public function userLogControl(Request $request)
    {
        $agent = $this->get_browser_name($_SERVER['HTTP_USER_AGENT']);
        if ($agent == false){
            return true;
        }
        if (strpos($_SERVER['HTTP_USER_AGENT'], "bot") and $agent !== 'google'){
            return true;
        }
        $log = new UserLog();
        $log->setIp($request->getClientIp());
        $log->setCreatedAt(new \DateTime());
        $log->setAgent($_SERVER['HTTP_USER_AGENT']);
        $log->setIsBanned(false);
        if ($request->query->get('ads') === "1")
        {
            $log->setIsWhat(true);
            //$this->smsService->sendSms("Reklamdan Giren bir kullanıcı var!");
        } else {
            $log->setIsWhat(false);
        }
        $this->em->persist($log);
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

        elseif (strpos($t, 'google'    )                           )        return 'google';
        elseif (strpos($t, 'bing'      )                           )        return false;
        elseif (strpos($t, 'slurp'     )                           )        return false;
        elseif (strpos($t, 'duckduckgo')                           )        return false;
        elseif (strpos($t, 'baidu'     )                           )        return false;
        elseif (strpos($t, 'yandex'    )                           )        return false;
        elseif (strpos($t, 'sogou'     )                           )        return false;
        elseif (strpos($t, 'exabot'    )                           )        return false;
        elseif (strpos($t, 'msn'       )                           )        return false;
        elseif (strpos($t, 'mj12bot'   )                           )        return false;
        elseif (strpos($t, 'ahrefs'    )                           )        return false;
        elseif (strpos($t, 'semrush'   )                           )        return false;
        elseif (strpos($t, 'rogerbot'  ) || strpos($t, 'dotbot')   ) return false;
        elseif (strpos($t, 'frog'      ) || strpos($t, 'screaming')) return false;
        elseif (strpos($t, 'facebook'  )                           )        return false;
        elseif (strpos($t, 'pinterest' )                           )        return false;

        elseif (strpos($t, 'crawler' ) || strpos($t, 'api'    ) ||
            strpos($t, 'spider'  ) || strpos($t, 'http'   ) ||
            strpos($t, 'bot'     ) || strpos($t, 'archive') ||
            strpos($t, 'info'    ) || strpos($t, 'data'   )    ) return false   ;

        return false;
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