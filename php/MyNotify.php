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
    <script type="text/javascript" src="../script/aui-toast.js" ></script>
    <title>我的通知</title>
</head>
<body>
<header class="aui-bar aui-bar-nav">
    <a class="aui-pull-left aui-btn" id="Back">
        <span class="aui-iconfont aui-icon-left">返回</span>
    </a>我的通知
</header>

<div class="aui-list">
    <section class="aui-content aui-grid">
        <div class="aui-row">
            <span class="aui-col-xs-12 aui-padded-15">
            <span style="font-size: 14px">欢迎您进入妙颜官方商城！<br>
妙颜真诚做产品，用心服务您。<br>
为了更方便您熟悉商城，请您仔细阅读以下小提示：<br>
                <span style="font-size: 10px">
一、商城所有产品直接由妙颜公司出品，没有任何中间环节，所以将利润直接奖励给喜欢妙颜，认可妙颜的您作为推广费；<br>
二、您在商城购买任意一款产品后，系统将在“美丽分享”里自动生成“我的分享二维码”，您可以将您的二维码分享到您的朋友圈和任何社交平台；<br>
三、分享奖励规则如下表:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
                   <table class="table table-bordered" style="margin-left:5%;border: #9e9e9e" border="1" cellspacing="0" cellpadding="5">
                        <thead>
                            <tr>
                                <th>分享人数</th>
                                <th>购买折扣</th>
                                <th>等级</th>
                                <th>分享奖励</th>
                                <th>区域共享</th>
                            </tr>
                        </thead>
                        <tbody id="order_list_content">
                            <tr>
                                 <td>1-3</td>
                                 <td>9折</td>
                                 <td>VIP</td>
                                 <td>20%</td>
                                 <td>无</td>
                            </tr>
                            <tr>
                                 <td>4-10</td>
                                 <td>8折</td>
                                 <td>共享人</td>
                                 <td>25%</td>
                                 <td>无</td>
                            </tr>
                            <tr>
                                 <td>11-50</td>
                                 <td>8折</td>
                                 <td>区域共享人</td>
                                 <td>30%</td>
                                 <td>区域共享5%</td>
                            </tr>
                            <tr>
                                 <td>51及以上</td>
                                 <td>8折</td>
                                 <td>总监</td>
                                 <td>35%</td>
                                 <td>区域共享5%</td>
                            </tr>
                        </tbody>
                   </table>
四、如您未经任何人分享，第一次购买为原价，往后购买可享9折优惠；<br>
五、经由您分享的用户第一次购买即享9折优惠；<br>
六、关注“妙颜彩妆官方公众号”可直接进入商城；<br>
七、有任何问题可咨询在线客服或直接在公众号给妙颜留言。<br>
带着满满诚意，愿遇上妙颜商城的您，人生亦会妙不可颜！<br></span>
<span style="font-size: 4px">注：以上分享奖励由妙颜公司制定，解释权归妙颜商城所有。<br>
            </span>
        </div>
</div>
</section>

<ul class="aui-list aui-list-in" style="background-color: #f5f5f5">
    <li class="aui-list-item" id="Change_Addr_Info" style="margin-bottom: 5px; background-color: #ffffff">
        <!--            <div class="aui-list-item-inner">-->
        <!--                <div class="aui-col-xs-6 aui-list-item-title aui-font-size-12">NFIEFggrgrgIE<br><span>是大款fwfewffewfew</span></div>-->
        <!--                <div class="aui-col-xs-3" aui-padded-5>-->
        <!--                    <div class="aui-btn aui-btn-primary aui-btn-block" id="giveRedBag">打 赏</div>-->
        <!--                </div>-->
        <!--                <div class="aui-col-xs-3 aui-padded-5">-->
        <!--                    <div class="aui-btn aui-btn-primary aui-btn-block" id="giveCoupon">送 券</div>-->
        <!--                </div>-->
        <!--            </div>-->
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
        element:document.getElementById("footer"),index:4
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




    $('#withdraw').click(function () {
        var toast = new auiToast();
        toast.custom({
            title:"提现功能2月下旬开放！",
            html:'<i class="aui-iconfont aui-icon-info"></i>',
            duration:2000
        });
    })


    $('#My_Coupon').click(function () {
        $(location).attr('href', './shareMyCoupon.php?fuid=<?php echo $uid?>');
    })
    $('#My_Upper').click(function () {
        $(location).attr('href', './shareMyUpper.php?fuid=<?php echo $uid?>');
    })

    $("#Back").click(function () {
        $(location).attr('href', './My.php?fuid=<?php echo $uid?>');
    })

</script>



</html>
