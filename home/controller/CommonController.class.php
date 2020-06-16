<?php


namespace home\controller;


use admin\controller\API\API_CategoryController;

class CommonController extends BaseController
{
    public function index()
    {
        parent::index();
        // 分类id
        $catId = "";
        if (isset($_GET["catId"])){
            $catId = $_GET["catId"];
        }else {
            // 默认第一个分类
            $catController = new API_CategoryController();
            $catList = $catController->loadCategotyList(true);
            if ($catList && count($catList) > 0){
                $catId = $catList[0]["id"];
            }
        }

        $this->data["catId"] = $catId;
        $this->loadTemplate(["data"=>$this -> data],"common/index.html");
    }
}