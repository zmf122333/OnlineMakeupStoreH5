<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/26 0026
 * Time: 03:31
 */

ini_set('date.timezone','Asia/Shanghai');
error_reporting(-1);
require_once 'Config.php';

Config::_setAnchor();

$uid = Config::_uidInit();

$goodList = Config::_goodList($uid);
if(Config::_isBindUser($uid)){
    //already binded.
    //pass to next step: can create order and pay
    $bind=1;
}else{
    //unbinded user.
    $bind=0;
}
$myVipId = Config::_getMyVipId($uid);
$myVipInfo = Config::_getMyVipInfo($myVipId);
$myDiscount = 10;
if(Config::_isSharedUser($uid) && $myVipId == 0){
    $myDiscount = 9;
}else{
    $myDiscount = $myVipInfo[0]['discount'];
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
    <title>购物车</title>
    <script type="text/javascript" src="../script/aui-toast.js" ></script>
    <link rel="stylesheet" type="text/css" href="../css/iconfont.css">
    <link rel="stylesheet" type="text/css" href="../css/iconfont1.css">
    <link rel="stylesheet" type="text/css" href="../css/iconfont2.css">
    <script type="text/javascript" src="../script/aui-dialog.js" ></script>
    <style type="text/css">
        #flt
        {
            width:100%;
            height:50px;
            top:81.2%;
            margin-bottom: 120px;
            background: #ffffff;
            position:fixed;
            z-index: 999;
            bottom:0px;
            left:0px;

        }
        #Inquery{
            padding-left: 5%;
            padding-top: 3%;
            height:50px;
            /*border-top: 1px #d1d1d1;*/
        }
        #Sumup{
            height:50px;
            padding: 5px;
            padding-top: 3.3%;
            /*border-top: 1px #d1d1d1;*/
        }
        #Bill{
            padding-left: 13%;
            padding-top: 3.3%;
            height:50px;
            background-color: grey;
        }
        #tips-1{
            width:100%;
            height:200px;
            margin-top:15%;
            background: transparent;
            color: darkgray;
        }
        #tips-1>img{
            width:50px;
            height:100px;
            margin-top:15%;
            margin-left: 45%;
            color: darkgray;
        }
        #vacant_cart{
            margin-top:5px;
            margin-left: 33%;
        }
        #promotion_word{
            margin-top:5px;
            margin-left: 15%;
        }
        .close{
            width: 32px;
            height: 32px;
            margin-right:-15px;
            background-image: url("../image/close.png");
            background-size:100% 100%
        }

        .aui-list .aui-list-item-inner{
            min-height: 2.75rem;
        }
    </style>
</head>
<body>
<div id="tips-1" style="display:none;">
    <img src="../image/logo0.png"></br>
    <div class="aui-tips-title" id="vacant_cart">购物车就这么空了</div>
    <div class="aui-tips-title" id="vacant_cart">&nbsp;&nbsp;&nbsp;对自己好一点</div>
    <div class="aui-tips-title" id="vacant_cart">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
</div>

    <div class="aui-content aui-margin-b-15">
        <ul class="aui-list aui-media-list" style="background-color: #f5f5f5">
            <?php
                 for($i=0;$i<sizeof($goodList);++$i){
            ?>
                   <li class="aui-list-item" style="margin-bottom: 5px; background-color: #ffffff">
                       <div class="aui-media-list-item-inner">
                           <div class="aui-list-item-media" style="margin-top: 45px;margin-right: -40px">
                               <input type="checkbox" class="aui-radio" name="item_check">
                           </div>
                           <div class="aui-list-item-media">
                               <img src="<?php echo $goodList[$i]['thumbnail']?>">
                           </div>
                           <div class="aui-list-item-inner">
                               <div class="aui-list-item-text">
                                   <div class="aui-list-item-title"><?php echo $goodList[$i]['name']?></div>
                                   <div class="aui-list-item-right" style="margin-top:-14px"><div class="close"><a class="item_goodId" style="display: none"><?php echo $goodList[$i]['goodId']?></a></div></div>
                               </div>
                               <div class="aui-info aui-margin-t-5 aui-list-item-inner" style="margin-left:40px;">
                                   <div class="aui-bar aui-bar-btn aui-bar-btn-sm" style="float:right;margin-top:-20px;padding: 10%">
                                       <div class="aui-bar-btn-item">
                                           <i class="aui-iconfont aui-icon-minus"></i>
                                       </div>
                                       <div class="aui-bar-btn-item">
                                           <a type="number" class="aui-input aui-text-center"><?php echo $goodList[$i]['sum']?></a>
                                       </div>
                                       <div class="aui-bar-btn-item">
                                           <i class="aui-iconfont aui-icon-plus"></i>
                                       </div>
                                   </div>
                               </div>
                               <div class="aui-info aui-margin-t-5" style="padding:0;">
                                   <div class="aui-info-item" style="color: orange">
                                       数量：<a style="color: red" class="item_num"><?php echo $goodList[$i]['sum']?></a>
                                   </div>
                                   <div class="aui-info-item" style="color: orange">价格：￥<a style="color: red" class="item_money"><?php echo $goodList[$i]['price']?></a></div>
                               </div>
                           </div>
                       </div>
                   </li>

            <?php
                 }
             ?>
            <?php
            if(sizeof($goodList)>=3){
            ?>
                <li class="aui-list-item">
                </li>
                <li class="aui-list-item">
                </li>
                <?php
            }
            ?>
        </ul>
    </div>

    <div id="flt" class="aui-border-t">
        <div class="aui-col-xs-4 aui-border-t" tapmode  id="Inquery">
            <div class="aui-bar-tab-label aui-text-info"><input type="checkbox" name="item_all_check" id="All" class="aui-radio" style="margin-top:3px;"><a style="margin-top:10rem;padding:10px;font-size: 13px;line-height: 2.4">全选</a>
            </div>
        </div>
        <div class="aui-col-xs-4 aui-border-t" tapmode  id="Sumup">
            <div class="aui-bar-tab-label aui-text-warning" style="padding:5px;font-size: 13px">合计:<a style="color: red">￥</a><a style="color: red" id="item_sum">0</a></div>
        </div>
        <div class="aui-col-xs-4  aui-text-white" tapmode  id="Bill">结算</div>
    </div>


    <footer class="aui-bar aui-bar-tab aui-border-t" id="footer">
        <div class="aui-bar-tab-item" tapmode >
            <i class="aui-iconfont aui-icon-home"></i>
            <div class="aui-bar-tab-label">商城</div>
        </div>
        <div class="aui-bar-tab-item aui-active" tapmode >
            <div class="aui-badge" style="display: none"></div>
            <i class="aui-iconfont aui-icon-cart"></i>
            <div class="aui-bar-tab-label">购物车</div>
        </div>
        <div class="aui-bar-tab-item" tapmode >
<!--            <div class="aui-dot"></div>-->
            <i class="iconfont icon-networking" style="font-size:21px"></i>
            <div class="aui-bar-tab-label">美丽分享</div>
        </div>
        <div class="aui-bar-tab-item" tapmode>
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





<script type="text/javascript">

     var item_total_num = <?php echo sizeof($goodList)?>;

     function toDecimal2(x) {
         var f = parseFloat(x);
         if (isNaN(f)) {
             return false;
         }
         var f = Math.round(x*100)/100;
         var s = f.toString();
         var rs = s.indexOf('.');
         if (rs < 0) {
             rs = s.length;
             s += '.';
         }
         while (s.length <= rs + 2) {
             s += '0';
         }
         return s;
     }

     function  refreshTotalMoney(){
         var sum = 0;
         var item_selected_num = $("input[name='item_check']:checked").length;

         if ( item_selected_num < item_total_num ) {
             $("#All").each(function () {
                 this.checked = false;
             })
         }else{
             $("#All").each(function () {
                 this.checked = true;
             })
         }

         if ($(".aui-radio").is(":checked")){
             $("input[name='item_check']:checked").each(function () {
                 var _index =  $(".aui-radio").index($(this));
                 sum=sum*1 + $(".item_money").eq(_index).html()*1;
                 $("#item_sum").html(toDecimal2(sum));
             })
             if($("#All").is(":checked")) {
                 if ( item_selected_num == 0 ) {
                     $("#item_sum").html(0);
                 }
             }
         }else{
             $("#item_sum").html(0);
         }
     }

     function lightTheBill(){
         if($("#item_sum").html()>0){
             $("#Bill").css('background-color','red');
         }else{
             $("#Bill").css('background-color','grey');
         }
     }

     
     function OrderedGoodsListStr() {
         var goodListStr='';
         var _length = $("input[name='item_check']:checked").length;
         _length = parseInt(_length);
         var i=0;
         $("input[name='item_check']:checked").each(function () {
             var _index =  $(".aui-radio").index($(this));
             var goodId=$(".item_goodId").eq(_index).html();
             var goodNum=$('.item_num').eq(_index).html();
             i++;
             if(i == _length){
                 goodListStr+=goodId+'-'+goodNum;
                 return;
             }
             goodListStr+=goodId+'-'+goodNum+',';
         })
         return goodListStr;
     }
     

     $(".aui-radio").unbind('click').click(function () {
        refreshTotalMoney();
        lightTheBill();
    })



     $(".close").unbind('click').click(function () {
         var _index = $(".close").index($(this));
         var goodId = $(".item_goodId").eq(_index).html();
         var dialog = new auiDialog();
         dialog.alert({
             title:"提示",
             msg:"确认删除该商品",
             buttons:['取消','确认']
         },function(ret){
             if(ret.buttonIndex == 2){
                 var toast = new auiToast();
                 toast.loading({
                     title:"处理中...",
                     duration:600
                 },function(ret){
                     setTimeout(function(){
                         $.get("./DeleteCartGoodInServer.php?goodId="+goodId+"&uid=<?php echo $uid?>", function(result){
                             if($.trim(result) == <?php echo Config::OK?>){
                                 toast.success({
                                     title:"删除成功",
                                     duration:600
                                 });
                                 // _index++;
                                 $(".aui-list-item").eq(_index).remove();
                                 var badge = $('.aui-badge').html();
                                 badge=badge*1 - 1*1;
                                 if(badge ==0){
                                     $('.aui-badge').hide();
                                     $('#tips-1').show();
                                 }else{
                                     $('.aui-badge').html(badge);
                                 }
                                 refreshTotalMoney();
                                 lightTheBill();
                             }else{
                                 toast.fail({
                                     title:"删除失败，请稍后重试",
                                     duration:1000
                                 });
                             }
                         });
                         toast.hide();
                     }, 600)
                 });
             }
         });

     })


     $("#All").unbind('click').click(function () {
         if ($("#All").is(":checked")) {
             $(".aui-radio").each(function () {
                 this.checked = true;
             })

             var sum = 0;
             $(".item_money").each(function () {
                 sum=sum*1+$(this).html()*1;
             })
             $("#item_sum").html(toDecimal2(sum));
         }else{
             $(".aui-radio").each(function () {
                 this.checked = false;
             })

             $("#item_sum").html(0);
         }
         lightTheBill();
     })



     var u = navigator.userAgent;
     var ua = navigator.userAgent.toLowerCase();
     var client ='';
     var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端
     var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
     if(ua.match(/MicroMessenger/i)=="micromessenger") { //微信内置浏览器
        client = 'WeiXin';
     }else{
         if(isiOS){
             client = 'iOS-H5';
         }else if(isAndroid){
             client = 'Android-H5';
         }else{
             client = 'PC';
         }
     }

     $('#Bill').unbind('click').click(function () {
         if(<?php echo $bind?> ==0){
             $(location).attr('href', './bind.php?fuid=<?php echo $uid?>&return=./Cart.php?fuid=<?php echo $uid?>');
             return;
         }

         if($("#item_sum").html()>0){
             //generate the Order Id:
             var money=$('#item_sum').html()*100; //结算金额：单位分
             money=money*<?php echo $myDiscount?>*0.1; //VIP折扣后的价格
             var subject="购物车结算"; //商品名称
             //generate the Ordered Goods List:
             var goodListStr=OrderedGoodsListStr();
             var type=1; //0-direct bill;1-cart bill
             $.get("./CreateOrder.php?money="+money+"&uid=<?php echo $uid?>&client="+client+"&goodListStr="+goodListStr+"&type="+type, function(result){
                 //to bill:
                 if($.trim(result) == 'SYS_ERR'){
                     var toast = new auiToast();
                     toast.fail({
                         title:"下单失败，请稍后重试！",
                         duration:3000
                     });
                 }else{
                     $(location).attr('href', '../php/bill.php?fuid=<?php echo $uid?>&orderId='+result+'&money='+money+'&subject='+subject);
                 }

             });

         }else{
             var toast = new auiToast();
             toast.custom({
                 title:"请先选中商品",
                 html:'<i class="aui-iconfont aui-icon-info"></i>',
                 duration:2000
             });
         }

     })


     if(<?php echo sizeof($goodList)?>>0){
         $('.aui-badge').show();
         $('.aui-badge').html(<?php echo sizeof($goodList)?>);
         $('#tips-1').hide();
     }else{
         $('#tips-1').show();
     }

     $(".aui-info.aui-margin-t-5.aui-list-item-inner").each(function(i){
         var _index = i;

         var num = $(".item_num").eq(_index).text();
         var money = $(".item_money").eq(_index).text();
         var single = toDecimal2(money/num);
         if(single <= 0){single = toDecimal2(money)};
         var toast = new auiToast();

         $(".aui-iconfont.aui-icon-plus").eq(_index).unbind('click').click(function () {
             var goodId = $(".item_goodId").eq(_index).html();
             toast.loading({
                 title:"处理中...",
                 duration:300
             },function(ret){
                 setTimeout(function(){
                     //$.get("./EnterCart.php?goodId="+goodId+"&uid=<?php //echo $uid?>//", function(result){
                     $.getJSON("./EnterCart.php?goodId="+goodId+"&uid=<?php echo $uid?>", function(data){
                         var toast = new auiToast();
                         if(data.state == <?php echo Config::OK?>){
                             var a = parseInt($(".aui-input.aui-text-center").eq(_index).text());
                             a=a+1;
                             $(".aui-input.aui-text-center").eq(_index).html(a);
                             $(".item_num").eq(_index).html(a);
                             $(".item_money").eq(_index).html(toDecimal2(a*single));
                         }
                         toast.hide();
                         refreshTotalMoney();
                         lightTheBill();
                     });
                 }, 300)
             });
         });

         $(".aui-iconfont.aui-icon-minus").eq(_index).unbind('click').click(function () {
             var goodId = $(".item_goodId").eq(_index).html();
             var b = parseInt($(".aui-input.aui-text-center").eq(_index).text());
             if(b<=1){return}
             toast.loading({
                 title:"处理中...",
                 duration:300
             },function(ret){
                 setTimeout(function(){
                     $.get("./DeleteCart.php?goodId="+goodId+"&uid=<?php echo $uid?>", function(result){
                         if($.trim(result) == <?php echo Config::OK?>){
                             b=b-1;
                             if(b<=1){b=1;}
                             $(".aui-input.aui-text-center").eq(_index).html(b);
                             $(".item_num").eq(_index).html(b);
                             $(".item_money").eq(_index).html(toDecimal2(b*single));
                         }
                         toast.hide();
                         refreshTotalMoney();
                         lightTheBill();
                     });
                 }, 300)
             });
         })
     })

</script>
<script type="text/javascript" src="../script/api.js" ></script>
<script type="text/javascript" src="../script/aui-tab.js" ></script>
<script type="text/javascript">
    var tab = new auiTab({
        element:document.getElementById("footer"),index:2
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
                $(location).attr('href', './Cart.php?fuid=<?php echo $uid?>&uid=<?php echo $uid?>');

        }


    });

    var tab = new auiTab({
        element:document.getElementById("footer1")
    },function(ret){
        console.log(ret);
    });
</script>

</html>
