<?php
/**
 * 框架的配置文件
 */
return [
    //数据库的配置信息
    // vps数据库信息
    'host'              =>      'localhost',
    'user'              =>      'root',
    'pass'              =>      '199156',
    'dbname'            =>      'web_stack_db',
    'port'              =>      3306,
    'charset'           =>      'utf8',

    //默认的模块
    'default_module'     =>      'admin',
    'default_controller'=>      'CategoryManager',
    'default_action'    =>      'index',
];