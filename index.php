<?php

ini_set('date.timezone','Asia/Shanghai');
error_reporting(-1);
require_once 'php/Config.php';


if(isset($_COOKIE['uid']) && !isset($_GET['fuid'])){
    echo Config::_redirect('./index.php?fuid='.$_COOKIE['uid']);
    exit;
}


$uid=isset($_COOKIE['uid'])?$_COOKIE['uid']:"";
//$uid=array_key_exists('uid',$_COOKIE)?$_COOKIE['uid']:"";
$fatherUid=isset($_GET['fuid'])?$_GET['fuid']:"";


//新的韭菜来了！！！
if($fatherUid !='' && $uid ==''){
    $uid = Guest::RegisterWithGuest(Config::_mysql());
    if ($uid != Config::SQL_ERR ) {
            if(Config::_haveRightsToShare($fatherUid)){
                Config::_setFatherUid($uid,$fatherUid);
            }
            setcookie('uid', "$uid");//save uid at client via Cookie.
    }
}else if($fatherUid !='' && $uid !='' && !Config::_isBindUser($uid) && Config::_haveRightsToShare($fatherUid) && $fatherUid != $uid){
    Config::_setFatherUid($uid,$fatherUid);
}else if($uid ==''){
    $uid = Guest::RegisterWithGuest(Config::_mysql());
    if ($uid != Config::SQL_ERR) {
        setcookie('uid', "$uid");//save uid at client via Cookie.
    }
}

$goodList = Config::_goodList($uid);

//刷新自己的状态
$myVipId = Config::_getMyVipId($uid);
if($myVipId>0){
    config::_updateMyShareMoney($uid);
}

Config::_updateMyVipIdAndShare($uid);


?>
<!doctype html>
<html  lang="zh_CN">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>xxxxxxxx</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="manifest" href="site.webmanifest">
        <link rel="apple-touch-icon" href="icon.png">
        <!-- Place favicon.ico in the root directory -->

        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">

        <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0"/>
        <meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
        <title>xxxxxxxx商城</title>
        <link rel="stylesheet" type="text/css" href="./css/iconfont.css">
        <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js" ></script>
        <script type="text/javascript" src="../script/aui-toast.js" ></script>
        <script src="../js/jquery.fly.min.js"></script>
        <link rel="stylesheet" type="text/css" href="./css/aui.css" />
        <script src="https://cdn.bootcss.com/js-cookie/latest/js.cookie.min.js"></script>
        <style type="text/css">
            .header {
                padding: 0.5rem 0.5rem 0.5rem 0.5rem;
                background-image: url("./image/logo_back.png");
                background-repeat:no-repeat; background-size:100% 100%;-moz-background-size:100% 100%;
            }
        </style>

        <link rel="stylesheet" type="text/css" href="./css/aui-flex.css" />
        <style type="text/css">
            img {
                display: block;
                max-width: 100%;
            }
        </style>

        <style type="text/css">
            .text-light {
                color: #ffffff;
            }
        </style>

        <style type="text/css">
            .goods-title {
                color: #757575 !important;
            }
            .goods-price {
                color: #666666 !important;
                font-weight: 700;
            }
        </style>

        <link rel="stylesheet" type="text/css" href="./css/aui-slide.css" />
        <style type="text/css">

            .bg-dark {
                background: #333333 !important;
            }
            .aui-slide-node img {
                width: 100%;
                height: 100%;
            }

            .aui-list .aui-list-item{
                padding-left: 0px;
            }

            .aui-list-item-inner.aui-swipe-handle {
                overflow-x: hidden;
                position: relative;
                z-index: 1;

            }

            .good_list{
                border: 0px;
            }

            #Home-Category{
                width:auto;
                /*height:55px;*/
                margin-top: 3px;
                margin-bottom: 3px;
                margin-right: 3px;
                padding: 0px
            }

            #Category_1,#Category_2{
                width:160px;
                height:50px;
                /*background-image: url('image/UI/button.png');*/
                background-repeat:no-repeat;
                background-size:100% 100%;
                -moz-background-size:100% 100%;
            }
            #Category_1 div,#Category_2 div{
                font-size: 22px;
                color: #51562b;
                margin-top: -0.45rem;
            }
            #Category_1{
                margin-right: 10px;
                margin-left: 10px;
            }
            #Home_Show_Single_Item_1,#Home_Show_Single_Item_3{
                width:160px;
                height:225px;
                margin-right: 10px;
                margin-top: 1rem;
                padding-left: 15px;
            }
            #Home_Show_Single_Item_2,#Home_Show_Single_Item_4{
                width:160px;
                height:225px;
                margin-right: 10px;
                margin-top: 1rem;
                padding-left: 15px;
            }
            #Home_Show_Single_Item_5,#Home_Show_Single_Item_6{
                width:160px;
                height:225px;
                margin-right: 10px;
                margin-top: 1rem;
                padding-left: 15px;
            }

            #Home_Show_Single_Item_7,#Home_Show_Single_Item_8{
                width:160px;
                height:225px;
                margin-right: 10px;
                margin-top: 1rem;
                padding-left: 15px;
            }
            #Home_Show_Single_Item_9,#Home_Show_Single_Item_10{
                width:160px;
                height:225px;
                margin-right: 10px;
                margin-top: 1rem;
                padding-left: 15px;
            }

            #Home_Show_Single_Item_11,#Home_Show_Single_Item_12{
                width:160px;
                height:225px;
                margin-right: 10px;
                margin-top: 1rem;
                padding-left: 15px;
            }

            #Home_Show_Single_Item_13,#Home_Show_Single_Item_14{
                width:160px;
                height:225px;
                margin-right: 10px;
                margin-top: 1rem;
                padding-left: 15px;
            }

            #Home_Show_Single_Item_16,#Home_Show_Single_Item_17{
                width:160px;
                height:225px;
                margin-right: 10px;
                margin-top: 1rem;
                padding-left: 15px;
            }

            #Home_Show_Single_Item_18{
                width:160px;
                height:225px;
                margin-right: 10px;
                margin-top: 1rem;
                padding-left: 15px;
            }

            #Home_Show_Single_Item_15{
                width:160px;
                height:245px;
                margin-right: 10px;
                margin-top: 1rem;
                padding-left: 15px;
            }

            #Home_Show_Single_Item_Bank{
                height:50px;
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



            <div id="Slide">
                <div class="aui-slide-wrap" >
                    <div class="aui-slide-node bg-dark">
                        <img class="adImg" src="image/advs/zb01.jpg" />
                    </div>
                    <div class="aui-slide-node bg-dark">
                        <img class="adImg" src="image/advs/zb02.jpg" />
                    </div>
                    <div class="aui-slide-node bg-dark">
                        <img class="adImg" src="image/advs/zb03.jpg" />
                    </div>
                    <div class="aui-slide-node bg-dark">
                        <img class="adImg" src="image/advs/zb04.jpg" />
                    </div>
                    <div class="aui-slide-node bg-dark">
                        <img class="adImg" src="image/advs/zb05.jpg" />
                    </div>
                </div>
                <div class="aui-slide-page-wrap"><!--分页容器--></div>
            </div>

       <div class="aui-list  aui-form-list aui-margin-b-0" id="Home">


        <li class="aui-list-item">
            <section id="Home-Category" class="aui-grid" >
                <div class="aui-row" >
                    <div id="Category_1" class="aui-col-xs-6">
                        <div>彩 妆</div>
                    </div>
                    <div id="Category_2" class="aui-col-xs-6">
                        <div>和 香</div>
                    </div>
                </div>
            </section>
        </li>

           <li class="aui-list-item" id="good_list" style="background-color: #f5f5f5">
               <section  class="aui-content" style="background-color: #ffffff">
                       <div id="Home_Show_Single_Item_1" class="aui-col-xs-5" style="background-color: #ffffff">
                           <div class="good_img">
                               <img class="goodImg" src="./image/goods/6971362590017/thumbnail.jpg">
                           </div>
                            <div class="good_info_title aui-col-xs-10">
                                   <a class="good_name">绛红 冷调 晚妆 莹润滋养精油口红</a><br>
                                    <a class="good_price">￥189</a>
                            </div>
                             <div class="cart aui-col-xs-3" >
                                   <img src="../image/UI/cart.png" class="img" name="6971362590017">
                             </div>
                       </div>
                       <div id="Home_Show_Single_Item_2" class="aui-col-xs-5" style="background-color: #ffffff">
                           <div class="good_img">
                               <img class="goodImg" src="./image/goods/6971362590024/thumbnail.jpg">
                           </div>
                           <div class="good_info_title aui-col-xs-10">
                               <a class="good_name">XXXXXXX</a><br>
                               <a class="good_price">￥189</a>
                           </div>
                           <div class="cart aui-col-xs-3" >
                               <img src="../image/UI/cart.png" class="img" name="6971362590024">
                           </div>
                       </div>

                       <div id="Home_Show_Single_Item_3" class="aui-col-xs-5" style="background-color: #ffffff">
                           <div class="good_img">
                               <img class="goodImg" src="./image/goods/6971362590031/thumbnail.jpg">
                           </div>
                           <div class="good_info_title aui-col-xs-10">
                               <a class="good_name">XXXXXXX</a><br>
                               <a class="good_price">￥189</a>
                           </div>
                           <div class="cart aui-col-xs-3" >
                               <img src="../image/UI/cart.png" class="img" name="6971362590031">
                           </div>
                       </div>
                       <div id="Home_Show_Single_Item_4" class="aui-col-xs-5" style="background-color: #ffffff">
                           <div class="good_img">
                               <img class="goodImg" src="./image/goods/6971362590048/thumbnail.jpg">
                           </div>
                           <div class="good_info_title aui-col-xs-10">
                               <a class="good_name">XXXXXXX</a><br>
                               <a class="good_price">￥189</a>
                           </div>
                           <div class="cart aui-col-xs-3" >
                               <img src="../image/UI/cart.png" class="img" name="6971362590048">
                           </div>
                       </div>
                       <div id="Home_Show_Single_Item_5" class="aui-col-xs-5" style="background-color: #ffffff">
                           <div class="good_img">
                               <img class="goodImg" src="./image/goods/6971362590055/thumbnail.jpg">
                           </div>
                           <div class="good_info_title aui-col-xs-10">
                               <a class="good_name">XXXXXXX</a><br>
                               <a class="good_price">￥189</a>
                           </div>
                           <div class="cart aui-col-xs-3" >
                               <img src="../image/UI/cart.png" class="img" name="6971362590055">
                           </div>
                       </div>
                       <div id="Home_Show_Single_Item_6" class="aui-col-xs-5" style="background-color: #ffffff">
                           <div class="good_img">
                               <img class="goodImg" src="./image/goods/6971362590062/thumbnail.jpg">
                           </div>
                           <div class="good_info_title aui-col-xs-10">
                               <a class="good_name">XXXXXXX</a><br>
                               <a class="good_price">￥189</a>
                           </div>
                           <div class="cart aui-col-xs-3" >
                               <img src="../image/UI/cart.png" class="img" name="6971362590062">
                           </div>
                       </div>

                   <div id="Home_Show_Single_Item_7" class="aui-col-xs-5" style="background-color: #ffffff">
                       <div class="good_img">
                           <img class="goodImg" src="./image/goods/6971362590091/thumbnail.jpg">
                       </div>
                       <div class="good_info_title aui-col-xs-10">
                           <a class="good_name">XXXXXXX<br>
                           <a class="good_price">￥149</a>
                       </div>
                       <div class="cart aui-col-xs-3" >
                           <img src="../image/UI/cart.png" class="img" name="6971362590091">
                       </div>
                   </div>

                   <div id="Home_Show_Single_Item_8" class="aui-col-xs-5" style="background-color: #ffffff">
                       <div class="good_img">
                           <img class="goodImg" src="./image/goods/6971362590092/thumbnail.jpg">
                       </div>
                       <div class="good_info_title aui-col-xs-10">
                           <a class="good_name">XXXXXXX</a><br>
                           <a class="good_price">￥139</a>
                       </div>
                       <div class="cart aui-col-xs-3" >
                           <img src="../image/UI/cart.png" class="img" name="6971362590092">
                       </div>
                   </div>


                   <div id="Home_Show_Single_Item_10" class="aui-col-xs-5" style="background-color: #ffffff">
                       <div class="good_img">
                           <img class="goodImg" src="./image/goods/6971362590101/thumbnail.jpg">
                       </div>
                       <div class="good_info_title aui-col-xs-10">
                           <a class="good_name">XXXXXXX</a><br>
                           <a class="good_price">￥169</a>
                       </div>
                       <div class="cart aui-col-xs-3" >
                           <img src="../image/UI/cart.png" class="img" name="6971362590101">
                       </div>
                   </div>

                   <div id="Home_Show_Single_Item_16" class="aui-col-xs-5" style="background-color: #ffffff">
                       <div class="good_img">
                           <img class="goodImg" src="./image/goods/6971362590102/thumbnail.jpg">
                       </div>
                       <div class="good_info_title aui-col-xs-10">
                           <a class="good_name">XXXXXXX</a><br>
                           <a class="good_price">￥178</a>
                       </div>
                       <div class="cart aui-col-xs-3" >
                           <img src="../image/UI/cart.png" class="img" name="6971362590102">
                       </div>
                   </div>

                   <div id="Home_Show_Single_Item_17" class="aui-col-xs-5" style="background-color: #ffffff">
                       <div class="good_img">
                           <img class="goodImg" src="./image/goods/6971362590103/thumbnail.jpg">
                       </div>
                       <div class="good_info_title aui-col-xs-10">
                           <a class="good_name">XXXXXXX</a><br>
                           <a class="good_price">￥198</a>
                       </div>
                       <div class="cart aui-col-xs-3" >
                           <img src="../image/UI/cart.png" class="img" name="6971362590103">
                       </div>
                   </div>

                   <div id="Home_Show_Single_Item_18" class="aui-col-xs-5" style="background-color: #ffffff">
                       <div class="good_img">
                           <img class="goodImg" src="./image/goods/6971362590104/thumbnail.jpg">
                       </div>
                       <div class="good_info_title aui-col-xs-10">
                           <a class="good_name">XXXXXXX</a><br>
                           <a class="good_price">￥189</a>
                       </div>
                       <div class="cart aui-col-xs-3" >
                           <img src="../image/UI/cart.png" class="img" name="6971362590104">
                       </div>
                   </div>



                   <div id="Home_Show_Single_Item_11" class="aui-col-xs-5" style="background-color: #ffffff">
                       <div class="good_img">
                           <img class="goodImg" src="./image/goods/6971362590093/thumbnail.jpg">
                       </div>
                       <div class="good_info_title aui-col-xs-10">
                           <a class="good_name">XXXXXXX</a><br>
                           <a class="good_price">￥300</a>
                       </div>
                       <div class="cart aui-col-xs-3" >
                           <img src="../image/UI/cart.png" class="img" name="6971362590093">
                       </div>
                   </div>

                   <div id="Home_Show_Single_Item_12" class="aui-col-xs-5" style="background-color: #ffffff">
                       <div class="good_img">
                           <img class="goodImg" src="./image/goods/6971362590109/thumbnail.jpg">
                       </div>
                       <div class="good_info_title aui-col-xs-10">
                           <a class="good_name">XXXXXXX</a><br>
                           <a class="good_price">￥260</a>
                       </div>
                       <div class="cart aui-col-xs-3" >
                           <img src="../image/UI/cart.png" class="img" name="6971362590109">
                       </div>
                   </div>



                   <div id="Home_Show_Single_Item_13" class="aui-col-xs-5" style="background-color: #ffffff">
                       <div class="good_img">
                           <img class="goodImg" src="./image/goods/6971362590116/thumbnail.jpg">
                       </div>
                       <div class="good_info_title aui-col-xs-10">
                           <a class="good_name">XXXXXXX</a><br>
                           <a class="good_price">￥280</a>
                       </div>
                       <div class="cart aui-col-xs-3" >
                           <img src="../image/UI/cart.png" class="img" name="6971362590116">
                       </div>
                   </div>

                   <div id="Home_Show_Single_Item_14" class="aui-col-xs-5" style="background-color: #ffffff">
                       <div class="good_img">
                           <img class="goodImg" src="./image/goods/6971362590123/thumbnail.jpg">
                       </div>
                       <div class="good_info_title aui-col-xs-10">
                           <a class="good_name">XXXXXXX</a><br>
                           <a class="good_price">￥310</a>
                       </div>
                       <div class="cart aui-col-xs-3" >
                           <img src="../image/UI/cart.png" class="img" name="6971362590123">
                       </div>
                   </div>

                   <div id="Home_Show_Single_Item_15" class="aui-col-xs-5" style="background-color: #ffffff">
                       <div class="good_img">
                           <img class="goodImg" src="./image/goods/6971362590130/thumbnail.jpg">
                       </div>
                       <div class="good_info_title aui-col-xs-10">
                           <a class="good_name">XXXXXXX</a><br>
                           <a class="good_price">￥320</a>
                       </div>
                       <div class="cart aui-col-xs-3" >
                           <img src="../image/UI/cart.png" class="img" name="6971362590130">
                       </div>
                   </div>





                   <div id="Home_Show_Single_Item_Bank" class="aui-col-xs-5">
                   </div>
               </section>
           </li>

           <li class="aui-list-item" style="background-color: #f5f5f5">
           </li>
           <li class="aui-list-item" style="background-color: #f5f5f5">
           </li>

    </div>



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
                <i class="iconfont icon-networking" style="font-size:21px"></i>
                <div class="aui-bar-tab-label">分享</div>
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



    <script type="text/javascript" src="./script/api.js"></script>
    <script type="text/javascript">

        function replaceParamVal(paramName,replaceWith) {
            var oUrl = this.location.href.toString();
            var re=eval('/('+ paramName+'=)([^&]*)/gi');
            var nUrl = oUrl.replace(re,paramName+'='+replaceWith);
            this.location = nUrl;
            window.location.href=nUrl
        }

        function _changeUrlArg(url,arg,val){
            var pattern = arg+'=([^&]*)';
            var replaceText = arg+'='+val;
            return url.match(pattern) ? url.replace(eval('/('+ arg+'=)([^&]*)/gi'), replaceText) : (url.match('[\?]') ? url+'&'+replaceText : url+'?'+replaceText);
        }


        $('.goodImg').each(function(index,element){
            var _src = $('.goodImg').eq(index).attr('src');
            $('.goodImg').eq(index).attr('src',_src+"?rand="+Math.random());
        });

        $('.adImg').each(function(index,element){
            var _src = $('.adImg').eq(index).attr('src');
            $('.adImg').eq(index).attr('src',_src+"?rand="+Math.random());
        });

        //set cookie at browser
        if('<?php echo $uid?>' != '' && Cookies.get('uid') == null) {
            // console.error('cookie have not uid and uid is not null, so set');
            Cookies.set('uid', '<?php echo $uid?>');
            //window.location.href=_changeUrlArg(this.location.href.toString(),'fuid','<?php //echo $uid?>//');
        }
        //}else if('<?php //echo $uid?>//' != ''){
        //    window.location.href=_changeUrlArg(this.location.href.toString(),'fuid','<?php //echo $uid?>//');
        //}


        apiready = function(){
            api.setStatusBarStyle({
                style: 'light'
            });
            api.parseTapmode();
            var header = $api.byId('header');
            var headerPos = $api.offset(header);
            var body_h = $api.offset($api.dom('body')).h;
            var header_h = $api.offset($api.byId('header')).h;
            api.openFrame({
                name: 'index_frm',
                url: './html/index_frm.html',
                bounces: true,
                rect: {
                    x: 0,
                    y: header_h,
                    w: 'auto',
                    h: 'auto'
                }
            })
        };

    </script>


    <script type="text/javascript" src="./script/aui-tab.js" ></script>
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
                    $(location).attr('href', './php/Cart.php?fuid=<?php echo $uid?>&uid=<?php echo $uid?>');

                    break;
                case 3:
                    $(location).attr('href', './php/Share.php?fuid=<?php echo $uid?>');

                    break;
                case 4:
                    $(location).attr('href', './php/My.php?fuid=<?php echo $uid?>');
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




    <script type="text/javascript">

            $("#Home_Show_Single_Item_1 div:first-child").click(function(){
                $(location).attr('href', './php/GoodInfo.php?fuid=<?php echo $uid?>&goodId=6971362590017');
            });
            $("#Home_Show_Single_Item_2 div:first-child").click(function(){
                $(location).attr('href', './php/GoodInfo.php?fuid=<?php echo $uid?>&goodId=6971362590024');
            });
            $("#Home_Show_Single_Item_3 div:first-child").click(function(){
                $(location).attr('href', './php/GoodInfo.php?fuid=<?php echo $uid?>&goodId=6971362590031');
            });
            $("#Home_Show_Single_Item_4 div:first-child").click(function(){
                $(location).attr('href', './php/GoodInfo.php?fuid=<?php echo $uid?>&goodId=6971362590048');
            });
            $("#Home_Show_Single_Item_5 div:first-child").click(function(){
                $(location).attr('href', './php/GoodInfo.php?fuid=<?php echo $uid?>&goodId=6971362590055');
            });

            $("#Home_Show_Single_Item_6 div:first-child").click(function(){
                $(location).attr('href', './php/GoodInfo.php?fuid=<?php echo $uid?>&goodId=6971362590062');
            });

            $("#Home_Show_Single_Item_7 div:first-child").click(function(){
                $(location).attr('href', './php/GoodInfo.php?fuid=<?php echo $uid?>&goodId=6971362590091');
            });
            $("#Home_Show_Single_Item_8 div:first-child").click(function(){
                $(location).attr('href', './php/GoodInfo.php?fuid=<?php echo $uid?>&goodId=6971362590092');
            });
            $("#Home_Show_Single_Item_9 div:first-child").click(function(){
                $(location).attr('href', './php/GoodInfo.php?fuid=<?php echo $uid?>&goodId=6971362590094');
            });
            $("#Home_Show_Single_Item_10 div:first-child").click(function(){
                $(location).attr('href', './php/GoodInfo.php?fuid=<?php echo $uid?>&goodId=6971362590101');
            });
            $("#Home_Show_Single_Item_11 div:first-child").click(function(){
                $(location).attr('href', './php/GoodInfo.php?fuid=<?php echo $uid?>&goodId=6971362590093');
            });
            $("#Home_Show_Single_Item_12 div:first-child").click(function(){
                $(location).attr('href', './php/GoodInfo.php?fuid=<?php echo $uid?>&goodId=6971362590109');
            });
            $("#Home_Show_Single_Item_13 div:first-child").click(function(){
                $(location).attr('href', './php/GoodInfo.php?fuid=<?php echo $uid?>&goodId=6971362590116');
            });
            $("#Home_Show_Single_Item_14 div:first-child").click(function(){
                $(location).attr('href', './php/GoodInfo.php?fuid=<?php echo $uid?>&goodId=6971362590123');
            });
            $("#Home_Show_Single_Item_15 div:first-child").click(function(){
                $(location).attr('href', './php/GoodInfo.php?fuid=<?php echo $uid?>&goodId=6971362590130');
            });
            $("#Home_Show_Single_Item_16 div:first-child").click(function(){
                $(location).attr('href', './php/GoodInfo.php?fuid=<?php echo $uid?>&goodId=6971362590102');
            });
            $("#Home_Show_Single_Item_17 div:first-child").click(function(){
                $(location).attr('href', './php/GoodInfo.php?fuid=<?php echo $uid?>&goodId=6971362590103');
            });
            $("#Home_Show_Single_Item_18 div:first-child").click(function(){
                $(location).attr('href', './php/GoodInfo.php?fuid=<?php echo $uid?>&goodId=6971362590104');
            });
            if(<?php echo sizeof($goodList)?>>0){
                $('.aui-badge').show();
                $('.aui-badge').html(<?php echo sizeof($goodList)?>);
            }


            //Cart icon fly to the Cart.
            var offset = $("#Cart").offset();
            $(".cart.aui-col-xs-3").click(function(event){
                var addcar = $(this);
                var img = addcar.find('img').attr('src');
                // var _index = $(this).index();
                // var img = $('.goodImg').eq(_index).attr('src');
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
                        var goodId = addcar.find('img').attr('name');
                        $.getJSON("./php/EnterCart.php?uid=<?php echo $uid?>&goodId="+goodId, function(data) {
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


            });


    </script>





    <script type="text/javascript">

        $("#Category_1").click(function(){
            $(location).attr('href', '../php/item_list.php?fuid=<?php echo $uid?>&category=1');
        });
        $("#Category_2").click(function(){
            $(location).attr('href', '../php/item_list.php?fuid=<?php echo $uid?>&category=2');
        });
    </script>




    <script type="text/javascript" src="./script/aui-slide.js"></script>
    <script type="text/javascript">

        var slide3 = new auiSlide({
            container:document.getElementById("Slide"),
            // "width":300,
            "height":190,
            "speed":500,
            "autoPlay": 3000, //自动播放
            "loop":true,
            "pageShow":true,
            "pageStyle":'line',
            'dotPosition':'center'
        })
        // console.log(slide3.pageCount());
    </script>



</html>

