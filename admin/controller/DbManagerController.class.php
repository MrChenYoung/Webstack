<?php


namespace admin\controller;


use framework\core\Controller;

class DbManagerController extends Controller
{
    public function index()
    {
        parent::index();

        $msg = "";
        if (isset($_GET["msg"])){
            $msg = $_GET["msg"];
        }

        $this->data["msg"] = base64_decode($msg);

        $this -> loadTemplate(["data"=>$this -> data],"db/index.html");
    }
}