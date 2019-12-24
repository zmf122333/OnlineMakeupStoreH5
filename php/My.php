<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(-1);
require_once 'Config.php';

Config::_setAnchor();

$uid='';
$tag=0;
$weixinLoginStatus = Config::OK;
$weixinLoginStatus2 = Config::OK;
if(array_key_exists('uid', $_GET)){
    $tag=1;
    $uid = $_GET['uid'];
    $weixinLoginStatus=$_GET['status'];
    $weixinLoginStatus2=$_GET['status2'];
}else{
    $uid = Config::_uidInit();
}


$goodList = Config::_goodList($uid);

$userInfo = Config::_userInfo($uid);
$name=Config::_getUserName($uid)==Config::SQL_ERR?'':Config::_getUserName($uid);
$phone='未绑定';
$point=0;
$thumbnail='../image/users/thumbnail.jpg';
if(sizeof($userInfo)>0){
    $phone=is_null($userInfo[0]['phone'])?"未绑定":$userInfo[0]['phone'];
    $point=$userInfo[0]['point'];
    $thumbnail=file_exists($userInfo[0]['thumbnail'])?$userInfo[0]['thumbnail']:'../image/users/thumbnail.jpg';
}


$bind=0;

if(Config::_isBindUser($uid)){
    //already binded.
    //pass to next step: can create order and pay
    $bind=1;
}else{
    //unbinded user.
    $bind=0;
}

//刷新自己的VIP状态
Config::_updateMyVipIdAndShare($uid);

$myPoint = Config::_getMyPoint($uid);

?>
<!DOCTYPE html>
<html lang="zh_CN">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0"/>
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js" ></script>
    <script src="https://cdn.bootcss.com/js-cookie/latest/js.cookie.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/aui.css" />
    <link rel="stylesheet" type="text/css" href="../css/iconfont.css">
    <link rel="stylesheet" type="text/css" href="../css/iconfont1.css">
    <link rel="stylesheet" type="text/css" href="../css/iconfont3.css">
    <link rel="stylesheet" type="text/css" href="../css/iconfont4.css">
    <link rel="stylesheet" type="text/css" href="../css/iconfont5.css">
    <link rel="stylesheet" type="text/css" href="../css/iconfont6.css">
    <script type="text/javascript" src="../script/aui-toast.js" ></script>
    <title>我的</title>
</head>
<body>
    <header class="aui-bar aui-bar-nav">
        <!--<a class="aui-pull-left aui-btn" id="Back">-->
            <!--<span class="aui-iconfont aui-icon-left"></span>-->
        </a>我的信息
    </header>
    <div class="aui-content aui-margin-b-15">
    <section class="aui-content" id="user-info">
        <div class="aui-list aui-media-list aui-list-noborder aui-bg-info">
            <div class="aui-list-item aui-list-item-middle">
                <div class="aui-media-list-item-inner ">
                    <div class="aui-list-item-media" style="width:5rem;"  id="Change_User_Info">
                        <img src="../image/users/thumbnail.jpg" class="aui-img-round" id="user_head_pic">
                    </div>

                    <div class="aui-list-item-inner">
                        <div class="aui-list-item-text text-white aui-font-size-18" style="display: none" id="user_name"><?php echo $name?></div>
                        <div class="aui-list-item-text text-white aui-font-size-18" id="user_uid"><i id="user_title">游客</i></div>
                        <div class="aui-list-item-text text-white"  id="BindMyPhone">
                            <div style="display: none" id="phone"><i class="iconfont icon-shoujihao aui-font-size-18"></i><?php echo $phone?></div>
                        </div>
                        <div class="aui-list-item-text text-white">
                            <div style="display: none"  id="piont"><i class="iconfont icon-ziyuan aui-font-size-16"></i><?php echo $myPoint?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="aui-grid aui-margin-b-10">
                    <div class="aui-row">
                        <ul class="aui-list aui-list-in">
                            <li class="aui-list-item" id="MyAllOrder">
                                <div class="aui-list-item-label-icon">
                                    <i class="aui-iconfont aui-icon-info aui-text-danger"></i>
                                </div>
                                <div class="aui-list-item-inner aui-list-item-arrow">
                                <div class="aui-list-item-inner">
                                    <div class="aui-list-item-title">我的订单</div>
                                    <div class="aui-list-item-right">查看更多订单</div>
                                </div>
                            </li>
                        </ul>
                        <div class="aui-col-xs-3" id="NonPayOrder">
                            <i class="iconfont icon-qianbao-" style="font-size:24px"></i>
                            <?php
                            if(sizeof(Config::_getOrder($uid,0)) != 0){
                            ?>
                            <div class="aui-badge"><?php echo sizeof(Config::_getOrder($uid,0))?></div>
                            <?php } ?>
                            <div class="aui-grid-label">待付款</div>
                        </div>
                        <div class="aui-col-xs-3" id="NonDeliverOrder">
                            <i class="iconfont icon-fahuo" style="font-size:24px"></i>
                            <?php
                            if(sizeof(Config::_getOrder($uid,1)) != 0){
                            ?>
                            <div class="aui-badge"><?php echo sizeof(Config::_getOrder($uid,1))?></div>
                            <?php } ?>
                            <div class="aui-grid-label">待发货</div>
                        </div>
                        <div class="aui-col-xs-3" id="NonReciveOrder">
                            <i class="iconfont icon-gifs" style="font-size:24px"></i>
                            <?php
                            if(sizeof(Config::_getOrder($uid,2)) != 0){
                                ?>
                                <div class="aui-badge"><?php echo sizeof(Config::_getOrder($uid,2))?></div>
                            <?php } ?>
                            <div class="aui-grid-label">待收货</div>
                        </div>
                        <div class="aui-col-xs-3" id="NonCommentOrder">
                            <i class="iconfont icon-shouhouguanli" style="font-size:24px"></i>
<!--                            --><?php
//                            if(sizeof(Config::_getOrder($uid,3)) != 0){
//                                ?>
<!--                                <div class="aui-badge">--><?php //echo sizeof(Config::_getOrder($uid,3))?><!--</div>-->
<!--                            --><?php //} ?>
                            <div class="aui-grid-label">售 后</div>
                        </div>
                    </div>
    </section>

    <section class="aui-content aui-margin-b-10">
                    <ul class="aui-list aui-list-in">
                        <li class="aui-list-item" id="Change_Addr_Info">
                            <div class="aui-list-item-label-icon">
                                <i class="aui-iconfont aui-icon-location aui-text-info"></i>
                            </div>
                            <div class="aui-list-item-inner aui-list-item-arrow">
                                <div class="aui-list-item-title">我的收货地址</div>
                            </div>
                        </li>

                        <li class="aui-list-item" id="Bind_alipay">
                            <div class="aui-list-item-label-icon">
                                <i class="iconfont icon-zhifubao aui-text-info"></i>
                            </div>
                            <div class="aui-list-item-inner aui-list-item-arrow">
                                <div class="aui-list-item-title">绑定支付宝</div>
                            </div>
                        </li>


                        <li class="aui-list-item" id="Bind_weixin">
                            <div class="aui-list-item-label-icon">
                                <i class="iconfont icon-ai-weixin aui-text-info"></i>
                            </div>
                            <div class="aui-list-item-inner aui-list-item-arrow">
                                <div class="aui-list-item-title">绑定微信</div>
                            </div>
                        </li>

                        <li class="aui-list-item" id="My_notification">
                            <div class="aui-list-item-label-icon">
                                <i class="iconfont icon-tongzhi aui-text-info"></i>
                            </div>
                            <div class="aui-list-item-inner aui-list-item-arrow">
                                <div class="aui-list-item-title">我的通知</div>
                            </div>
                        </li>
                        <li class="aui-list-item" id="Online_CS">
                            <div class="aui-list-item-label-icon">
                                <i class="iconfont icon-kefu aui-text-info"></i>
                            </div>
                            <div class="aui-list-item-inner aui-list-item-arrow">
                                <div class="aui-list-item-title">在线客服</div>
                            </div>
                        </li>
                        <?php if($bind == 1){?>
                        <li class="aui-list-item" id="Gongzonghao_follow">
                            <div class="aui-list-item-label-icon">
                                <i class="iconfont icon-WeChat aui-text-info"></i>
                            </div>
                            <div class="aui-list-item-inner aui-list-item-arrow">
                                <div class="aui-list-item-title">关注公众号</div>
                            </div>
                        </li>
                        <?php }?>
                    </ul>
    </section>

            <section class="aui-content" style="margin-left: 20px;margin-right: 20px">
                <div class="aui-col-xs-3 aui-btn aui-btn-primary aui-btn-block " id="login" style="display: none">登 录</div>
<!--                <div class="aui-btn aui-btn-primary aui-btn-block aui-col-xs-9" id="loginByWeixin" style="margin-top:10px;display: none">微信登录</div>-->
            </section>
        <section class="aui-content" style="margin-left: 20px; margin-top:10px;margin-right: 20px">

        </section>
            <section class="aui-content" style="margin-left: 20px;margin-right: 20px">
                <div class="aui-btn aui-btn-warning aui-btn-block" id="logout" style="display: none">退 出 当 前 账 户</div>
            </section>

        <section class="aui-content" style="margin: 20px">
        </section>
        <section class="aui-content" style="margin: 20px">
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
<!--            <div class="aui-dot"></div>-->
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
    $(function() {
        if ('<?php echo $weixinLoginStatus?>' == '<?php echo Config::OK?>' && '<?php echo $tag?>' == '1') {
            var toast = new auiToast();
            toast.loading({
                title:"处理中...",
                duration:600
            },function(ret){
                setTimeout(function() {
                    toast.success({
                        title: "微信登录成功",
                        duration: 800
                    });
                }, 600);
                toast.hide();
            });

            Cookies.remove('uid');
            Cookies.set('uid', '<?php echo $uid?>');
        } else if(('<?php echo $weixinLoginStatus?>' == '<?php echo Config::SQL_ERR?>' && '<?php echo $tag?>' == '1')) {
            var toast = new auiToast();

            toast.loading({
                title:"处理中...",
                duration:600
            },function(ret){
                setTimeout(function() {
                    toast.fail({
                        title: "微信登录失败",
                        duration: 600
                    });
                }, 600);
                toast.hide();
            });

            Cookies.remove('uid');
            Cookies.set('uid', '<?php echo $uid?>');
        }


        if ('<?php echo $weixinLoginStatus2?>' == '<?php echo Config::OK?>' && '<?php echo $tag?>' == '1') {
            var toast = new auiToast();
            toast.loading({
                title:"处理中...",
                duration:600
            },function(ret){
                setTimeout(function() {
                    toast.success({
                        title: "微信绑定成功",
                        duration: 1000
                    });
                }, 600);
                toast.hide();
            });

        } else if(('<?php echo $weixinLoginStatus2?>' == '<?php echo Config::WEIXIN_ALREADY_BINDED?>' && '<?php echo $tag?>' == '1')) {
            var toast = new auiToast();

            toast.loading({
                title:"处理中...",
                duration:600
            },function(ret){
                setTimeout(function() {
                    toast.fail({
                        title: "微信已绑定其他账号",
                        duration: 2000
                    });
                }, 600);
                toast.hide();
            });

        }else if(('<?php echo $weixinLoginStatus2?>' == '<?php echo Config::SQL_ERR?>' && '<?php echo $tag?>' == '1')) {
            var toast = new auiToast();

            toast.loading({
                title:"处理中...",
                duration:600
            },function(ret){
                setTimeout(function() {
                    toast.fail({
                        title: "微信绑定失败",
                        duration: 2000
                    });
                }, 600);
                toast.hide();
            });
        }
    });

    $("#Back").click(function () {
        $(location).attr('href', '/?fuid=<?php echo $uid?>');
    })

    if(<?php echo $bind?> == 1){
        $('#logout').show();
        $('#login').hide();
        $('#loginByWeixin').hide();
        $('#user_uid').html('<?php echo $uid?>');
        $('#phone').show();
        $('#piont').show();
        $('#user_name').show();
        $('#user_head_pic').attr('src','<?php echo $thumbnail?>')
    }else{
        $('#logout').hide();
        $('#login').show();
        $('#loginByWeixin').show();
    }



    $('#login').unbind('click').click(function () {
        $(location).attr('href', './bind.php?fuid=<?php echo $uid?>');
    });

    $('#logout').unbind('click').click(function () {
        Cookies.remove('uid');
        $.get("./Logout.php", function(result){
            if($.trim(result) == <?php echo Config::OK?>){
                $(location).attr('href', '/');
            }
        });

    });
    $('#Online_CS').unbind('click').click(function () {
        if(<?php echo $bind?> ==1){
            // $(location).attr('href', './OnlineCustomerService.php');
            var dialog = new auiDialog();
            dialog.alert({
                title:"官方客服QQ:<?php echo Config::$_customerQQ?>",
                msg:"任何问题请直接咨询",
                buttons:['好哒']
            },function(ret){
                if(ret.buttonIndex == 2) {
                }
            })
        }else{
            $(location).attr('href', './bind.php?fuid=<?php echo $uid?>&return=./OnlineCustomerService.php?fuid=<?php echo $uid?>');
        }
    });
    $('#My_notification').unbind('click').click(function () {
        if(<?php echo $bind?> ==1){
            $(location).attr('href', './MyNotify.php?fuid=<?php echo $uid?>');
        }else{
            $(location).attr('href', './bind.php?fuid=<?php echo $uid?>&return=./MyNotify.php?fuid=<?php echo $uid?>');
        }
    });
    $('#NonPayOrder').unbind('click').click(function () {
        if(<?php echo $bind?> ==1){
            $(location).attr('href', './MyOrder.php?&fuid=<?php echo $uid?>&status=0');
        }else{
            $(location).attr('href', './bind.php?return=./MyOrder.php?fuid=<?php echo $uid?>&status=0');
        }
    });
    $('#NonDeliverOrder').unbind('click').click(function () {
        if(<?php echo $bind?> ==1){
            $(location).attr('href', './MyOrder.php?fuid=<?php echo $uid?>&status=1');
        }else{
            $(location).attr('href', './bind.php?fuid=<?php echo $uid?>&return=./MyOrder.php?fuid=<?php echo $uid?>&status=1');
        }
    });
    $('#NonReciveOrder').unbind('click').click(function () {
        if(<?php echo $bind?> ==1){
            $(location).attr('href', './MyOrder.php?fuid=<?php echo $uid?>&status=2');
        }else{
            $(location).attr('href', './bind.php?fuid=<?php echo $uid?>&return=./MyOrder.php?fuid=<?php echo $uid?>&status=2');
        }
    });
    $('#NonCommentOrder').unbind('click').click(function () {
        if(<?php echo $bind?> ==1){
            $(location).attr('href', './MyOrder.php?fuid=<?php echo $uid?>&status=3');
        }else{
            $(location).attr('href', './bind.php?fuid=<?php echo $uid?>&return=./MyOrder.php?fuid=<?php echo $uid?>&status=3');
        }
    });
    $('#MyAllOrder').unbind('click').click(function () {
        if(<?php echo $bind?> ==1){
            $(location).attr('href', './MyAllOrder.php?fuid=<?php echo $uid?>');
        }else{
            $(location).attr('href', './bind.php?fuid=<?php echo $uid?>&return=./MyAllOrder.php?fuid=<?php echo $uid?>');
        }
    });

    $('#Gongzonghao_follow').unbind('click').click(function () {
        $(location).attr('href', '<?php echo Config::$_weixinGongZongHaoUrl?>');
    });

</script>

<script type="text/javascript">

    $("#Change_User_Info").click(function(){
        if(<?php echo $bind?> ==1){
            $(location).attr('href', '../html/change_user_info.html');
        }else{
            $(location).attr('href', './bind.php?fuid=<?php echo $uid?>&return=../html/change_user_info.html');
        }
    });
    $("#BindMyPhone").click(function(){
        if(<?php echo $bind?> ==1){
            $(location).attr('href', './BindMyPhone.php?fuid=<?php echo $uid?>');
        }else{
            $(location).attr('href', './bind.php?fuid=<?php echo $uid?>&return=./BindMyPhone.php?fuid=<?php echo $uid?>');
        }
    });
    $("#Change_Addr_Info").click(function(){
        if(<?php echo $bind?> ==1){
            $(location).attr('href', './ChangeAddrInfo.php?fuid=<?php echo $uid?>');
        }else{
            $(location).attr('href', './bind.php?fuid=<?php echo $uid?>&return=./ChangeAddrInfo.php?fuid=<?php echo $uid?>');
        }
    });
    $("#Bind_alipay").click(function(){
        if(<?php echo $bind?> ==1){
            $(location).attr('href', './BindMyAlipay.php?fuid=<?php echo $uid?>');
        }else{
            $(location).attr('href', './bind.php?fuid=<?php echo $uid?>&return=./BindMyAlipay.php?fuid=<?php echo $uid?>');
        }
    });

    $("#Bind_weixin").click(function(){
        if(<?php echo $bind?> ==1){
            $(location).attr('href', './BindMyWeixin.php?fuid=<?php echo $uid?>');
        }else{
            $(location).attr('href', './bind.php?fuid=<?php echo $uid?>&return=./BindMyWeixin.php?fuid=<?php echo $uid?>');
        }
    });

    $("#loginByWeixin").click(function(){
        $(location).attr('href', './LoginByWeixin.php');
    });
</script>


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
                $(location).attr('href', './Share.php?fuid=<?php echo $uid?>');
                break;
            case 4:
                $(location).attr('href', './My.php?fuid=<?php echo $uid?>');
                break;
            default:
                $(location).attr('href', './My.php?fuid=<?php echo $uid?>');

        }


    });

    var tab = new auiTab({
        element:document.getElementById("footer1")
    },function(ret){
        console.log(ret);
    });
</script>


</html>
