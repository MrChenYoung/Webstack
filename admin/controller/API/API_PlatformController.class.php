<?php


namespace admin\controller\API;


use framework\tools\DatabaseDataManager;

class API_PlatformController extends API_BaseController
{
    private $tableName;
    public function __construct()
    {
        parent::__construct();
        $this->tableName = "platform";
    }

    // 获取平台列表
    public function loadPlatformList(){
        // 查询平台列表
        $platData = DatabaseDataManager::getSingleton()->find($this->tableName);

        // 获取网站列表名称
        $accData = DatabaseDataManager::getSingleton()->find("web_list");
        $acces = [];
        foreach ($accData as $accDatum) {
            $acces[$accDatum["id"]] = $accDatum["web_title"];
        }

        if ($platData !== false){
            // 获取每个平台所属的分类
            foreach ($platData as $key=>$platDatum) {
                $catId = $platDatum["cat_id"];
                $catData = DatabaseDataManager::getSingleton()->find("category",["id"=>$catId],["cat_title"]);
                $catName = "";
                if ($catData !== false && count($catData) > 0){
                    $catName = $catData[0]["cat_title"];
                }
                $platData[$key]["cat_title"] = $catName;

                // 获取每个平台包含的账号描述信息
                $plat_acces = $platDatum["web_list"];
                $acc_list = [];
                if (strlen($plat_acces)){
                    $plat_acces_arr = explode(",",$plat_acces);
                    foreach ($plat_acces_arr as $item) {
                        if (array_key_exists($item,$acces)){
                            $acc_list[] = $acces[$item];
                        }
                    }
                }
                $platData[$key]["web_list"] = $acc_list;
            }

            echo $this->success($platData);
        }else {
            echo $this->failed("获取平台列表失败");
        }
    }

    // 添加平台
    public function addPlatform(){
        // 平台所属分类
        if (!isset($_GET["catId"])){
            echo $this->failed("需要catId参数");
            die;
        }
        $catId = $_GET["catId"];

        // 平台名
        if (!isset($_GET["platName"])){
            echo $this->failed("需要platName参数");
            die;
        }
        $platName = $_GET["platName"];

        // 插入数据库
        $res = DatabaseDataManager::getSingleton()->insert($this->tableName,["plat_name"=>$platName,"cat_id"=>$catId]);
        if ($res){
            // 添加id到分类表
            $resId = $this->addPlatformToCat($catId,$res);
            if ($resId){
                // 成功
                echo $this->success("平台添加成功 ");
                die;
            }
        }
        echo $this->failed("平台添加失败");
    }

    // 修改平台
    public function editPlatform(){
        // id
        if (!isset($_GET["id"])){
            echo $this->failed("需要id参数");
            die;
        }
        $platId = $_GET["id"];

        // 所属分类id
        if (!isset($_GET["catId"])){
            echo $this->failed("需要catId参数");
            die;
        }
        $catId = $_GET["catId"];

        // 平台名
        if (!isset($_GET["platName"])){
            echo $this->failed("需要platName参数");
            die;
        }
        $platName = $_GET["platName"];

        // 查询原来所属的分类并移除
        $oldCatData = DatabaseDataManager::getSingleton()->find($this->tableName,["id"=>$platId],["cat_id"]);
        if ($oldCatData){
            $oldCatId = $oldCatData[0]["cat_id"];
            $this->deletePlatFromCat($oldCatId,$platId);
        }
        // 添加平台id记录到新的分类中
        $resId = $this->addPlatformToCat($catId,$platId);
        if ($resId){
            $res = DatabaseDataManager::getSingleton()->update($this->tableName,["plat_name"=>$platName,"cat_id"=>$catId],["id"=>$platId]);
            if ($res){
                echo $this->success("修改成功");
                die;
            }
        }

        echo $this->failed("修改失败");
    }

    // 删除平台
    public function deletePlatform(){
        // id
        if (!isset($_GET["id"])){
            echo $this->failed("需要id参数");
            die;
        }
        $platId = $_GET["id"];

        // id
        if (!isset($_GET["catId"])){
            echo $this->failed("需要catId参数");
            die;
        }
        $catId = $_GET["catId"];

        // 先删除分类列表中的平台列表
        $this->deletePlatFromCat($catId,$platId);

        // 再删除平台表中数据
        $res = DatabaseDataManager::getSingleton()->delete($this->tableName,["id"=>$platId]);
        if ($res){
            echo $this->success("删除成功");
            die;
        }

        // 删除失败
        echo $this->failed("删除失败");
    }

    // 从$catId分类中移除$deletePlatId平台记录
    private function deletePlatFromCat($catId,$deletePlatId){
        // 删除分类表中的平台列表
        $catData = DatabaseDataManager::getSingleton()->find("category",["id"=>$catId],["platform_list"]);
        if ($catData){
            $platStr = $catData[0]["platform_list"];
            $platArr = explode(",",$platStr);

            $key = array_search($deletePlatId,$platArr);
            if($key !== false){
                unset($platArr[$key]);
                $platStr = implode(",",$platArr);
                $resId = DatabaseDataManager::getSingleton()->update("category",["platform_list"=>$platStr],["id"=>$catId]);
            }
        }
    }

    // 添加$platId平台到$catId记录中
    private function addPlatformToCat($catId,$platId){
        $catData = DatabaseDataManager::getSingleton()->find("category",["id"=>$catId],["platform_list"]);
        $plat_list = "";
        if ($catData) {
            $plat_list = $catData[0]["platform_list"];
            if (strlen($plat_list) > 0) {
                // 以逗号分成数组
                $platArr = explode(",", $plat_list);
                $platArr[] = $platId;
                $plat_list = implode(",", $platArr);
            } else {
                $plat_list = $platId;
            }

            $resId = DatabaseDataManager::getSingleton()->update("category", ["platform_list" => $plat_list], ["id" => $catId]);

            return $resId;
        }

        return false;
    }


    // 请求指定平台数据
    public function loadPlatform(){
        // id
        if (!isset($_GET["id"])){
            echo $this->failed("需要id参数");
            die;
        }
        $platId = $_GET["id"];

        $sql = <<<EEE
SELECT acc_platform.*,acc_category.cat_title  FROM acc_category,acc_platform WHERE acc_category.id=acc_platform.cat_id AND acc_platform.id=$platId;
EEE;
        $res = DatabaseDataManager::getSingleton()->fetch($sql);

        if ($res && count($res) > 0){
            echo $this->success($res[0]);
        }else {
            echo $this->failed("查询平台数据失败");
        }
    }
}