<?php


namespace admin\controller;

use framework\core\Controller;

class CategoryManagerController extends Controller
{
    public function index()
    {
        parent::index();
        $this -> loadTemplate(["data"=>$this -> data],"category/index.html");
    }

}