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
    <title>我的申请</title>
</head>
<body>
<header class="aui-bar aui-bar-nav">
    <a class="aui-pull-left aui-btn" id="Back">
        <span class="aui-iconfont aui-icon-left">返回</span>
    </a>我的申请
</header>

<div class="aui-list">
    <section class="aui-content aui-grid aui-margin-b-10">
        <div class="aui-row">
            <div class="aui-col-xs-1">
            </div>
            <div class="aui-col-xs-11">
                <div class="aui-gird-lable aui-font-size-18">申请市代、省代的朋友可在此提交申请</div>
<!--                <big class="aui-text-warning">￥<big style="font-size: 30px;">1000</big></big>-->
            </div>
        </div>
        <div class="aui-row">
            <div class="aui-col-xs-12">
                <small style="padding: 20px; color: #9e9e9e">提交申请后，请耐心等待，随时关注商城内的通知</small>
            </div>
        </div>
        <ul class="aui-row">
            <li class="aui-list-item">
                <div class="aui-col-xs-12 aui-font-size-12" >
                    为何选择妙颜
                </div>
                <textarea rows="5" cols="5" placeholder="请填写"></textarea>
            </li>
            <li class="aui-list-item">
                <div class="aui-col-xs-12 aui-font-size-12" >
                    您将如何扩大推广
                </div>
                <textarea rows="5" cols="5" placeholder="请填写"></textarea>
            </li>
            <li class="aui-list-item">
                <div class="aui-col-xs-12 aui-font-size-12" >
                    您将如何提高销量
                </div>
                <textarea rows="5" cols="5" placeholder="请填写"></textarea>
            </li>
            <li class="aui-list-item">
                <div class="aui-col-xs-12 aui-font-size-12" >
                    您觉得您的优势和劣势是什么
                </div>
                <textarea rows="5" cols="5" placeholder="请填写"></textarea>
            </li>
        </ul>

</div>


<section class="aui-content" style="margin-left: 20px;margin-right: 20px">
    <div class="aui-btn aui-btn-primary aui-btn-block" id="login">提 交</div>
</section>
<section class="aui-content">
</section>
<li class="aui-list-item" style="background-color: #f5f5f5">
</li>

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
        $(location).attr('href', './shareMyVIP.php?fuid=<?php echo $uid?>');
    })


</script>



</html>
