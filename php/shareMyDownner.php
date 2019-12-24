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
$shareList = Config::_getMySharePeople($uid);
$prettyShare=array();
$visitor=array();
$j=0;
$k=0;
for($i=0;$i<sizeof($shareList);$i++){
    if($shareList[$i]['sumMoney'] >= Config::$vip_bench){
        $prettyShare[$j]=$shareList[$i];
        $j++;
    }else{
        $visitor[$k]=$shareList[$i];
        $k++;
    }
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
    <script type="text/javascript" src="../script/aui-toast.js" ></script>
    <title>我的美丽分享</title>
</head>
<body>
<header class="aui-bar aui-bar-nav">
    <a class="aui-pull-left aui-btn" id="Back">
    <span class="aui-iconfont aui-icon-left">返回</span>
    </a>我的美丽分享
</header>

<div class="aui-tab" id="tab">
    <div class="aui-tab-item aui-active">美丽分享</div>
    <div class="aui-tab-item">到店访客</div>
</div>

<div class="aui-list" id="prettyShare">
    <section class="aui-content aui-grid">
        <div class="aui-row">
            <div class="aui-col-xs-8">
                <div class="aui-gird-lable aui-font-size-14">分享人数</div>
                <big class="aui-text-warning"><?php echo sizeof($prettyShare)?><small> 人</small></big><br>
                <small style="padding: 10px; margin: 10px;color: #9e9e9e">被分享的人购买任意一款产品才算您的美丽分享人数</small>
            </div>
            <?php if(sizeof($prettyShare)>0){?>
            <div class="aui-col-xs-4 aui-padded-15">
                <div class="aui-btn aui-btn-warning aui-btn-block" id="give">全部奖励</div>
            </div>
            <?php }?>
        </div>
    </section>

    <ul class="aui-list aui-list-in" style="background-color: #f5f5f5">
        <?php
        for($i=0;$i<sizeof($prettyShare);++$i) {
            ?>
            <li class="aui-list-item" id="Change_Addr_Info" style="margin-bottom: 5px; background-color: #ffffff">
                <div class="aui-list-item-inner">
                    <div class="aui-col-xs-2" aui-padded-5>
                        <img style="width: 45px;height: 45px;" src="<?php echo Config::_uidHeadPic($prettyShare[$i]['uid'])?>" class="aui-img-round" id="user_head_pic">
                    </div>
                    <div class="aui-col-xs-4 aui-list-item-title aui-font-size-12"><span><?php echo $prettyShare[$i]['name']?><br><span><?php echo $prettyShare[$i]['phone']?><br></span><?php echo $prettyShare[$i]['uid']?>
                    </div>
                    <div class="aui-col-xs-3 aui-list-item-title aui-font-size-12">订单数:<?php echo $prettyShare[$i]['orderNum']?><br>总消费:<span><?php echo $prettyShare[$i]['sumMoney']?></span>
                    </div>
                    <div class="aui-col-xs-2 aui-padded-5">
                        <div class="aui-margin-3 giveRedBag"><img style="width: 42px;height: 42px;" src="../image/redbag.png"><i style="display: none"><?php echo $prettyShare[$i]['uid']?></i></div>
                    </div>
<!--                    <div class="aui-col-xs-2 aui-padded-5">-->
<!--                        <div class="aui-margin-3 giveFlower"><img style="width: 42px;height: 42px;" src="../image/flower.png"><i style="display: none">--><?php //echo $prettyShare[$i]['uid']?><!--</i></div>-->
<!--                    </div>-->
                </div>
            </li>
            <?php
        }
        ?>
    </ul>



    <li class="aui-list-item" style="background-color: #f5f5f5">
    </li>

    <li class="aui-list-item" style="background-color: #f5f5f5">
    </li>
</div>


<div class="aui-list" id="visitor" style="display: none">
    <section class="aui-content aui-grid" >
        <div class="aui-row" style="margin-bottom: 5px;">
            <div class="aui-col-xs-8">
                <div class="aui-gird-lable aui-font-size-14">访客人数</div>
                <big class="aui-text-warning"><?php echo sizeof($visitor)?><small> 人</small></big>
            </div>
            <?php if(sizeof($visitor)>0){?>
                <div class="aui-col-xs-4 aui-padded-15">
<!--                    <div class="aui-btn aui-btn-warning aui-btn-block" id="give">全部打赏</div>-->
                </div>
            <?php }?>
        </div>
    </section>

    <ul class="aui-list aui-list-in" style="background-color: #f5f5f5">
        <?php
        for($i=0;$i<sizeof($visitor);++$i) {
            ?>
            <li class="aui-list-item" id="Change_Addr_Info" style="margin-bottom: 5px; background-color: #ffffff">
                <div class="aui-list-item-inner">
                    <div class="aui-col-xs-3" aui-padded-5>
                        <img style="width: 40px;height: 40px;" src="<?php echo Config::_uidHeadPic($visitor[$i]['uid'])?>" class="aui-img-round" id="user_head_pic">
                    </div>
                    <div class="aui-col-xs-6 aui-list-item-title aui-font-size-12"><?php echo $visitor[$i]['uid']?><br><span><?php echo $visitor[$i]['name']?></span>
                    </div>
                    <div class="aui-col-xs-3 aui-padded-5">
<!--                        <div class="aui-btn aui-btn-warning aui-btn-block aui-margin-5" id="giveRedBag">打 赏</div>-->
<!--                        <div class="aui-btn aui-btn-primary aui-btn-block aui-margin-5" id="giveCoupon">送 券</div>-->
                    </div>
                </div>
            </li>
            <?php
        }
        ?>
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
        element:document.getElementById("tab"),
    },function(ret){
        // console.log(ret);
        switch(ret.index)
        {
            case 1:
                $('#prettyShare').show();
                $('#visitor').hide();
                break;
            case 2:
                $('#prettyShare').hide();
                $('#visitor').show();
                break;
            default:
                $('#prettyShare').show();
                $('#visitor').hide();

        }
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
        $(location).attr('href', './Share.php?fuid=<?php echo $uid?>');
    })

</script>
<script type="text/javascript" src="../script/aui-dialog.js" ></script>
<script type="text/javascript">
    var dialog = new auiDialog();
    $('.aui-margin-3.giveFlower').each(function() {
        var _index = $(".aui-margin-3.giveFlower").index($(this));
        var cuid=$('.aui-margin-3.giveFlower').eq(_index).find('i').html();
        $(".aui-margin-3.giveFlower").eq(_index).unbind('click').click(function () {
            if(<?php echo Config::_getMyFlowerDown($uid)?> == 0){
                dialog.alert({
                    title:"提示",
                    msg: "小红花数量不足",
                    buttons:['好哒']
                });
                return;
            }

            dialog.prompt({
                    title:"送花 (余额:<?php echo Config::_getMyFlowerDown($uid)?>朵)",
                    text:'输入小红花数量',
                    type:'number',
                    buttons:['取消','送出']
                },function(ret1){
                    if(ret1.buttonIndex == 2){
                        if(!(ret1.text > 0 && !isNaN(ret1.text) && ret1.text != "")){
                            dialog.alert({
                                title:"提示",
                                msg: "小红花数量必须是数字且大于0",
                                buttons:['好哒']
                            });
                            return;
                        }
                        if(ret1.text > <?php echo Config::_getMyFlowerDown($uid)?>){
                            dialog.alert({
                                title:"提示",
                                msg: "小红花数量不能大于您的余额",
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
                                $.get("./FlowerGive.php?fuid=<?php echo $uid?>&cuid="+cuid+"&flower="+ret1.text, function(result){
                                    if($.trim(result) == <?php echo Config::OK?>){
                                        var toast = new auiToast();
                                        toast.success({
                                            title:"送花成功",
                                            duration:2000
                                        });
                                        $(location).attr('href', './shareMyDownner.php?fuid=<?php echo $uid?>');
                                    }
                                });
                                toast.hide();
                            }, 600)
                        });

                    }
                }
            );
         });
    });


    $('.aui-margin-3.giveRedBag').each(function() {
        var _index = $(".aui-margin-3.giveRedBag").index($(this));
        var cuid=$('.aui-margin-3.giveRedBag').eq(_index).find('i').html();
        $(".aui-margin-3.giveRedBag").eq(_index).unbind('click').click(function () {
            if(<?php echo Config::_getMyRemainingShareMoney($uid)?> == 0){
                dialog.alert({
                    title:"提示",
                    msg: "余额不足",
                    buttons:['好哒']
                });
                return;
            }

            dialog.prompt({
                    title:"发红包 (余额:<?php echo Config::_getMyRemainingShareMoney($uid)?>元)",
                    text:'输入金额',
                    type:'number',
                    buttons:['取消','发红包']
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
                        if(ret1.text > <?php echo Config::_getMyRemainingShareMoney($uid)?>){
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
                                $.get("./RedbagGive.php?fuid=<?php echo $uid?>&cuid="+cuid+"&redbag="+ret1.text, function(result){
                                    if($.trim(result) == <?php echo Config::OK?>){
                                        var toast = new auiToast();
                                        toast.success({
                                            title:"发红包成功",
                                            duration:2000
                                        });
                                        $(location).attr('href', './shareMyDownner.php?fuid=<?php echo $uid?>');
                                    }
                                });
                                toast.hide();
                            }, 600)
                        });

                    }
                }
            );
        });
    });

    $('#give').unbind('click').click(function () {

        if(<?php echo Config::_getMyRemainingShareMoney($uid)?> == 0){
                dialog.alert({
                    title:"提示",
                    msg: "余额不足",
                    buttons:['好哒']
                });
                return;
        }

        if(<?php echo sizeof($prettyShare)?> == 0){
            dialog.alert({
                title:"提示",
                msg: "您没有分享人",
                buttons:['好哒']
            });
            return;
        }

            dialog.prompt({
                    title:"发红包 共<?php echo sizeof($prettyShare)?>人(余额:<?php echo Config::_getMyRemainingShareMoney($uid)?>元)",
                    text:'输入金额，所有人均摊该红包',
                    type:'number',
                    buttons:['取消','发红包']
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
                        if(ret1.text > <?php echo Config::_getMyRemainingShareMoney($uid)?>){
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
                                $.get("./RedbagGiveAll.php?fuid=<?php echo $uid?>&redbag="+ret1.text, function(result){
                                    if($.trim(result) == <?php echo Config::OK?>){
                                        var toast = new auiToast();
                                        toast.success({
                                            title:"发红包成功",
                                            duration:2000
                                        });
                                        $(location).attr('href', './shareMyDownner.php?fuid=<?php echo $uid?>');
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

</html>
