<?php $webSite = "http://".$_SERVER['HTTP_HOST'];  $pubP = $webSite."/public"?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>添加秘钥</title>
    <link rel="stylesheet" href="<?php echo $pubP?>/common/layui/css/layui.css">
    <script src="<?php echo $pubP?>/home/js/jquery.2.js" type="text/javascript"></script>
    <script src="<?php echo $pubP?>/common/layui/layui.js" charset="utf-8"></script>
    <script src="<?php echo $pubP?>/common/js/cookie.js" charset="utf-8"></script>
    <style type="text/css">
        .aw-content-wrap {
            width: 80%;
            margin:20px auto;
            position: relative;
        }

        .public-key-box {
            width: 100%;
            height: 250px;
        }

        .layui-form-item {
            margin-bottom: 5px;
        }

        .layui-btn-primary {
            background-color: #393D49;
            color: #fff;
            position: absolute;
            right: 0;
        }

        .layui-btn-primary:hover{
            color: #FFF;
        }

        .notice-area {
            width: 100%;
            height: 40px;
            font-size: 20px;
            font-weight: 300;
            color: #393D49;
        }

        .tip-content {
            font-size: 12px;
            color: red;
        }

        .err-message {
            font-size: 16px;
            color: red;
            display: none;
        }
    </style>
</head>
<body>
<div class="aw-content-wrap">
    <div>
        <span id="notice_area" class="notice-area">添加RSA密钥</span>
        <span id="tip_content" class="tip-content">RSA公钥和私钥只保存在浏览器cookie中，不会上传到服务端，所有密码的加密解密工作都在客户端完成</span>
    </div>

    <form class="layui-form layui-form-pane" action="">
        <div class="public-key-box">
            <div class="layui-form-item">
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">公钥</label>
                    <div class="layui-input-block">
                        <textarea readonly="readonly" style="resize: none;height: 150px;cursor: default" id="public_key_content" placeholder="没有公钥记录" class="layui-textarea"></textarea>
                    </div>
                </div>
                <p id="public_err_message" class="err-message">添加失败，请添加正确格式的公钥</p>
                <button onclick="addPublicKey()" type="button" class="layui-btn layui-btn-primary">添加</button>
            </div>
        </div>


        <div class="layui-form-item">
            <div class="layui-form-item layui-form-text">
                <label class="layui-form-label">私钥</label>
                <div class="layui-input-block">
                    <textarea readonly="readonly" style="resize: none;height: 150px;cursor: default" id="private_key_content" placeholder="没有私钥记录" class="layui-textarea"></textarea>
                </div>
            </div>
            <p id="private_err_message" class="err-message">添加失败，请添加正确格式的私钥</p>
            <button onclick="addPrivateKey()" type="button" class="layui-btn layui-btn-primary">添加</button>
        </div>
    </form>
    <input type="file" id="key_select_input" style="display:none">

</div>
<input id="web_site" type="hidden" value="<?php echo "http://".$_SERVER['HTTP_HOST'] ?>">
</body>
</html>
<script>
    layui.use('form',function () {
        var form = layui.form;
        form.render();
    });

    // 是否正在添加公钥
    var isAddPublickKey;
    // 保存到cookie的公钥键
    var publickKey = "rsaPublicKey";
    // 保存到cookie的私钥键
    var privateKey = "rsaPrivateKey";
    $(document).ready(function () {
        // 设置边栏服务器信息选中状态
        $("#rsa_key_manager").addClass("active");
        $(".add-btn").css("display","none");

        // 监听文件被选择
        $('#key_select_input').change(function(e){
            //获取到文件列表
            var objFile = document.getElementById("key_select_input");
            var files = $('#key_select_input').prop('files');//获取到文件列表

            if (files.length == 0){
                // 取消选择文件
            }else {
                //读取文件
                var reader = new FileReader();
                reader.readAsText(files[0], "UTF-8");
                reader.onload = function(evt){ //读取完文件之后会回来这里
                    var fileString = evt.target.result; // 读取文件内容
                    // 保存内容
                    addKey(fileString);
                }
            }
        });

        // 获取保存的公钥和私钥内容
        loadRsaKeyContent();
    });

    // 获取保存的公钥和私钥内容
    function loadRsaKeyContent() {
        // 公钥
        var publicKeyContent = getCookie(publickKey);
        $("#public_key_content").val(publicKeyContent);


        // 私钥
        var privateKeyContent = getCookie(privateKey);
        $("#private_key_content").val(privateKeyContent);
    }

    // 添加key
    function addKey(content) {
        // 保存内容到cookie
        var key = isAddPublickKey ? publickKey : privateKey;

        // 获取添加前cookie内容
        var oldCoolie = getCookie(key);

        // 设置cookie
        setCookie(key,content);

        // 再次获取cookie
        var newCookie = getCookie(key);
        console.log("old:" + oldCoolie);
        console.log("new:" + newCookie);
        if (newCookie != null && newCookie.length > 0 && newCookie != oldCoolie){
            // 添加成功
            if (isAddPublickKey){
                $("#public_err_message").css("display","none");
            }else {
                $("#private_err_message").css("display","none");
            }
            loadRsaKeyContent();

            // 检测如果公钥和私钥都添加 直接跳转到index.php
            var publicKeyContent = getCookie(publickKey);
            var privateKeyContent = getCookie(privateKey);

            if (publicKeyContent != null && publicKeyContent.length > 0 && privateKeyContent != null && privateKeyContent.length > 0){
                var url = $("#web_site").val() + "/index.php";
                window.location = url;
            }
        }else {
            console.log("添加失败");

            // 添加失败
            if (isAddPublickKey){
                $("#public_err_message").css("display","inline-block");
            }else {
                $("#private_err_message").css("display","inline-block");
            }
        }
    }

    // 添加公钥
    function addPublicKey() {
        // 清空input文件选择 防止公钥和私钥选择同一个文件不触发input文件选择框的选择文件完成方法
        var file = document.getElementById('key_select_input');
        file.value = '';

        // 弹出文件选择框
        isAddPublickKey = true;
        $("#key_select_input").click();
    }

    // 添加私钥
    function addPrivateKey() {
        // 清空input文件选择 防止公钥和私钥选择同一个文件不触发input文件选择框的选择文件完成方法
        var file = document.getElementById('key_select_input');
        file.value = '';

        isAddPublickKey = false;
        $("#key_select_input").click();
    }

    // 是否要跳转到rsa秘钥添加页面
    function toAddRSAKey() {
        return false;
    }
</script>