<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(-1);
require_once 'Config.php';

Config::_setAnchor();

$uid = Config::_uidInit();

$goodList = Config::_goodList($uid);

$bind=0;

if(Config::_isBindUser($uid)){
    //already binded.
    //pass to next step: can create order and pay
    $bind=1;
}else{
    //unbinded user.
    $bind=0;
}

?>

<!DOCTYPE html>
<html lang="zh_CN">
<head>
    <meta charset="UTF-8">
    <title>咨询</title>
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0"/>
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js" ></script>
    <script src="https://cdn.bootcss.com/js-cookie/latest/js.cookie.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/aui.css" />
    <link rel="stylesheet" type="text/css" href="../css/iconfont.css">
    <link rel="stylesheet" type="text/css" href="../css/iconfont1.css">
    <link rel="stylesheet" type="text/css" href="../css/iconfont2.css">
</head>
<body>
<header class="aui-bar aui-bar-nav">
    <a class="aui-pull-left aui-btn" id="Back">
        <span class="aui-iconfont aui-icon-left">返回</span>
    </a>
    <div class="aui-title">咨询产品</div>
</header>


<section class="aui-chat">
    <div class="aui-chat-header">2017年11月3日</div>
    <div class="aui-chat-item aui-chat-left">
        <div class="aui-chat-media">
            <img src="../image/demo2.png" />
        </div>
        <div class="aui-chat-inner">
            <div class="aui-chat-name"> <span class="aui-label aui-label-warning">客服</span></div>
            <div class="aui-chat-content">
                <div class="aui-chat-arrow"></div>
                Hello！
            </div>
            <div class="aui-chat-status aui-chat-status-refresh">
                <i class="aui-iconfont aui-icon-correct aui-text-success"></i>
            </div>
        </div>
    </div>
    <div class="aui-chat-item aui-chat-right">
        <div class="aui-chat-media">
            <img src="../image/liulangnan.png" />
        </div>
        <div class="aui-chat-inner">
            <div class="aui-chat-name">Tony</div>
            <div class="aui-chat-content">
                <div class="aui-chat-arrow"></div>
                你好！
            </div>
            <div class="aui-chat-status">
                <i class="aui-iconfont aui-icon-info aui-text-danger"></i>
            </div>
        </div>
    </div>
    <div class="aui-chat-item aui-chat-left">
        <div class="aui-chat-media">
            <img src="../image/demo2.png" />
        </div>
        <div class="aui-chat-inner">
            <div class="aui-chat-name"> <span class="aui-label aui-label-warning">客服</span></div>
            <div class="aui-chat-content">
                <div class="aui-chat-arrow"></div>
                <img src="../image/l1.png" />
            </div>
        </div>
    </div>
    <div class="aui-chat-item aui-chat-right">
        <div class="aui-chat-media">
            <img src="../image/liulangnan.png" />
        </div>
        <div class="aui-chat-inner">
            <div class="aui-chat-name">Tony</div>
            <div class="aui-chat-content">
                <div class="aui-chat-arrow"></div>
                需要什么样的口红呢
            </div>
        </div>
    </div>
    <div class="aui-chat-item aui-chat-left">
        <div class="aui-chat-media">
            <img src="../image/demo2.png" />
        </div>
        <div class="aui-chat-inner">
            <div class="aui-chat-name"> <span class="aui-label aui-label-warning">客服</span></div>
            <div class="aui-chat-content">
                <div class="aui-chat-arrow"></div>
                <img src="../image/l2.png" />
            </div>
        </div>
    </div>

</section>

<div class="aui-bar aui-bar-tab" id="chat">
    <div class="aui-bar-tab-item aui-text-info aui-bg-success" tapmode style="width: 3rem;">
        <i class="aui-iconfont aui-icon-phone aui-text-white"></i>
    </div>
    <div class="aui-list-item-input aui-bg-normal " style="border: 3rem;padding:0.1rem;margin-left:0.3rem;border-color: #00bbd4">
        <input type="text" placeholder="请输入文字 向客服咨询">
    </div>
    <div class="aui-bar-tab-item aui-text-info aui-bg-success" tapmode style="width: 3rem;">
        <div class="aui-bar-tab-label aui-text-white">发送</div>
    </div>
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
