<?php


namespace admin\controller\API;


use framework\tools\DatabaseDataManager;

class API_CategoryController extends API_BaseController
{
    private $tableName;
    public function __construct()
    {
        parent::__construct();
        $this->tableName = "category";
    }

    // 获取分类列表
    public function loadCategotyList($ret=false){
        $res = DatabaseDataManager::getSingleton()->find($this->tableName,[],[],"ORDER BY sort");
        // 获取平台列表名称
        $platData = DatabaseDataManager::getSingleton()->find("platform");
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

                if ($re["sort"] == 0){
                    DatabaseDataManager::getSingleton()->update($this->tableName,["sort"=>$re["id"]],["id"=>$re["id"]]);
                }
                $res[$key]["platform_list"] = $platList;
            }

            if ($ret){
                return $res;
            }else {
                echo $this->success($res);
            }
        }else {
            if ($ret){
                return [];
            }else {
                echo $this->failed("查询分类列表失败");
            }
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

        // 排序
        if (!isset($_GET["sort"])){
            echo $this->failed("需要sort参数");
            die;
        }
        $sort = $_GET["sort"];

        // 插入数据库
        $res = DatabaseDataManager::getSingleton()->insert($this->tableName,["cat_icon"=>$iconName,"cat_title"=>$catName,"sort"=>$sort]);
        if ($res){
            if ($sort == "0"){
                DatabaseDataManager::getSingleton()->update($this->tableName,["sort"=>$res],["id"=>$res]);
            }
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

        // 排序
        if (!isset($_GET["sort"])){
            echo $this->failed("需要sort参数");
            die;
        }
        $sort = $_GET["sort"];
        $sortInt = (int)$sort;
        if ($sortInt > 0){
            $oldsort = DatabaseDataManager::getSingleton()->find($this->tableName,["id"=>$catId]);

            $existSort = DatabaseDataManager::getSingleton()->find($this->tableName,["sort"=>$sort]);
            if ($existSort){
                $existSort = $existSort[0];
                $oldsort = $oldsort[0];
                DatabaseDataManager::getSingleton()->update($this->tableName,["sort"=>$oldsort["sort"]],["id"=>$existSort["id"]]);
            }
        }

        $res = DatabaseDataManager::getSingleton()->update($this->tableName,["cat_icon"=>$iconName,"cat_title"=>$catName,"sort"=>$sort],["id"=>$catId]);
        if ($res){
            if ($sort == "0"){
                DatabaseDataManager::getSingleton()->update($this->tableName,["sort"=>$catId],["id"=>$catId]);
            }
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