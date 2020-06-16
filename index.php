<?php
/**
 * 根据用户的需求，将用户分配到对应的控制器的某个方法中
 */

// 初始化
require_once "init.php";

// 如果是请求API 直接进入主页终止后续代码执行
if (isset($_GET["API"])){
    // 请求API进入的
    toHome();
    return;
}

// 点击后台的注销按钮
if (isset($_GET["login"])){
    toLogin();
    return;
}

// 检测是否已经添加rsa key
require_once "./framework/tools/CookieManager.class.php";
checkRsaKey();
function checkRsaKey(){
    // 保存到cookie的公钥键
    $publickKey = "rsaPublicKey";
    // 保存到cookie的私钥键
    $privateKey = "rsaPrivateKey";

    $privateKeyContent = CookieManager::getCookie($privateKey);
    $publickKeyContent = CookieManager::getCookie($publickKey);

    // 没有添加 跳转到添加页面
    if (strlen($privateKeyContent) == 0 || strlen($publickKeyContent) == 0){
        // 没有添加 跳转到添加页面
        $url = "http://".$_SERVER['HTTP_HOST']."/addRsaKey.php&page";
        header("Refresh:0;url=".$url);
    }else {
        // 已经添加
        $modoule = isset($_GET["m"]) ? $_GET["m"] : "home";
        if ($modoule == "admin"){
            // 检测登录状态
            checkLoginStatus();
        }else {
            // 进入前台首页
            toHome();
        }
    }
}

// 检测登录状态
function checkLoginStatus(){
    require_once './framework/tools/SessionManager.class.php';
    $session = \framework\tools\SessionManager::getSingleTon();

    // 获取session里的isLogin标识
    $isLogin = $session -> getSession("isLogin");

    if ($session -> issetSession("isLogin")){
        // session已经记录登录状态
        if ($isLogin){
            // 进入后台页面
            $modoule = isset($_GET["m"]) ? $_GET["m"] : "admin";
            $controller = isset($_GET["c"]) ? $_GET["c"] : "CategoryManager";
            $action = isset($_GET["a"]) ? $_GET["a"] : "index";
            toHome($modoule,$controller,$action);
        }else {
            // 点击首页退出登录页面进来的
            toLogin();
        }
    }else if(isset($_POST["login"])){
        // 点击登录页面登录按钮进来的
        // 用户名和密码是否匹配
        $loginSuccess = $_POST["loginSuccess"];

        if ($loginSuccess == "1"){
            // 账号密码正确 保存登录状态数据到session
            $session -> setSession("isLogin",true);

            // 进入主页
            toHome();
        }else {
            // 账号密码不正确 重新进入登录页
            toLogin(true,"账号或密码错误");
        }
    } else {
        // session已经记录登录状态
        toLogin();
    }
}


// 进入主页面
function toHome($m='',$c='',$a=''){
    require_once './framework/core/Framework.class.php';
    new \framework\core\Framework($m,$c,$a);
}

// 进入登录页
function toLogin($loginerr=false,$errMsg=""){
    $pass = "";
    if (strlen($errMsg) == 0){
        // 查询登录密码
        $option = $GLOBALS["db_info"];
        $mysqli = new \mysqli($option["host"],$option["user"],$option["pass"],$option["dbname"],$option["port"]);
        $sql = <<<EEE
SELECT passwd FROM acc_passwd WHERE `pass_desc`='登录密码';
EEE;
        $result = $mysqli->query($sql);

        if ($result){
            // 获取所有行数据 只要关联数组
            $res = $result -> fetch_all(MYSQLI_ASSOC);
            // 释放资源
            $result -> free();
            if ($res){
                $pass = $res[0]['passwd'];
            }
        }else {
            // 查询失败
            toLogin(true,"账号或密码错误");
            die;
        }
    }

    if (strlen($pass) > 0){
        // base64编码
        $pass = base64_encode($pass);
    }
    $errMsg = urlencode($errMsg);
    $url = "http://".$_SERVER['HTTP_HOST']."/admin/view/login/login.php?loginerr=".$loginerr."&errMsg=".$errMsg."&pass=".$pass;
    header("Refresh:0;url=".$url);
}
