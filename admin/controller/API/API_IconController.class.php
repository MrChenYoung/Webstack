<?php


namespace admin\controller\API;


class API_IconController extends API_BaseController
{
    // 获取图标库
    public function getIconDepository(){
        $icons = [
            "wegene",
            "contact",
            "about",
            "protect",
            "drug",
            "hear",
            "ear",
            "beat",
            "23",
            "good",
            "bad",
            "format",
            "strike",
            "full",
            "gene",
            "count",
            "order",
            "google",
            "facebook",
            "twitter",
            "cart",
            "bulb",
            "download",
            "home",
            "bar",
            "right",
            "left",
            "unlock",
            "verify",
            "log",
            "forbid",
            "transfer",
            "reader",
            "phone",
            "file",
            "ol",
            "undo",
            "redo",
            "bold",
            "italic",
            "underline",
            "ul",
            "image",
            "video",
            "quote",
            "code",
            "preview",
            "help",
            "h",
            "prestige",
            "v",
            "score",
            "plus",
            "followed",
            "mytopic",
            "up",
            "trash",
            "fold",
            "thank",
            "report",
            "qzone",
            "at",
            "attach",
            "bell",
            "triangle",
            "wechat",
            "lock",
            "i",
            "bubble",
            "flag",
            "txweibo",
            "bestbg",
            "best",
            "job",
            "favor",
            "down",
            "location",
            "bind",
            "weibo",
            "qq",
            "signup",
            "users",
            "topic",
            "login",
            "logout",
            "insert",
            "setting",
            "inbox",
            "pic",
            "user",
            "delete",
            "comment",
            "share",
            "loading",
            "inviteask",
            "list",
            "ask",
            "search",
            "more",
            "agree",
            "disagree",
            "reply",
            "draft",
            "check",
            "invite",
            "edit"];

        // 搜索关键词
        $keyw = $_GET["keyWords"];

        $searchResult = [];
        if (strlen($keyw) > 0){
            foreach ($icons as $icon) {
                if (strstr($icon,$keyw)){
                    $searchResult[] = $icon;
                }
            }
        }else {
            $searchResult = $icons;
        }

        echo $this->success($searchResult);
    }

    // 获取所有阿里矢量图标
    public function getAliIconfonts(){
        $icons = [
            "gugegoogle114",
            "Xtubiao-guanbichakan",
            "Xtubiao-chakan",
            "apple",
            "xt_weiruan",
            "code-box-fill",
            "shejiao",
            "changyongshoukuanrenguanli",
            "qiyeguanli",
            "yunxunizhuji",
            "qunfengshiyongchongzhiqiashangwang",
            "qita",
            "yuncunchu"
        ];
        echo $this->success($icons);
    }

}