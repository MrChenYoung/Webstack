var loadIndex;
// 保存到cookie的公钥键
var publickKey = "rsaPublicKey";
// 保存到cookie的私钥键
var privateKey = "rsaPrivateKey";
// rsa公钥内容
var rsaPublicContent;
// rsa私钥内容
var rsaPrivateContent;

$(document).ready(function () {
    // 获取公钥和私钥
    rsaPublicContent = getCookie(publickKey);
    rsaPrivateContent = getCookie(privateKey);

    if (!toAddRSAKey()){
        return;
    }
    // 如果公钥或私钥为空 提示去添加
    if (rsaPrivateContent == null || rsaPublicContent == null || rsaPublicContent.length == 0 || rsaPrivateContent.length == 0){
        var btns = [
            {
                title:"去添加",
                func:function () {
                    var url = baseUrl+"/admin?c=RSAKeyManager&a=index"
                    console.log("跳转url:" + url);
                    window.location = url;
                }
            }
        ];
        showAlert("RSA公钥或私钥为空",btns);
    }
});

// 是否要跳转到rsa秘钥添加页面
function toAddRSAKey() {
    return true;
}

// 显示hud
function showHud() {
    layui.use('layer', function() {
        var layer = layui.layer;
        layer.ready(function () {
            loadIndex = layer.load(0,{
                shade: [0.6, '#FFF'],
                shadeClose: true
            });
        });
    });
};

// 隐藏hud
function hideHud() {
    layui.use('layer', function() {
        var layer = layui.layer;
        layer.ready(function () {
            layer.close(loadIndex);
        });
    });
}

/**
 * 显示提示信息
 * @param message
 */
function toast(message,success=true) {
    layui.use('layer',function () {
        var layer = layui.layer;
        if (typeof message == 'string'){
            var time = message.length * 0.3 * 1000;
            var skin = success ? "" : "toast-fail-style";
            layer.msg(message, {
                // 多长事件后自动关闭(ms)
                time: time,
                offset: 'auto',
                skin: skin
            });
        }
    });
}

// 发送网络请求
function get(url, complete=null,withHud=true,showToast=false,timeOut=100000) {
    request(url,null,complete,withHud,showToast,timeOut);
}

// 发送请求
function request(url, data=null, complete=null,withHud=true,showToast=false,timeOut=100000) {
    if (withHud){
        showHud();
    }

    var method = data == null ? "GET" : "'POST'";

    console.log("方法:" + method);

    $.ajax({
        type : method,
        url : url,
        data: data,
        cache : false,
        async : true,
        timeout: timeOut,
        dataType : "json",
        complete: function () {
            console.log("加载完成");
            hideHud();
        }
        ,success: function (data,state,xhr) {
            var message = typeof(data.data) == 'string' ? data.data : data.message;
            if (data.code == 200){
                showToast ? toast(message) : "";
                complete ? complete(data) : "";

            }else if(data.code != 200){
                // 提示失败信息
                toast(message,false);
            }
        },
        error: function (xhr,textStatus,errorMessage) {
            if (showToast){
                toast("请求服务器失败");
            }
            console.log("请求失败了偶尔Lee:" + errorMessage);
        }
    });
}

// 提示框
function showAlert(message,btns=[{title:"好的",func:function () {
        // 关闭layer
        layer.close(index);
    }}]) {
    var btnTitles = [];
    var btnFunctions = [];
    for (var i = 0; i < btns.length; i++){
        var btn = btns[i];
        btnTitles[i] = btn["title"];
        btnFunctions[i] = btn["func"];
    }

    layui.use('layer',function () {
        var layer = layui.layer;
        layer.open({
            type: 1
            ,title: false //不显示标题栏
            ,closeBtn: false
            ,area: '300px;'
            ,shade: 0.8
            ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
            ,btn: btnTitles
            ,btnAlign: 'c'
            ,moveType: 1 //拖拽模式，0或者1
            ,content: '<div style="padding: 50px; line-height: 22px; background-color: #393D49; color: #fff; font-weight: 300;">'+message+'</div>'
            ,yes: function(index, layero){
                // 按钮1回调
                console.log("索引:" + index);
                if (index <= btnFunctions.length){
                    btnFunctions[index - 1]();
                }
                // 关闭layer
                layer.close(index);
            }
            ,btn2:function (index,layero) {
                console.log("索引:" + index);
                if (index<btnFunctions.length){
                    btnFunctions[index - 1]();
                }
                // 关闭layer
                layer.close(index);
            }
            ,btn3:function (index,layero) {
                console.log("索引:" + index);
                if (index<btnFunctions.length){
                    btnFunctions[index - 1]();
                }
                // 关闭layer
                layer.close(index);
            }
            ,btn4:function (index,layero) {
                console.log("索引:" + index);
                if (index<btnFunctions.length){
                    btnFunctions[index - 1]();
                }
                // 关闭layer
                layer.close(index);
            }
            ,btn5:function (index,layero) {
                console.log("索引:" + index);
                if (index<btnFunctions.length){
                    btnFunctions[index - 1]();
                }
                // 关闭layer
                layer.close(index);
            }
        });
    });
}

layui.use('form',function () {
    var form = layui.form;
    form.render();
});

// 复制输入框文本到剪切板
function copyInputContent(inputDom,message="复制成功") {
    inputDom.select();
    document.execCommand("Copy");
    toast(message);
}