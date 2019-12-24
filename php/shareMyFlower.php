<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(-1);
require_once 'Config.php';

Config::_setAnchor();

$uid = Config::_uidInit();

$goodList = Config::_goodList($uid);
$flowerDown = Config::_getMyFlowerDown($uid);
$myVip = Config::_getMyVipId($uid);
$myPoint = Config::_getMyPoint($uid);


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
    <link rel="stylesheet" type="text/css" href="../css/iconfont2.css">
    <link rel="stylesheet" type="text/css" href="../css/iconfont3.css">
    <link rel="stylesheet" type="text/css" href="../css/iconfont4.css">
    <link rel="stylesheet" type="text/css" href="../css/iconfont5.css">
    <title>我的积分</title>
</head>
<body>
<header class="aui-bar aui-bar-nav">
    <a class="aui-pull-left aui-btn" id="Back">
        <span class="aui-iconfont aui-icon-left">返回</span>
    </a>我的积分
</header>
<section class="aui-content" id="user-info">

    <ul class="aui-list aui-list-in">
        <li class="aui-list-item">
            <div class="aui-col-xs-4">
                <i class="iconfont icon-ziyuan aui-text-info" style="font-size: 32px"></i>目前积分：
<!--                <img style="margin: 10px;width: 42px;height: 42px;"" src="../image/flower.png">-->
            </div>
            <div class="aui-col-xs-2" style="margin-top: 15px;">
               <?php echo $myPoint?>分
            </div>
<!--            <div class="aui-col-xs-3" style="margin-top: 10px;margin-right: 20px">-->
<!--                <div class="aui-col-xs-12 aui-btn aui-btn-warning aui-margin-5" id="give">赠 送</div>-->
<!--            </div>-->
        </li>
        <li class="aui-list-item" style="color:lightgrey;padding: 10px;border: none">
            温馨提示：消费每满100元，积分即可增加1分，购买商品结算的时候，您可以用积分抵扣，抵扣标准是1积分=1元。
        </li>

    </ul>



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
<script type="text/javascript" src="../script/aui-toast.js" ></script>
<script type="text/javascript">

    $("#Change_User_Info").click(function(){
        $(location).attr('href', './change_user_info.html');
    });
    $("#Change_Addr_Info").click(function(){
        $(location).attr('href', './change_addr_info.html');
    });
    $("#give").click(function(){
        if(<?php echo $myVip?> < 1){
            var toast = new auiToast();
            toast.custom({
                title:"分享会员才能赠送！",
                html:'<i class="aui-iconfont aui-icon-info"></i>',
                duration:2000
            });
        }else{
            $(location).attr('href', './shareMyDownner.php?fuid=<?php echo $uid?>');
        }

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
