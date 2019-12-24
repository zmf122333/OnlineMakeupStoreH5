<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(-1);
require_once 'Config.php';

Config::_setAnchor();

$uid = Config::_uidInit();

//$fatherUid=isset($_GET['fuid'])?$_GET['fuid']:"";
//
////新的韭菜来了！！！
//if($fatherUid !='' && $uid ==''){
//   echo Config::_redirect(Config::$_mainPage."/?fuid=".$fatherUid);
//   exit;
//}
//if($fatherUid !='' && !Config::_isBindUser($uid)){
//    echo Config::_redirect(Config::$_mainPage."/?fuid=".$fatherUid);
//    exit;
//}
$goodList = Config::_goodList($uid);


?>
<!DOCTYPE html>
<html lang="zh_CN">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0"/>
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js" ></script>
    <link rel="stylesheet" type="text/css" href="../css/aui.css" />
    <link rel="stylesheet" type="text/css" href="../css/iconfont.css">
    <link rel="stylesheet" type="text/css" href="../css/iconfont1.css">
    <title>欢迎光临！我的妙颜彩妆商城：</title>
</head>
<body>
<header class="aui-bar aui-bar-nav">
    <a class="aui-pull-left aui-btn" id="Back">
    <span class="aui-iconfont aui-icon-left">返回</span>
    </a>我的二维码
</header>
<section class="aui-content" id="user-info">
<!--    <p style="color: orangered; font-size:20px; margin-top:5%; margin-left: 30%; margin-bottom: -5%">美丽分享官：--><?php //echo Config::_getUserName($uid)?><!--</p>-->
    <div>
        <img style="margin: 30px; margin-left:15%" src="../image/qrcode/<?php echo $uid?>.png">
    </div>
    <div class="aui-col-xs-12" style="padding-left:50px;padding-right:40px">
<!--        <div class="aui-btn aui-btn-primary aui-btn-block" id="withdraw" onclick="openSharebox()">分 享</div>-->
        <!--MOB SHARE BEGIN-->
        <div class="-mob-share-ui-button -mob-share-open">美 丽 分 享</div>
        <div class="-mob-share-ui" style="display: none">
            <ul class="-mob-share-list">
                <li class="-mob-share-weibo"><p>新浪微博</p></li>
                <li class="-mob-share-qzone"><p>QQ空间</p></li>
                <li class="-mob-share-douban"><p>豆瓣</p></li>
<!--                <li class="-mob-share-evernote"><p>印象笔记</p></li>-->
<!--                <li class="-mob-share-youdao"><p>有道笔记</p></li>-->
<!--                <li class="-mob-share-tieba"><img src="/image/tieba.png"><p>百度贴吧</p></li>-->
                <li class="-mob-share-facebook"><p style="margin-bottom:-13px;">Facebook<span style="margin-top:13px;color:#9e9e9e;font-size: 0.35rem"><br>海外用户</span></p></li>
                <li class="-mob-share-twitter"><p style="margin-bottom:-13px;">Twitter<span style="margin-top:13px;color:#9e9e9e;font-size: 0.35rem"><br>海外用户</span></p></li>
                <li class="-mob-share-google"><p style="margin-bottom:-13px;">google<span style="margin-top:13px;color:#9e9e9e;font-size: 0.35rem"><br>海外用户</span></p></li>
                <li class="-mob-share-pinterest"><p style="margin-bottom:-13px;">pinterest<span style="margin-top:13px;color:#9e9e9e;font-size: 0.35rem"><br>海外用户</span></p></li>
                <li class="-mob-share-pocket"><p style="margin-bottom:-13px;">pocket<span style="margin-top:13px;color:#9e9e9e;font-size: 0.35rem"><br>海外用户</span></p></li>

            </ul>
            <div class="-mob-share-close">取消</div>
        </div>
        <div class="-mob-share-ui-bg"></div>
        <script id="-mob-share" src="http://f1.webshare.mob.com/code/mob-share.js?appkey=XXXXXX"></script>
        <!--MOB SHARE END-->
    </div>
</section>






<footer class="aui-bar aui-bar-tab aui-border-t" id="footer">
    <div class="aui-bar-tab-item" tapmode >
        <i class="aui-iconfont aui-icon-home"></i>
        <div class="aui-bar-tab-label">商城</div>
    </div>
    <div class="aui-bar-tab-item " tapmode >
        <?php
        if(sizeof($goodList) != 0){
            ?>
            <div class="aui-badge"><?php echo sizeof($goodList)?></div>
        <?php } ?>
        <i class="aui-iconfont aui-icon-cart"></i>
        <div class="aui-bar-tab-label">购物车</div>
    </div>
    <div class="aui-bar-tab-item" tapmode >
<!--        <div class="aui-dot"></div>-->
        <i class="iconfont icon-networking" style="font-size:21px"></i>
        <div class="aui-bar-tab-label">美丽分享</div>
    </div>
    <div class="aui-bar-tab-item aui-active" tapmode>
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


<script type="text/javascript">
    $("#Back").click(function () {
        $(location).attr('href', './Share.php?fuid=<?php echo $uid?>');
    })
</script>

<script type="text/javascript">

    $("#Change_User_Info").click(function(){
        $(location).attr('href', './change_user_info.html');
    });
    $("#Change_Addr_Info").click(function(){
        $(location).attr('href', './change_addr_info.html');
    });
</script>


<script type="text/javascript" src="../script/aui-tab.js" ></script>
<script type="text/javascript">
    apiready = function(){
        api.parseTapmode();
    }
    var tab = new auiTab({
        element:document.getElementById("footer"),index:3
    },function(ret){
        console.log(ret);

        switch(ret.index)
        {
            case 1:
                $(location).attr('href', '/?fuid=<?php echo $uid?>');
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
                $(location).attr('href', './Share.php?fuid=<?php echo $uid?>');

        }


    });

    var tab = new auiTab({
        element:document.getElementById("footer1")
    },function(ret){
        console.log(ret);
    });
</script>

<script type="text/javascript" src="../script/aui-sharebox.js" ></script>
<script type="text/javascript">
    apiready = function(){
        api.parseTapmode();
    }
    var sharebox = new auiSharebox();
    function openSharebox(){
        sharebox.init({
            frameBounces:true,//当前页面是否弹动，（主要针对安卓端）
            buttons:[{
                image:'../image/share/wx.png',
                text:'微信',
                value:'wx'//可选
            },{
                image:'../image/share/wx-circle.png',
                text:'朋友圈',
                value:'wx-circle'
            },{
                image:'../image/share/qq.png',
                text:'QQ好友',
                value:'qq'
            },{
                image:'../image/share/qzone.png',
                text:'QQ空间',
                value:'qq-qzone'
            },{
                image:'../image/share/qq-weibo.png',
                text:'QQ微博'
            },{
                image:'../image/share/sina-weibo.png',
                text:'新浪微博'
            },{
                image:'../image/share/message.png',
                text:'短信'
            }],
            col:5,
            cancelTitle:'关闭'//可选,当然也可以采用下面的方式使用图标
            // cancelTitle:'<i class="aui-iconfont aui-icon-close aui-font-size-16"></i>'//可选
        },function(ret){
            if(ret){
                document.getElementById("button-index").textContent = ret.buttonIndex;
                document.getElementById("button-value").textContent = ret.buttonValue;
            }
        })
    }
</script>

</html>
