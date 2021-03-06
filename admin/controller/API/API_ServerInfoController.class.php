<?php


namespace admin\controller\API;


use framework\tools\FileManager;
use framework\tools\ShellManager;
use framework\tools\StringTool;

class API_ServerInfoController extends API_BaseController
{
    // 获取内存使用详情
    public function getServerMemoryUsage(){
        $cmd = "free";
        $res = ShellManager::exec($cmd);
        if ($res["success"]){
            $patt = '/\s{1,}/';
            $res = $res["result"];
            // 内存信息
            $memory = preg_replace($patt,' ',$res[1]);
            $memory = explode(" ", $memory);
            $memoryTotal = (int)$memory[1] * 1024;
            $memoryUsed = $memoryTotal - (int)$memory[3] * 1024;
            $memoryPersent = round($memoryUsed/$memoryTotal,2) * 100;
            $memoryTotal = FileManager::formatBytes((string)$memoryTotal);
            $memoryUsed = FileManager::formatBytes((string)$memoryUsed);

            // 虚拟内存信息
            $swap = preg_replace($patt,' ',$res[2]);
            $swap = explode(" ", $swap);
            $swapTotal = (int)$swap[1] * 1024;
            $swapUsed = $swapTotal - (int)$swap[3] * 1024;
            $swapPersent = round($swapUsed/$swapTotal,2) * 100;
            $swapTotal = FileManager::formatBytes((string)$swapTotal);
            $swapUsed = FileManager::formatBytes((string)$swapUsed);
            $memoryArray = [
                "memoryUsed"    => $memoryUsed."/".$memoryTotal,
                "memoryPersent" => $memoryPersent."%",
                "swapUsed"      => $swapUsed."/".$swapTotal,
                "swapPersent"   => $swapPersent."%"
            ];

            echo $this->success($memoryArray);
        }else {
            echo $this->failed("获取内存信息失败");
        }
    }

    // 获取进程占用cpu和内存详情
    public function getProgressInfo(){
        // 进程列表
//        $proList = ["rclone","BT-Panel","kcptun","sshd","ffmpeg","python","BT-Task"];
        $proList = $this->getAllProgress();

        $data = [];
        $mem = [];
        foreach ($proList as $item) {
            $res = $this->getProInfo($item);
            $data[] = $res;
            $mem[] = $res["memorySpace"];
        }

        // 排序
        array_multisort($mem, SORT_DESC, $data);
        echo $this->success($data);
    }

    // 重启服务器
    public function rebootServer(){
//        $cmd = "systemctl reboot -i";
//        $cmd = "sudo -u root reboot";
//        $res = ShellManager::exec($cmd);
//        if ($res["success"]){
//            echo $this->success("服务器重启中");
//        }else {
//            echo $this->failed("服务器重启失败");
//        }


        system("/www/wwwroot/webstack.yycode.ml/admin/controller/RebootServer.sh");
//        shell_exec("/usr/sbin/reboot");
//        exec("/usr/sbin/reboot");
//        system("/usr/sbin/reboot");
    }

    // 获取所有进程
    private function getAllProgress(){
        $cmd = "ps -A";
        $res = ShellManager::exec($cmd);
        $proList = [];
        if ($res["success"]){
            $res = $res["result"];
            $index = 0;
            foreach ($res as $re) {
                if ($index != 0){
                    $patt = '/\s{1,}/';
                    $re = preg_replace($patt,' ',$re);
                    $re = explode(" ",$re);
                    $pro = $re[count($re) - 1];
                    if (!in_array($pro,$proList)){
                        $proList[] = $pro;
                    }
                }
                $index++;
            }
        }

        return $proList;
    }

    // 获取指定进程占用cpu和内存详情
    private function getProInfo($proName){
        $cmd = 'ps -aux | grep '.$proName;
        $res = ShellManager::exec($cmd);

        $cpuUsed = 0.0;
        $memoryUsed = 0.0;
        $memorySpace = 0;
        $data = [];
        if ($res["success"]){
            $proList = $res["result"];
            foreach ($proList as $pro) {
                $patt = '/\s{1,}/';
                $pro = preg_replace($patt,' ',$pro);
                $pro = explode(" ",$pro);
                $cpuUsed += (float)$pro[2];
                $memoryUsed += (float)$pro[3];
                $memorySpace += (int)$pro[5];
            }
        }

        $cpuUsed = $cpuUsed."%";
        $memoryUsed = $memoryUsed."%";
        $memorySpaceFormate = FileManager::formatBytes($memorySpace * 1024);

        $data = [
            "proName"               =>  $proName,
            "cpuUsed"               =>  $cpuUsed,
            "memoryUsed"            =>  $memoryUsed,
            "memorySpace"           =>  $memorySpace,
            "memorySpaceFormate"    =>  $memorySpaceFormate
        ];

        return $data;
    }
}