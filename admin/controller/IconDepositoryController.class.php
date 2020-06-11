<?php


namespace admin\controller;


use framework\core\Controller;

class IconDepositoryController extends Controller
{
    public function index()
    {
        parent::index();

        $this->loadTemplate(["data"=>$this->data],"iconDepository/index.html");
    }
}