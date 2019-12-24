<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(-1);
require_once 'Config.php';

Config::_setAnchor();

$uid = Config::_uidInit();
$goodList = Config::_goodList($uid);

$myOrder=Config::_getAllOrder($uid);
$allGoodList=array();

for($i=0;$i<sizeof($myOrder);++$i) {
    $allGoodList[$i]=getGoodList(Config::_getOrderInfo($myOrder[$i]['orderId'],$uid,$myOrder[$i]['status']));
//    var_dump($allGoodList[$i]);
}

function getGoodList($orderInfo){
    $goodList=array();
    for($i=0;$i<sizeof($orderInfo);++$i) {
        $goodList[$i]['orderId']=$orderInfo[$i]['orderId'];
        $goodList[$i]['time']=$orderInfo[$i]['time'];
        $goodList[$i]['goodId']=$orderInfo[$i]['goodId'];
        $goodList[$i]['thumbnail']=$orderInfo[$i]['thumbnail'];
        $goodList[$i]['goodName']=$orderInfo[$i]['goodName'];
        $goodList[$i]['goodAmount']=$orderInfo[$i]['goodAmount'];
        $goodList[$i]['promotionPrice']=$orderInfo[$i]['promotionPrice'];
        $goodList[$i]['price']=$orderInfo[$i]['price'];
        $goodList[$i]['status']=$orderInfo[$i]['status'];
        $goodList[$i]['color']=$orderInfo[$i]['color'];
        $goodList[$i]['pack']=$orderInfo[$i]['pack'];

    }
    return $goodList;
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
    <title>全部订单</title>
</head>
<body>
<header class="aui-bar aui-bar-nav">
    <a class="aui-pull-left aui-btn" id="Back">
        <span class="aui-iconfont aui-icon-left">返回</span>
    </a>全部订单
</header>
<div class="aui-list  aui-form-list aui-margin-b-0" id="Home">
    <ul class="aui-list aui-list-in" style="background-color: #f5f5f5">

        <?php
        for($i=0;$i<sizeof($allGoodList);$i++){
            $sum_price=0;
        ?>

        <li class="aui-card-list aui-margin-b-10">
            <div class="aui-card-list-header">
                <div style="font-size: 0.58rem">订单号：<i class="orderId"><?php echo $allGoodList[$i][0]['orderId']?></i></div>
                <div style="font-size: 0.58rem">时间：<i class="time"><?php echo $allGoodList[$i][0]['time']?></i></div>
            </div>
            <?php
            for($j=0;$j<sizeof($allGoodList[$i]);++$j) {
                ?>
                <div class="aui-card-list-header aui-padded-10">
                    <div class="aui-col-xs-3 aui-padded-5">
                        <img src="<?php echo $allGoodList[$i][$j]['thumbnail'] ?>">
                    </div>
                    <div class="aui-col-xs-3 aui-padded-5">
                        <div class="aui-card-list-content aui-font-size-14"><?php echo $allGoodList[$i][$j]['goodName'] ?></div>
                    </div>
                    <div class="aui-col-xs-2">
                        <div class="aui-card-list-content aui-font-size-12">
                            <?php echo $allGoodList[$i][$j]['color'] ?> <?php echo $allGoodList[$i][$j]['pack'] ?></div>
                    </div>
                    <div class="aui-col-xs-2">
                        <div class="aui-card-list-content aui-font-size-12">
                            x<?php echo $allGoodList[$i][$j]['goodAmount'] ?></div>
                    </div>
                    <div class="aui-col-xs-2">
                        <div class="aui-card-list-content aui-font-size-12">
                            ￥<?php echo $allGoodList[$i][$j]['promotionPrice'] * $allGoodList[$i][$j]['goodAmount'] ?></div>
                    </div>
                </div>
                <?php
                $sum_price+=$allGoodList[$i][$j]['promotionPrice'] * $allGoodList[$i][$j]['goodAmount'];
            }
            ?>
            <div class="aui-card-list-footer">
                    <div class="aui-font-size-14 aui-list-item-right">￥<span class="money"><?php echo $sum_price?></span></div>
                    <span class="tips" style="display: none">5分钟内未付款订单自动失效</span>
                <?php
                        $action1='';
//                        var_dump($allGoodList[$i][0]);
                        switch ($allGoodList[$i][0]['status']){
                            case 0:
                                $action1='去付款';
                                break;
                            case 1:
                                $action1='催单';
                                break;
                            case 2:
                                $action1='查看物流';
                                break;
                            case 3:
                                $action1='已完成';
                                break;
                            case 4:
                                $action1='已失效';
                                break;
                            case 5:
                                $action1='未收到货';
                                break;
                            default:
                        }
                    ?>
                    <div class="aui-btn aui-btn-warning aui-list-item-right <?php echo $allGoodList[$i][0]['status']?>"><?php echo $action1?></div>
            </div>
        </li>
            <?php
        }
        ?>

        <li class="aui-list-item">

        </li>
        <li class="aui-list-item">

        </li>
    </ul>
</div>


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

<script type="text/javascript" src="../script/aui-dialog.js" ></script>
<script type="text/javascript">
    $("#Back").click(function () {
        $(location).attr('href', './My.php?fuid=<?php echo $uid?>');
    })

    function datifyString(a){
        return a.substring(0,4)+'-'+a.substring(4,6)+'-'+a.substring(6,8)+' '+
            a.substring(8,10)+':'+a.substring(10,12)+':'+a.substring(12,14);
    }

    $('.time').each(function () {
        var _index = $(".time").index($(this));
        var orderTime = $(".time").eq(_index).html();
        $(".time").eq(_index).html(datifyString(orderTime));
    });

    $('.aui-btn.aui-btn-warning.aui-list-item-right.0').each(function () {
        var _index = $(".aui-btn.aui-btn-warning.aui-list-item-right.0").index($(this));
        var orderId = $(".orderId").eq(_index).html();
        var money= $(".money").eq(_index).html();
        var subject=$(".aui-card-list-content.aui-font-size-14").eq(_index).html();
        $(".aui-btn.aui-btn-warning.aui-list-item-right.0").eq(_index).unbind('click').click(function () {
                    $(location).attr('href', './bill.php?orderId='+orderId+'&money='+money+'&subject='+subject);
        });
        $('.tips').eq(_index).show();
    });

    $('.aui-btn.aui-btn-warning.aui-list-item-right.1').each(function () {
        var _index = $(".aui-btn.aui-btn-warning.aui-list-item-right.1").index($(this));
        $(".aui-btn.aui-btn-warning.aui-list-item-right.1").eq(_index).unbind('click').click(function () {
            var dialog = new auiDialog();
            dialog.alert({
                title:"已催单",
                msg:"工作人员正在紧急准备您的订单！",
                buttons:['好哒']
            },function(ret){
                if(ret.buttonIndex == 2) {
                }
            })
        })
    });

    $('.aui-btn.aui-btn-warning.aui-list-item-right.2').each(function () {
        var _index = $(".aui-btn.aui-btn-warning.aui-list-item-right.2").index($(this));
        $(".aui-btn.aui-btn-warning.aui-list-item-right.2").eq(_index).unbind('click').click(function () {
            var dialog = new auiDialog();
            dialog.alert({
                title:"查看物流",
                msg:"请通过订单号去查询相关物流详情",
                buttons:['好哒']
            },function(ret){
                if(ret.buttonIndex == 2) {
                }
            })
        })
    });

    $('.aui-btn.aui-btn-warning.aui-list-item-right.3').each(function () {
        var _index = $(".aui-btn.aui-btn-warning.aui-list-item-right.3").index($(this));
        $(".aui-btn.aui-btn-warning.aui-list-item-right.3").eq(_index).unbind('click').click(function () {
            var dialog = new auiDialog();
            dialog.alert({
                title:"已完成",
                msg:"该订单已确认收货并完成",
                buttons:['好哒']
            },function(ret){
                if(ret.buttonIndex == 2) {
                }
            })
        })
    });

    $('.aui-btn.aui-btn-warning.aui-list-item-right.4').each(function () {
        var _index = $(".aui-btn.aui-btn-warning.aui-list-item-right.4").index($(this));
        $(".aui-btn.aui-btn-warning.aui-list-item-right.4").eq(_index).unbind('click').click(function () {
            var dialog = new auiDialog();
            dialog.alert({
                title:"已失效",
                msg:"订单超过5分钟未付款已失效",
                buttons:['好哒']
            },function(ret){
                if(ret.buttonIndex == 2) {
                }
            })
        })
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
        // console.log(ret);
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
        // console.log(ret);
    });
</script>

</html>