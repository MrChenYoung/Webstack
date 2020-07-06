<?php


namespace home\controller;


class AccountController extends BaseController
{
    public function index()
    {
        parent::index();

        // 分类id
        $catId = "";
        if (!isset($_GET["catId"])){
            // 默认选择第一个分类
            if (count($this->catList) > 0){
                $cat = $this->catList[0];
                $catId = $cat["id"];
            }
        }else {
            $catId = $_GET["catId"];
        }
        $this->data["catId"] = $catId;
        $this->loadTemplate(["data"=>$this -> data],"account/index.html");
    }
}