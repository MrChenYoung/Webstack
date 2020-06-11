<?php


namespace admin\controller\API;


use framework\tools\DatabaseDataManager;

class API_AccountController extends API_BaseController
{
    private $tableName;
    public function __construct()
    {
        parent::__construct();
        $this->tableName = "acc_account";
    }

    // 获取账号列表
    public function loadAccountList(){
        // 查询账号列表
        $accData = DatabaseDataManager::getSingleton()->find($this->tableName);
        if ($accData !== false){
            // 获取每个账号所属的平台
            foreach ($accData as $key=>$accDatum) {
                $platId = $accDatum["plat_id"];
                $platData = DatabaseDataManager::getSingleton()->find("acc_platform",["id"=>$platId],["plat_name"]);
                $platName = "";
                if ($platData !== false && count($platData) > 0){
                    $platName = $platData[0]["plat_name"];
                }
                $accData[$key]["plat_name"] = $platName;
            }

            echo $this->success($accData);
        }else {
            echo $this->failed("获取平台列表失败");
        }
    }

    // 请求指定账号数据
    public function loadAccount(){
        // id
        if (!isset($_GET["id"])){
            echo $this->failed("需要id参数");
            die;
        }
        $accId = $_GET["id"];

        $sql = <<<EEE
SELECT acc_account.*,acc_platform.plat_name FROM acc_account,acc_platform WHERE acc_account.plat_id=acc_platform.id AND acc_account.id=$accId;
EEE;
        $res = DatabaseDataManager::getSingleton()->fetch($sql);

        if ($res !== false){
            echo $this->success($res[0]);
        }else {
            echo $this->failed("查询账号数据失败");
        }
    }

    // 添加账号
    public function addAccount(){
        // 所属平台
        if (!isset($_GET["platId"])){
            echo $this->failed("需要platId参数");
            die;
        }
        $platId = $_GET["platId"];

        // 账户描述信息
        if (!isset($_GET["desc"])){
            echo $this->failed("需要desc参数");
            die;
        }
        $desc = $_GET["desc"];

        // 用户名
        if (!isset($_GET["user"])){
            echo $this->failed("需要user参数");
            die;
        }
        $user = $_GET["user"];
        // base64解码
        $user = base64_decode($user);

        // 密码
        if (!isset($_GET["pass"])){
            echo $this->failed("需要pass参数");
            die;
        }
        $pass = $_GET["pass"];
        // base64解码
        $pass = base64_decode($pass);

        // 登录地址
        if (!isset($_GET["address"])){
            echo $this->failed("需要address参数");
            die;
        }
        $address = $_GET["address"];
        // base64解码
        $address = base64_decode($address);

        // 备注
        if (!isset($_GET["remark"])){
            echo $this->failed("需要remark参数");
            die;
        }
        $remark = $_GET["remark"];


        // 插入数据库
        $insertData = [
            "acc_desc"      =>  $desc,
            "user"          =>  $user,
            "passwd"        =>  $pass,
            "address"       =>  $address,
            "plat_id"       =>  $platId,
            "remark"        =>  $remark
        ];
        $res = DatabaseDataManager::getSingleton()->insert($this->tableName,$insertData);
        if ($res){
            // 添加id到平台表
            $resId = $this->addAccountToPlat($platId,$res);
            if ($resId){
                // 成功
                echo $this->success("账号添加成功 ");
                die;
            }
        }
        echo $this->failed("账号添加失败");
    }

    // 修改账号信息
    public function editAccount(){
        // id
        if (!isset($_GET["id"])){
            echo $this->failed("需要id参数");
            die;
        }
        $accId = $_GET["id"];

        // 所属平台id
        if (!isset($_GET["platId"])){
            echo $this->failed("需要platId参数");
            die;
        }
        $platId = $_GET["platId"];

        // 账户描述
        if (!isset($_GET["desc"])){
            echo $this->failed("需要desc参数");
            die;
        }
        $accDesc = $_GET["desc"];

        // 用户名
        if (!isset($_GET["user"])){
            echo $this->failed("需要user参数");
            die;
        }
        $user = $_GET["user"];
        // base64解码
        $user = base64_decode($user);

        // 密码
        if (!isset($_GET["pass"])){
            echo $this->failed("需要pass参数");
            die;
        }
        $pass = $_GET["pass"];
        // base64解密
        $pass = base64_decode($pass);

        // 登录地址
        if (!isset($_GET["address"])){
            echo $this->failed("需要address参数");
            die;
        }
        $address = $_GET["address"];
        // base64解码
        $address = base64_decode($address);

        // 备注
        if (!isset($_GET["remark"])){
            echo $this->failed("需要remark参数");
            die;
        }
        $remark = $_GET["remark"];

        // 查询原来所属的平台并移除
        $oldPlatData = DatabaseDataManager::getSingleton()->find($this->tableName,["id"=>$accId],["plat_id"]);
        if ($oldPlatData){
            $oldPlatId = $oldPlatData[0]["plat_id"];
            $this->deleteAccFromPlatform($oldPlatId,$accId);
        }

        // 添加账号id记录到新的平台中
        $resId = $this->addAccountToPlat($platId,$accId);
        if ($resId){
            // 修改的数据
            $editData = [
                "plat_id"       =>  $platId,
                "acc_desc"      =>  $accDesc,
                "user"          =>  $user,
                "passwd"        =>  $pass,
                "address"       =>  $address,
                "remark"        =>  $remark
            ];
            $res = DatabaseDataManager::getSingleton()->update($this->tableName,$editData,["id"=>$accId]);
            if ($res){
                echo $this->success("修改成功");
                die;
            }
        }

        echo $this->failed("修改失败");
    }

    // 删除账号
    public function deleteAccount(){
        // id
        if (!isset($_GET["id"])){
            echo $this->failed("需要id参数");
            die;
        }
        $accId = $_GET["id"];

        // platid
        if (!isset($_GET["platId"])){
            echo $this->failed("需要platId参数");
            die;
        }
        $platId = $_GET["platId"];

        // 先删除平台表中的账号列表记录
        $this->deleteAccFromPlatform($platId,$accId);

        // 再删除账号表中数据
        $res = DatabaseDataManager::getSingleton()->delete($this->tableName,["id"=>$accId]);

        if ($res){
            echo $this->success("删除成功");
            die;
        }

        // 删除失败
        echo $this->failed("删除失败");
    }

    // 添加$accountId账户到$platId记录中
    private function addAccountToPlat($platId,$accountId){
        $platData = DatabaseDataManager::getSingleton()->find("acc_platform",["id"=>$platId],["acc_list"]);
        $acc_list = "";
        if ($platData) {
            $acc_list = $platData[0]["acc_list"];
            if (strlen($acc_list) > 0) {
                // 以逗号分成数组
                $accArr = explode(",", $acc_list);
                $accArr[] = $accountId;
                $acc_list = implode(",", $accArr);
            } else {
                $acc_list = $accountId;
            }

            $resId = DatabaseDataManager::getSingleton()->update("acc_platform", ["acc_list" => $acc_list], ["id" => $platId]);
            return $resId;
        }

        return false;
    }

    // 从$platId平台中移除$deleteAccId账号记录
    private function deleteAccFromPlatform($platId,$deleteAccId){
        // 删除平台表中的账号列表记录
        $platData = DatabaseDataManager::getSingleton()->find("acc_platform",["id"=>$platId],["acc_list"]);
        if ($platData){
            $accStr = $platData[0]["acc_list"];
            $accArr = explode(",",$accStr);

            $key = array_search($deleteAccId,$accArr);
            if($key !== false){
                unset($accArr[$key]);
                $accStr = implode(",",$accArr);
                $resId = DatabaseDataManager::getSingleton()->update("acc_platform",["acc_list"=>$accStr],["id"=>$platId]);
            }
        }
    }
}