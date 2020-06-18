<?php
/**
 * 框架的配置文件
 */
return [
    //数据库的配置信息
    // vps数据库信息
    'host'              =>      'localhost',
    'user'              =>      'root',
    'pass'              =>      '123456',
    'dbname'            =>      'account_db',
    'port'              =>      3306,
    'charset'           =>      'utf8',

    //默认的模块
    'default_module'     =>      'home',
    'default_controller'=>      'Common',
    'default_action'    =>      'index',
];