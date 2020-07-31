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
            $res = (string)trim($res["result"]);
            echo $this->success($res);
        }else {
            echo $this->failed("获取内存信息失败");
        }
    }
}