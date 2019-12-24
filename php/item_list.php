<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(-1);
require_once 'Config.php';

Config::_setAnchor();

$uid = Config::_uidInit();

$category = '';

if(array_key_exists('category',$_GET)){
    $category = $_GET["category"];
};

$goodList = Config::_goodList($uid);

?>

<!DOCTYPE html>
<html lang="zh_CN">
<head>
    <meta charset="UTF-8">
    <title>商品分类列表</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0"/>
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js" ></script>
    <link rel="stylesheet" type="text/css" href="../css/aui.css" />
    <link rel="stylesheet" type="text/css" href="../css/iconfont.css">
    <script type="text/javascript" src="../script/aui-toast.js" ></script>
    <script src="../js/jquery.fly.min.js"></script>
    <style type="text/css">
        .aui-content-padded {
            padding: 0.75rem;
            background-color: #ffffff;
            margin-top: 0.75rem;
        }

        .aui-col-xs-5{
            width:160px;
            height:225px;
            margin-right: 10px;
            margin-top: 1rem;
            padding-left: 15px;
        }
        .good_img,.good_img img{
            width:160px;height:160px;
        }
        .good_info_title.aui-col-xs-10{
            margin-top: 0.2rem;
        }
        .good_info_title.aui-col-xs-10 a{
            font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
            font-size: 12px;
            color: #51562b;
        }
        .good_img{
            /*border:1px solid #d1d1d1;*/
            /*-moz-box-shadow:1px 1px 5px #d1d1d1; -webkit-box-shadow:1px 1px 5px #d1d1d1; box-shadow:1px 1px 5px #d1d1d1;*/
        }
        .cart.aui-col-xs-3{
            width:20px;
            height:20px;
            margin-top:25px;
            margin-left:0px;
        }
        .comingsoon.aui-col-xs-3{
            width:20px;
            height:20px;
            margin-top:25px;
            margin-left:0px;
        }
        .img{
            width:20px;height:20px;
        }
    </style>
</head>
<body>


<!--<section class="aui-refresh-content">-->
<!--    <div class="aui-tips" id="tips-1">-->
<!--        <i class="aui-iconfont aui-icon-info"></i>-->
<!--        <div class="aui-tips-title">下拉刷新查看更多哦~</div>-->
<!--        <i class="aui-iconfont aui-icon-close" tapmode onclick="closeTips()"></i>-->
<!--    </div>-->
    <header class="aui-bar aui-bar-nav">
        <a class="aui-pull-left aui-btn" id="Back">
            <span class="aui-iconfont aui-icon-left"></span>
        </a>
        <div class="aui-title" style="left:2rem; right: 0.5rem;">
            <div class="aui-searchbar" id="search">
                <div class="aui-searchbar-input aui-border-radius">
                    <i class="aui-iconfont aui-icon-search"></i>
                    <input type="search" placeholder="请输入搜索内容" id="search-input">
                    <div class="aui-searchbar-clear-btn">
                        <i class="aui-iconfont aui-icon-close"></i>
                    </div>
                </div>
                <div class="aui-searchbar-btn" tapmode>取消</div>
            </div>
        </div>
    </header>

<div class="aui-tab" id="tab">
    <?php if($category==1){?>
    <div class="aui-tab-item aui-active">口红</div>
    <div class="aui-tab-item">腮红</div>
    <div class="aui-tab-item">眉眼</div>
    <div class="aui-tab-item">底妆</div>
    <div class="aui-tab-item">其他</div>
    <?php }?>
    <?php if($category==2){?>
        <div class="aui-tab-item aui-active">线香</div>
        <div class="aui-tab-item">炼香</div>
        <div class="aui-tab-item">香具</div>
        <div class="aui-tab-item">精油</div>
        <div class="aui-tab-item">其他</div>
    <?php }?>
</div>

<!--    <div class="aui-content">-->
<!--        <div id="List">-->
            <li class="aui-list-item" id="good_list" style="background-color: #f5f5f5">
                <section id="List" class="aui-content"  style="background-color: #ffffff">
                </section>
            </li>
            <li class="aui-list-item" style="background-color: #f5f5f5"></li>
            <li class="aui-list-item" style="background-color: #f5f5f5"></li>


<!--        </div>-->
<!--    </div>-->


<!--</section>-->

<footer class="aui-bar aui-bar-tab aui-border-t" id="footer">

    <div class="aui-bar-tab-item aui-active" tapmode >
        <i class="aui-iconfont aui-icon-home"></i>
        <div class="aui-bar-tab-label">商城</div>
    </div>
    <div class="aui-bar-tab-item" tapmode id="Cart">
        <div class="aui-badge" style="display: none"></div>
        <i class="aui-iconfont aui-icon-cart"></i>
        <div class="aui-bar-tab-label">购物车</div>
    </div>
    <div class="aui-bar-tab-item" tapmode >
        <!--                <div class="aui-dot"></div>-->
        <i class="iconfont icon-networking" style="font-size:21px"></i>
        <div class="aui-bar-tab-label">美丽分享</div>
    </div>
    <div class="aui-bar-tab-item" tapmode>
        <?php
        if(Config::_haveOrder_($uid)){
            ?>
            <div class="aui-dot"></div>
        <?php } ?>
        <i class="aui-iconfont aui-icon-my"></i>
        <div class="aui-bar-tab-label">我的</div>
    </div>
</footer>

</body>


<script type="text/javascript" src="../script/aui-tab.js" ></script>
<script type="text/javascript">

    function refreshList(subCategory) {
        $('#List').html("");
        $.getJSON("../php/GoodsList.php?category=<?php echo $category?>&subCategory="+subCategory,function(result){
            if(result == 300){$('#List').html("新产品即将上市，尽请期待！"); return;}
                var html='';
                $.each(result, function(i, field){
                    if(field['comingSoon'] == 1){
                        html += '<div class="aui-col-xs-5" style="background-color: #ffffff">'+
                            '<div class="good_img">'+
                            '<img class="goodImg" src="'+field['thumbnail']+'">'+
                            '</div>'+
                            '<div class="good_info_title aui-col-xs-10">'+
                            '<a class="good_name"><span style="color: red">即将上市 </span>'+field['name']+'</a><br>'+
                            '<a class="good_price">￥'+field['promotionPrice']+'</a>'+
                            '</div>'+
                            '<div class="comingsoon aui-col-xs-3" style="display: none;">'+
                            field['goodId']+
                            '</div>'+
                            '</div>';
                    }else{
                        html += '<div class="aui-col-xs-5" style="background-color: #ffffff">'+
                            '<div class="good_img">'+
                            '<img class="goodImg" src="'+field['thumbnail']+'">'+
                            '</div>'+
                            '<div class="good_info_title aui-col-xs-10">'+
                            '<a class="good_name">'+field['name']+'</a><br>'+
                            '<a class="good_price">￥'+field['promotionPrice']+'</a>'+
                            '</div>'+
                            '<div class="cart aui-col-xs-3" >'+
                            '<img src="../image/UI/cart.png" class="img" name="'+field['goodId']+'">'+
                            '</div>'+
                            '</div>';
                    }


                });
            var html0='<div class="aui-col-xs-5" style="background-color: #ffffff"><br><div class="aui-col-xs-5" style="background-color: #ffffff">';
            html+=html0
            $('#List').html(html);
        });
    }

    if(<?php echo sizeof($goodList)?>>0){
        $('.aui-badge').show();
        $('.aui-badge').html(<?php echo sizeof($goodList)?>);
    }


    apiready = function(){
        api.parseTapmode();
    }
    var tab = new auiTab({
        element:document.getElementById("tab"),
    },function(ret){
        // console.log(ret);
        switch(ret.index)
        {
            case 1:
                refreshList(1);
                break;
            case 2:
                refreshList(2);
                break;
            case 3:
                refreshList(3);
                break;
            case 4:
                refreshList(4);
                break;
            case 5:
                refreshList(5);
                break;
            default:
                refreshList(1);

        }
    });
</script>
<script src="../script/aui-pull-refresh.js"></script>
<script type="text/javascript">

    $("#Back").click(function () {
        $(location).attr('href', '/');
    })


    //动态绑定一定需要有父节点
    $('#List').on('click','.good_img',function(){
        var _index = $(this).index('.good_img');
        var goodId = $(".img").eq(_index).attr('name') || $(".comingsoon.aui-col-xs-3").eq(_index).html();
        $(location).attr('href', './GoodInfo.php?fuid=<?php echo $uid?>&goodId='+goodId+'&category='+<?php echo $category?>);
    })



    refreshList(1);





    //Cart icon fly to the Cart.
    var offset = $("#Cart").offset();

    //动态绑定一定需要有父节点
    $('#List').on('click','.cart.aui-col-xs-3',function(event){
        var _index = $(this).index('.cart.aui-col-xs-3');
        var img = $('.cart.aui-col-xs-3').eq(_index).find('img').attr('src');
        var flyer = $('<img class="u-flyer" src="'+img+'">');
        flyer.fly({
            start: {
                // left: event.pageX-30,
                // top: event.pageY-30,
                left: event.screenX-30,
                top: event.screenY-80,
                width: 0,
                height: 0
            },
            end: {
                left: offset.left+50,
                top: offset.top,
                width: 1,
                height: 1
            },
            onEnd: function(){
                this.destory();
                //add the good to the cart.
                var toast = new auiToast();
                var goodId = $('.cart.aui-col-xs-3').eq(_index).find('img').attr('name');
                $.getJSON("./EnterCart.php?uid=<?php echo $uid?>&goodId="+goodId, function(data) {
                    if(data.state == <?php echo Config::OK?>){
                        $('.aui-badge').show();
                        $('.aui-badge').html(data.result[0].goodIdSum);
                        toast.custom({
                            title:"已加入购物车",
                            html:'<i class="aui-iconfont aui-icon-laud"></i>',
                            duration:1000
                        });
                    }else{
                        toast.fail({
                            title:"加入购物车失败",
                            duration:2000
                        });
                    }
                });
            }
        });
    })




</script>




<script type="text/javascript">
    apiready = function(){
        api.parseTapmode();
    }
    var searchBar = document.querySelector(".aui-searchbar");
    var searchBarInput = document.querySelector(".aui-searchbar input");
    var searchBarBtn = document.querySelector(".aui-searchbar .aui-searchbar-btn");
    var searchBarClearBtn = document.querySelector(".aui-searchbar .aui-searchbar-clear-btn");
    if(searchBar){
        searchBarInput.onclick = function(){
            searchBarBtn.style.marginRight = 0;
        }
        searchBarInput.oninput = function(){
            if(this.value.length){
                searchBarClearBtn.style.display = 'block';
                searchBarBtn.classList.add("aui-text-info");
                searchBarBtn.textContent = "搜索";
            }else{
                searchBarClearBtn.style.display = 'none';
                searchBarBtn.classList.remove("aui-text-info");
                searchBarBtn.textContent = "取消";
            }
        }
    }
    searchBarClearBtn.onclick = function(){
        this.style.display = 'none';
        searchBarInput.value = '';
        searchBarBtn.classList.remove("aui-text-info");
        searchBarBtn.textContent = "取消";
    }
    searchBarBtn.onclick = function(){
        var keywords = searchBarInput.value;
        if(keywords.length){
            searchBarInput.blur();
            $('#List').html("");
            $.getJSON("../php/GoodsList.php?keyword="+keywords,function(result){
                console.error(result);
                if(result == 300){$('#List').html('新产品即将上市，尽请期待！'); return;}
                var html='';
                $.each(result, function(i, field){
                    if(field['comingSoon'] == 1){
                        html += '<div class="aui-col-xs-5" style="background-color: #ffffff">'+
                            '<div class="good_img">'+
                            '<img class="goodImg" src="'+field['thumbnail']+'">'+
                            '</div>'+
                            '<div class="good_info_title aui-col-xs-10">'+
                            '<a class="good_name"><span style="color: red">即将上市 </span>'+field['name']+'</a><br>'+
                            '<a class="good_price">￥'+field['promotionPrice']+'</a>'+
                            '</div>'+
                            '<div class="comingsoon aui-col-xs-3" style="display: none;">'+
                            field['goodId']+
                            '</div>'+
                            '</div>';
                    }else{
                        html += '<div class="aui-col-xs-5" style="background-color: #ffffff">'+
                            '<div class="good_img">'+
                            '<img class="goodImg" src="'+field['thumbnail']+'">'+
                            '</div>'+
                            '<div class="good_info_title aui-col-xs-10">'+
                            '<a class="good_name">'+field['name']+'</a><br>'+
                            '<a class="good_price">￥'+field['promotionPrice']+'</a>'+
                            '</div>'+
                            '<div class="cart aui-col-xs-3" >'+
                            '<img src="../image/UI/cart.png" class="img" name="'+field['goodId']+'">'+
                            '</div>'+
                            '</div>';
                    }

                });
                var html0='<div class="aui-col-xs-5" style="background-color: #ffffff"><br><div class="aui-col-xs-5" style="background-color: #ffffff">';
                html+=html0
                $('#List').html(html);
            });

        }else{
            this.style.marginRight = "-"+this.offsetWidth+"px";
            searchBarInput.value = '';
            searchBarInput.blur();
        }
    }
</script>


<script type="text/javascript">
    apiready = function(){
        api.parseTapmode();
    }
    function closeTips(){
        $api.remove($api.byId("tips-1"));
    }

    
</script>

<script type="text/javascript" src="../script/aui-tab.js" ></script>
<script type="text/javascript">
    apiready = function(){
        api.parseTapmode();
    }
    var tab = new auiTab({
        element:document.getElementById("footer")
    },function(ret){
        // console.log(ret);

        switch(ret.index)
        {
            case 1:
                $(location).attr('href', '#');

                break;
            case 2:
                $(location).attr('href', './Cart.php?fuid=<?php echo $uid?>&uid=<?php echo $uid?>');

                break;
            case 3:
                $(location).attr('href', './Share.php?fuid=<?php echo $uid?>');

                break;
            case 4:
                $(location).attr('href', './My.php?fuid=<?php echo $uid?>');
                break;
            default:
                $(location).attr('href', '#');

        }


    });

    var tab = new auiTab({
        element:document.getElementById("footer1")
    },function(ret){
        // console.log(ret);
    });
</script>


</html>