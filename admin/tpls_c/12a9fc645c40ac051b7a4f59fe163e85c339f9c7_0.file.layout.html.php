<?php
/* Smarty version 3.1.30, created on 2020-06-11 13:17:37
  from "/Users/mrchen/Desktop/www/PhpProjects/AccountManager/admin/view/layout.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5ee22ef1c0ef61_82492967',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '12a9fc645c40ac051b7a4f59fe163e85c339f9c7' => 
    array (
      0 => '/Users/mrchen/Desktop/www/PhpProjects/AccountManager/admin/view/layout.html',
      1 => 1591881453,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5ee22ef1c0ef61_82492967 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
?>
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
    <META HTTP-EQUIV="pragma" CONTENT="no-cache">
    <META HTTP-EQUIV="Cache-Control" CONTENT="no-cache, must-revalidate">
    <META HTTP-EQUIV="expires" CONTENT="Wed, 26 Feb 1997 08:21:57 GMT">
    <META HTTP-EQUIV="expires" CONTENT="0">

    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_4873495465ee22ef1c006e8_44666768', "title");
?>


    <link href="<?php echo PUBLIC_PATH;?>
common/css/icon.css" rel="stylesheet" type="text/css">
    <link href="<?php echo PUBLIC_PATH;?>
common/css/common.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="<?php echo PUBLIC_PATH;?>
common/layui/css/layui.css">
    <link rel="stylesheet" type="text/css" href="//at.alicdn.com/t/font_1877015_xvyhtha6n5q.css">

    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_8504430185ee22ef1c03f82_96990110', "myStyles");
?>

    <link rel="stylesheet" href="<?php echo PUBLIC_PATH;?>
admin/css/common.css">
</head>

<body>
<!--网络请求baseUrl-->
<input id="base_url" type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['baseUrl'];?>
">
<!--图片baseUrl-->
<input id="image_base_url" type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['imageUrl'];?>
">

<!--侧边栏-->
<div id="as_float_menu" class="as-float-menu">
    <button class="btn btn-sm mod-head-btn pull-left" style="padding: 0px; width: 50px; border-right:0">
        <i class="icon icon-bar"></i>
    </button>
</div>
<?php if ($_smarty_tpl->tpl_vars['data']->value['slideBarState'] == 0) {?>
<div id="aw-side" class="aw-side ps-container">
    <div class="mod">
        <div class="mod-logo" style="padding: 0">
            <div class="as-menu" style="width: 50px;height: 50px; float: left; margin: 0">
                <button class="btn btn-sm mod-head-btn pull-left" style="padding: 0px; width: 50px; border-right:0">
                    <i class="icon icon-bar"></i>
                </button>
            </div>
            <div style="font-family: HUPOFont;font-size: 20px;color: white;height: 55px;line-height: 55px;padding-left: 30px">账号管家后台系统</div>
        </div>

        <ul class="mod-bar">
            <input type="hidden" val="0" id="hide_values">
            <li>
                <a id="category_manager" class=" icon icon-format" href="?c=CategoryManager&a=index">
                    <span>分类管理</span>
                </a>
            </li>
            <li>
                <a id="platform_manager" class=" icon icon-score" href="?c=PlatformManager&a=index">
                    <span>平台管理</span>
                </a>
            </li>
            <li>
                <a id="account_manager" class=" icon icon-users" href="?c=AccountManager&a=index">
                    <span>账号管理</span>
                </a>
            </li>
            <li>
                <a id="pass_manager" class=" icon icon-protect" href="?c=PasswdManager&a=index">
                    <span>密码管理</span>
                </a>
            </li>
            <li>
                <a id="icon_depository" class="icon icon-list" href="?c=IconDepository&a=index">
                    <span>图标库</span>
                </a>
            </li>
            <li>
                <a id="db_manager" class="iconfont icon-cc-database" href="?c=DbManager&a=index">
                    <span>数据库管理</span>
                </a>
            </li>
            <li>
                <a id="rsa_key_manager" class="icon icon-verify" href="?c=RSAKeyManager&a=index">
                    <span>RSA密钥管理</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="ps-scrollbar-x-rail" style="width: 235px; display: none; left: 0px; bottom: 3px;">
        <div class="ps-scrollbar-x" style="left: 0px; width: 0px;"></div>
    </div>
    <div class="ps-scrollbar-y-rail" style="top: 0px; height: 607px; display: inherit; right: 0px;">
        <div class="ps-scrollbar-y" style="top: 0px; height: 560px;"></div>
    </div>
</div>
<?php }?>

<input type="hidden" id="slidebar_state_flag" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['slideBarState'];?>
"/>

<!--添加按钮-->
<button type="button" class="add-btn" onclick="addData()">
    <img src="<?php echo $_smarty_tpl->tpl_vars['data']->value['imageUrl'];?>
add.png">
</button>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_11278650225ee22ef1c0b242_53300284', "content");
?>

</body>
</html>
<?php echo '<script'; ?>
 src="<?php echo PUBLIC_PATH;?>
common/layui/layui.js" charset="utf-8"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo PUBLIC_PATH;?>
home/js/jquery.2.js" type="text/javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo PUBLIC_PATH;?>
home/js/jquery.form.js" type="text/javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo PUBLIC_PATH;?>
admin/js/framework.js" type="text/javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo PUBLIC_PATH;?>
admin/js/global.js" type="text/javascript"><?php echo '</script'; ?>
>
<!--[if lte IE 8]>
<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo PUBLIC_PATH;?>
home/js/respond.js"><?php echo '</script'; ?>
>
<![endif]-->
<?php echo '<script'; ?>
 src="<?php echo PUBLIC_PATH;?>
admin/js/common.js"  type="text/javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo PUBLIC_PATH;?>
admin/js/sidebar.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo PUBLIC_PATH;?>
admin/js/jsencrypt.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo PUBLIC_PATH;?>
admin/js/RSAEncrypt.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo PUBLIC_PATH;?>
common/js/cookie.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>
    // 网络请求baseUrl
    var baseUrl = $("#base_url").val();
    // 图片所在文件夹
    var imageBaseUrl = $("#image_base_url").val();

    // 检查需要函数是否被禁用了
    function checkDisableFunc() {
        var functions = ["exec","proc_open"];
        functions = functions.join(",");
        var url = baseUrl
            + "?m=admin&c=API_GetLocalMovies&a=functionDisabled&API=&function_name="+functions;

        get(url,function (data) {
            var result = data.data;
            if (result["disabled"]){
                showAlert("以下函数被禁用了，请取消禁用\r\n"+result["funcs"]);
            }
        },false)
    }

    // 给密码加密
    function RSAEncryptPass(pass) {
        return publicEncrypt(rsaPublicContent,pass);
    }

    // 给密码解密
    function RSADecryptPass(pass) {
        return privateDecrypt(rsaPrivateContent,pass);
    }
<?php echo '</script'; ?>
>
<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_17658847495ee22ef1c0e549_80460413', "scriptCode");
?>




<?php }
/* {block "title"} */
class Block_4873495465ee22ef1c006e8_44666768 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <title>账号管家后台管理系统</title>
    <?php
}
}
/* {/block "title"} */
/* {block "myStyles"} */
class Block_8504430185ee22ef1c03f82_96990110 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
 <?php
}
}
/* {/block "myStyles"} */
/* {block "content"} */
class Block_11278650225ee22ef1c0b242_53300284 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block "content"} */
/* {block "scriptCode"} */
class Block_17658847495ee22ef1c0e549_80460413 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block "scriptCode"} */
}
