var browser={
    versions:function(){
        var u = navigator.userAgent, app = navigator.appVersion;
        return {//移动终端浏览器版本信息
            trident: u.indexOf('Trident') > -1, //IE内核
            presto: u.indexOf('Presto') > -1, //opera内核
            webKit: u.indexOf('AppleWebKit') > -1, //苹果、谷歌内核
            gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1, //火狐内核
            mobile: !!u.match(/AppleWebKit.*Mobile.*/)||!!u.match(/AppleWebKit/), //是否为移动终端
            ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
            android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1, //android终端或者uc浏览器
            iPhone: u.indexOf('iPhone') > -1 || u.indexOf('Mac') > -1, //是否为iPhone或者QQHD浏览器
            iPad: u.indexOf('iPad') > -1, //是否iPad
            webApp: u.indexOf('Safari') == -1, //是否web应该程序，没有头部与底部
            qq:u.indexOf('MQQBrowser') > -1,
            uc:u.indexOf('UCBrowser') > -1,
        };
    }(),
    language:(navigator.browserLanguage || navigator.language).toLowerCase()
};
function open_download()
{
    $("#TB_overlay").show();
    $(".xiazai_p").show();
    _czc.push(["_trackEvent","延伸图集","显示下载浮层","显示下载浮层"]);
}
function close_download()
{
    $("#TB_overlay").hide();
    $(".xiazai_p").hide();
}
$(document).ready(function(){
    $("#page_guid").click(function(){
        $("#page_list").toggle();
    });
    $("#TB_overlay").click(function(){
        close_download();
    });
    if(browser.versions.android){
        $(".zhengwen img").each(function(){
            $(this).parent().attr('style','text-align:center; position:relative;');
            var img_src = $(this).attr('src');
            $(this).parent().html('<a href="javascript:open_download();"><b class="tuji">延伸图集</b><img border="0" src="'+img_src+'" /></a>');
        });
    }
});
var bottom_div_status = 0;
window.onscroll = function(){
    var scrollTop = document.body.scrollTop || document.documentElement.scrollTop || 0;

    var vtop = $(document).scrollTop();
    var dheight = $(window).height();
    if(vtop > dheight){
        $(".fanhui").show();
    }else{
        $(".fanhui").hide();
    }
    var titleheight = $('.zhengwen').offset().top;
    if(vtop > titleheight && bottom_div_status == 0){
        $('#bottom_div').show();
    }else{
        $('#bottom_div').hide();
    }
};

