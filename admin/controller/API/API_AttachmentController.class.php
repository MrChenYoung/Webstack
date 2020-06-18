<?php


namespace admin\controller\API;


use framework\tools\DatabaseDataManager;
use framework\tools\ImageTool;

class API_AttachmentController extends API_BaseController
{
    // 表名
    private $tableName;
    // 图片保存目录
    private $imageSaveDir;

    public function __construct()
    {
        parent::__construct();
        $this->tableName = "acc_attachment";
        $this->imageSaveDir = ADMIN."resource/attachmentImages";
        if (!file_exists($this->imageSaveDir)){
            mkdir($this->imageSaveDir);
            chmod($this->imageSaveDir,0777);
        }
    }

    // 根据参数获取附件表中对应数据的id
    public function getAttachmentId(){
        // 获取哪一条数据的附件
        if (!isset($_GET["aid"])){
            echo $this->failed("需要aid参数");
            die;
        }
        $aid = $_GET["aid"];
        $aid = strlen($aid) == 0 ? "0" : $aid;

        // 哪个表
        if (!isset($_GET["tbName"])){
            echo $this->failed("需要tbName参数");
            die;
        }
        $tbName = $_GET["tbName"];

        // 查询
        $res = DatabaseDataManager::getSingleton()->find($this->tableName,["aid"=>$aid,"tb_name"=>$tbName]);
        if (!$res){
            // 插入附件记录
            $attId = DatabaseDataManager::getSingleton()->insert($this->tableName,["aid"=>$aid,"tb_name"=>$tbName]);
        }else {
            $attId = $res[0]["id"];
        }

        echo $this->success($attId);
    }

    // 获取指定附件
    function loadAttachmentLists(){
        // 获取哪一条数据的附件
        if (!isset($_GET["id"])){
            echo $this->failed("需要id参数");
            die;
        }
        $aid = $_GET["id"];

        // 哪个表
        if (!isset($_GET["tbName"])){
            echo $this->failed("需要tbName参数");
            die;
        }
        $tbName = $_GET["tbName"];
        $data = $this->getAttachmentLists($aid,$tbName);

        echo $this->success($data);
    }

    public function getAttachmentLists($aid,$tbName){
        $res = DatabaseDataManager::getSingleton()->find($this->tableName,["aid"=>$aid,"tb_name"=>$tbName]);
        $data = [];
        $remanentCount = 0;
        if ($res){
            // 查询到结果
            $res = $res[0];
            foreach ($res as $key=>$re) {
                if ($key !== "id" && $key !== "aid" && $key !== "tb_name" && $re){
                    $handleResult = $this->imageDataHandle($re);
                    $imageName = "";
                    if ($handleResult !== false){
                        $imageName = $handleResult["name"];
                    }
                    if (strlen($imageName) > 0){
                        $imageUrl = $this->website."/admin/resource/attachmentImages/".$imageName;
                        $data[] = [
                            "alt" => $imageName,
                            "pid" => 666,
                            "src" => $imageUrl,
                            "thumb" => $imageUrl,
                            "imageColumn" => $key
                        ];
                    }
                }else if ($key !== "id" && $key !== "aid" && $key !== "tb_name"){
                    $remanentCount++;
                }
            }
            $data = [
                "title" => "photo",
                "id" => 123,
                "start" => 0,
                "data" => $data,
                "remanentCount" => $remanentCount
            ];
        }

        return $data;
    }

    // 添加附件
    public function addAttachment(){
        // 获取哪一条数据的附件
        if (!isset($_REQUEST["aid"])){
            echo $this->failed("需要aid参数");
            die;
        }
        $aid = $_REQUEST["aid"];

        // 哪个表
        if (!isset($_REQUEST["tbName"])){
            echo $this->failed("需要tbName参数");
            die;
        }
        $tbName = $_REQUEST["tbName"];

        // base64编码的图片信息
        if (!isset($_REQUEST["img"])){
            echo $this->failed("请选择图片");
            die;
        }
        $imgContent = $_REQUEST["img"];

        // 查询还没有被存储的附件位置
        $res = DatabaseDataManager::getSingleton()->find($this->tableName,["aid"=>$aid,"tb_name"=>$tbName]);
        $number = "";
        if ($res){
            // 已经存储过
            $res = $res[0];
            for ($i = 1; $i < 11; $i++){
                $img = $res["att_".$i];
                if (strlen($img) == 0){
                    $number = "$i";
                    break;
                }
            }
        }else {
            // 没有存储过
            $number = "1";
        }

        if (strlen($number) == 0){
            echo $this->failed("附件上传数已达上限");
            die;
        }
        $number = "att_".$number;

        // 图片保存
        $imageName = ImageTool::saveBase64ToImage($imgContent,$this->imageSaveDir);
        // 把图片名f附带到base64后面
        $imgContent .= "<<<".$imageName;

        if ($res){
            $res = DatabaseDataManager::getSingleton()->update($this->tableName,[$number=>$imgContent],["aid"=>$aid,"tb_name"=>$tbName]);
        }else {
            $res = DatabaseDataManager::getSingleton()->insert($this->tableName,["aid"=>$aid,"tb_name"=>$tbName,$number=>$imgContent]);
        }

        if ($res){
            echo $this->success("添加附件成功");
        }else {
            echo $this->failed("附件添加失败");
        }
    }

    // 删除附件
    public function deleteAttachment(){
        // 获取哪一条数据的附件
        if (!isset($_REQUEST["aid"])){
            echo $this->failed("需要aid参数");
            die;
        }
        $aid = $_REQUEST["aid"];

        // 哪个表
        if (!isset($_REQUEST["tbName"])){
            echo $this->failed("需要tbName参数");
            die;
        }
        $tbName = $_REQUEST["tbName"];

        // 删除的字段名
        if (!isset($_REQUEST["imageKey"])){
            echo $this->failed("需要imageKey参数");
            die;
        }
        $imageKey = $_REQUEST["imageKey"];

        // 查询图片信息
        $imageData = DatabaseDataManager::getSingleton()->find($this->tableName,["aid"=>$aid,"tb_name"=>$tbName],[$imageKey]);
        if ($imageData){
            $imageData = $imageData[0][$imageKey];
            // 解析
            $imageData = $this->imageDataHandle($imageData);
            if ($imageData !== false){
                $imageName = $imageData["name"];
                $imageBase64 = $imageData["content"];
                // 删除图片
                $imagePath = $this->imageSaveDir."/".$imageName;
                if (file_exists($imagePath)){
                    unlink($imagePath);
                }
            }
        }
        $res = DatabaseDataManager::getSingleton()->update($this->tableName,[$imageKey=>""],["aid"=>$aid,"tb_name"=>$tbName]);
        if ($res){
            echo $this->success("附件删除成功");
        }else {
            echo $this->failed("附件删除失败");
        }
    }

    // 删除所有的附件
    public function clearAttachment($aid,$tbName,$attId=""){
        // 查询附件
        if (strlen($attId) > 0){
            $imageData = DatabaseDataManager::getSingleton()->find($this->tableName,["id"=>$attId]);
        }else {
            $imageData = DatabaseDataManager::getSingleton()->find($this->tableName,["aid"=>$aid,"tb_name"=>$tbName]);
        }

        if ($imageData){
            // 查询对应的账户表是否存在 如果不存在则删除附件记录
            $accRecord = DatabaseDataManager::getSingleton()->find("acc_account",["id"=>$imageData[0]["aid"]]);

            if ($accRecord){
                // 有记录 不能删除该附件记录
                return;
            }

            // 删除附件记录
            $imageData = $imageData[0];
            foreach ($imageData as $key=>$re) {
                if ($key !== "id" && $key !== "aid" && $key !== "tb_name" && $re){
                    $handleResult = $this->imageDataHandle($re);
                    $imageName = "";
                    if ($handleResult !== false){
                        $imageName = $handleResult["name"];
                    }
                    // 删除图片文件
                    $imagePath = ADMIN."resource/attachmentImages/".$imageName;
                    if (file_exists($imagePath)){
                        unlink($imagePath);
                    }
                    // 清空图片记录
                    DatabaseDataManager::getSingleton()->update($this->tableName,[$key=>""],["aid"=>$aid,"tb_name"=>$tbName]);
                }
            }

            // 删除该条附件记录
            if (strlen($attId) > 0){
                DatabaseDataManager::getSingleton()->delete($this->tableName,["id"=>$attId]);
            }else {
                DatabaseDataManager::getSingleton()->delete($this->tableName,["aid"=>$aid,"tb_name"=>"acc_account"]);
            }
        }
    }

    // 解析图片信息
    private function imageDataHandle($imageContent){
        $needle = "<<<";
        $strPos = strpos($imageContent,$needle);
        if ($strPos){
            $imageName = substr($imageContent,$strPos+strlen($needle));
            $imageContent = str_replace($needle.$imageName,"",$imageContent);
            $result = ["name"=>$imageName,"content"=>$imageContent];
            return $result;
        }else {
            return false;
        }
    }
}