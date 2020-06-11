<?php
/* Smarty version 3.1.30, created on 2020-06-11 19:39:31
  from "/Users/mrchen/Desktop/www/PhpProjects/AccountManager/admin/view/RSAKey/index.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5ee2887347cef0_96673134',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6b0e87454eda143f863c09a2cf68305a6e45e2db' => 
    array (
      0 => '/Users/mrchen/Desktop/www/PhpProjects/AccountManager/admin/view/RSAKey/index.html',
      1 => 1591904347,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:layout.html' => 1,
  ),
),false)) {
function content_5ee2887347cef0_96673134 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_10403239595ee2887347a0f5_59904979', "myStyles");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1414556365ee2887347b601_85106893', "scriptCode");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_21205937515ee2887347c7a9_82862286', "content");
?>

<?php $_smarty_tpl->inheritance->endChild();
$_smarty_tpl->_subTemplateRender("file:layout.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 2, false);
}
/* {block "myStyles"} */
class Block_10403239595ee2887347a0f5_59904979 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<style type="text/css">
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
        right: 15px;
        /*margin-top: 10px;*/
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

</style>
<?php
}
}
/* {/block "myStyles"} */
/* {block "scriptCode"} */
class Block_1414556365ee2887347b601_85106893 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<?php echo '<script'; ?>
>
    // 是否正在添加公钥
    var isAddPublickKey;
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

        // 提示文字
        var notice = "提示：RSA公钥和私钥只保存在浏览器cookie中，不会上传到服务端，所有密码的加密解密工作都在客户端完成。"
        $("#notice_area").text(notice);

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
        setCookie(key,content);

        loadRsaKeyContent();
    }

    // 添加公钥
    function addPublicKey() {
        // 弹出文件选择框
        isAddPublickKey = true;
        $("#key_select_input").click();
    }
    
    // 添加私钥
    function addPrivateKey() {
        isAddPublickKey = false;
        $("#key_select_input").click();
    }

    // 是否要跳转到rsa秘钥添加页面
    function toAddRSAKey() {
        return false;
    }
    
    function clearCookie() {
        delCookie("rsaPublicKey");
        delCookie("rsaPrivateKey");
        var url = baseUrl + "/admin?c=API_PassManager&a=clearCookie";
        get(url,function () {
            console.log("cookie111:" + document.cookie);
        });

    }

<?php echo '</script'; ?>
>
<?php
}
}
/* {/block "scriptCode"} */
/* {block "content"} */
class Block_21205937515ee2887347c7a9_82862286 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<div class="aw-content-wrap">
    <p id="notice_area" class="notice-area"></p>

    <form class="layui-form layui-form-pane" action="">
        <div class="public-key-box">
            <div class="layui-form-item">
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">公钥</label>
                    <div class="layui-input-block">
                        <textarea readonly="readonly" style="resize: none;height: 150px;cursor: default" id="public_key_content" placeholder="没有公钥记录" class="layui-textarea"></textarea>
                    </div>
                </div>
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
            <button onclick="addPrivateKey()" type="button" class="layui-btn layui-btn-primary">添加</button>
        </div>
    </form>
    <button style="margin-top: 50px;" onclick="clearCookie()" type="button" class="layui-btn layui-btn-primary">清空cookie</button>
    <input type="file" id="key_select_input" style="display:none">
</div>
<?php
}
}
/* {/block "content"} */
}
