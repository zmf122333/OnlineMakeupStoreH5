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
$bonusShareType = '共享';
//if($myVipId == 8 || $myVipId ==11){
//    $bonusShareType = '独享';
//}
$myDistrict = Config::_getMyDistrict($uid);
$myCity = Config::_getMyCity($uid);
$myProvince = Config::_getMyProvince($uid);

$myAreaSalesAmount = 1;
$myAreaPeersNum = 1;
$settleTime=Config::_getSettleTime();
$myArea=$myDistrict;
if($myVipId == 3 || $myVipId == 4){
    $myAreaSalesAmount = Config::_getAreaSalesAmount($uid,3,$settleTime);
    $myAreaPeersNum = Config::_getAreaPeersNum($uid,3,$myVipId);
}
if($myVipId == 5){
    $myArea=$myCity;
    $myAreaSalesAmount = Config::_getAreaSalesAmount($uid,2,$settleTime);
    $myAreaPeersNum = Config::_getAreaPeersNum($uid,2,$myVipId);
}
if($myVipId == 6){
    $myArea=$myProvince;
    $myAreaSalesAmount = Config::_getAreaSalesAmount($uid,1,$settleTime);
    $myAreaPeersNum = Config::_getAreaPeersNum($uid,1,$myVipId);
}
//if($myVipId == 11){
//    $myArea=$myProvince;
//    $myAreaSalesAmount = Config::_getAreaSalesAmount($uid,1,$settleTime);
//    $myAreaPeersNum = Config::_getAreaPeersNum($uid,1,$myVipId);
//}
$myBonus=0;
$myBonusShareRate = $myVipInfo[0]['bonus'];
$myAreaSalesAmountShare=$myAreaSalesAmount*$myBonusShareRate;
if($myAreaSalesAmountShare==0){
    $myBonus=0;
}else{
    $myBonus = sprintf("%.2f", $myAreaSalesAmountShare/$myAreaPeersNum);
}
$myExtraBonus=0;
$myExtraBonusShareRate = $myVipInfo[0]['extraBonus'];
$myExtraAreaSalesAmountShare=$myAreaSalesAmount*$myExtraBonusShareRate;
if($myExtraAreaSalesAmountShare==0){
    $myExtraBonus=0;
}else{
    $myExtraBonus = sprintf("%.2f", $myExtraAreaSalesAmountShare/$myAreaPeersNum);
}

$vipName='';
if($myVipId==5){
    $vipName='市代';
}
if($myVipId==6){
    $vipName='省代';
}

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
    <title>我的奖金</title>
</head>
<body>
<header class="aui-bar aui-bar-nav">
    <a class="aui-pull-left aui-btn" id="Back">
        <span class="aui-iconfont aui-icon-left">返回</span>
    </a>我的奖金
</header>

<div class="aui-list">
    <section class="aui-content aui-grid aui-margin-b-15">
        <div class="aui-row">
            <div class="aui-col-xs-5">
                <div class="aui-gird-lable aui-font-size-14"><span><?php echo $myArea?></span>总销量</div>
                <big class="aui-text-warning"><br>￥<small><?php echo $myAreaSalesAmount?></small></big>
            </div>
            <div class="aui-col-xs-2">
                <big class="aui-text-warning"><?php echo $myAreaPeersNum?>人<br><?php echo $bonusShareType?><br><big style="font-size: 30px"><?php echo $myBonusShareRate*100?>%</big></big>
            </div>
            <div class="aui-col-xs-5">
                <div class="aui-gird-lable aui-font-size-14">我的奖金</div>
                <big class="aui-text-warning"><br>￥<small><?php echo $myBonus?></small></big>
            </div>
            <div class="aui-col-xs-12">
                <small style="padding-left: 20px; color: #9e9e9e">上次结算：<small><?php echo $settleTime?></small></small>
            </div>
        </div>
</div>

<?php if($myVipId>=5){?>
<div class="aui-list">
    <section class="aui-content aui-grid aui-margin-b-15">
        <div class="aui-row">
            <div class="aui-col-xs-5">
                <div class="aui-gird-lable aui-font-size-14"><span><?php echo $myArea?></span>总销量</div>
                <big class="aui-text-warning"><br>￥<small><?php echo $myAreaSalesAmount?></small></big>
            </div>
            <div class="aui-col-xs-2">
                <big class="aui-text-warning"><?php echo $vipName?><br><?php echo $myAreaPeersNum?>人<br><?php echo $bonusShareType?><br><big style="font-size: 30px"><?php echo $myExtraBonusShareRate*100?>%</big></big>
            </div>
            <div class="aui-col-xs-5">
                <div class="aui-gird-lable aui-font-size-14">我的额外奖金</div>
                <big class="aui-text-warning"><br>￥<small><?php echo $myExtraBonus?></small></big>
            </div>
            <div class="aui-col-xs-12">
                <small style="padding-left: 20px; color: #9e9e9e">上次结算：<small><?php echo $settleTime?></small></small>
            </div>
        </div>
</div>
<?php }?>

<ul class="aui-list aui-list-in aui-margin-b-15" >
    <li class="aui-list-item" id="myBlanceHistory">
        <div class="aui-list-item-label-icon">
            <i class="iconfont icon-lishicaozuoliebiao aui-text-info aui-font-size-18"></i>
        </div>
        <div class="aui-list-item-inner aui-list-item-arrow">
            <div class="aui-list-item-title">奖金结算历史</div>
        </div>
    </li>
    <li class="aui-list-item" id="myBlanceIssue">
        <div class="aui-list-item-label-icon">
            <i class="iconfont icon-changjianwenti aui-text-info aui-font-size-18"></i>
        </div>
        <div class="aui-list-item-inner aui-list-item-arrow">
            <div class="aui-list-item-title">结算遇到问题</div>
        </div>
    </li>
</ul>


<li class="aui-list-item" style="background-color: #f5f5f5">
</li>

<li class="aui-list-item" style="background-color: #f5f5f5">
</li>




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

    $("#Back").click(function () {
        $(location).attr('href', './shareMyVIP.php?fuid=<?php echo $uid?>');
    })

    $("#myBlanceHistory").click(function () {
        $(location).attr('href', './MyBonusSettlementHistory.php?fuid=<?php echo $uid?>');
    })
    $("#myBlanceIssue").click(function () {
        var dialog = new auiDialog();
        dialog.alert({
            title:"结算问题",
            msg:"请联系官方客服咨询",
            buttons:['好哒']
        },function(ret){
            if(ret.buttonIndex == 2) {
            }
        })
    })

</script>



</html>
