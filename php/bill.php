<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(-1);


require_once 'Config.php';
require_once 'Register.php';

Config::_setAnchor();

$uid = '';


$orderId=88888;
$money=0;
$subject='';

// get array check.
if(array_key_exists('orderId',$_GET)){
    $orderId = $_GET["orderId"];
};
if(array_key_exists('money',$_GET)){
    $money = $_GET["money"];
};
if(array_key_exists('subject',$_GET)){
    $subject = $_GET["subject"];
};


$ref=0;


//var_dump($_SERVER['HTTP_REFERER']);
//check parameters.
if($money >0 && $orderId !== '' && $subject !=='' && (count($_GET) == 3 || count($_GET) == 4) ){
    $ref = 1;
    $uid = Config::_uidInit();
}else if(strstr($_SERVER['HTTP_REFERER'],'alipay.com') || strstr($_SERVER['HTTP_REFERER'],"weixinPayJSAPI")
    ||strstr($_SERVER['HTTP_REFERER'],"wx.tenpay.com") ) { //支付宝的同步回调自带了多余的参数也要通过:-D
    $ref = 2;
    if(strstr($_SERVER['HTTP_REFERER'],"wx.tenpay.com") || strstr($_SERVER['HTTP_REFERER'],'alipay.com')){
        $uid = $_GET["uid"];  // other domain the cookie is diff, do not use Config::_uidInit to set uid to cookie.
        setcookie('uid', '');
        setcookie('uid', $uid);
    }else{
        $uid = Config::_uidInit();
    }

//    $return = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
//    var_dump($return);
//    exit;
}else{
    $ref = 3; // lack of parameters.
    $uid = Config::_uidInit();
}


if(!array_key_exists('HTTP_REFERER',$_SERVER) && !isset($_COOKIE['uid']) ){
    echo Config::_redirect('/?fuid='.$uid);
    exit;
}



$orderInfo = Config::_getOrderInfo($orderId,$uid,0);

if($ref != 2){
//check user bind
    if(!Config::_isBindUser($uid)){
        $return='';
        if(array_key_exists('REQUEST_URI',$_SERVER)){
            $return = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        };
        echo Config::_redirect('./bind.php?fuid='.$uid.'&return='.urlencode($return));
        exit;
    }

//check user info
    if(Config::_checkUserInfoNull($uid) == Config::USER_INFO_NULL){
        $return='';
        if(array_key_exists('REQUEST_URI',$_SERVER)){
            $return = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        };
        echo Config::_redirect("./ChangeAddrInfo.php?fuid=".$uid."&return=".urlencode($return));
        exit;
    }

//check order
//var_dump($orderInfo);
//exit;
    if(sizeof($orderInfo) == 0){
        echo Config::_redirect("/?fuid=".$uid);
        exit;
    }
}






$name='';
$phone='';
$addr='';
$goodList=array();
$freight=10;
$discount=10;
$deduct=0;
$time='';
$vipId=0;
$total_price=0;

$myVipId = Config::_getMyVipId($uid);
$myVipInfo = Config::_getMyVipInfo($myVipId);

for($i=0;$i<sizeof($orderInfo);++$i) {
    $name=$orderInfo[0]['name'];
    $phone=$orderInfo[0]['goodsPhone'];
    $addr=$orderInfo[0]['addr_province'].$orderInfo[0]['addr_city'].$orderInfo[0]['addr_district'].$orderInfo[0]['addr_street'];
    $time=$orderInfo[0]['time'];
    $vipId=$orderInfo[0]['vipId'];
    $freight=$orderInfo[0]['freight'];
    $deduct=$orderInfo[0]['deduct'];
    if(Config::_isSharedUser($uid) && $myVipId == 0){
        $discount=9; //新韭菜自购一律9折
    }else{
        $discount=$orderInfo[0]['discount'];
    }


    $goodList[$i]['goodId']=$orderInfo[$i]['goodId'];
    $goodList[$i]['thumbnail']=$orderInfo[$i]['thumbnail'];
    $goodList[$i]['goodName']=$orderInfo[$i]['goodName'];
    $goodList[$i]['goodAmount']=$orderInfo[$i]['goodAmount'];
    $goodList[$i]['promotionPrice']=$orderInfo[$i]['promotionPrice'];
    $goodList[$i]['price']=$orderInfo[$i]['price'];


    $total_price+=$orderInfo[$i]['goodAmount']*$orderInfo[$i]['promotionPrice'];
}

$myDiscount = 10;
if(Config::_isSharedUser($uid) && $myVipId == 0){
    $myDiscount=9;  //新韭菜自购一律9折
}else{
    $myDiscount = $myVipInfo[0]['discount'];
}

//vip 折扣后的价格
$money = $total_price*100*$myDiscount*0.1;
$total_price = $total_price*$myDiscount*0.1;

$moneyAlipay = $money - 100;

$flowerUp = Config::_getMyFlowerUp($uid);

$myPoint = Config::_getMyPoint($uid);

//
//if($uid == 'ODFJEW94RN2'){
//    $moneyAlipay = 200;
//    $money = 200;
//    $flowerDown = 2;
//}


//$register = new Register('grege','43g4356',1);
//$registerResult = $register->RegisterWith1($mysql);
//if($registerResult == Config::USR_EXIST){
//    echo "用户已存在";
//}else if($registerResult == Config::SQL_ERR){
//    echo "SQL 错误";
//}else{
//    echo "注册成功，uid: ".$registerResult;
//}
//
//
//$login = new Login('grege','43g4356',1);
//$loginResult = $login->loginWith1($mysql);
//if($loginResult == Config::USR_PAS_ERR){
//    echo "登录失败";
//}else{
//    echo "登录成功,uid: ".$login->loginWith1($mysql);
//}
//exit;

echo <<<HTML
<!DOCTYPE html>
<html lang="zh_CN">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js" ></script>
    <link rel="stylesheet" type="text/css" href="../css/aui.css" />
    <title>订单详情</title>
    <script type="text/javascript" src="../script/aui-dialog.js" ></script>
    <script type="text/javascript" src="../script/aui-popup-new.js" ></script>
    <script type="text/javascript" src="../script/aui-toast.js" ></script>
</head>
<body>

<header class="aui-bar aui-bar-nav">
    <a class="aui-pull-left aui-btn" id="Back">
        <span class="aui-iconfont aui-icon-left">返回</span>
    </a>订单详情
</header>

<div class="aui-content ">

    <ul class="aui-list aui-media-list" style="background-color: #f5f5f5">
        <li class="aui-list-item">
            <div class="aui-media-list-item-inner">
                <div class="aui-list-item-media">
                    收货人
                </div><a class="aui-list-item-media">$name</a>

            </div>
            <div class="aui-media-list-item-inner">
                <div class="aui-list-item-media">
                    电话
                </div><a style="margin-top: 10px">$phone</a>

            </div>
             <div class="aui-media-list-item-inner">
                <div class="aui-list-item-media">收货地址 
                </div><a class="aui-col-xs-12" style="margin-top: 10px">$addr</a>
            </div>
        </li>
        <li class="aui-list-item">

        <div class="aui-list  aui-form-list aui-margin-b-0" id="Home" style="margin-right: 15px">
            <ul class="aui-list aui-list-in" style="background-color: #f5f5f5; ">
                <li class="aui-card-list">
                    <div class="aui-card-list-header">
                        <div style="font-size: 0.50rem">订单号：$orderId</div>
                        <div style="font-size: 0.50rem">时间：$time</div>
                    </div>
HTML;


for($i=0;$i<sizeof($goodList);++$i) {
                    echo '<div class="aui-card-list-header">';
                    echo '<div class="aui-col-xs-4 aui-padded-5">';    
                    echo "<img src=".$goodList[$i]['thumbnail'].">";
                    echo '</div>';    
                    echo '<div class="aui-col-xs-4 aui-padded-5">';    
                    echo '<div class="aui-card-list-content aui-font-size-14">'.$goodList[$i]['goodName'].'</div>';
                    echo '</div>';
                    echo '<div class="aui-col-xs-2">';
                    echo ' <div class="aui-card-list-content aui-font-size-12">x'.$goodList[$i]['goodAmount'].'</div>';
                    echo '</div>';
                    echo '<div class="aui-col-xs-2">';
                    echo ' <div class="aui-card-list-content aui-font-size-12">￥'.$goodList[$i]['goodAmount']*$goodList[$i]['promotionPrice'].'</div>';
                    echo '</div>';
                    echo '</div>';
}



echo <<<END1
                    <div class="aui-card-list-footer">
                            <div class="aui-font-size-12 aui-list-item-right"></div>
                            <div class="aui-font-size-14 aui-list-item-right" style="margin-right: 10px">合计：￥$total_price</div>
                    </div>
                </li>
            </ul>
        </div>
            
            
            
            
            
        </li>
        </li>
        <li class="aui-list-item">
            <div class="aui-info-item" style="color: orange">订单号：<a style="color: red" class="out_trade_no">$orderId</a></div>
        </li>
        <li class="aui-list-item">
            <div class="aui-info-item" style="color: orange">价格：￥<a style="color: red" id="total_price">$total_price</a>&nbsp;<small>(美丽分享折扣: $myDiscount 折)</small></div>
        </li>
        <li class="aui-list-item">
            <div class="aui-info-item" style="color: orange">运费：￥<a style="color: red"  id="freight_price">$freight</a></div>
        </li>
        <li class="aui-list-item">
            <div class="aui-info-item" style="color: orange">抵扣：￥-<a style="color: red"  id="deduct_price">0</a><div class="aui-btn aui-btn-primary" id="littleFlower" style="margin-left:5px;">$myPoint 分积分可用</div></div>
        </li>
        <li class="aui-list-item">
            <div class="aui-info-item" style="color: orange">总计：￥<a style="color: red"  id="sum_price"></a></div>
        </li>
        <li class="aui-list-item" id="msg"></li>
    </ul>


</div>



<footer class="aui-bar aui-bar-tab aui-border-t" id="footer">
    <div class="aui-bar-tab-item" tapmode style="width: auto;">
        <div class="aui-bar-tab-label aui-text-warning">总计:<a style="color: red">￥</a><a style="color: red" id="item_sum"></a></div>
    </div>
    <!--<div class="aui-bar-tab-item aui-bg-warning aui-text-white" tapmode style="width: auto;"  onclick="showDefault('order')">加入购物车</div>-->
    <div class="aui-bar-tab-item  aui-text-white" tapmode style="width: auto;background-color: red" id="Bill" onclick="showPopup('bottom')">立即支付</div>
</footer>



</body>


<script type="text/javascript" src="../script/api.js"></script>
<script type="text/javascript" src="../script/aui-actionsheet.js" ></script>
<script type="text/javascript">
    apiready = function(){
        api.parseTapmode();
    }
    var actionsheet = new auiActionsheet();
    function openActionsheet(){
        actionsheet.init({
            frameBounces:true,//当前页面是否弹动，（主要针对安卓端）
            title:"修改头像",
            cancelTitle:'取消',
            buttons:['拍照','图库选择']
        },function(ret){
            if(ret){
                //  document.getElementById("button-index").textContent = ret.buttonIndex;
                //  document.getElementById("button-title").textContent = ret.buttonTitle;
            }
        })
    }
</script>

<script type="text/javascript">

    $('#littleFlower').click(function() {
        if($myPoint == 0){
                dialog.alert({
                    title:"提示",
                    msg: "积分不足",
                    buttons:['好哒']
                });
                return;
            }

            dialog.prompt({
                    title:"抵扣 (余额:$myPoint 分)",
                    text:'输入积分数量',
                    type:'number',
                    buttons:['取消','抵扣']
                },function(ret1){
                    if(ret1.buttonIndex == 2){
                        if(!(ret1.text >= 0 && !isNaN(ret1.text) && ret1.text != "")){
                            dialog.alert({
                                title:"提示",
                                msg: "积分数量必须是数字且大于等于0",
                                buttons:['好哒']
                            });
                            return;
                        }
                        if(ret1.text > $myPoint){
                            dialog.alert({
                                title:"提示",
                                msg: "积分数量不能大于您的余额",
                                buttons:['好哒']
                            });
                            return;
                        }
                         
                        $('#deduct_price').html(ret1.text);
                        var remainFlower = $myPoint - $.trim(ret1.text);
                        $('#littleFlower').html(remainFlower+" 分积分可抵扣");
                        var totalMoney = $('#total_price').html();
                        var remainMoney = totalMoney - $.trim(ret1.text);
                        if(remainMoney<=1){
                             dialog.alert({
                                title:"提示",
                                msg: "积分抵扣数超过订单价格，请重新设置抵扣积分！",
                                buttons:['好哒']
                            });
                            return;
                        }
                        $('#item_sum').html(remainMoney);
                        $('#sum_price').html(remainMoney);
                        
                    }
                }
            );
    });
        
    




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

    $("#Back").click(function () {
        $(location).attr('href', '/');
    })
    
    $(document).ready(function(){
        var m = $('#total_price').html();
        var n = $('#freight_price').html();
        var p = $('#deduct_price').html();
        m=parseFloat(m);
        n=parseFloat(n);
        p=parseFloat(p);
        var s=m-n-p;
        $('#sum_price').html(toDecimal2(s));
        $('#item_sum').html(toDecimal2(s));
    });
</script>

<script type="text/javascript" src="../js/ap.js"></script>
<script type="text/javascript">
    var u = navigator.userAgent;
    var ua = navigator.userAgent.toLowerCase();
    var popup = new auiPopup();

    function showPopup(location,orderId){
        popup.init({
            frameBounces:true,//当前页面是否弹动，（主要针对安卓端）
            location:location,//位置，top(默认：顶部中间),top-left top-right,bottom,bottom-left,bottom-right
            buttons:[{
                image:'../image/share/wx.png',
                text:'微信支付',
                value:'wx'//可选
            },{
                image:'../image/share/zhifubao.png',
                text:'支付宝  <a style="color: orange;font-size: small">立减1元</a>',
                value:'wx-circle'
            }],
        },function(ret){
            var deductMoney = $('#deduct_price').html();
            var moneyAlipay1 = $moneyAlipay - deductMoney*100;
            var moneyWeixin1 = $money - deductMoney*100;
            var toast = new auiToast();
            $.get("./SetDeductMoney.php?orderId=$orderId&deductMoney="+deductMoney,function(result){
                if($.trim(result) == 200){
                    
                }else{
                   // toast.fail({
                   //      title:"抵扣失败,请稍后重试！",
                   //      duration:2000
                   //  });   
                }
            });
            
            
            console.log(ret.buttonIndex);
            if(ret.buttonIndex == 2){
  
END1;
             $return_url0=urlencode(Config::$_payReturnUrl.'?uid='.$uid.'&orderId='.$orderId);
             $return_url=Config::$_payReturnUrl.'?uid='.$uid.'&orderId='.$orderId;
             echo  '_AP.pay("'.Config::$_alipayH5Url.'?money="+moneyAlipay1+"&orderId='.$orderId.'&subject='.urlencode($subject)
                             .'&return_url='.$return_url.'");';
             echo '}else if(ret.buttonIndex == 1){';
             echo ' if(ua.match(/MicroMessenger/i)=="micromessenger"){';
             echo 'window.location.href = "'.Config::$_weixinPayJSAPIUrl.'?money='.'"+moneyWeixin1+"'.'&orderId='.$orderId.'&subject='.urlencode($subject)
                                            .'&return_url='.$return_url.'";';
             echo '}else{';
             echo '_AP.pay("'.Config::$_weixinPayH5Url.'?total_fee="+moneyWeixin1+"&out_trade_no='.$orderId.'&subject='.urlencode($subject)
                             .'&return_url='.$return_url0.'");';

echo <<<END2
                } 
	        }
        })
    }
</script>

<script type="text/javascript">
    var dialog = new auiDialog();
    function pay_result_hook_pop() {
            dialog.alert({
                // title:"温馨提示",
                msg: "请确认支付是否已完成",
                buttons:['支付遇到问题','已完成支付']
            },function(ret) {
                if(ret.buttonIndex == 1){
                    dialog.alert({
                        // title:"提示",
                        msg: "支付遇到问题，重新支付",
                        buttons:['重新支付']
                    },function(ret) {
                        if(ret.buttonIndex == 1){
END2;
                            echo '$(location).attr(\'href\', \'./bill.php?orderId='.$orderId.'&money='.$money.'&subject='.$subject.'\');';
echo <<<END3
                            return;
                        }
                    });
                    return;
                }
                if(ret.buttonIndex == 2){
END3;
                   echo '$.get("./pay_check.php?orderId='.$orderId.'", function(result){';
                   echo 'if($.trim(result) == \'true\'){';
                   echo '$(location).attr(\'href\', \'./bill_ok.php?orderId='.$orderId.'\');';
echo <<<END4
                           }else{
                                    var toast = new auiToast();
                                    toast.fail({
                                        title:"你支付尚未完成",
                                        duration:2000
                                    });
                           }
                     });  
                    return;
                }
            });
    }
</script>




END4;
//支付宝H5、微信内微信支付、微信H5支付 同步回调
if(!array_key_exists('HTTP_REFERER',$_SERVER)){
    echo Config::_redirect('/');
    exit;
}
if(strstr($_SERVER['HTTP_REFERER'],"weixinPayJSAPI")||strstr($_SERVER['HTTP_REFERER'],"wx.tenpay.com") || strstr($_SERVER['HTTP_REFERER'],"alipay.com")) {
echo <<<END5
<script type="text/javascript">
$(document).ready(function(){
END5;
        //去支付宝和微信支付异步回调的结果查询真正的支付结果
       echo '$.get("./pay_check.php?uid='.$uid.'&orderId='.$orderId.'", function(result){';
       echo 'if($.trim(result) == \'true\'){';
       echo '$(location).attr(\'href\', \'./bill_ok.php?orderId='.$orderId.'\');';
echo <<<END6
			}else{
                pay_result_hook_pop();
             }
         }); 
  });
</script>

</html>
END6;

}


?>





