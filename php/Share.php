<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/26 0026
 * Time: 03:31
 */
ini_set('date.timezone','Asia/Shanghai');
error_reporting(-1);
require_once 'Config.php';

Config::_setAnchor();

$uid = Config::_uidInit();

$fatherUid = Config::_getFatherUid($uid)!=Config::SQL_ERR?Config::_getFatherUid($uid):"";
$mysql = Config::_mysql();

$goodList = Config::_goodList($uid);


$todayMoney=0;
$thisMonthMoney=0;
$allMoney=0;
$r0=$mysql->query("SELECT todayMoney,thisMonthMoney,allMoney FROM XXXXXXX.shareMoney where uid='$uid'");
$ar0=$mysql->to2DArray($r0);
if(count($ar0)==0){  //never use mysql_fetch_array($r0) to check the data, it will retrieve the 1st row to make u fucked.
    $todayMoney = 0;
    $thisMonthMoney = 0;
    $allMoney = 0;
}else{
    $todayMoney = $ar0[0][0];
    $thisMonthMoney = $ar0[0][1];
    $allMoney = $ar0[0][2];
}

$myVipId = Config::_getMyVipId($uid);
if($myVipId>0){
    config::_updateMyShareMoney($uid);
}
Config::_updateMyVipIdAndShare($uid);


$MyRemainingShareMoney=Config::_getMyRemainingShareMoney($uid);
//$MyGotRedbag=Config::_getMyRedbagUp($uid);

//刷新自己的VIP状态
Config::_updateMyVipIdAndShare($uid);

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
    <script type="text/javascript" src="../script/aui-toast.js" ></script>
    <title>美丽分享</title>
</head>
<body>
<header class="aui-bar aui-bar-nav">
    </a>美丽分享
</header>

<div class="aui-list">
    <section class="aui-content aui-grid">
        <div class="aui-row">
            <div class="aui-col-xs-4">
                <div class="aui-gird-lable aui-font-size-14">今 日</div>
                <big class="aui-text-warning"><?php echo $todayMoney?><small> 元</small></big>
            </div>
            <div class="aui-col-xs-4">
                <div class="aui-gird-lable aui-font-size-14">本 月</div>
                <big class="aui-text-danger"><?php echo $thisMonthMoney?><small> 元</small></big>
            </div>
            <div class="aui-col-xs-4">
                <div class="aui-gird-lable aui-font-size-14">总 额</div>
                <big class="aui-text-success"><?php echo $allMoney?><small> 元</small></big>
            </div>
            <div class="aui-col-xs-6 aui-padded-15">
                <div class="aui-gird-lable aui-font-size-14">余额</div>
                <big class="aui-text-success"><?php echo $MyRemainingShareMoney?><small> 元</big>
            </div>
            <div class="aui-col-xs-6 aui-padded-15">
                <div class="aui-btn aui-btn-primary aui-btn-block" id="withdraw">提 现</div>
            </div>
        </div>
    </section>

    <ul class="aui-list aui-list-in aui-margin-b-15">
        <li class="aui-list-item" id="My_QRcode">
            <div class="aui-list-item-label-icon">
                <i class="iconfont icon-qrcode-1-copy aui-text-info"></i>
            </div>
            <div class="aui-list-item-inner aui-list-item-arrow">
                <div class="aui-list-item-title">我的分享二维码</div>
            </div>
        </li>
        <li class="aui-list-item" id="My_Coupon">
            <div class="aui-list-item-label-icon">
                <i class="iconfont icon-ziyuan aui-text-info"></i>
            </div>
            <div class="aui-list-item-inner aui-list-item-arrow">
                <div class="aui-list-item-title">我的积分</div>
            </div>
        </li>
    </ul>
    <ul class="aui-list aui-list-in aui-margin-b-15">
        <li class="aui-list-item" id="My_Upper">
            <div class="aui-list-item-label-icon">
                <i class="iconfont icon-bussinessman aui-text-info"></i>
            </div>
            <div class="aui-list-item-inner aui-list-item-arrow">
                <div class="aui-list-item-title">美丽分享官</div>
            </div>

        </li>
<!--        <li class="aui-list-item" id="My_Query">-->
<!--            <div class="aui-list-item-label-icon">-->
<!--                <i class="aui-iconfont aui-icon-search aui-text-info"></i>-->
<!--            </div>-->
<!--            <div class="aui-list-item-inner aui-list-item-arrow">-->
<!--                <div class="aui-list-item-title">区域查询</div>-->
<!--            </div>-->
<!---->
<!--        </li>-->
    </ul>
    <ul class="aui-list aui-list-in">
        <li class="aui-list-item" id="My_Share">
            <div class="aui-list-item-label-icon">
                <i class="aui-iconfont aui-icon-share aui-text-warning"></i>
            </div>
            <div class="aui-list-item-inner aui-list-item-arrow">
                <div class="aui-list-item-title">我的美丽分享</div>
            </div>
        </li>
        <li class="aui-list-item" id="My_VIP">
            <div class="aui-list-item-label-icon">
                <i class="aui-iconfont aui-icon-cert aui-text-warning"></i>
            </div>
            <div class="aui-list-item-inner aui-list-item-arrow">
                <div class="aui-list-item-title">会员权益</div>
            </div>
        </li>
    </ul>


    <li class="aui-list-item">
    </li>

    <li class="aui-list-item">
    </li>
</div>




<footer class="aui-bar aui-bar-tab aui-border-t" id="footer">
    <div class="aui-bar-tab-item" tapmode >
        <i class="aui-iconfont aui-icon-home"></i>
        <div class="aui-bar-tab-label">商城</div>
    </div>
    <div class="aui-bar-tab-item" tapmode>
        <?php
        if(sizeof($goodList) != 0){
            ?>
            <div class="aui-badge"><?php echo sizeof($goodList)?></div>
        <?php } ?>
        <i class="aui-iconfont aui-icon-cart"></i>
        <div class="aui-bar-tab-label">购物车</div>
    </div>
    <div class="aui-bar-tab-item aui-active" tapmode >
<!--        <div class="aui-dot"></div>-->
        <i class="iconfont icon-networking" style="font-size:21px"></i>
        <div class="aui-bar-tab-label">美丽分享</div>
    </div>
    <div class="aui-bar-tab-item " tapmode>
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
                $(location).attr('href', '#');
                break;
            case 4:
                $(location).attr('href', './My.php?fuid=<?php echo $uid?>');
                break;
            default:
                $(location).attr('href', '#');

        }


    });


</script>

<script type="text/javascript" src="../script/api.js" ></script>
<script type="text/javascript">
    apiready = function(){
        api.parseTapmode();
    }
    function closeTips(){
        $api.remove($api.byId("tips-1"));
    }


    $('#My_QRcode').click(function () {
        $.getJSON("./QRcode.php?url=<?php echo Config::_myShareUrl($uid)?>", function(data){
            console.error(data);
            if(data.state == <?php echo Config::OK?>) {
                $(location).attr('href', './shareMyQRcode.php?fuid=<?php echo $uid?>');
            }else{
                var toast = new auiToast();
                toast.custom({
                    title:"生成二维码故障，请联系客服",
                    html:'<i class="aui-iconfont aui-icon-info"></i>',
                    duration:1000
                });
            }
        });
    })

    if(<?php echo $myVipId?> == 0){
        $('#My_QRcode').hide();
        $('#My_Share').hide();
    }


    $('#withdraw').click(function () {
        $(location).attr('href', './WithDraw.php?fuid=<?php echo $uid?>');
    })

    $('#My_Coupon').click(function () {
        $(location).attr('href', './shareMyFlower.php?fuid=<?php echo $uid?>');
    })
    $('#My_Upper').click(function () {
        $(location).attr('href', './shareMyUpper.php?fuid=<?php echo $uid?>');
    })
    $('#My_Share').click(function () {
        $(location).attr('href', './shareMyDownner.php?fuid=<?php echo $uid?>');
    })
    $('#My_Query').click(function () {
        $(location).attr('href', './shareMyQuery.php?fuid=<?php echo $uid?>');
    })
    $('#My_VIP').click(function () {
        $(location).attr('href', './shareMyVIP.php?fuid=<?php echo $uid?>');
    })


    if( '<?php echo $fatherUid?>' == ''){
        $('#My_Upper').hide();
    }

</script>



</html>