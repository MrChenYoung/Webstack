<?php


namespace admin\controller\API;


use framework\tools\DatabaseDataManager;
use framework\tools\ImageTool;

class API_IconManagerController extends API_BaseController
{
    private $apiList;

    public function __construct()
    {
        parent::__construct();
        $this->apiList = [
            "常用API"            => "/favicon.ico",
            "Google API1"       => "http://www.google.com/s2/favicons?domain=",
            "Google API2"       => "https://favicon.link/",
            "Google API3"       =>  "https://favicon.link/v3/?url=",
            "360 API1"          =>  "https://favicon.link/v1/ico.php?url=",
            "360 API2"          =>  "https://favicon.link/v2/ico.php?url=",
            "其他接口1"          =>   "http://tool.bitefu.net/ico/?url=",
            "其他接口2"          =>   "http://favicon.byi.pw/?url="
        ];
    }

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
            "hostingyunxunizhuji",
            "qunfengshiyongchongzhiqiashangwang",
            "qita",
            "yuncunchu",
            "wode"
        ];
        echo $this->success($icons);
    }

    // 获取查询favicon的接口
    public function loadApiLists(){
        $apis = array_keys($this->apiList);
        echo $this->success($apis);
    }

    // 查询favicon
    public function getFavicon(){
        // 接口
        if (!isset($_GET["api"])){
            echo $this->failed("需要api参数");
            die;
        }
        $api = $_GET["api"];

        // 网址
        if (!isset($_GET["url"])){
            echo $this->failed("需要url参数");
            die;
        }
        $url = $_GET["url"];

        switch ($api){
            case "常用API":
                $url = $url.$this->apiList[$api];
                break;
            case "Google API1":
            case "Google API2":
            case "Google API3":
            case "360 API1":
            case "360 API2":
            case "其他接口1":
            case "其他接口2":
                $url = $this->apiList[$api].$url;
                break;
        }

        $res = $this->request($url);
        echo $this->success($res);
    }

    // 获取图片类型
    public function getFaviconType(){
        // 网址
        if (!isset($_GET["base64"])){
            echo $this->failed("需要base64参数");
            die;
        }
        $base64 = $_GET["base64"];

        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64, $result)){
            echo $this->success($result[2]);
        }

        echo $this->success("");
    }

    // 请求
    private function request($url){
        //1. 初始化curl请求
        $ch = curl_init();
        //2. 设置请求的服务器地址
        curl_setopt($ch,CURLOPT_URL,$url);
        //3. 不管get、post，都跳过证书验证
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $result = curl_exec($ch);
        //4. 关闭资源
        curl_close($ch);

        // 转成base64
        $result = ImageTool::imageContentToBase64($result);
        return $result;
    }

    // 获取logo列表
    public function loadLogoList(){
        $res = DatabaseDataManager::getSingleton()->find("acc_account",[],["logo"]);

        $data = [];
        if ($res){
            foreach ($res as $re) {
                $base64 = $re["logo"];
                if (strlen($base64) > 0 && !in_array($base64,$data)){
                    $data[] = $base64;
                }
            }
        }

        echo $this->success($data);
    }

}