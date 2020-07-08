<?php

namespace admin\controller;

use framework\core\Controller;

class PhpNeedleController extends Controller
{
    public function index()
    {
        parent::index();
        $this -> loadTemplate(["data"=>$this -> data],"phpneedle/index.html");
    }
}