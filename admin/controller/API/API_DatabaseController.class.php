<?php


namespace admin\controller\API;


use framework\tools\DatabaseBackupManager;
use framework\tools\FileManager;

class API_DatabaseController extends API_BaseController
{
    private $backupPath;
    public function __construct()
    {
        parent::__construct();
        $this->backupPath = ADMIN."resource/dbBackup/";
    }

    // 备份数据库
    public function backupDB(){
        (new DatabaseBackupManager())->backup('',$this->backupPath);
        echo $this->success("数据库备份完成");
    }

    // 获取数据库备份历史
    public function loadDbBackupHistory(){
        $path = $this->backupPath;

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

        $path = $this->backupPath.$fileName;
        if (file_exists($path)){
            $status = unlink($path);
            if ($status){
                echo $this->success("备份删除成功");
            }else {
                echo $this->failed("备份删除失败");
            }
        }else {
            echo $this->failed("备份不存在");
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

        $path = $this->backupPath.$fileName;
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
}