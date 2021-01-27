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

        $tbName = "";
        if (isset($_GET["tbName"])){
            $tbName = $_GET["tbName"];
        }

        $this->data["msg"] = base64_decode($msg);
        $this->data["tbName"] = $tbName;

        echo "<pre>";
        var_dump($this->data);


//        $this -> loadTemplate(["data"=>$this -> data],"db/index.html");
    }
}