<?php
/* Smarty version 3.1.30, created on 2020-06-11 22:20:09
  from "/Users/mrchen/Desktop/www/PhpProjects/AccountManager/admin/view/iconDepository/index.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5ee2ae19ae62f7_56686329',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8e0e447c6f40ba44c0c5087569c99c85b9efe007' => 
    array (
      0 => '/Users/mrchen/Desktop/www/PhpProjects/AccountManager/admin/view/iconDepository/index.html',
      1 => 1591913820,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:layout.html' => 1,
  ),
),false)) {
function content_5ee2ae19ae62f7_56686329 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_3186022965ee2ae19ae4800_74102610', "myStyles");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_321912035ee2ae19ae5431_66905129', "scriptCode");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_2506032005ee2ae19ae5e63_56519440', "content");
?>

<?php $_smarty_tpl->inheritance->endChild();
$_smarty_tpl->_subTemplateRender("file:layout.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 2, false);
}
/* {block "myStyles"} */
class Block_3186022965ee2ae19ae4800_74102610 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<style type="text/css">
    html, body, div, ul, li, h1, h2, h3, h4, h5, h6, p, dl, dt, dd, ol, form, input, textarea, th, td, select {
        margin: 0;
        padding: 0;
    }

    * {
        box-sizing: border-box;
    }

    html, body {
        min-height: 100%;
    }

    h1, h2, h3, h4, h5, h6 {
        font-weight: normal;
    }

    ul, ol {
        list-style: none;
    }

    img {
        border: none;
        vertical-align: middle;
    }

    .layui-table {
        width: 220px;
        display: inline-block;
    }

    .icon-container {
        width: 100%;
    }

    .layui-form-pane .layui-form-label {
        background-color: #393D49;
        width: 50px;
    }

    .layui-form-pane .layui-form-label i {
        background-color: #393D49;
        margin-left: -5px;
        height: 25px;
        line-height: 25px;
    }

    ::-webkit-input-placeholder {
        color: #e6e6e6;
    }

    :-moz-placeholder { /* Firefox 18- */
        color: #e6e6e6;
    }

    ::-moz-placeholder { /* Firefox 19+ */
        color: #e6e6e6;
    }

    :-ms-input-placeholder {
        color: #e6e6e6;
    }

    .icon-input {
        border: none;
        width: 60px;
        height: 20px;
        line-height: 20px;
        cursor: default;
        background-color: transparent;
    }

    .icon-input:hover {
        background-color: transparent;
    }

    .icon-input::selection {
        background-color: transparent;
    }

</style>
<?php
}
}
/* {/block "myStyles"} */
/* {block "scriptCode"} */
class Block_321912035ee2ae19ae5431_66905129 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<?php echo '<script'; ?>
>
    $(document).ready(function () {
        // 设置边栏服务器信息选中状态
        $("#icon_depository").addClass("active");
        // 隐藏右下角添加按钮
        $(".add-btn").css("display","none");

        layui.use("form", function () {
            var form = layui.form;

            var searchBar = $("#search_icon");
            searchBar.focus();
            // 搜索icon
            searchBar.bind("input propertychange", function (event) {
                loadIcons();
            });
        });

        // 加载icon
        loadIcons();
    });

    // 请求icon
    function loadIcons() {
        var kwds = $("#search_icon").val();
        kwds = kwds != undefined ? kwds : "";
        var url = baseUrl + "?m=admin&c=API_Icon&a=getIconDepository&API=&keyWords=" + kwds;
        console.log("请求icon:" + url);
        get(url, function (data) {
            createIconDom(data.data);
        }, false)
    }

    // 创建icon显示
    function createIconDom(icons) {
        var dom = "";
        for (var i = 0; i < icons.length; i++) {
            var icon = icons[i];
            dom += '<table class="layui-table"><colgroup><col width="50"><col width="100"><col width="63"></colgroup>';
            dom += '<tbody><tr><td><i class="icon icon-' + icon + '" style="background-color:#393D49;"></i></td>';
            dom += '<td><input readonly="readonly" type="text" id="icon_'+ icon +'"  class="icon-input" value="' + icon + '"></td><td><a href="javascript:;" iconName="' + icon + '" onclick="copyIcon(this)" style="color: #2b77a8">复制</a></td></tr></tbody></table>';
        }
        $(".icon-container").html(dom);
    }

    // 拷贝icon
    function copyIcon(obj) {
        var $this = $(obj);
        var iconName = $this.attr("iconName");
        var inputDom = document.getElementById("icon_" + iconName);
        copyInputContent(inputDom);
    }

<?php echo '</script'; ?>
>
<?php
}
}
/* {/block "scriptCode"} */
/* {block "content"} */
class Block_2506032005ee2ae19ae5e63_56519440 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<div class="aw-content-wrap">

    <form class="layui-form layui-form-pane" action="">
        <div class="layui-form-item">
            <label class="layui-form-label">
                <i class="icon icon-search"></i>
            </label>
            <div class="layui-input-inline">
                <input id="search_icon" type="text" name="username" lay-verify="required" placeholder="输入icon名称"
                       autocomplete="off" class="layui-input">
            </div>
        </div>
    </form>
    <div class="icon-container">
    </div>
</div>
<?php
}
}
/* {/block "content"} */
}
