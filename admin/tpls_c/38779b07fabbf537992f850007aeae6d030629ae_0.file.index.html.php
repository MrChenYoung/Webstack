<?php
/* Smarty version 3.1.30, created on 2020-06-08 00:48:27
  from "/Users/mrchen/Desktop/www/PhpProjects/AccountManager/admin/view/serverInfo/index.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5edd8adbab03f3_37651573',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '38779b07fabbf537992f850007aeae6d030629ae' => 
    array (
      0 => '/Users/mrchen/Desktop/www/PhpProjects/AccountManager/admin/view/serverInfo/index.html',
      1 => 1589958393,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:layout.html' => 1,
  ),
),false)) {
function content_5edd8adbab03f3_37651573 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_14353308445edd8adbaac458_71360315', "scriptCode");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_17057390505edd8adbaafd92_09594116', "content");
?>

<?php $_smarty_tpl->inheritance->endChild();
$_smarty_tpl->_subTemplateRender("file:layout.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 2, false);
}
/* {block "scriptCode"} */
class Block_14353308445edd8adbaac458_71360315 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<?php echo '<script'; ?>
>
    $(document).ready(function () {
        // 设置边栏服务器信息选中状态
        $("#server_info").addClass("active");

        // 页面加载完成 执行自动执行代码
        $("#form").submit();
    })
<?php echo '</script'; ?>
>
<?php
}
}
/* {/block "scriptCode"} */
/* {block "content"} */
class Block_17057390505edd8adbaafd92_09594116 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<div class="aw-content-wrap">
    <form id="form" action="?m=admin&c=ServerInfo&a=loadTempleteComplete" method="post" target="exec_target">
    </form>
    <iframe hidden id="exec_target" name="exec_target"></iframe>

    <div class="mod">
        <div class="mod-head">
            <h3>
                <span class="pull-left">系统信息</span>
            </h3>
        </div>
        <div class="tab-content mod-content">
            <table class="table table-striped">
                <tbody>
                <tr>
                    <td>操作系统</td>
                    <td><?php echo $_smarty_tpl->tpl_vars['data']->value['system'];?>
</td>
                </tr>
                <tr>
                    <td>主机</td>
                    <td><?php echo $_smarty_tpl->tpl_vars['data']->value['host'];?>
</td>
                </tr>
                <tr>
                    <td>PHP</td>
                    <td><?php echo $_smarty_tpl->tpl_vars['data']->value['phpV'];?>
</td>
                </tr>
                <tr>
                    <td>MySQL</td>
                    <td><?php echo $_smarty_tpl->tpl_vars['data']->value['mysqlV'];?>
</td>
                </tr>
                <tr>
                    <td>Web Server</td>
                    <td><?php echo $_smarty_tpl->tpl_vars['data']->value['webServer'];?>
</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
}
}
/* {/block "content"} */
}
