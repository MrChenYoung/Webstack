<?php


namespace admin\controller\API;


use framework\tools\DatabaseDataManager;
use framework\tools\SessionManager;

class API_PassManagerController extends API_BaseController
{
    private $tableName;
    public function __construct()
    {
        parent::__construct();
        $this->tableName = "acc_passwd";
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

}