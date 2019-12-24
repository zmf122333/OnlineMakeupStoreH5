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
$myShareMoney=Config::_getMyShareMoney($uid);

$MyRemainingShareMoney=Config::_getMyRemainingShareMoney($uid);

$weixinBindUser=0;
if(Config::_isWeixinBindUser($uid)){
    $weixinBindUser=1;
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
    <title>提现</title>
</head>
<body>
<header class="aui-bar aui-bar-nav">
    <a class="aui-pull-left aui-btn" id="Back">
        <span class="aui-iconfont aui-icon-left">返回</span>
    </a>提现
</header>

<div class="aui-list">
    <section class="aui-content aui-grid">
        <div class="aui-row">
<!--            <div class="aui-col-xs-1">-->
<!--            </div>-->
            <div class="aui-col-xs-11">
                <div class="aui-gird-lable aui-font-size-14">我的余额</div>
                <big class="aui-text-warning">￥<big style="font-size: 30px;"><?php echo $MyRemainingShareMoney?></big></big>
            </div>
        </div>
        <div class="aui-row">
            <div class="aui-col-xs-12">
                <small style="padding: 20px; color: #9e9e9e">满<?php echo Config::$withDraw_bench?>元方可提现，每日提现单笔不能超过2000、总额不超过5000</small>
            </div>
        </div>
        <div class="aui-row">
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-label-icon">
                        提现金额
                    </div>
                    <div class="aui-list-item-input aui-margin-l-10">
                        <input id="Money" type="text" placeholder="请输入金额">
                    </div>
                </div>
            </li>
        </div>
</div>



<ul class="aui-list aui-list-in aui-margin-b-15" >
    <li class="aui-list-item">
        <div class="aui-list-item-inner">
            <div class="aui-list-item-label-icon">
                <i class="aui-text-success aui-font-size-12"><img src="../image/weixinPay.png"></i>
            </div>
            <div class="aui-list-item-inner">
                <div class="aui-list-item-title">微信零钱</div>
            </div>
            <div class="aui-list-item-right">
                <input type="checkbox" class="aui-checkbox" id="weixin_checkbox" checked>
            </div>
        </div>
    </li>
    <li class="aui-list-item">
        <div class="aui-list-item-inner">
            <div class="aui-list-item-label-icon">
                <i class="aui-text-success aui-font-size-12"><img src="../image/aliPay.png"></i>
            </div>
            <div class="aui-list-item-inner">
                <div class="aui-list-item-title">支付宝</div>
            </div>
            <div class="aui-list-item-right">
                <input type="checkbox" class="aui-checkbox" id="alipay_checkbox">
            </div>
        </div>
    </li>

<!--    <li class="aui-list-item">-->
<!--        <div class="aui-list-item-inner">-->
<!--            <div class="aui-list-item-label-icon">-->
<!--                <i class="aui-text-info aui-font-size-18"><img src="../image/bankPay.png"></i>-->
<!--            </div>-->
<!--            <div class="aui-list-item-inner">-->
<!--                <div class="aui-list-item-title">银行卡</div>-->
<!--            </div>-->
<!--            <div class="aui-list-item-right">-->
<!--                <input type="checkbox" class="aui-checkbox">-->
<!--            </div>-->
<!--        </div>-->
<!--    </li>-->
</ul>

<section class="aui-content" style="margin-left: 20px;margin-right: 20px">
    <div class="aui-btn aui-btn-primary aui-btn-block" id="submit">提 交</div>
</section>

<li class="aui-list-item" style="background-color: #f5f5f5">
</li>

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
        $(location).attr('href', './Share.php?fuid=<?php echo $uid?>');
    })


    $('#weixin_checkbox').click(function () {
        $('#weixin_checkbox').prop("checked");
        $('#alipay_checkbox').prop('checked',false);
    });

    $('#alipay_checkbox').click(function () {
        $('#alipay_checkbox').prop("checked");
        $('#weixin_checkbox').prop('checked',false);
    });




    $("#submit").click(function () {
        if('<?php echo Config::_getMyAlipayAccount($uid)?>' ==  '<?php echo Config::SQL_ERR?>'){
            $(location).attr('href', './BindMyAlipay.php?fuid=<?php echo $uid?>&return=./WithDraw.php?fuid=<?php echo $uid?>');
            return;
        }

        if('<?php echo $weixinBindUser?>' !=  '1'){
            $(location).attr('href', './BindMyWeixin.php?fuid=<?php echo $uid?>&return=./WithDraw.php?fuid=<?php echo $uid?>');
            return;
        }

        var channel = 1;
        var money = $('#Money').val();
        if($('#weixin_checkbox').prop('checked')){
            channel=1;
        }
        if($('#alipay_checkbox').prop('checked')){
            channel=0;
        }
        console.error(channel);
        var toast = new auiToast();

        if('<?php echo $uid?>' != 'JBRGP5WPS4F'){
            if($.trim(money) < <?php echo Config::$withDraw_bench?>){
                toast.fail({
                    title:"提现金额必须大于等于<?php echo Config::$withDraw_bench?>元！",
                    duration:3000
                });
                return;
            }

            if($.trim(money) > <?php echo $MyRemainingShareMoney?>){
                toast.fail({
                    title:"提现金额不能大于余额！",
                    duration:3000
                });
                return;
            }
            var regex = /^[1-9]\d*.\d*|0.\d*[1-9]\d*$/;
            if(!regex.test($.trim(money))){
                toast.fail({
                    title:"提现金额只能是数字哦！",
                    duration:3000
                });
                return;
            }
        }


        if(channel == 0){
            toast.loading({
                title:"处理中...",
                duration:600
            },function(ret){
                var toast1 = new auiToast();
                setTimeout(function(){
                    $.get("./CreateWithDrawOrder.php?money="+$.trim(money)+"&uid=<?php echo $uid?>&channel="+channel, function(result){
                        if($.trim(result) == '<?echo Config::SQL_ERR?>'){
                            toast1.fail({
                                title:"提交失败，请稍后重试！",
                                duration:3000
                            });
                        }else if($.trim(result) == '<?echo Config::WITHDRAW_MONEY_BIG_THAN_REMAINING_MONEY?>') {
                            toast1.fail({
                                title:"提交失败，提现金额大于余额！",
                                duration:3000
                            });
                        }else{
                            $.get("./withDrawStatusUpdate.php?status=2&orderId="+$.trim(result), function(result1){
                                if($.trim(result1) == '<?php echo Config::OK?>'){
                                    $.post("./withDrawProxy.php",{orderId:$.trim(result),uid:'<?php echo $uid?>',money:$.trim(money),channel:channel,
                                        account:'<?php echo Config::_getMyAlipayAccount($uid)!=Config::SQL_ERR?Config::_getMyAlipayAccount($uid):'';?>'}, function(data){
                                        if($.trim(data) == '<?php echo Config::OK?>'){
                                            var dialog = new auiDialog();
                                            dialog.alert({
                                                title:"提现成功！",
                                                msg:"请及时查看支付宝到账情况",
                                                buttons:['好哒']
                                            },function(ret){
                                                if(ret.buttonIndex == 1) {
                                                    $(location).attr('href', './Share.php?fuid=<?php echo $uid?>');
                                                }
                                            })
                                        }else if($.trim(data) == '<?php echo Config::ALIPAY_ACCOUNT_NAME_ERR?>') {
                                            toast1.fail({
                                                title:"支付宝账号和您真实姓名不匹配！",
                                                duration:3000
                                            });
                                            $.get("./withDrawStatusUpdate.php?status=0&orderId="+$.trim(result), function(result1) {
                                            });
                                        }else if($.trim(data) == '<?php echo Config::WITHDRAW_BIG_THAN_REVIEW_MONEY?>'){
                                            $.get("./withDrawStatusUpdate.php?status=1&orderId="+$.trim(result), function(result1) {
                                            });
                                            var dialog = new auiDialog();
                                            dialog.alert({
                                                title:"已提交审核！",
                                                msg:"请稍后查看支付宝到账情况",
                                                buttons:['好哒']
                                            },function(ret){
                                                if(ret.buttonIndex == 2) {
                                                    $(location).attr('href', './WithDraw.php?fuid=<?php echo $uid?>');
                                                }
                                            })
                                        }else{
                                            toast1.fail({
                                                title:"提现失败！",
                                                duration:3000
                                            });
                                            $.get("./withDrawStatusUpdate.php?status=0&orderId="+$.trim(result), function(result1) {
                                            });
                                        }
                                    });
                                }
                            });
                        }
                    });
                    toast.hide();
                }, 600)
            });
        }else{
            toast.loading({
                title:"处理中...",
                duration:600
            },function(ret){
                var toast1 = new auiToast();
                setTimeout(function(){
                    $.get("./CreateWithDrawOrder.php?money="+$.trim(money)+"&uid=<?php echo $uid?>&channel="+channel, function(result){
                        if($.trim(result) == '<?echo Config::SQL_ERR?>'){
                            toast1.fail({
                                title:"提交失败，请稍后重试！",
                                duration:3000
                            });
                        }else if($.trim(result) == '<?echo Config::WITHDRAW_MONEY_BIG_THAN_REMAINING_MONEY?>') {
                            toast1.fail({
                                title:"提交失败，提现金额大于余额！",
                                duration:3000
                            });
                        }else{
                            $.get("./withDrawStatusUpdate.php?status=2&orderId="+$.trim(result), function(result1){
                                if($.trim(result1) == '<?php echo Config::OK?>'){
                                    $.post("./withDrawProxy.php",{orderId:$.trim(result),uid:'<?php echo $uid?>',money:$.trim(money),channel:channel,
                                        account:'<?php echo Config::_getMyAlipayAccount($uid)!=Config::SQL_ERR?Config::_getMyAlipayAccount($uid):'';?>'}, function(data){
                                        if($.trim(data) == '<?php echo Config::OK?>'){
                                            var dialog = new auiDialog();
                                            dialog.alert({
                                                title:"提现成功！",
                                                msg:"请及时查看微信到账情况",
                                                buttons:['好哒']
                                            },function(ret){
                                                if(ret.buttonIndex == 1) {
                                                    $(location).attr('href', './Share.php?fuid=<?php echo $uid?>');
                                                }
                                            })
                                        }else if($.trim(data) == '<?php echo Config::WITHDRAW_WEIXIN_NAME_MISMATCH?>') {
                                            toast1.fail({
                                                title:"账号和您真实姓名不匹配！",
                                                duration:3000
                                            });
                                            $.get("./withDrawStatusUpdate.php?status=0&orderId="+$.trim(result), function(result1) {
                                            });
                                        }else if($.trim(data) == '<?php echo Config::WITHDRAW_WEIXIN_AMOUNT_LIMIT?>') {
                                            toast1.fail({
                                                title:"微信提现金额超出限制！",
                                                duration:3000
                                            });
                                            $.get("./withDrawStatusUpdate.php?status=0&orderId="+$.trim(result), function(result1) {
                                            });
                                        }else if($.trim(data) == '<?php echo Config::WITHDRAW_BIG_THAN_REVIEW_MONEY?>'){
                                            $.get("./withDrawStatusUpdate.php?status=1&orderId="+$.trim(result), function(result1) {
                                            });
                                            var dialog = new auiDialog();
                                            dialog.alert({
                                                title:"已提交审核！",
                                                msg:"请稍后查看微信到账情况",
                                                buttons:['好哒']
                                            },function(ret){
                                                if(ret.buttonIndex == 2) {
                                                    $(location).attr('href', './WithDraw.php?fuid=<?php echo $uid?>');
                                                }
                                            })
                                        }else{
                                            toast1.fail({
                                                title:"提现失败！",
                                                duration:3000
                                            });
                                            $.get("./withDrawStatusUpdate.php?status=0&orderId="+$.trim(result), function(result1) {
                                            });
                                        }
                                    });
                                }
                            });
                        }
                    });
                    toast.hide();
                }, 600)
            });
        }






    })

</script>



</html>
