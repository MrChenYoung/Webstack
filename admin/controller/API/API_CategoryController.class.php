<?php


namespace admin\controller\API;


use framework\tools\DatabaseDataManager;

class API_CategoryController extends API_BaseController
{
    private $tableName;
    public function __construct()
    {
        parent::__construct();
        $this->tableName = "acc_category";
    }

    // 获取分类列表
    public function loadCategotyList(){
        $res = DatabaseDataManager::getSingleton()->find($this->tableName);
        // 获取平台列表名称
        $platData = DatabaseDataManager::getSingleton()->find("acc_platform");
        $plats = [];
        foreach ($platData as $platDatum) {
            $plats[$platDatum["id"]] = $platDatum["plat_name"];
        }

        if ($res !== false){
            foreach ($res as $key=>$re) {
                $platList = [];
                $platStr = $re["platform_list"];
                if (strlen($platStr)){
                    $platArr = explode(",",$platStr);
                    foreach ($platArr as $item) {
                        if (array_key_exists($item,$plats)){
                            $platList[] = $plats[$item];
                        }
                    }
                }
                $res[$key]["platform_list"] = $platList;
            }
            echo $this->success($res);
        }else {
            echo $this->failed("查询分类列表失败");
        }
    }

    // 添加分类
    public function addCategory(){
        // 图标
        if (!isset($_GET["icon"])){
            echo $this->failed("需要icon参数");
            die;
        }
        $iconName = $_GET["icon"];

        // 分类名
        if (!isset($_GET["catName"])){
            echo $this->failed("需要catName参数");
            die;
        }
        $catName = $_GET["catName"];

        // 插入数据库
        $res = DatabaseDataManager::getSingleton()->insert($this->tableName,["cat_icon"=>$iconName,"cat_title"=>$catName]);
        if ($res){
            echo $this->success("添加分类成功 ");
        }else {
            echo $this->failed("添加分类失败");
        }
    }

    // 修改分类
    public function editCategory(){
        // id
        if (!isset($_GET["id"])){
            echo $this->failed("需要id参数");
            die;
        }
        $catId = $_GET["id"];

        // 图标
        if (!isset($_GET["icon"])){
            echo $this->failed("需要icon参数");
            die;
        }
        $iconName = $_GET["icon"];

        // 分类名
        if (!isset($_GET["catName"])){
            echo $this->failed("需要catName参数");
            die;
        }
        $catName = $_GET["catName"];

        $res = DatabaseDataManager::getSingleton()->update($this->tableName,["cat_icon"=>$iconName,"cat_title"=>$catName],["id"=>$catId]);
        if ($res){
            echo $this->success("修改成功");
        }else {
            echo $this->failed("修改失败");
        }
    }

    // 删除分类
    public function deleteCategory(){
        // id
        if (!isset($_GET["id"])){
            echo $this->failed("需要id参数");
            die;
        }
        $catId = $_GET["id"];

        $res = DatabaseDataManager::getSingleton()->delete($this->tableName,["id"=>$catId]);
        if ($res){
            // 删除成功
            echo $this->success("删除成功");
        }else {
            // 删除失败
            echo $this->failed("删除失败");
        }
    }

    // 请求指定分类数据
    public function loadCategory(){
        // id
        if (!isset($_GET["id"])){
            echo $this->failed("需要id参数");
            die;
        }
        $catId = $_GET["id"];

        $res = DatabaseDataManager::getSingleton()->find($this->tableName,["id"=>$catId]);
        if ($res && count($res) > 0){
            echo $this->success($res[0]);
        }else {
            echo $this->failed("查询分类数据失败");
        }
    }
}