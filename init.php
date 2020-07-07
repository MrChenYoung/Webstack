<?php

//初始化常量
initConst();
function initConst()
{
    $root = str_replace('\\','/',getcwd().'/');
    define('ROOT',$root);
    define('ADMIN',ROOT.'admin/');
    define('HOME',ROOT.'home/');
    define('FRAMEWORK',ROOT.'framework/');
    //公共的静态资源路径
    define('PUBLIC_PATH','./public/');
}


// 初始化数据库
initDb();
function initDb(){
    require_once "./admin/controller/CreateTablesController.class.php";
    new \admin\controller\CreateTablesController();
}

// 初始化日志文件
require_once "./framework/tools/LogManager.class.php";
\framework\tools\LogManager::getSingleton();