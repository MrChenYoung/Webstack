<?php


namespace admin\controller\API;


use framework\tools\DatabaseDataManager;

class API_PassManagerController extends API_BaseController
{
    private $tableName;
    public function __construct()
    {
        parent::__construct();
        $this->tableName = "acc_passwd";
    }

    // 请求密码列表
    public function loadPassList(){
        // 查询密码列表
        $res = $platData = DatabaseDataManager::getSingleton()->find($this->tableName);
        echo $this->success($res);
    }

    // 添加密码
    public function addPass(){
        // 密码描述信息
        if (!isset($_GET["desc"])){
            echo $this->failed("需要desc参数");
            die;
        }
        $desc = $_GET["desc"];

        // 密码
        if (!isset($_GET["pass"])){
            echo $this->failed("需要pass参数");
            die;
        }
        $pass = $_GET["pass"];
        // base64解密
        $pass = base64_decode($pass);

        // 安全级别
        if (!isset($_GET["level"])){
            echo $this->failed("需要level参数");
            die;
        }
        $level = $_GET["level"];

        $res = DatabaseDataManager::getSingleton()->insert($this->tableName,["pass_desc"=>$desc,"passwd"=>$pass,"pass_level"=>$level]);

        if ($res){
            echo $this->success("添加成功");
        }else {
            echo $this->failed("添加失败");
        }
    }

    // 请求指定密码信息
    public function loadPasswd(){
        // 密码id
        if (!isset($_GET["id"])){
            echo $this->failed("需要id参数");
            die;
        }
        $id = $_GET["id"];

        $res = DatabaseDataManager::getSingleton()->find($this->tableName,["id"=>$id]);
        if ($res !== false){
            echo $this->success($res[0]);
        }else {
            echo $this->failed("请求密码数据失败");
        }
    }

    // 修改密码
    public function editPass(){
        // 密码id
        if (!isset($_GET["id"])){
            echo $this->failed("需要id参数");
            die;
        }
        $id = $_GET["id"];

        // 密码安全级别
        if (!isset($_GET["level"])){
            echo $this->failed("需要level参数");
            die;
        }
        $level = $_GET["level"];

        // 密码描述
        if (!isset($_GET["desc"])){
            echo $this->failed("需要desc参数");
            die;
        }
        $desc = $_GET["desc"];

        // 密码
        if (!isset($_GET["pass"])){
            echo $this->failed("需要pass参数");
            die;
        }
        $pass = $_GET["pass"];
        // base64解密
        $pass = base64_decode($pass);

        $res = DatabaseDataManager::getSingleton()->update($this->tableName,["pass_desc"=>$desc,"passwd"=>$pass,"pass_level"=>$level],["id"=>$id]);
        if ($res){
            echo $this->success("修改成功");
        }else {
            echo $this->failed("修改失败");
        }
    }

    // 删除密码
    public function deletePass(){
        // 密码id
        if (!isset($_GET["id"])){
            echo $this->failed("需要id参数");
            die;
        }
        $id = $_GET["id"];

        $res = DatabaseDataManager::getSingleton()->delete($this->tableName,["id"=>$id]);
        if ($res){
            echo $this->success("删除成功");
        }else {
            echo $this->failed("删除失败");
        }
    }
}