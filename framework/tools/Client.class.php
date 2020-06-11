<?php


namespace framework\tools;

/**
 * Class Client 获取客户端信息类
 */
class Client
{
    /**
     * 获取客户端ip
     * @return mixed
     */
    public static function getClientIp(){
//        $ip = $_SERVER["REMOTE_ADDR"];
//        return $ip;

        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

                foreach ($arr as $ip) {
                    $ip = trim($ip);

                    if ($ip != 'unknown') {
                        $realip = $ip;
                        break;
                    }
                }
            } else if (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            } else if (isset($_SERVER['REMOTE_ADDR'])) {
                $realip = $_SERVER['REMOTE_ADDR'];
            } else {
                $realip = '0.0.0.0';
            }
        } else if (getenv('HTTP_X_FORWARDED_FOR')) {
            $realip = getenv('HTTP_X_FORWARDED_FOR');
        } else if (getenv('HTTP_CLIENT_IP')) {
            $realip = getenv('HTTP_CLIENT_IP');
        } else {
            $realip = getenv('REMOTE_ADDR');
        }

        preg_match('/[\\d\\.]{7,15}/', $realip, $onlineip);
        $realip = (!empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0');
        return $realip;
    }

//    public static function getClientIp(){
//        $ip = false;
//        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
//            $ip = $_SERVER["HTTP_CLIENT_IP"];
//        }
//        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
//            $ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
//            if ($ip) {
//                array_unshift($ips, $ip);
//                $ip = FALSE;}
//            for ($i = 0; $i < count($ips); $i++) {
//                if (!eregi("^(10│172.16│192.168).", $ips[$i])) {
//                    $ip = $ips[$i];
//                    break;
//                }
//            }
//        }
//        return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
//    }

}