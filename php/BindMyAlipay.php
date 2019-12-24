<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(-1);
require_once 'Config.php';
$uid = Config::_uidInit();

Config::_setAnchor();

$goodList = Config::_goodList($uid);


$return = '';

if(array_key_exists('return',$_GET)){
    $return = $_GET['return'];
};

if($return == '' ){
    $return = './My.php?fuid='.$uid;
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


$myAlipayAccount=Config::_getMyAlipayAccount($uid)!=Config::SQL_ERR?Config::_getMyAlipayAccount($uid):'';

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
    <link rel="stylesheet" type="text/css" href="../css/iconfont2.css">
    <script src="../js/Area.js" type="text/javascript"></script>
    <script src="../js/areaData_min.js" type="text/javascript"></script>
    <title>绑定支付宝</title>
    <style type="text/css">
        #seachprov,#seachcity,#seachdistrict{
            width: auto;
            margin: 5px;
        }
    </style>
</head>
<body>

<header class="aui-bar aui-bar-nav">
    <a class="aui-pull-left aui-btn">
                <span class="aui-iconfont aui-icon-left" id="Back">返回</span>
    </a>绑定支付宝
</header>

<div class="aui-content aui-margin-b-15">
    <ul class="aui-list aui-form-list">
        <li class="aui-list-item">
            <div class="aui-list-item-label aui-font-size-14">
                支付宝账号
            </div>
            <div class="aui-list-item-input">
                <input class="aui-font-size-12" type="text" placeholder="请输入支付宝账号或支付宝绑定的手机号" id="myAlipayAccount" value="<?php echo $myAlipayAccount?>">
            </div>
        </li>
        <small style="font-size: 14px; color: lightgrey; margin-left: 10px">请绑定收货人姓名对应的支付宝账户</small>
        <div class="aui-content aui-padded-10">
            <div class="aui-btn aui-btn-primary aui-btn-block" id="change_name_addr">保 存</div>
        </div>
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

<script type="text/javascript">
    $("#Back").click(function () {
        $(location).attr('href', './My.php?fuid=<?php echo $uid?>');
    })

    $("#change_name_addr").click(function () {
        var dialog = new auiDialog();
        dialog.alert({
            title:"提示",
            msg:"确认保存",
            buttons:['取消','确认']
        },function(ret){
            if(ret.buttonIndex == 2){
                var toast = new auiToast();
                var alipay = $("#myAlipayAccount").val();
                if(myAlipayAccount == ''){
                    toast.fail({
                        title:"支付宝账号不能为空",
                        duration:2000
                    });
                    return;
                }
                if( alipay == "<?php echo $myAlipayAccount?>" ) {
                    toast.custom({
                        title:"支付宝账号未变化",
                        html:'<i class="aui-iconfont aui-icon-info"></i>',
                        duration:1000
                    });
                    return;
                }
                toast.loading({
                    title:"处理中...",
                    duration:600
                },function(ret){
                    setTimeout(function(){
                        $.get("./BindMyAlipayAccount.php?alipayAccount="+alipay, function(result){
                            if($.trim(result) == <?php echo Config::OK?>){
                                toast.success({
                                    title:"保存成功",
                                    duration:600
                                });
                                $(location).attr('href', '<?php echo $return?>');
                            }else{
                                toast.fail({
                                    title:"保存失败，请稍后重试",
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
</script>
<script type="text/javascript" src="../script/aui-dialog.js" ></script>
<script type="text/javascript" src="../script/aui-toast.js" ></script>
<script type="text/javascript" src="../script/api.js"></script>
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

</script>





</html>
