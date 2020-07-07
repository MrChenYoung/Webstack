<?php


namespace admin\controller\API;


use framework\tools\DatabaseBackupManager;
use framework\tools\DatabaseManager;
use framework\tools\FileManager;
use framework\tools\LogManager;
use framework\tools\MultiThreadTool;
use framework\tools\ShellManager;

class API_DatabaseController extends API_BaseController
{
    // 本地数据库备份目录
    private $backupPath;
    // 谷歌云上数据库备份目录
    private $driveDbPath;
    // 数据库
    private $dbName;
    // 数据库列表
    private $dbList;
    public function __construct()
    {
        parent::__construct();
        $this->backupPath = ADMIN."resource/dbBackup/";
        $this->driveDbPath = "/www/wwwroot/cloud.yycode.ml/cloudmount/GDSuite/我的数据/备份数据/db/";
        $this->dbName = $GLOBALS["db_info"]["dbname"];
        $this->dbList = ["movie","account_db","cloud_manager_db","web_stack_db"];
    }

    // 获取所有数据库名
    public function loadAllDatabase(){
        $currentDb = $this->dbName;
        echo $this->success(["list"=>$this->dbList,"currentDb"=>$currentDb]);
    }

    // 获取所有表名称和备注
    public function loadTableList(){
        $sql = "SHOW TABLES";
        $tableLists = DatabaseManager::getSingleton()->fetch($sql);
        $tables = [];
        foreach ($tableLists as $re) {
            // 表名
            $field = "Tables_in_".$this->dbName;
            $tableName = $re[$field];

            // 获取表备注
            $sql = "SHOW CREATE TABLE $tableName";
            $tableI = DatabaseManager::getSingleton()->fetch($sql);
            $tableI = $tableI[0]['Create Table'];

            // 通过正则表达式获取表备注
            $commentReg = '/COMMENT=([^\s]+)[\s|.]*/';
            preg_match($commentReg,$tableI,$tbComment);
            // 去掉字符串中的单引号和双引号
            $tbComment = str_replace(array("'",'"'), "", $tbComment);
            if (count($tbComment) > 1){
                $tbInfo["comment"] = $tbComment[1];
                $tbInfo["tableName"] = $tableName;
                $tables[] = $tbInfo;

                // 创建盛放单个表备份数据的文件夹
                $saveDirPath = ROOT."admin/resource/dbBackup/".$this->dbName."/".$tableName;
                if (!file_exists($saveDirPath)){
                    mkdir($saveDirPath,0777,true);
                }
            }
        }


        // 创建盛放整个数据库(所有表)备份数据的文件夹
        $allBackDirPath = ROOT."admin/resource/dbBackup/".$this->dbName."/all";
        if (!file_exists($allBackDirPath)){
            mkdir($allBackDirPath,0777,true);
        }

        echo $this->success($tables);
    }

    // 备份数据库
    public function backupDB(){
        // 表名
        if (!isset($_GET["tbName"])){
            echo $this->failed("需要tbName参数");
            die;
        }
        $tbName = $_GET["tbName"];

        // 是否是备份所有数据库
        if (!isset($_GET["backupAll"])){
            echo $this->failed("需要backupAll参数");
            die;
        }
        $backupAll = $_GET["backupAll"];

        // 备份类文件位置
        $backupManagerFilePath = ROOT."framework/tools/DatabaseBackupManager.class.php";

        // 后台移动
        $params = [
            "m"=>"admin",
            "c"=>"AsynTask",
            "a"=>"index",
            "localBackupPath" => $this->backupPath,
            "backupManagerFilePath" => $backupManagerFilePath,
            "tbName" => $tbName,
            "backupAll" => $backupAll,
            "dbList"    =>  json_encode($this->dbList)
        ];

        MultiThreadTool::addTask($this->website."/index.php","backupDb",$params);
        // 提示正在后台移动
        echo $this->success("数据库后台备份中");
    }

    // 获取数据库备份历史
    public function loadDbBackupHistory(){
        // 表名
        if (!isset($_GET["tbName"])){
            echo $this->failed("需要tbName参数");
            die;
        }
        $tbName = $_GET["tbName"];
        $tbDirName = strlen($tbName) > 0 ? $tbName : "all";

        $path = $this->driveDbPath.$this->dbName."/".$tbDirName;
        // 获取所有文件名
        $fileLists = [];
        $handler = file_exists($path) ? opendir($path) : null;
        if ($handler){
            while (($filename = readdir($handler)) !== false) { //务必使用!==，防止目录下出现类似文件名“0”等情况
                if ($filename != "." && $filename != ".." && substr($filename,0,1) != ".") {
                    //获取文件修改日期
                    date_default_timezone_set('PRC');
                    $filetime = date('Y-m-d H:i:s', filemtime($path . "/" . $filename));
                    //文件修改时间作为健值
                    if (array_key_exists($filetime,$fileLists)){
                        // 已经有相同时间文件
                        $existsValue = $fileLists[$filetime];
                        if (is_array($existsValue)){
                            $existsValue[] = $filename;
                            $fileLists[$filetime] = $existsValue;
                        }else {
                            $fileLists[$filetime] = [$existsValue,$filename];
                        }
                    }else {
                        // 没有没有相同时间的文件
                        $fileLists[$filetime] = $filename;
                    }
                }
            }
            closedir($handler);
        }

        // key按照时间排序
        ksort($fileLists);
        $data = [];
        foreach ($fileLists as $key=>$item) {
            $fileInfo = [];
            if (is_array($item)){
                foreach ($item as $newItem){
                    // 获取文件大小
                    $fileSize = FileManager::getFileSize($path."/".$newItem);
                    $fileSize = FileManager::formatBytes($fileSize);
                    $fileInfo["size"] = $fileSize;
                    $fileInfo["name"] = $newItem;
                    $fileInfo["time"] = $key;
                    $data[] = $fileInfo;
                }
            }else {
                // 获取文件大小
                $fileSize = FileManager::getFileSize($path."/".$item);
                $fileSize = FileManager::formatBytes($fileSize);
                $fileInfo["size"] = $fileSize;
                $fileInfo["name"] = $item;
                $fileInfo["time"] = $key;
                $data[] = $fileInfo;
            }
        }
        // 倒序排列
        $data = array_reverse($data);

        echo $this->success($data);
    }


    // 删除备份
    public function deleteBackup(){
        // 文件名
        if (!isset($_GET["fileName"])){
            echo $this->failed("需要fileName参数");
            die;
        }
        $fileName = $_GET["fileName"];

        // 表名
        if (!isset($_GET["tbName"])){
            echo $this->failed("需要tbName参数");
            die;
        }
        $tbName = $_GET["tbName"];
        $tbDirName = strlen($tbName) > 0 ? $tbName : "all";

        $cmd = "rclone delete GDSuite:我的数据/备份数据/db/".$this->dbName."/".$tbDirName."/".$fileName;
        $res = ShellManager::exec($cmd);
        if ($res["success"]){
            echo $this->success("备份删除成功");
        }else {
            echo $this->failed("备份删除失败");
        }
    }

    // 导入备份
    public function importBackup(){
        // 文件名
        if (!isset($_GET["fileName"])){
            echo $this->failed("需要fileName参数");
            die;
        }
        $fileName = $_GET["fileName"];

        // 表名
        if (!isset($_GET["tbName"])){
            echo $this->failed("需要tbName参数");
            die;
        }
        $tbName = $_GET["tbName"];
        $tbDirName = strlen($tbName) > 0 ? $tbName : "all";

        $path = $this->backupPath.$tbDirName."/".$fileName;
        (new DatabaseBackupManager())->restore($path);
        echo $this->success("导入完成");
    }

    // 上传备份文件
    public function uploadBackup(){
        //判断是否有文件上传
        if (isset($_FILES['file'])) {
            // 文件信息
            $fileInfo = $_FILES["file"];
            // 名字
            $name = $fileInfo["name"];
            // 类型
            $type = $fileInfo["type"];

            // 限制文件必须是sql
            if ($type != "application/x-sql"){
                echo $this->failed("只能上传sql文件");
                die;
            }

            // 目标文件目录
            $target_path = ADMIN."resource/dbBackup/".$name;

            //将文件从临时目录拷贝到指定目录
            if(move_uploaded_file($fileInfo['tmp_name'], $target_path)) {
                //上传成功,可进行进一步操作,将路径写入数据库等.
                echo $this->success("上传成功");
            }  else {
                // 上传失败
                echo $this->failed("上传失败");
            }
        }else {
            echo $this->failed("上传发生错误");
        }
    }

    // 清空日志
    public function clearLog(){
        LogManager::getSingleton()->clearLog();
        echo $this->success("");
    }
}