<?php

require_once '../../framework/tools/StringTool.class.php';

//判断是否有文件上传
$res = [];
if (isset($_FILES['file'])) {
    // 返回值默认json

    // 文件信息
    $fileInfo = $_FILES["file"];

    // 名字
    $name = $fileInfo["name"];
    // 类型
    $type = \framework\tools\StringTool::getExtension($name);

    // 要跳转的目标主机
    $localHost = "http://".$_SERVER['HTTP_HOST'];
    $url = $localHost."?m=admin&c=DbManager&a=index&msg=";

    $msg = "";
    // 限制文件必须是sql
    if ($type != ".sql"){
        $msg = "只能上传sql文件";
    }else {
        // 目标文件目录
        $tbName = strlen($_POST["tbName"]) > 0 ? $_POST["tbName"] : "all";
        $target_path = "../resource/dbBackup/".$tbName."/".$name;
        //将文件从临时目录拷贝到指定目录
        if(move_uploaded_file($fileInfo['tmp_name'], $target_path)) {
            //上传成功,可进行进一步操作,将路径写入数据库等.
            $msg = "上传成功";
        }  else {
            // 上传失败
            $msg = "上传失败";
        }
    }
}else {
    $msg = "上传发生错误";
}


$url .= base64_encode($msg);
header("Refresh:0;url=$url");