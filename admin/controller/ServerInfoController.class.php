<?php

namespace admin\controller;

use framework\core\Controller;

class ServerInfoController extends Controller
{
    public function index()
    {
        parent::index(); // TODO: Change the autogenerated stub
        $this -> loadTemplate(["data"=>$this -> data],"server/index.html");
    }
}