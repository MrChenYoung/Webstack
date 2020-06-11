<?php
/* Smarty version 3.1.30, created on 2020-06-10 10:51:12
  from "/Users/mrchen/Desktop/www/PhpProjects/AccountManager/admin/view/platform/index.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5ee0bb20385219_64991668',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0c3b71fb08ac768bb9dc708dac87f156de34d5a5' => 
    array (
      0 => '/Users/mrchen/Desktop/www/PhpProjects/AccountManager/admin/view/platform/index.html',
      1 => 1591777934,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:layout.html' => 1,
  ),
),false)) {
function content_5ee0bb20385219_64991668 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_509172075ee0bb20382550_12723219', "myStyles");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_20951761035ee0bb203838d7_66388728', "scriptCode");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_16341738465ee0bb20384a95_89512511', "content");
?>

<?php $_smarty_tpl->inheritance->endChild();
$_smarty_tpl->_subTemplateRender("file:layout.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 2, false);
}
/* {block "myStyles"} */
class Block_509172075ee0bb20382550_12723219 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<style type="text/css">
    .aw-content-wrap .icon {
        background-color: #393D49;
        margin-right: 10px;
    }

    .layui-table thead th{
        text-align: center;
        font-weight: bold;
    }

    .add-category-box {
        background-color: rgba(0,0,0,0.7);
        width: 100%;
        height: 1000px;
        margin: 0 auto;
        position: fixed;
        left: 0;
        top: 0;
        z-index: 500;
        display: none;
    }

    .add-category-container {
        width: 800px;
        height: 300px;
        background-color: #FFF;
        margin: 150px auto;
        padding: 10px;
        border-radius: 2px;
        position: relative;
    }


    .layui-field-title{
        width: 800px;
    }

    .layui-form-select dl dd.layui-this {
        background-color: #393D49;
    }

    .layui-form-item .cat-name {
        width: 250px;
    }

    .layui-input-inline .layui-btn {
        color: #e6e6e6
    }

    .icons-box {
        width: 700px;
        height: 100px;
        overflow-x: hidden;
        overflow-y: scroll;
        margin-bottom: 10px;
    }

    .icons-box ul {
        width: 700px;
        height: 200px;
        padding: 5px;
    }

    .icons-box ul li {
        width: 20px;
        height: 20px;
        float: left;
        cursor: pointer;
        color: #e6e6e6;
        margin: 5px;
    }

    .icons-box ul li:hover {
        color: #393D49;
    }

    .icons-box ul li i {
        font-size: 18px;
    }

    .btns-box {
        width: 146px;
        position: absolute;
        right: 10px;
        bottom: 20px;
    }

    .btns-box .confirm-btn{
        background-color: #393D49;
    }

    .acc_list_ul {
        width: 800px;
        height: 30px;
        display: flex;
        flex-direction: row;
        justify-content: center;
    }

    .acc_list_ul li {
        font-size: 12px;
        height: 20px;
        float: left;
        margin-right: 10px;
        padding: 5px;
        background-color: #e6e6e6;
    }

</style>
<?php
}
}
/* {/block "myStyles"} */
/* {block "scriptCode"} */
class Block_20951761035ee0bb203838d7_66388728 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<?php echo '<script'; ?>
>
    // 添加平台选择的catid
    var selectedCatId = '';
    // 添加平台的平台名
    var addPlatformName = '';
    // 是否是修改分类
    var isEdit = false;
    // 修改平台id
    var editPlatId = "";

    $(document).ready(function () {
        // 设置边栏服务器信息选中状态
        $("#platform_manager").addClass("active");

        // 监听添加平台输入平台名
        var addPlatInput = $("#add_plat_name");
        addPlatInput.bind("input propertychange", function (event) {
            addPlatformName = addPlatInput.val();
        });

        // 监听分类选择
        layui.use('form',function () {
            var form = layui.form;
            form.on('select(cat-list)', function(data){
                // 选择的catid
                var catid = data.value;
                selectedCatId = catid;
                console.log("catid选择:" + catid);
            });
        });

        // 请求平台列表
        loadPlatformList();
    });

    // 获取平台列表
    function loadPlatformList() {
        var url = baseUrl + "/admin?c=API_Platform&a=loadPlatformList";
        get(url,function (data) {
            // 创建分类列表
            createPlatformDom(data.data);
        },false);
    }

    // 请求分类列表
    function loadCatList() {
        var url = baseUrl + "/admin?c=API_Category&a=loadCategotyList";
        get(url, function (data) {
            layui.use('form', function(){
                var form = layui.form;
                var catData = data.data;
                // 创建要选择的分类列表
                var dom = '<option value="">请选择分类</option>';
                for (var i = 0; i < catData.length; i++){
                    var cat = catData[i];
                    dom += '<option value="'+ cat["id"] +'" >'+ cat["cat_title"] +'</option>';
                }
                $("#cat_select").html(dom);
                $("#cat_select").val(selectedCatId);
                form.render();
                // 显示添加界面
                showEditCatCover();
            });
        })
    }

    // 添加平台
    function addData() {
        isEdit = false;
        selectedCatId = "";
        addPlatformName = "";
        loadCatList();
    }

    // 创建平台列表
    function createPlatformDom(data) {
        var dom = "";
        for(var i = 0; i < data.length; i++){
            var platform = data[i];
            dom += '<tr><td>'+ platform["plat_name"] +'</td>';
            dom += '<td><ul class="acc_list_ul">';
            var accList = platform["acc_list"];
            for (var j = 0; j < accList.length; j++){
                var acc = accList[j];
                dom += '<li>'+ acc +'</li>';
            }
            dom += '</ul></td>';
            dom += '<td>' + platform["cat_title"] + '</td>';
            dom += '<td><i onclick="editPlatform(this)" platId="'+ platform["id"] +'" style="cursor: pointer" class="icon icon-edit"></i>';
            dom += '<i catId="'+ platform["cat_id"] +'" platId="'+ platform["id"] +'" onclick="deletePlatform(this)" style="cursor: pointer" class="icon icon-trash"></i></td></tr>';
        }

        // 添加分类一行
        $("tbody").html(dom);
    }

    // 确定添加/修改平台
    function confirm() {
        if (selectedCatId.length == 0){
            toast("请选择分类");
            return;
        }
        if (addPlatformName.length == 0){
            toast("请输入平台名称");
            return;
        }

        // 请求添加分类接口
        var url = "";
        if (isEdit){
            // 修改
            url = baseUrl + "/admin?c=API_Platform&a=editPlatform&id=" + editPlatId + "&catId=" + selectedCatId + "&platName=" + addPlatformName;
        }else {
            // 添加
            url = baseUrl + "/admin?c=API_Platform&a=addPlatform&catId=" + selectedCatId + "&platName=" + addPlatformName;
        }

        get(url,function () {
            // 浮层消失
            hideEditCatCover();
            // 刷新分类列表
            loadPlatformList();
        },true,true);
    }

    // 取消添加平台
    function cancel() {
        hideEditCatCover();
    }

    // 删除平台
    function deletePlatform(obj) {
        var $this = $(obj);
        var platId = $this.attr("platId");
        var catId = $this.attr("catId");
        var url = baseUrl + "/admin?c=API_Platform&a=deletePlatform&id=" + platId + "&catId=" + catId;
        get(url,function () {
            // 刷新列表
            loadPlatformList();
        },true,true);
    }
    
    // 编辑平台
    function editPlatform(obj) {
        isEdit = true;
        var $this = $(obj);
        var platId = $this.attr("platId");
        editPlatId = platId;

        // 请求指定平台数据
        var url = baseUrl + "/admin?c=API_Platform&a=loadPlatform&id=" + platId;

        get(url,function (data) {
            var platData = data.data;
            selectedCatId = platData["cat_id"];
            addPlatformName = platData["plat_name"];
            // 加载分类
            loadCatList();
        })
    }
    
    // 显示添加/修改平台浮层
    function showEditCatCover() {
        $(".add-category-box").css("display","block");
        // 默认设置的平台名称
        $("#add_plat_name").val(addPlatformName);
    }

    // 隐藏添加/修改分类浮层
    function hideEditCatCover() {
        $(".add-category-box").css("display","none");
    }

<?php echo '</script'; ?>
>
<?php
}
}
/* {/block "scriptCode"} */
/* {block "content"} */
class Block_16341738465ee0bb20384a95_89512511 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<div class="aw-content-wrap">
    <form id="form" action="?m=admin&c=ServerInfo&a=loadTempleteComplete" method="post" target="exec_target">
    </form>
    <iframe hidden id="exec_target" name="exec_target"></iframe>

    <div class="mod">
        <div class="layui-form" style="text-align: center;">
            <table class="layui-table">
                <colgroup>
                    <col width="150">
                    <col>
                    <col width="150">
                    <col width="200">
                </colgroup>
                <thead>
                <tr>
                    <th>平台名称</th>
                    <th>账号</th>
                    <th>所属分类</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!--添加分类浮层-->
<div class="add-category-box">
    <div class="add-category-container">
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
            <legend>添加平台</legend>
        </fieldset>
        <form class="layui-form layui-form-pane" action="">
            <div class="layui-form-item">
                <label class="layui-form-label">所属分类</label>
                <div class="layui-input-inline">
                    <select id="cat_select" lay-filter="cat-list">
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">平台名称</label>
                <div class="layui-input-inline cat-name">
                    <input id="add_plat_name" type="text" name="username" lay-verify="required" placeholder="请输入平台名称" autocomplete="off" class="layui-input">
                </div>
            </div>
        </form>

        <!--确定/取消按钮-->
        <div class="btns-box">
            <button onclick="cancel()" type="button" class="layui-btn layui-btn-primary">取消</button>
            <button onclick="confirm()" type="button" class="layui-btn confirm-btn">确认</button>
        </div>
    </div>
</div>
<?php
}
}
/* {/block "content"} */
}
