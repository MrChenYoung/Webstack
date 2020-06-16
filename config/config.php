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

    //默认每页显示多少记录
    'pagesize'          =>      1,

    //发送邮件的配置
    'email_host'        =>      'smtp.163.com', //邮箱的服务器地址
    'from'              =>      'alsothank@163.com',   //发送者的邮箱
    'nickname'          =>      '泰牛程序员',    //发送者的昵称
    'account'          =>       'alsothank',    //发送者的邮箱账号
    'grant_pass'        =>      'itbull2017',    //发送者的邮箱授权码

    //发送短信的配置
    'accountSid'       =>       '8a216da85e7e4bbd015e92e9da880609',
    'accountToken'     =>       'a04bea5f1ebd4ed78c4d3bd4647cd528',
    'appId'            =>       '8a216da85e7e4bbd015e92e9dac6060d',
    'serverPort'       =>       8883,
    'serverIP'         =>       'app.cloopen.com',
    'softVersion'      =>       '2013-12-26',

    //验证码的有效期，默认是5分钟
    'expire'           =>       '500'
];