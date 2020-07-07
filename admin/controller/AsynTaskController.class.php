<?php

namespace admin\controller;

use framework\core\Controller;
use framework\tools\DatabaseDataManager;
use framework\tools\LogManager;
use framework\tools\ShellManager;

class AsynTaskController extends Controller
{
    public function index()
    {
        parent::index();

        // 要调用哪个方法
        if (!isset($_POST["action"])){
            die;
        }
        $action = $_POST["action"];

        // 执行指定方法
        if ($action){
            $this->$action();
        }
    }

    // 备份数据库
    public function backupDb(){
        // 本地数据库备份目录
        if (!isset($_REQUEST["localBackupPath"])) die;
        $localBackupPath = $_REQUEST["localBackupPath"];
        // 备份类文件位置
        if (!isset($_REQUEST["backupManagerFilePath"])) die;
        $backupManagerFilePath = $_REQUEST["backupManagerFilePath"];
        // 表名
        if (!isset($_REQUEST["tbName"])) die;
        $tbName = $_REQUEST["tbName"];
        $tbName = strlen($tbName) == 0 ? "-1" : $tbName;
        // 是否是备份所有数据库
        if (!isset($_REQUEST["backupAll"])) die;
        $backupAll = $_REQUEST["backupAll"];
        // 数据库列表
        if (!isset($_REQUEST["dbList"])) die;
        $dbList = $_REQUEST["dbList"];
        // 数据库
        $dbName = $GLOBALS["db_info"]["dbname"];
        $tName = $tbName === "-1" ? "全部" : $tbName;

        // 清空日志
        LogManager::getSingleton()->clearLog();
        LogManager::getSingleton()->addLog("开始备份数据库:".$dbName.",数据表:".$tName);

        // 执行转存文件php脚本
        $cmd = "php ".ADMIN."controller/DbBackup.php ".LogManager::getSingleton()->logFilePath." ".$dbName;
        $cmd = $cmd." ".$localBackupPath." ".$tbName." ".$backupManagerFilePath." ".$backupAll." '".$dbList."'";
        $res = ShellManager::exec($cmd);
        if (!$res["success"]){
            LogManager::getSingleton()->addLog("执行测试php脚本失败:".json_encode($res));
        }else {
            LogManager::getSingleton()->addLog("执行测试php脚本成功:".json_encode($res));
        }
    }
}