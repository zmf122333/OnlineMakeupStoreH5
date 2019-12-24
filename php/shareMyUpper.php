<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(-1);
require_once 'Config.php';

Config::_setAnchor();

$uid = Config::_uidInit();
$fatherUid = Config::_getFatherUid($uid)!=Config::SQL_ERR?Config::_getFatherUid($uid):"";
$fatherName = Config::_getUserName($fatherUid)!=Config::SQL_ERR?Config::_getUserName($fatherUid):"";
$goodList = Config::_goodList($uid);
$flowerUp = Config::_getMyFlowerUp($uid);

$redbagUpGot = Config::_getMyRedbagUp($uid);
$redbagUpTransfer = Config::_getMyRedbagUpTransfer($uid);
$redbagRedRemaing = $redbagUpGot - $redbagUpTransfer;
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
    <title>美丽分享官</title>
    <style type="text/css">
        .aui-padded-15{
            width: auto;
            margin: 10px;
        }
    </style>
</head>
<body>
<header class="aui-bar aui-bar-nav">
    <a class="aui-pull-left aui-btn" id="Back">
        <span class="aui-iconfont aui-icon-left">返回</span>
    </a>我的美丽分享官
</header>
<div class="aui-list" id="user-info">
    <div class="aui-list-item-inner aui-border-b">
        <div class="aui-list-item-title aui-padded-15">
            <div class="aui-col-xs-3 aui-margin-r-15 aui-margin-l-15"><img src="<?php echo Config::_uidHeadPic($fatherUid)?>">
            </div>
            <div class="aui-col-xs-7">
                <br>
                <div class="aui-col-xs-12"><?php echo $fatherName ?></div>
                <div class="aui-col-xs-12"><?php echo Config::_getUserPhone($fatherUid) ?></div>
            </div>
        </div>
    </div>
</div>

<ul class="aui-list aui-list-in">
    <li class="aui-list-item" id="MyRedbagUpper">
        <div class="aui-list-item-label-icon">
            <img src="../image/redbag.png" style="width: 27px;height: 27px">
        </div>
        <div class="aui-list-item-inner aui-list-item-arrow">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-title">收到她发的红包(余额)</div>
                <div class="aui-list-item-right">￥<?php echo $redbagRedRemaing?></div>
            </div>
    </li>
</ul>
<!--<ul class="aui-list aui-list-in">-->
<!--    <li class="aui-list-item" id="MyFlowerUpper">-->
<!--        <div class="aui-list-item-label-icon">-->
<!--            <img src="../image/flower.png" style="width: 27px;height: 27px">-->
<!--        </div>-->
<!--        <div class="aui-list-item-inner aui-list-item-arrow">-->
<!--            <div class="aui-list-item-inner">-->
<!--                <div class="aui-list-item-title">收到她送的小红花(余额)</div>-->
<!--                <div class="aui-list-item-right">--><?php //echo $flowerUp?><!--朵</div>-->
<!--            </div>-->
<!--    </li>-->
<!--</ul>-->


<li class="aui-list-item" style="color:lightgrey;padding: 10px;border: none">
    友情提示：<br>收到的红包您可以手动转入您的美丽分享余额，您可以随时提现哦~
</li>

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

    $("#MyRedbagUpper").click(function(){
        $(location).attr('href', './shareMyUpperSentRedBag.php?fuid=<?php echo $uid?>');
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


</html>
