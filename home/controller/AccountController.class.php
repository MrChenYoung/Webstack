<?php


namespace home\controller;


class AccountController extends BaseController
{
    public function index()
    {
        parent::index();
        // 分类id
        $catId = $_GET["catId"];
        $this->data["catId"] = $catId;
        $this->loadTemplate(["data"=>$this -> data],"account/index.html");
    }
}