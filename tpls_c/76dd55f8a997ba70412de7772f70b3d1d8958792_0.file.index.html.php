<?php
/* Smarty version 3.1.30, created on 2020-06-11 22:20:10
  from "/Users/mrchen/Desktop/www/PhpProjects/AccountManager/admin/view/db/index.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5ee2ae1aebc6c6_30278076',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '76dd55f8a997ba70412de7772f70b3d1d8958792' => 
    array (
      0 => '/Users/mrchen/Desktop/www/PhpProjects/AccountManager/admin/view/db/index.html',
      1 => 1591913739,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:layout.html' => 1,
  ),
),false)) {
function content_5ee2ae1aebc6c6_30278076 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_11714551505ee2ae1aeaf178_67336111', "myStyles");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1408472975ee2ae1aeb0e34_02410855', "scriptCode");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_6766972645ee2ae1aebc156_27479384', "content");
?>

<?php $_smarty_tpl->inheritance->endChild();
$_smarty_tpl->_subTemplateRender("file:layout.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 2, false);
}
/* {block "myStyles"} */
class Block_11714551505ee2ae1aeaf178_67336111 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<style type="text/css">
    .aw-content-wrap .icon{
        background-color: #393D49;
        margin-right: 10px;
    }

    .aw-content-wrap .iconfont {
        color: #FFF;
        background-color: #393D49;
        width: 25px;
        height: 25px;
        line-height: 25px;
        margin-right: 10px;
        display: inline-block;
        border-radius: 5px;
        text-align: center;
    }

    .layui-table thead th{
        text-align: center;
        font-weight: bold;
    }

    .layui-btn-primary {
        background-color: #393D49;
        color: #fff;
    }

    .layui-btn-primary:hover{
        color: #FFF;
    }

</style>
<?php
}
}
/* {/block "myStyles"} */
/* {block "scriptCode"} */
class Block_1408472975ee2ae1aeb0e34_02410855 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<?php echo '<script'; ?>
>
    $(document).ready(function () {
        // 设置边栏服务器信息选中状态
        $("#db_manager").addClass("active");
        $(".add-btn").css("display","none");

        var msg = $("#msg_input").val();
        if (msg.length > 0){
            toast(msg);
        }

        // 监听文件被选择
        $('#file_select_input').change(function(e){
            var form = $("#upload_back_form");
            form.submit();
        });

        // 获取备份历史文件
        setTimeout(function () {
            loadDbBackupLists();
        }, 100);
    });


    // 获取备份历史文件
    function loadDbBackupLists() {
        var url = baseUrl + "?m=admin&c=API_Database&a=loadDbBackupHistory";
        get(url,function (data) {
            // 创建备份历史列表
            createBackupListDom(data.data);
        });
    }

    // 创建备份文件列表
    function createBackupListDom(data) {
        var dom = "";
        for(var i = 0; i < data.length; i++){
            var backupData = data[i];
            dom += '<tr><td>'+ backupData["name"] +'</td>';
            dom += '<td>' + backupData["size"] + '</td>';
            dom += '<td>' + backupData["time"] + '</td>';
            dom += '<td><i title="导入" onclick="importBackup(this)" backName="'+ backupData["name"] +'" style="cursor: pointer" class="iconfont icon-daoru"></i>';
            dom += '<i title="下载" backName="'+ backupData["name"] +'" onclick="downloadBackup(this)" style="cursor: pointer" class="icon icon-download"></i>';
            dom += '<i title="删除" backName="'+ backupData["name"] +'" onclick="deleteBackup(this)" style="cursor: pointer" class="icon icon-trash"></i></td>';
        }

        // 添加分类一行
        $("tbody").html(dom);
    }

    // 删除备份
    function deleteBackup(obj) {
        var $this = $(obj);
        var backName = $this.attr("backName");
        var url = baseUrl + "?m=admin&c=API_Database&a=deleteBackup&fileName=" + backName;
        get(url,function () {
            // 刷新列表
            loadDbBackupLists();
        },false,true);
    }

    // 导入备份
    function importBackup(obj) {
        var $this = $(obj);
        var backName = $this.attr("backName");

        // 请求指定分类数据
        var url = baseUrl + "?m=admin&c=API_Database&a=importBackup&fileName=" + backName;
        get(url,function () {
            // 导入完成
        })
    }

    // 备份数据库
    function backupDB() {
        var url = baseUrl + "?m=admin&c=API_Database&a=backupDB";
        get(url,function () {
            // 备份完成 刷新备份历史列表
            loadDbBackupLists();
        },true,true);
    }

    // 上传备份文件
    function uploadBackupDB() {
        $("#file_select_input").click();
    }
<?php echo '</script'; ?>
>
<?php
}
}
/* {/block "scriptCode"} */
/* {block "content"} */
class Block_6766972645ee2ae1aebc156_27479384 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<div class="aw-content-wrap">
    <input id="msg_input" type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['msg'];?>
">
    <button onclick="backupDB()" type="button" class="layui-btn layui-btn-primary layui-btn-lg">新增备份</button>
    <button onclick="uploadBackupDB()" type="button" class="layui-btn layui-btn-primary layui-btn-lg">上传文件</button>
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;width: 100%">
        <legend>备份历史</legend>
    </fieldset>

    <div class="mod">
        <div class="layui-form" style="text-align: center;">
            <table class="layui-table">
                <thead>
                <tr>
                    <th>文件名</th>
                    <th>文件大小</th>
                    <th>备份时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <form id="upload_back_form" enctype="multipart/form-data" method="post" action="<?php echo $_smarty_tpl->tpl_vars['data']->value['baseUrl'];?>
/admin/controller/UploadDBBack.php">
        <input type="file" name="file" id="file_select_input" style="display:none">
    </form>
</div>
<?php
}
}
/* {/block "content"} */
}
