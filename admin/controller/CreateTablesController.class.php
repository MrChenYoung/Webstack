<?php


namespace admin\controller;

use framework\core\Controller;
use framework\tools\DatabaseTableManager;

class CreateTablesController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        // 初始化数据表
        $this->initCategoryTable();
        $this->initPlatformTable();
        $this->initAccountTable();
        $this->initPassWDTable();
    }

    // 创建分类表
    public function initCategoryTable(){
        $tableName = "acc_category";
        // 创建视频数据表
        $sql = <<<EEE
                    CREATE TABLE $tableName(
                        id int AUTO_INCREMENT PRIMARY KEY COMMENT '分类id',
                        cat_icon varchar(64) DEFAULT '' COMMENT '分类图标',
                        cat_title varchar(300) DEFAULT '' COMMENT '分类标题',
                        platform_list varchar(300)  DEFAULT '' COMMENT '平台列表'
                    ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='分类表';
EEE;
        DatabaseTableManager::getSingleton()->createTable($tableName,$sql);
    }

    // 创建平台表
    public function initPlatformTable(){
        $tableName = "acc_platform";
        // 创建视频数据表
        $sql = <<<EEE
                    CREATE TABLE $tableName(
                        id int AUTO_INCREMENT PRIMARY KEY COMMENT '平台id',
                        plat_name varchar(300) DEFAULT '' COMMENT '平台名称',
                        acc_list varchar(300) DEFAULT '' COMMENT '账号列表',
                        cat_id int  DEFAULT 0 COMMENT '所属分类'
                    ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='平台表';
EEE;
        DatabaseTableManager::getSingleton()->createTable($tableName,$sql);
    }

    // 创建账号表
    public function initAccountTable(){
        $tableName = "acc_account";
        // 创建视频数据表
        $sql = <<<EEE
                    CREATE TABLE $tableName(
                        id int AUTO_INCREMENT PRIMARY KEY COMMENT '账号id',
                        acc_desc varchar(128) DEFAULT '' COMMENT '描述',
                        user varchar(128) DEFAULT '' COMMENT '用户名',
                        passwd varchar(256) DEFAULT '' COMMENT '密码',
                        address varchar(300) DEFAULT '' COMMENT '登录地址',
                        plat_id int  DEFAULT 0 COMMENT '所属平台',
                        remark varchar(500) DEFAULT '' COMMENT '备注'
                    ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='账号表';
EEE;
        DatabaseTableManager::getSingleton()->createTable($tableName,$sql);
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
        DatabaseTableManager::getSingleton()->createTable($tableName,$sql);
    }
}