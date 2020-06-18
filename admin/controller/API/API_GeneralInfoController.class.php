<?php


namespace admin\controller\API;


use framework\tools\DatabaseDataManager;

class API_GeneralInfoController extends API_BaseController
{
    private $tableName;
    public function __construct()
    {
        parent::__construct();
        $this->tableName = "acc_general_info";
    }

    // 请求常用信息列表
    public function loadGeneralInfoList(){
        $res = $platData = DatabaseDataManager::getSingleton()->find($this->tableName);
        echo $this->success($res);
    }

    // 添加常用信息
    public function addGInfo(){
        // 描述信息
        if (!isset($_GET["desc"])){
            echo $this->failed("需要desc参数");
            die;
        }
        $desc = $_GET["desc"];

        // 加密文本
        if (!isset($_GET["pass"])){
            echo $this->failed("需要pass参数");
            die;
        }
        $pass = $_GET["pass"];
        // base64解密
        $pass = base64_decode($pass);

        // 备注
        if (!isset($_GET["remark"])){
            echo $this->failed("需要remark参数");
            die;
        }
        $remark = $_GET["remark"];

        $res = DatabaseDataManager::getSingleton()->insert($this->tableName,["info_desc"=>$desc,"encrypt_info"=>$pass,"remark"=>$remark]);

        if ($res){
            echo $this->success("添加成功");
        }else {
            echo $this->failed("添加失败");
        }
    }

    // 请求指定常用信息
    public function loadGInfo(){
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

    // 修改常用信息
    public function editGInfo(){
        // 密码id
        if (!isset($_GET["id"])){
            echo $this->failed("需要id参数");
            die;
        }
        $id = $_GET["id"];


        // 描述
        if (!isset($_GET["desc"])){
            echo $this->failed("需要desc参数");
            die;
        }
        $desc = $_GET["desc"];

        // 加密信息
        if (!isset($_GET["pass"])){
            echo $this->failed("需要pass参数");
            die;
        }
        $pass = $_GET["pass"];
        // base64解密
        $pass = base64_decode($pass);

        // 备注
        if (!isset($_GET["remark"])){
            echo $this->failed("需要remark参数");
            die;
        }
        $remark = $_GET["remark"];

        $res = DatabaseDataManager::getSingleton()->update($this->tableName,["info_desc"=>$desc,"encrypt_info"=>$pass,"remark"=>$remark],["id"=>$id]);
        if ($res){
            echo $this->success("修改成功");
        }else {
            echo $this->failed("修改失败");
        }
    }

    // 删除常用信息
    public function deleteGInfo(){
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