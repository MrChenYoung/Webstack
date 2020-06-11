<?php
/* Smarty version 3.1.30, created on 2020-06-08 17:01:38
  from "/Users/mrchen/Desktop/www/PhpProjects/AccountManager/admin/view/add/index.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5ede6ef26c35f8_83844525',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2364317d2a7a40e9b336e445982f5b8f23baacf5' => 
    array (
      0 => '/Users/mrchen/Desktop/www/PhpProjects/AccountManager/admin/view/add/index.html',
      1 => 1591635697,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:layout.html' => 1,
  ),
),false)) {
function content_5ede6ef26c35f8_83844525 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_19039942365ede6ef26c1a39_82481943', "myStyles");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_18065519495ede6ef26c2655_89031769', "scriptCode");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_12023113565ede6ef26c30f3_08804405', "content");
?>

<?php $_smarty_tpl->inheritance->endChild();
$_smarty_tpl->_subTemplateRender("file:layout.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 2, false);
}
/* {block "myStyles"} */
class Block_19039942365ede6ef26c1a39_82481943 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<style type="text/css">
    body {
        background-color: #FFF;
    }
    legend {
        /*width: 100px;*/
    }
</style>
<?php
}
}
/* {/block "myStyles"} */
/* {block "scriptCode"} */
class Block_18065519495ede6ef26c2655_89031769 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<?php echo '<script'; ?>
>
    // iconshu数据
    var iconData;
    $(document).ready(function () {
        // 设置边栏服务器信息选中状态
        $("#category_manager").addClass("active");

    })

<?php echo '</script'; ?>
>
<?php
}
}
/* {/block "scriptCode"} */
/* {block "content"} */
class Block_12023113565ede6ef26c30f3_08804405 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<div class="aw-content-wrap">
    <div style="width: 1000px;height: 500px;background-color: red">
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
            <legend>添加分类</legend>
        </fieldset>

        <form class="layui-form" action="">
            <div class="layui-form-item">
                <label class="layui-form-label">分类图标</label>
                <div class="layui-input-inline">
                    <select name="quiz1">
                        <option value="">请选择图标</option>
                        <option value="浙江" selected="">浙江省</option>
                        <option value="你的工号">江西省</option>
                        <option value="你最喜欢的老师">福建省</option>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">分类名</label>
                <div class="layui-input-block">
                    <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入标题" class="layui-input">
                </div>
            </div>
        </form>
    </div>
</div>
<?php
}
}
/* {/block "content"} */
}
