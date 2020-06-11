<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="webkit" name="renderer">
    <meta content="IE=edge,Chrome=1" http-equiv="X-UA-Compatible">
    <meta content="width=device-width,initial-scale=1,maximum-scale=1" name="viewport">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="blank" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <title>账号管家</title>

    <?php $webSite = "http://".$_SERVER['HTTP_HOST'];  $pubP = $webSite."/public"?>
    <link href="<?php echo $pubP?>/common/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $pubP?>/common/css/icon.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $pubP?>/common/css/login.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $pubP?>/common/css/common.css" rel="stylesheet" type="text/css">
    <script src="<?php echo $pubP?>/home/js/jquery.2.js" type="text/javascript"></script>
    <script src="<?php echo $pubP?>/home/js/jquery.form.js" type="text/javascript"></script>
    <script src="<?php echo $pubP?>/admin/js/framework.js" type="text/javascript"></script>
    <script src="<?php echo $pubP?>/admin/js/global.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo $pubP?>/admin/js/RSAEncrypt.js"></script>
    <script type="text/javascript" src="<?php echo $pubP?>/common/js/cookie.js"></script>
    <script type="text/javascript" src="<?php echo $pubP?>/admin/js/jsencrypt.min.js"></script>
    <style type="text/css">
        #cotent{
            overflow-y: hidden;
        }
    </style>
</head>

<body>
    <input id="encrypt_pass" type="hidden" value="<?php echo $_GET["pass"]?>"/>
    <div id="cotent" class="aw-login">
        <div class="mod center-block" style="padding-top: 60px;">
            <div class="mod-logo" style="text-align: center">
                <h1 style="font-family: HUPOFont;font-size: 50px; color: #FFFFFF">账号管家</h1>
            </div>

            <form method="post" action="<?php echo $webSite.'/admin/index.php'?>" id="login_submit">
                <?php if ($_GET["loginerr"]) {
                    $disp='display';
                }else{
                    $disp='none';
                }
                ?>
                <div style="color: red; height: 30px; text-align: center;font-size: 20px;margin-top: -10px; display: <?php echo $disp?>">
                    <span><?php echo urldecode($_GET["errMsg"])?></span>
                </div>

                <input type="hidden" name="login" value="true"/>
                <div class="form-group">
                    <label>用户名</label>
                    <input type="text"
                           name="username"
                           value=""
                           placeholder="用户名"
                           class="form-control">
                    <i class="icon icon-user"></i>
                </div>

                <div class="form-group">
                    <label>密码</label>
                    <input id="passwd_input" type="password" autofocus=""
                           onkeydown="if (event.keyCode == 13) {
                               document.getElementById('login_submit').click();
                           };"
                           name="pass"
                           placeholder="密码"
                           class="form-control"><i class="icon icon-lock"></i>
                </div>
                <input id="user_pass_input" type="password" name="loginSuccess" style="display: none">

                <button class="btn btn-primary" type="submit" >登录</button>
            </form>
        </div>
    </div>
</body>
</html>
<script>
    var realPass = "";
    $(document).ready(function () {
        // 解密传过来的密文
        var encryptPass = $("#encrypt_pass").val();
        if (encryptPass.length > 0){
            // base64解码
            encryptPass = atob(encryptPass);
            var privateKeyContent = getCookie("rsaPrivateKey");
            realPass = privateDecrypt(privateKeyContent,encryptPass);
        }

        // 监听密码输入 同时把输入的明文密码加密为密文
        $("#passwd_input").bind("input propertychange", function (event) {
            var userPass = $("#passwd_input").val();

            if (realPass == userPass){
                $("#user_pass_input").val("1");
            }else {
                $("#user_pass_input").val("0");
            }
        });
    });

    function setCookie1(name, value, time='',path='') {
        if(time && path){
            var strsec = time * 1000;
            var exp = new Date();
            exp.setTime(exp.getTime() + strsec * 1);
            document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString() + ";path="+path;
        }else if(time){
            var strsec = time * 1000;
            var exp = new Date();
            exp.setTime(exp.getTime() + strsec * 1);
            document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString();
        }else if(path){
            document.cookie = name + "=" + escape(value) + ";path="+path;
        }else{
            document.cookie = name + "=" + escape(value);
        }
    }
</script>