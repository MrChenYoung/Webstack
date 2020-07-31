<?php


namespace admin\controller\API;


use framework\tools\ShellManager;

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
            // 虚拟内存信息
            $swap = preg_replace($patt,' ',$res[2]);

            $memory = array_filter($memory);
            echo "<pre>";
            var_dump($memory);
            var_dump(explode(" ", $memory));
//            echo $this->success($res);
        }else {
            echo $this->failed("获取内存信息失败");
        }
    }
}