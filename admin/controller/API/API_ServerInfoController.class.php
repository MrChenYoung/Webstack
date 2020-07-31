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

    // 获取cpu使用详情
    public function getServerCpuUseage(){
        $cmd = 'top';
        $res = ShellManager::exec($cmd);
        if ($res["success"]){
            $res = $res["result"];
//            $cpu_info = explode(",", $res[2]);
//            $cpu_usage = trim(trim($cpu_info[0], '%Cpu(s): '), 'us'); //百分比

            echo "<pre>";
            var_dump($res);
        }else {
            echo $this->failed("获取cpu使用详情失败");
        }
    }
}