<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(-1);
require_once 'Config.php';

Config::_setAnchor();

$uid = Config::_uidInit();
$fatherUid = Config::_getFatherUid($uid)!=Config::SQL_ERR?Config::_getFatherUid($uid):"";
$fatherName = Config::_getUserName($fatherUid)!=Config::SQL_ERR?Config::_getUserName($fatherUid):"";
$goodList = Config::_goodList($uid);
$flowerUp = Config::_getMyFlowerUp($uid);
$redbagUpGot = Config::_getMyRedbagUp($uid);
$redbagUpTransfer = Config::_getMyRedbagUpTransfer($uid);
$redbagRedRemaing = $redbagUpGot - $redbagUpTransfer;

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
    <title>来自美丽分享官的红包</title>
    <style type="text/css">
        /*.aui-padded-15{*/
            /*width: auto;*/
            /*margin: 10px;*/
        /*}*/
    </style>
</head>
<body>
<header class="aui-bar aui-bar-nav">
    <a class="aui-pull-left aui-btn" id="Back">
        <span class="aui-iconfont aui-icon-left">返回</span>
    </a>来自美丽分享官的红包
</header>
<div class="aui-list" id="user-info">
    <div class="aui-list-item-inner aui-border-b">
        <div class="aui-list-item-title aui-padded-15">
            <div class="aui-col-xs-4 aui-margin-l-10"> 红包余额
            </div>
            <div class="aui-col-xs-4">
                <div class="aui-col-xs-3">￥<?php echo $redbagRedRemaing?></div>
            </div>
            <div class="aui-col-xs-8 aui-padded-10">
                <div class="aui-btn aui-btn-primary aui-btn-block" id="transfer">转入分享余额</div>
            </div>
        </div>
    </div>
</div>

<ul class="aui-list aui-list-in">
    <li class="aui-list-item" id="MyAllOrder">
        <div class="aui-list-item-label-icon">
            <img src="../image/redbag.png" style="width: 32px;height: 32px">
        </div>
        <div class="aui-list-item-inner aui-list-item-arrow">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-title">收到的红包总数</div>
                <div class="aui-list-item-right">￥<?php echo $redbagUpGot?></div>
            </div>
    </li>
</ul>



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
        $(location).attr('href', './shareMyUpper.php?fuid=<?php echo $uid?>');
    })

    var dialog = new auiDialog();
    $('#transfer').click(function() {
            if(<?php echo $redbagRedRemaing?> == 0){
                dialog.alert({
                    title:"提示",
                    msg: "余额不足",
                    buttons:['好哒']
                });
                return;
            }

            dialog.prompt({
                    title:"转入分享余额 (红包余额:<?php echo $redbagRedRemaing?>元)",
                    text:'输入金额',
                    type:'number',
                    buttons:['取消','转入']
                },function(ret1){
                    if(ret1.buttonIndex == 2){
                        if(!(ret1.text > 0 && !isNaN(ret1.text) && ret1.text != "")){
                            dialog.alert({
                                title:"提示",
                                msg: "金额必须是数字且大于0",
                                buttons:['好哒']
                            });
                            return;
                        }
                        if(ret1.text > <?php echo $redbagRedRemaing?>){
                            dialog.alert({
                                title:"提示",
                                msg: "金额不能大于您的余额",
                                buttons:['好哒']
                            });
                            return;
                        }

                        var toast = new auiToast();
                        toast.loading({
                            title:"处理中...",
                            duration:600
                        },function(ret){
                            setTimeout(function(){
                                $.get("./RedbagTransfer.php?uid=<?php echo $uid?>"+"&redbag="+ret1.text, function(result){
                                    if($.trim(result) == <?php echo Config::OK?>){
                                        var toast = new auiToast();
                                        toast.success({
                                            title:"转入成功",
                                            duration:2000
                                        });
                                        $(location).attr('href', './shareMyUpperSentRedBag.php?fuid=<?php echo $uid?>');
                                    }
                                });
                                toast.hide();
                            }, 600)
                        });

                    }
                }
            );
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
                $(location).attr('href', './Share.php?fuid=<?php echo $uid?>');

        }


    });

    var tab = new auiTab({
        element:document.getElementById("footer1")
    },function(ret){
        console.log(ret);
    });
</script>


</html>
