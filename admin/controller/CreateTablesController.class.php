<?php


namespace admin\controller;
use framework\dao;

require_once "./framework/dao/DAOPDO.class.php";
class CreateTablesController
{
    private $dao;
    public function __construct()
    {
        $this->initDabaseInfo();
        $this->dao = dao\DAOPDO::getSingleton();

        // 初始化数据表
        $this->initCategoryTable();
        $this->initPlatformTable();
        $this->initWebListTable();
        $this->initPassWDTable();
    }

    // 初始化数据库信息
    private function initDabaseInfo(){
        // 获取数据库链接信息
        $path = getcwd()."/config/config.php";
        $config = require_once $path;
        $GLOBALS['config'] = $config;

        $option['host'] = $config['host'];
        $option['user'] = $config['user'];
        $option['pass'] = $config['pass'];
        $option['dbname'] = $config['dbname'];
        $option['port'] = $config['port'];
        $option['charset'] = $config["charset"];
        $GLOBALS["db_info"] = $option;
    }

    // 创建分类表
    public function initCategoryTable(){
        $tableName = "category";
        // 创建视频数据表
        $sql = <<<EEE
                    CREATE TABLE $tableName(
                        id int AUTO_INCREMENT PRIMARY KEY COMMENT '分类id',
                        cat_icon varchar(64) DEFAULT '' COMMENT '分类图标',
                        cat_title varchar(300) DEFAULT '' COMMENT '分类标题',
                        platform_list varchar(300)  DEFAULT '' COMMENT '平台列表'
                    ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='分类表';
EEE;
        $this->dao->createTable($tableName,$sql);
    }

    // 创建平台表
    public function initPlatformTable(){
        $tableName = "platform";
        // 创建平台表
        $sql = <<<EEE
                    CREATE TABLE $tableName(
                        id int AUTO_INCREMENT PRIMARY KEY COMMENT '平台id',
                        plat_name varchar(300) DEFAULT '' COMMENT '平台名称',
                        web_list varchar(300) DEFAULT '' COMMENT '网站列表',
                        cat_id int  DEFAULT 0 COMMENT '所属分类'
                    ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='平台表';
EEE;
        $this->dao->createTable($tableName,$sql);
    }

    // 创建网站表
    public function initWebListTable(){
        $tableName = "web_list";
        // 创建视频数据表 logo以base64编码方式存储
        $sql = <<<EEE
                    CREATE TABLE $tableName(
                        id int AUTO_INCREMENT PRIMARY KEY COMMENT '网站id',
                        web_title varchar(128) DEFAULT '' COMMENT '标题',
                        web_desc varchar(128) DEFAULT '' COMMENT '描述',
                        address varchar(300) DEFAULT '' COMMENT '地址',
                        plat_id int  DEFAULT 0 COMMENT '所属平台',
                        logo MEDIUMTEXT COMMENT 'logo'
                    ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='网站表';
EEE;
        $this->dao->createTable($tableName,$sql);
    }

    // 创建密码表
    public function initPassWDTable(){
        $tableName = "acc_passwd";
        // 创建视频数据表
        $sql = <<<EEE
                    CREATE TABLE $tableName(
                        id int AUTO_INCREMENT PRIMARY KEY COMMENT '密码id',
                        pass_desc varchar(128) DEFAULT '' COMMENT '密码描述',
                        passwd varchar(256) DEFAULT '' COMMENT '密码值',
                        pass_level int DEFAULT 0 COMMENT '密码安全级别'
                    ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='密码表';
EEE;
        $this->dao->createTable($tableName,$sql);
        // 添加登录密码
        $data = [
            "pass_desc"     =>  "'登录密码'",
            "passwd"        =>  "QDAoTKf4iGADBGSjt4VXXElC7eanPD3gS9sn3DRZHTBjVpbm/ZQ7Y5a2KEYujU6cjXFJdMudNB06Y1UalS6Gd5ThiYd+EcwKcPsT1Xp5xHdDtJL0lWyirZhRwdOHPQ/P/Xzc0wArFP2hjccJAlucpc8FpN+oOvfAzojzL0/liYQ=",
            "pass_level"    => 4
        ];
        $this->dao->insertData($tableName,"pass_desc",$data);
    }
}