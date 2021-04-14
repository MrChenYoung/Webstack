<?php


namespace admin\controller\API;


use framework\tools\DatabaseDataManager;
use framework\tools\ImageTool;

class API_IconManagerController extends API_BaseController
{
    // 获取所有阿里矢量图标
    public function getAliIconfonts(){
        $icons = [
            "gugegoogle114",
            "apple",
            "xt_weiruan",
            "code-box-fill",
            "shejiao",
            "changyongshoukuanrenguanli",
            "qiyeguanli",
            "hostingyunxunizhuji",
            "qunfengshiyongchongzhiqiashangwang",
            "qita",
            "yuncunchu",
            "wode",
            "sousuo",
            "shipinbofangyingpian2",
            "hostingyunxunizhuji",
            "changyong2",
            "ziyuan",
            "wangshangzhifu-01",
            "gongzuo"
        ];
        echo $this->success($icons);
    }
}