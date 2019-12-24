<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(-1);
require_once 'Config.php';

Config::_setAnchor();

$uid = Config::_uidInit();
$goodList = Config::_goodList($uid);
$status=0;
$status=$_GET['status'];
$type='';
$action='';
$action1='';
switch ($status){
    case 0:
        $type='待付款';
        $action='去付款';
        break;
    case 1:
        $type='待发货';
        $action='催单';
        break;
    case 2:
        $type='待收货';
        $action='查看物流';
        $action1='确认收货';
        break;
    case 3:
        $type='已完成';
        break;
    default:
//        $type='待付款';
//        $action='去付款';
}


$myOrder=Config::_getOrder($uid,$status);

$allGoodList=array();

for($i=0;$i<sizeof($myOrder);++$i) {
    $allGoodList[$i]=getGoodList(Config::_getOrderInfo($myOrder[$i]['orderId'],$uid,$status),$status);
}

function getGoodList($orderInfo,$status){
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
        $goodList[$i]['expressNo']=$orderInfo[$i]['expressNo'];
        $goodList[$i]['deliverTime']=$orderInfo[$i]['deliverTime'];
        $goodList[$i]['statusRecieve']=$orderInfo[$i]['statusRecieve'];
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
    <title><?php echo $type?>订单</title>
</head>
<body>
<header class="aui-bar aui-bar-nav">
    <a class="aui-pull-left aui-btn" id="Back">
        <span class="aui-iconfont aui-icon-left">返回</span>
    </a><?php echo $type?>订单
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
                <div style="font-size: 0.58rem;display: none">快递单号：<i class="expressNo"><?php echo $allGoodList[$i][0]['expressNo']?></i></div>
                <div style="font-size: 0.58rem;display: none">发货时间：<i class="deliverTime"><?php echo $allGoodList[$i][0]['deliverTime']?></i></div>
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
                <?php if($status==2){?>
                    <div class="aui-btn aui-btn-success aui-list-item-right" style="color: white"><?php echo $action1?></div>
                <?php }?>
                <?php if($status!=3){?>
                    <div class="aui-btn aui-btn-warning aui-list-item-right"><?php echo $action?></div>
                <?php }?>
                <?php if($status==3){
                        if($allGoodList[$i][0]['statusRecieve'] == 0) {
                            $action = '是否已收到货';
                    ?>

                    <div class="aui-btn aui-btn-danger aui-list-item-right"><?php echo $action?></div>
                <?php }
                }?>
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
<script type="text/javascript" src="../script/aui-toast.js" ></script>
<script type="text/javascript" src="../script/aui-dialog.js" ></script>
<script type="text/javascript">
    $("#Back").click(function () {
        $(location).attr('href', './My.php?fuid=<?php echo $uid?>');
    });

    function datifyString(a){
        return a.substring(0,4)+'-'+a.substring(4,6)+'-'+a.substring(6,8)+' '+
            a.substring(8,10)+':'+a.substring(10,12)+':'+a.substring(12,14);
    }

    $('.time').each(function () {
        var _index = $(".time").index($(this));
        var orderTime = $(".time").eq(_index).html();
        $(".time").eq(_index).html(datifyString(orderTime));
    });

    $('.aui-btn.aui-btn-warning.aui-list-item-right').each(function () {
        var _index = $(".aui-btn.aui-btn-warning.aui-list-item-right").index($(this));
        var orderId = $(".orderId").eq(_index).html();
        var money= $(".money").eq(_index).html();
        var subject=$(".aui-card-list-content.aui-font-size-14").eq(_index).html();
        switch (<?php echo $status?>){
            case 0:
                $(".aui-btn.aui-btn-warning.aui-list-item-right").eq(_index).unbind('click').click(function () {
                    $(location).attr('href', './bill.php?fuid=<?php echo $uid?>&orderId='+orderId+'&money='+money+'&subject='+subject);
                });
                $('.tips').eq(_index).show();
                break;
            case 1:
                $(".aui-btn.aui-btn-warning.aui-list-item-right").eq(_index).unbind('click').click(function () {
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
                break;
            case 2:
                //查看物流
                $(".aui-btn.aui-btn-warning.aui-list-item-right").eq(_index).unbind('click').click(function () {
                    var deliverTime = $(".deliverTime").eq(_index).html();
                    var expressNo=$(".expressNo").eq(_index).html();
                    // var expressAPI='http://q.kdpt.net/api?id=XDB2gzsjbsss11owa47aNo0I_292365330&com=auto&show=json&nu='+expressNo;
                    // 返回状态state说明：
                    // 0：在途，即货物处于运输过程中；
                    // 1：揽件，货物已由快递公司揽收并且产生了第一条跟踪信息；
                    // 2：疑难，货物寄送过程出了问题；
                    // 3：签收，收件人已签收；
                    // 4：退签或异常签收，即货物由于用户拒签、超区等原因退回，而且发件人已经签收；
                    // 5：派件，即快递正在进行同城派件；
                    // 6：退回，货物正处于退回发件人的途中；
                    // 返回status说明：
                    // 0:无记录；
                    // 1:查询成功.

                    var dialog = new auiDialog();
                    $.getJSON("./QueryExpress.php?expressNo="+expressNo, function(json){
                        if(json.status == '1'){
                            $.each(json.data, function(i, field){
                                if(i==0){
                                    msg="<div style='font-size: 10px'><i style='font-size: 12px;color: #e51c23'>"+field['time']+' '+field['context']+"</i><br>";
                                }else{
                                    msg+=field['time']+' '+field['context']+"<br>";
                                }
                            });
                            msg+="</div>";
                            message=msg;

                            dialog.alert({
                                title:"物流跟踪",
                                msg:message,
                                buttons:['好哒']
                            },function(ret){
                                if(ret.buttonIndex == 2) {
                                }
                            });
                        }else{
                            dialog.alert({
                                title:"物流跟踪",
                                msg:"<div style='font-size: 10px'><i style='font-size: 12px;color: #e51c23'>"+deliverTime+' 妙颜官方仓库已出库 开始派件</i></div>',
                                buttons:['好哒']
                            },function(ret){
                                if(ret.buttonIndex == 2) {
                                }
                            });
                        }

                    });

                })

                break;
            case 3:
                //未收到货
                break;
            default:
                break;
        }
    })

    //确认收货
    $('.aui-btn.aui-btn-success.aui-list-item-right').each(function () {
        var _index = $(".aui-btn.aui-btn-success.aui-list-item-right").index($(this));
        var orderId = $(".orderId").eq(_index).html();
        $(".aui-btn.aui-btn-success.aui-list-item-right").eq(_index).unbind('click').click(function () {
            $.get("./SetOrderStatusRecieveGood.php?orderId=" + orderId, function (result) {
                if ($.trim(result) == <?php echo Config::OK?>) {
                    var toast = new auiToast();
                    toast.success({
                        title: "已确认收货，欢迎下次继续为您服务！",
                        duration: 2000
                    });
                    $(location).attr('href', './My.php?fuid=<?php echo $uid?>');
                }
            });
        });
    });

    //未收到货
    $(".aui-btn.aui-btn-danger.aui-list-item-right").each(function () {
        var dialog = new auiDialog();
        var _index = $(".aui-btn.aui-btn-danger.aui-list-item-right").index($(this));
        var orderId = $(".orderId").eq(_index).html();
        $(".aui-btn.aui-btn-danger.aui-list-item-right").eq(_index).unbind('click').click(function () {
            dialog.alert({
                title:"是否已收到货",
                msg:"您是否已收到货？",
                buttons:['未收到货','已收货']
            },function(ret){
                if(ret.buttonIndex == 1) {
                    $.get("./SetOrderStatusUNRecieveGood.php?orderId="+orderId, function(result){
                        if($.trim(result) == <?php echo Config::OK?>){
                            dialog.alert({
                                title:"我未收到货",
                                msg:'已通知官方客服，稍后与您取得联系！',
                                buttons:['好哒']
                            },function(ret1){
                                if(ret1.buttonIndex == 1) {
                                }
                            });
                        }
                    });
                }
                if(ret.buttonIndex == 2) {
                    $.get("./SetOrderStatusRecieveGood.php?orderId="+orderId, function(result){
                        if($.trim(result) == <?php echo Config::OK?>){
                            var toast = new auiToast();
                            toast.success({
                                title:"已确认收货，欢迎下次继续为您服务！",
                                duration:2000
                            });
                            $(location).attr('href', './My.php?fuid=<?php echo $uid?>');
                        }
                    });
                }
            });

        });
    })

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