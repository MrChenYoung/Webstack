<?php


namespace admin\controller\API;


use framework\tools\FileManager;

class API_PhpNeedleController extends API_BaseController
{

    // 获取基本信息
    public function loadBaseInfo(){
        // 获取php版本
        $phpV = phpversion();
        // 获取Zend engine版本
        $zendV = zend_version();
        // 获取服务器类型和版本
        $serverInfo = $_SERVER['SERVER_SOFTWARE'];
        // mysql版本
        $link = mysqli_connect($GLOBALS["db_info"]["host"],$GLOBALS["db_info"]["user"],$GLOBALS["db_info"]["pass"]);
        $mysqlV = mysqli_get_server_info($link);
        // 获取ip地址
        $ip = array_key_exists('REMOTE_HOST',$_SERVER) ?  $_SERVER['REMOTE_HOST'] : "--";
        // 域名
        $domain = $_SERVER['HTTP_HOST'];
        // 协议和端口信息
        $protocol = $_SERVER['SERVER_PROTOCOL']." ".$_SERVER['SERVER_PORT'];
        // 站点根目录
        $rootPath = array_key_exists('PATH_TRANSLATED',$_SERVER) ? $_SERVER['PATH_TRANSLATED'] : "--";
        // 服务器事件
        $serverTime = date('Y年m月d日,H:i:s,D');
        // 当前用户
        $curUser = get_current_user();
        // 操作系统
        $system = php_uname('s').php_uname('r').php_uname('v');
        // include_path
        $include_path = ini_get('include_path');
        // server API
        $serverApi = php_sapi_name();
        // error_reporting level
        $errorReportingLevel = ini_get("display_errors");
        // POST提交限制
        $postLimit = ini_get('post_max_size');
        // upload_max_filesize
        $uploadLimit = ini_get('upload_max_filesize');
        // 脚本超时时间
        $scriptTimeout = ini_get('max_execution_time').'秒';
        // php安全模式状态
        $phpSafeStatus = ini_get("safe_mode") == 0 ? "off" : "on";
        // memory_get_usage
        $memoryUsage = "0";
        if (function_exists('memory_get_usage')){
            $memoryUsage = ini_get('memory_get_usage');
        }
        // 磁盘总空间
        $diskTotal = FileManager::formatBytes(disk_total_space("/"));
        // 磁盘可用空间
        $diskFree = FileManager::formatBytes(diskfreespace("/"));
        $data = [
            "PHP版本"                     =>  $phpV,
            "Zend engine版本"             =>  $zendV,
            "服务器版本"                   =>  $serverInfo,
            "Mysql版本"                   =>  $mysqlV,
            "IP"                         =>  $ip,
            "域名"                        =>  $domain,
            "协议端口"                     =>  $protocol,
            "站点根目录"                   =>  $rootPath,
            "服务器事件"                   =>  $serverTime,
            "当前用户"                     =>  $curUser,
            "操作系统"                     =>  $system,
            "include_path"                =>  $include_path,
            "serverApi"                   =>  $serverApi,
            "errorReportingLevel"         =>  $errorReportingLevel,
            "POST提交限制"                 =>  $postLimit,
            "文件上传最大限制"              =>  $uploadLimit,
            "脚本超时时间"                  =>  $scriptTimeout,
            "PHP安全模式"                  =>  $phpSafeStatus,
            "内存使用"                     =>  $memoryUsage,
            "磁盘总空间"                    =>  $diskTotal,
            "磁盘可用空间"                  =>  $diskFree
        ];
        echo $this->success($data);
    }

    // 获取性能检测结果
    public function loadPerformResult()
    {
        $time_start = $this->getime();
        for($i=0;$i<=500000;$i++){
            $count=1+1;
        }
        $timea=round(($this->getime()-$time_start)*1000,2)."ms";
        $data["i"] = $timea;

        $time_start = $this->getime();
        for($i=0;$i<=500000;$i++){
            sqrt(3.14);
        }
        $timea=round(($this->getime()-$time_start)*1000,2)."ms";
        $data["f"] = $timea;
        echo $this->success($data);
    }

    // 获取所有扩展
    public function loadExtensionList(){
        $arr =get_loaded_extensions();
        echo $this->success($arr);
    }

    // 获取所有禁用的函数
    public function loadDisableFuncList(){
        $disfun=ini_get('disable_functions');
        if (empty($disfun)){
            echo $this->success([]);
        }else {
            echo $this->success($disfun);
        }
    }

    private function getime(){
        $t = gettimeofday();
        return (float)($t['sec'] + $t['usec']/1000000);
    }
}