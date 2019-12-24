<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(-1);
require_once 'Config.php';

Config::_setAnchor();


$uid = Config::_uidInit();

$orderId=88888;

// get array check.
if(array_key_exists('orderId',$_GET)){
    $orderId = $_GET["orderId"];
};

Config::_setOrderStatus($orderId,$uid,1,0);

echo <<<HTML

<!DOCTYPE html>
<html lang="zh_CN">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,users-scalable=0,width=device-width,initial-scale=1.0"/>
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js" ></script>
    <link rel="stylesheet" type="text/css" href="../css/aui.css" />
    <title>订单成功</title>
    <script type="text/javascript" src="../script/aui-popup-new.js" ></script>
    <link rel="stylesheet" type="text/css" href="../css/aui-flex.css" />
    <style type="text/css">
        img {
            display: block;
            max-width: 100%;
        }
    </style>
</head>
<body>

<header class="aui-bar aui-bar-nav">
    <a class="aui-pull-left aui-btn" id="Back">
        <span class="aui-iconfont aui-icon-left"></span>
    </a>订单成功
</header>

<div class="aui-content ">

    <ul class="aui-list aui-media-list">
        <li class="aui-list-item" style="height: 80px">
                <div class="aui-flex-item-2 aui-text-left" style="margin-top:10px"><img src="../image/good-or-tick.png" style="width: 48px"></div>
                <div  class="aui-flex-item-10 aui-text-left" style="margin-top:18px">
                    <a>您的订单已经支付成功！</a>
                </div>
        </li>

        </li>
        <li class="aui-list-item"><h5 style="margin-top: 8px">我们将尽快帮您安排发货！</h5></li>
    </ul>


</div>





</body>


<script type="text/javascript" src="../script/api.js"></script>


<script type="text/javascript">
    $("#Back").click(function () {
        $(location).attr('href', '/?fuid='.$uid);
    })

</script>





</html>
HTML;

?>

