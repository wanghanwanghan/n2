var total = 12;
var wapurl= 'http://n1.iissbbs.com';

//点击加载下十条
function clicknextten(categoryid)
{
    var lablearr = $(".hot_nr label");

    lasttime = $($(".hot_nr label")[lablearr.length-1]).html() || 0;

    $.getJSON(wapurl+"/do.php?callback=?", {inajax:1,do:'touch', ac:'n5more',vtype:'touch',lasttime:lasttime,categoryid:categoryid}, function(data)
    {
        var html = '';

        var len = data.artlist.length;

        if(data.artlist.length==10)
        {
            for(var i=0;i<len;i++)
            {
                total ++;

                var item = data.artlist[i];

                html += '<div class="hot_nr"><div class="pic_p1"><a href="'+item.url+'"><img src="'+item.pic+'"></a></div><div class="hots_pic"><a href="'+item.url+'">'+item.subject+'</a><p><b></b>'+item.gmdate+'</p></div><div class="clear"></div><label style="display:none;" class="lbllast">'+item.pubdate+'</label></div>';
            }
        }

        $($(".jiazai")).before(html);
    });
}

function showWin()
{
    $("#bg").height($(document).height());
    $("#bg").show();
    $(".tankuang").show();
    $('.tankuang').offset({top:window.scrollY+$(window).height()/2-100});
}