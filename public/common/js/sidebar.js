$(document).ready(function () {
    // 追踪鼠标移动
    $(document).mousemove(function(e){
        var mouseLeft = e.pageX;
        var mouseTop = e.pageY;
        var $menu = $("#as_float_menu");
        var menuVisible = $('#aw-side').is(':visible');

        // 菜单按钮随鼠标移动
        $menu.css("top",mouseTop - 100);
        floatMenuControl($menu,mouseLeft,menuVisible);
    });

    // 菜单按钮绑定点击事件
    $(".as-menu").click(function () {
        if ($('#aw-side').is(':visible')){
            hideMenuControl();
        }else {
            showMenuControl();
        }
    })
    $("#as_float_menu").click(function () {
        if ($('#aw-side').is(':visible')){
            hideMenuControl();
        }else {
            showMenuControl();
        }
    })

    // 显示菜单栏
    function showMenuControl() {
        var $slideBar = $("#slidebar_state");

        //  显示菜单栏
        $('#aw-side').show();

        $('.aw-content-wrap, .aw-footer').removeClass('active');

        $("#as_float_menu").css("left",-55);

        $slideBar.val("0");
        $("#form_slide_bar").submit();
    }

    // 隐藏菜单栏
    function hideMenuControl() {
        var $slideBar = $("#slidebar_state");

        // 隐藏菜单
        $('#aw-side').hide();

        $('.aw-content-wrap, .aw-footer').addClass('active');

        $slideBar.val("1");
        $("#form_slide_bar").submit();
    }

    // 悬浮菜单按钮控制
    function floatMenuControl($floatMenu,mouseLeft,menuVisible) {
        if (mouseLeft <= 100 && !menuVisible){
            $floatMenu.css("left",0);
        }else {
            $floatMenu.css("left",-55);
        }
    }

    if ($("#slidebar_state_flag").val() == "1"){
        // 隐藏状态
        hideMenuControl();
    }else {
        // 显示状态
        showMenuControl();
    }
});