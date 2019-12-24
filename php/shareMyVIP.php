<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/27 0027
 * Time: 16:09
 */
ini_set('date.timezone','Asia/Shanghai');
error_reporting(-1);
require_once 'Config.php';

Config::_setAnchor();

$uid = Config::_uidInit();
$goodList = Config::_goodList($uid);
$myVipId = Config::_getMyVipId($uid);
$myVipInfo = Config::_getMyVipInfo($myVipId);

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
    <link rel="stylesheet" type="text/css" href="../css/iconfont5.css">
    <script type="text/javascript" src="../script/aui-toast.js" ></script>
    <title>会员权益</title>
</head>
<body>
<header class="aui-bar aui-bar-nav">
    <a class="aui-pull-left aui-btn" id="Back">
        <span class="aui-iconfont aui-icon-left">返回</span>
    </a>会员权益
</header>

<div class="aui-list">
    <section class="aui-content aui-grid">
        <div class="aui-row">
            <div class="aui-col-xs-6">
                            <div class="aui-gird-lable aui-font-size-14">当前等级：</div>
                            <big class="aui-text-warning"><br><?php echo $myVipInfo[0]['myVip']?></big>
            </div>
            <div class="aui-col-xs-6 aui-padded-15 aui-font-size-14">
                <br><span style="color: red;">会员升级条件</span></br><span><?php echo $myVipInfo[0]['upgradeCondition']?></span>
            </div>
        </div>
    </section>
<!--    <div class="aui-col-xs-12 aui-font-size-12" id="upgrade">-->
<!--        <small style="padding: 10px; margin: 10px;color: #9e9e9e">升级：--><?php //echo $myVipInfo[0]['shortRight']?><!--</small>-->
<!--    </div>-->
    <ul class="aui-list aui-list-in aui-margin-b-15" >
        <li class="aui-list-item" id="myLongRight">
            <div class="aui-list-item-label-icon">
                <i class="iconfont icon-iconjp aui-text-info aui-font-size-18"></i>
            </div>
            <div class="aui-list-item-inner aui-list-item-arrow">
                <div class="aui-list-item-title">当前权限详情</div>
            </div>
        </li>
        <li class="aui-list-item" id="myHighLevelLongRight">
            <div class="aui-list-item-label-icon">
                <i class="iconfont icon-viphuiyuanhuangguan aui-text-info"></i>
            </div>
            <div class="aui-list-item-inner aui-list-item-arrow">
                <div class="aui-list-item-title">升级后权限说明</div>
            </div>
        </li>
    </ul>

<ul class="aui-list aui-list-in aui-margin-b-15" >
    <li class="aui-list-item" id="myBonus" style="display: none">
        <div class="aui-list-item-label-icon">
            <i class="iconfont icon-purse aui-text-info aui-font-size-18"></i>
        </div>
        <div class="aui-list-item-inner aui-list-item-arrow">
            <div class="aui-list-item-title">我的奖金</div>
        </div>
    </li>
    <li class="aui-list-item" id="myBond" style="display: none">
        <div class="aui-list-item-label-icon">
            <i class="iconfont icon-baozhengjindanbao aui-text-info aui-font-size-18"></i>
        </div>
        <div class="aui-list-item-inner aui-list-item-arrow">
            <div class="aui-list-item-baozhengjindanbao">我的保证金</div>
        </div>
    </li>
    <li class="aui-list-item" id="myApplication" style="display: none">
        <div class="aui-list-item-label-icon">
            <i class="iconfont icon-shenqingshouquan aui-text-info"></i>
        </div>
        <div class="aui-list-item-inner aui-list-item-arrow">
            <div class="aui-list-item-title">我的申请</div>
        </div>
    </li>
    <li class="aui-list-item" id="myInterview" style="display: none">
        <div class="aui-list-item-label-icon">
            <i class="iconfont icon-iconfontrenshu aui-text-info"></i>
        </div>
        <div class="aui-list-item-inner aui-list-item-arrow">
            <div class="aui-list-item-title">我的面试</div>
        </div>
    </li>
</ul>

    <li class="aui-list-item" style="background-color: #f5f5f5">
    </li>

    <li class="aui-list-item" style="background-color: #f5f5f5">
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
<script type="text/javascript" src="../script/aui-dialog.js" ></script>
<script type="text/javascript" src="../script/api.js" ></script>
<script type="text/javascript">
    apiready = function(){
        api.parseTapmode();
    }
    function closeTips(){
        $api.remove($api.byId("tips-1"));
    }


    $('#myLongRight').click(function () {
        var dialog = new auiDialog();
        dialog.alert({
            title:"<?php echo $myVipInfo[0]['myVip']?>",
            msg:"<?php echo $myVipInfo[0]['myVipLongRight']?>",
            buttons:['好哒']
        },function(ret){
            if(ret.buttonIndex == 2) {
            }
        })
    })
    $('#myHighLevelLongRight').click(function () {
        var dialog = new auiDialog();
        dialog.alert({
            title:"<?php echo $myVipInfo[0]['myVipPlusOneLevel']?>",
            msg:"<?php echo $myVipInfo[0]['myVipPlusOneLongRight']?>",
            buttons:['好哒']
        },function(ret){
            if(ret.buttonIndex == 2) {
            }
        })
    })

    $('#myInterview').click(function () {
        var dialog = new auiDialog();
        dialog.alert({
            title:"友情提醒",
            msg:"请耐心等待，官方客服会与您取得联系，并确定面试具体事宜！",
            buttons:['好哒']
        },function(ret){
            if(ret.buttonIndex == 2) {
            }
        })
    })

    if(<?php echo $myVipId?> == <?php echo Config::$_vipLevelMax?>){
        $('#myHighLevelLongRight').hide();
        $('#upgrade').hide();
    }

    if( <?php echo $myVipId?> >= 3 ){
        $('#myBonus').show();
    }

    if(<?php echo $myVipId?> == 4 || <?php echo $myVipId?> == 5 || <?php echo $myVipId?> == 6 ){
        $('#myBond').show();
    }

    if(<?php echo $myVipId?> == 4 || <?php echo $myVipId?> == 5 ){
        $('#myBond').show();
        $('#myApplication').show();
        $('#myInterview').show();
    }

    $("#myApplication").click(function () {
        $(location).attr('href', './MyApplication.php?fuid=<?php echo $uid?>');
    })

    $("#myBonus").click(function () {
        $(location).attr('href', './MyBonus.php?fuid=<?php echo $uid?>');
    })
    $("#myBond").click(function () {
        $(location).attr('href', './MyBond.php?fuid=<?php echo $uid?>');
    })
    $("#Back").click(function () {
        $(location).attr('href', './Share.php?fuid=<?php echo $uid?>');
    })

</script>



</html>
