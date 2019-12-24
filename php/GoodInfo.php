<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/24 0024
 * Time: 20:41
 */
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

ini_set('date.timezone','Asia/Shanghai');
error_reporting(-1);
require_once 'Config.php';



$uid=isset($_COOKIE['uid'])?$_COOKIE['uid']:"";
$fatherUid=isset($_GET['fuid'])?$_GET['fuid']:"";
//新的韭菜来了！！！
if($fatherUid !='' && $uid ==''){
    $uid = Guest::RegisterWithGuest(Config::_mysql());
    if ($uid != Config::SQL_ERR) {
        if(Config::_haveRightsToShare($fatherUid)){
            Config::_setFatherUid($uid,$fatherUid);
        }
        setcookie('uid', "$uid");//save uid at client via Cookie.
    }
}else if($fatherUid !='' && $uid !='' && !Config::_isBindUser($uid) && Config::_haveRightsToShare($fatherUid) && $fatherUid != $uid){
    Config::_setFatherUid($uid,$fatherUid);
}else if($uid ==''){
    $uid = Guest::RegisterWithGuest(Config::_mysql());
    if ($uid != Config::SQL_ERR) {
        setcookie('uid', "$uid");//save uid at client via Cookie.
    }
}


$goodId = '';
$category = '';
$keyword = '';
$favorite = '';
$other = '';

$backUrl = '/';

if(array_key_exists('goodId',$_GET)){
    $goodId = $_GET["goodId"];
};
if(array_key_exists('category',$_GET)){
    $category = $_GET["category"];
};
if(array_key_exists('keyword',$_GET)){
    $keyword = $_GET["keyword"];
};
if(array_key_exists('favorite',$_GET)){
    $favorite = $_GET["favorite"];
};
if(array_key_exists('other',$_GET)){
    $other = $_GET["other"];
};

if($category != ''){ $backUrl = './item_list.php?fuid='.$uid.'&category='.$category;}
if($keyword != ''){ $backUrl = './item_list.php?fuid='.$uid.'&keyword='.$keyword;}
if($favorite != ''){ $backUrl = '/';}
if($other != ''){ $backUrl = '/?fuid='.$uid;}

$result = array();
$colorArr = array();
$packArr = array();

$result = getGoodInfo($goodId);
$colorArr = explode('@',$result[0]['color']);
$packArr = explode('@',$result[0]['pack']);


function getGoodInfo($goodId){
    $s = Config::_mysql()->query("SELECT pack,comingSoon,name,price,promotionPrice,promotionInfo,brand,net,selfLife,color,model,special,effect,origin,longDesc FROM good where goodId='$goodId'");
    if(mysql_affected_rows()>0){
        return Config::_mysql()->to2DArray($s);
    }else{
        return Config::NULL;
    }
}

$goodList = Config::_goodList($uid);

if(Config::_isBindUser($uid)){
    //already binded.
    //pass to next step: can create order and pay
    $bind=1;
}else{
    //unbinded user.
    $bind=0;
}
$return='';
if(array_key_exists('REQUEST_URI',$_SERVER)){
        $return = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
}

?>

<!DOCTYPE html>
<html lang="zh_CN">
<head>
    <meta charset="UTF-8">
    <title><?php echo $result[0]['name']?></title>
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0"/>
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no">


    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js" ></script>
    <script src="https://cdn.bootcss.com/lightbox2/2.10.0/js/lightbox.min.js"></script>
    <link href="https://cdn.bootcss.com/lightbox2/2.10.0/css/lightbox.min.css" rel="stylesheet" >
    <link rel="stylesheet" type="text/css" href="../css/aui.css" />
    <link rel="stylesheet" type="text/css" href="../css/iconfont.css">
    <link rel="stylesheet" type="text/css" href="../css/iSlider.min.css">
    <script src="../js/iSlider.js"></script>
    <script src="../js/iSlider.animate.min.js"></script>
    <script src="../js/iSlider.plugin.dot.min.js"></script>


    <script type="text/javascript" src="../script/aui-toast.js" ></script>
    <link rel="stylesheet" type="text/css" href="../css/iconfont.css">
    <link rel="stylesheet" type="text/css" href="../css/iconfont1.css">
    <link rel="stylesheet" type="text/css" href="../css/iconfont2.css">
    <script type="text/javascript" src="../script/aui-dialog.js" ></script>

    <script src="https://cdn.bootcss.com/js-cookie/latest/js.cookie.min.js"></script>

    <style>
        header {
            margin-top: 0.75rem;
        }
        .aui-searchbar {
            background: transparent;
        }
        .aui-bar-nav .aui-searchbar-input {
            background-color: #ffffff;
        }
        .aui-bar-light .aui-searchbar-input {
            background-color: #f5f5f5;
        }
        .aui-col-xs-5{
            /*border:1px solid #d1d1d1;*/
            -moz-box-shadow:1px 1px 5px #d1d1d1; -webkit-box-shadow:1px 1px 5px #d1d1d1; box-shadow:1px 1px 5px #d1d1d1;
            margin: 5px;
        }


        .aui-list .aui-list-item-inner{
            min-height: 2.75rem;
        }

    </style>

    <style type="text/css">
        #flt{
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
        #Add_Cart{
            padding-left: 13%;
            padding-top: 3.3%;
            height:50px;
        }
        #Buy_Now{
            padding-left: 13%;
            padding-top: 3.3%;
            height:50px;
        }
        .islider-dot-wrap{
            height: 40%;
        }

    </style>


    <link rel="stylesheet" type="text/css" href="../css/aui-flex.css" />
    <style type="text/css">
        img {
            display: block;
            max-width: 100%;
        }

        a,img{border:0;}

        #goodcover {
            display: none;
            position: absolute;
            top: 0%;
            left: 0%;
            width: 100%;
            height: 100%;
            background-color: black;
            z-index: 1001;
            -moz-opacity: 0.8;
            opacity: 0.50;
            filter: alpha(opacity=80);
        }
        #code {
            width: 100%;
            height: 50%;
            top:50%;
            background-color: #fff;
            /*position: absolute;*/
            display: none;
            left: 0;
            position:fixed;
            z-index: 1002;
            margin-bottom: 120px;
        }
        .close1 {
            width: 99%;
            height: 65px;
        }
        #closebt {
            float: right;
        }
        #closebt img {
            width: 20px;
        }
        .goodtxt {
            text-align: center;
        }
        .goodtxt p {
            height: 30px;
            line-height: 30px;
            font-size: 16px;
            color: #000;
            font-weight: 600;
        }
        .code-img {
            width: 250px;
            margin: 30px auto 0 auto;
            padding: 10px;
        }
        .code-img img {
            width: 240px;
        }

        .aui-bar-btn-item{
            width: 15%;
        }

    </style>

    <script type="text/javascript" src="../script/aui-toast.js" ></script>

</head>
<body>
    <header class="aui-bar aui-bar-nav">
        <a class="aui-pull-left aui-btn" id="Back">
            <span class="aui-iconfont aui-icon-left">返回</span>
        </a>
        <div class="aui-title"><?php echo $result[0]['name']?></div>
    </header>
    <div id="iSlider-wrapper" style="height: 300px;width: 100%"></div>
    <section class="aui-content">
        <div class="aui-card-list">


            <div class="aui-card-list-header">
                <strong><?php echo $result[0]['name']?></strong>
            </div>
            <p class="aui-padded-10">价格 <strong style="font-size: 20px;color: red">￥<?php echo $result[0]['promotionPrice']?></strong></p>
<!--            <p class="aui-padded-10">原价 <small style="font-size: 16px;color: red"><del>￥--><?php //echo $result[0]['price']?><!--</del></small></p>-->


                <section class="aui-content">
                    <ul class="aui-list aui-list-noborder">

                        <li class="aui-list-item">
                            <div class="aui-list-item-inner" >
                                <div class="aui-list-item-title aui-col-xs-12" style="font-size: 16px; float: right">产品名称：<?php echo $result[0]['name']?></div>
                            </div>
                        </li>

                        <div class="aui-collapse-item">
                            <li class="aui-list-item aui-collapse-header" tapmode>
                                <div class="aui-list-item-inner">
                                    <div class="aui-list-item-title">产品参数</div>
                                    <div class="aui-list-item-right">
                                        <i class="aui-iconfont aui-icon-down aui-collapse-arrow"></i>
                                    </div>
                                </div>
                            </li>
                            <div class="aui-collapse-content">
                                <li class="aui-list-item">
                                    <div class="aui-list-item-inner">
                                        <div class="aui-list-item-title aui-col-xs-6" style="font-size: 14px; "><strong>品牌: </strong><?php echo $result[0]['brand']?></div>
                                        <div class="aui-list-item-title aui-col-xs-6" style="font-size: 14px; "><strong>净含量: </strong><?php echo $result[0]['net']?></div>
                                    </div>
                                </li>
                                <li class="aui-list-item">
                                    <div class="aui-list-item-inner">
                                        <div class="aui-list-item-title aui-col-xs-6" style="font-size: 14px; "><strong>保质期: </strong><?php echo $result[0]['selfLife']?></div>
                                        <div class="aui-list-item-title aui-col-xs-6" style="font-size: 14px; "><strong>颜色分类: </strong><?php echo $result[0]['color']?></div>
                                    </div>
                                </li>
                                <li class="aui-list-item">
                                    <div class="aui-list-item-inner">
                                        <div class="aui-list-item-title aui-col-xs-6" style="font-size: 14px; "><strong>规格类型: </strong><?php echo $result[0]['model']?></div>
                                        <div class="aui-list-item-title aui-col-xs-6" style="font-size: 14px; "><strong>特殊用途: </strong><?php echo $result[0]['special']?></div>
                                    </div>
                                </li>
                                <li class="aui-list-item">
                                    <div class="aui-list-item-inner">
                                        <div class="aui-list-item-title aui-col-xs-6" style="font-size: 14px; "><strong>功效: </strong><?php echo $result[0]['effect']?></div>
                                        <div class="aui-list-item-title aui-col-xs-6" style="font-size: 14px; "><strong>产地: </strong><?php echo $result[0]['origin']?></div>
                                    </div>
                                </li>
                            </div>
                        </div>


<!--                        <li class="aui-list-item">-->
<!--                            <div class="aui-list-item-inner" >-->
<!--                                <div class="aui-list-item-title aui-col-xs-12" style="font-size: 16px; float: right">产品名称：--><?php //echo $result[0]['name']?><!--</div>-->
<!---->
<!--                            </div>-->
<!--                        </li>-->
<!---->
<!--                        <li class="aui-list-item" >-->
<!--                            <div class="aui-list-item-inner ">-->
<!--                                <div class="aui-list-item-title aui-col-xs-6" style="font-size: 14px; "><strong>品牌: </strong>--><?php //echo $result[0]['brand']?><!--</div>-->
<!--                                <div class="aui-list-item-title aui-col-xs-6" style="font-size: 14px; "><strong>净含量: </strong>--><?php //echo $result[0]['net']?><!--</div>-->
<!--                            </div>-->
<!--                        </li>-->
<!--                        <li class="aui-list-item">-->
<!--                            <div class="aui-list-item-inner ">-->
<!--                                <div class="aui-list-item-title aui-col-xs-6" style="font-size: 14px; "><strong>保质期: </strong>--><?php //echo $result[0]['selfLife']?><!--</div>-->
<!--                                <div class="aui-list-item-title aui-col-xs-6" style="font-size: 14px; "><strong>颜色分类: </strong>--><?php //echo $result[0]['color']?><!--</div>-->
<!--                            </div>-->
<!--                        </li>-->
<!--                        <li class="aui-list-item">-->
<!--                            <div class="aui-list-item-inner ">-->
<!--                                <div class="aui-list-item-title aui-col-xs-6" style="font-size: 14px; "><strong>规格类型: </strong>--><?php //echo $result[0]['model']?><!--</div>-->
<!--                                <div class="aui-list-item-title aui-col-xs-6" style="font-size: 14px; "><strong>特殊用途: </strong>--><?php //echo $result[0]['special']?><!--</div>-->
<!--                            </div>-->
<!--                        </li>-->
<!--                        <li class="aui-list-item">-->
<!--                            <div class="aui-list-item-inner ">-->
<!--                                <div class="aui-list-item-title aui-col-xs-6" style="font-size: 14px; "><strong>功效: </strong>--><?php //echo $result[0]['effect']?><!--</div>-->
<!--                                <div class="aui-list-item-title aui-col-xs-6" style="font-size: 14px; "><strong>产地: </strong>--><?php //echo $result[0]['origin']?><!--</div>-->
<!--                            </div>-->
<!--                        </li>-->
                    </ul>
                </section>


                <p class="aui-padded-10" style="font-size: 16px; color: black"><?php echo $result[0]['longDesc']?></p><br>

            <div class="aui-card-list-header">
                <img src="../image/goods/<?php echo $goodId?>/detail.jpg">
            </div>

            <p class="aui-padded-10" style="color: #9e9e9e">购买须知：</p>
            <p class="aui-padded-10" style="color:#9e9e9e">商品购买成功后，会在48小时内发货，法定节假日顺延，请在确认商品完好后再签收。</p>
            <p class="aui-padded-10" style="color:#9e9e9e">若存在质量问题，请务必在签收后7天内联系客服。客服账号：向在线客服所在的QQ反馈</p>
            <p class="aui-padded-10" style="color: #9e9e9e">售后服务承诺：</p>
            <p class="aui-padded-10" style="color:#9e9e9e">如何申请退换货：<br>1.自您签收产品快递起7日内（含），如果您所购买的产品存在质量问题，请第一时间联系客服QQ反馈。</p>
            <p class="aui-padded-10" style="color:#9e9e9e">2.签收后7天内存在不影响二次销售前提下支持因质量问题的退换货，退款将原路返回，不同的银行处理时间不同，预计1-5个工作日到账。</p><br>

            <div class="aui-card-list-header">
            </div>
            <div class="aui-card-list-header">
            </div>
        </div>

    </section>

    <div id="goodcover"></div>
    <div id="code" style="height: 330px">
        <div class="close1" style="height: 20px"><a href="javascript:void(0)" id="closebt"><img src="../image/close.gif"></a></div>
        <div class="aui-col-xs-12" style="height: 80px;margin-left: 5%">
            <div class="aui-col-xs-3 aui-padded-5"><img style="width: 64px;height: 64px" src="../image/goods/<?php echo $goodId?>/thumbnail.jpg"></div>
            <div class="aui-col-xs-9 aui-padded-5"><strong style="color: red;">￥<?php echo $result[0]['promotionPrice']?></strong><br>请选择规格属性</div>
        </div>
<!--        <div class="aui-col-xs-12">-->
<!--            <strong style="margin-left: 5%">颜色</strong>-->
<!--            <div class="aui-btn aui-btn-primary aui-margin-l-10">--><?php //echo $result[0]['color']?><!--</div>-->
<!--        </div>-->
        <div class="aui-col-xs-12" style="margin-top:3px">
            <strong style="margin-left: 5%">颜色</strong>
            <select id="ChangeColor" class="aui-btn aui-btn-primary aui-margin-l-10" style="width:15%">
                <?php for($i=0;$i<sizeof($colorArr);$i++){?>
                    <option value="<?php echo $i?>"><?php echo $colorArr[$i]?></option>
                <?php }?>
            </select>
        </div>
        <div class="aui-col-xs-12" style="margin-top:3px">
            <strong style="margin-left: 5%">包装</strong>
            <select id="ChangePack" class="aui-btn aui-btn-primary aui-margin-l-10" style="width:35%">
                <?php for($i=0;$i<sizeof($packArr);$i++){?>
                    <option value="<?php echo $i?>"><?php echo $packArr[$i]?></option>
                <?php }?>
            </select>
        </div>
        <div class="aui-info aui-list-item-inner" style="margin-left: 5%;margin-top:-5px">
            <strong style="width:16%;color:black;font-size:16px">数量</strong>
            <div class="aui-bar aui-bar-btn aui-bar-btn-sm" style="width:100%">
                <div class="aui-bar-btn-item">
                    <i class="aui-iconfont aui-icon-minus"></i>
                </div>
                <div class="aui-bar-btn-item">
                    <a type="number" class="aui-input aui-text-center" style="margin-top: 3px">1</a>
                </div>
                <div class="aui-bar-btn-item">
                    <i class="aui-iconfont aui-icon-plus"></i>
                </div>
            </div>
        </div>
        <div class="aui-btn aui-btn-primary aui-btn-block aui-col-xs-9" style="width: 90%;margin-left: 5%" id="Buy_Now_Confirm">确认购买</div>
    </div>




<?php if($result[0]['comingSoon'] == 0){ ?>
    <div id="flt" class="aui-border-t">
        <div class="aui-col-xs-6 aui-bg-warning aui-text-white" id="Add_Cart" onclick="showDefault('order')">加入购物车</div>
        <div class="aui-col-xs-6 aui-bg-danger aui-text-white"  id="Buy_Now">立即购买</div>
    </div>
<?php } ?>


    <footer class="aui-bar aui-bar-tab aui-border-t" id="footer">

        <div class="aui-bar-tab-item aui-active" tapmode >
            <i class="aui-iconfont aui-icon-home"></i>
            <div class="aui-bar-tab-label">商城</div>
        </div>
        <div class="aui-bar-tab-item" tapmode id="Cart">
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

    //set cookie at browser
    if('<?php echo $uid?>' != '' && Cookies.get('uid') == null){
        // console.error('cookie have not uid and uid is not null, so set');
        Cookies.set('uid', '<?php echo $uid?>');
    }


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

    $(function() {

        if (window.applicationCache.status == window.applicationCache.UPDATEREADY) {
            window.applicationCache.update(); }

        $('#Buy_Now').click(function() {
            body_height = parseInt($(document).height());
            $('#goodcover').css('height',body_height);
            $('#goodcover').show();
            $('#code').fadeIn();
        });
        $('#closebt').click(function() {
            $('#code').hide();
            $('#goodcover').hide();
        });
        $('#goodcover').click(function() {
            $('#code').hide();
            $('#goodcover').hide();
        });
    })




    //var data = [
    //    {content: "../image/goods/<?php //echo $goodId?>///1.jpg"},
    //    {content: "../image/goods/<?php //echo $goodId?>///2.jpg"},
    //    {content: "../image/goods/<?php //echo $goodId?>///3.jpg"},
    //    {content: "../image/goods/<?php //echo $goodId?>///4.jpg"},
    //    {content: "../image/goods/<?php //echo $goodId?>///5.jpg"}
    //];

    var data = [
        {content: "../image/goods/<?php echo $goodId?>/1.jpg"+"?rand="+Math.random()},
        {content: "../image/goods/<?php echo $goodId?>/2.jpg"+"?rand="+Math.random()},
        {content: "../image/goods/<?php echo $goodId?>/3.jpg"+"?rand="+Math.random()},
        {content: "../image/goods/<?php echo $goodId?>/4.jpg"+"?rand="+Math.random()},
        {content: "../image/goods/<?php echo $goodId?>/5.jpg"+"?rand="+Math.random()},
    ];
    var islider = new iSlider({
        dom: document.getElementById("iSlider-wrapper"),
        data: data,
        isVertical: false,
        isLooping: true,
        isDebug: false,
        isAutoplay: true,
        fixPage:false,
        // isOverspread: 1,
        animateTime: 800, // ms
        // plugins: ['dot']

    });






    $("#Back").click(function () {
          $(location).attr('href','<?php echo $backUrl ?>');
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


    var color=$('#ChangeColor option:selected').text();
    var pack=$('#ChangePack option:selected').text();
    var goodNum = 1;
    var goodListStr='<?php echo $goodId?>'+'-1'+':'+color+':'+pack;
    var goodId = '<?php echo $goodId?>';

    $(".aui-iconfont.aui-icon-plus").unbind('click').click(function () {
        var color=$('#ChangeColor option:selected').text();
        var pack=$('#ChangePack option:selected').text();
        goodNum=parseInt(goodNum)+1;
        $(".aui-input.aui-text-center").html(goodNum);
        goodListStr=goodId+'-'+goodNum+':'+color+':'+pack;
    });

    $(".aui-iconfont.aui-icon-minus").unbind('click').click(function () {
        var color=$('#ChangeColor option:selected').text();
        var pack=$('#ChangePack option:selected').text();
        if(goodNum>1){
            goodNum=parseInt(goodNum)-1;
        }
        $(".aui-input.aui-text-center").html(goodNum);
        goodListStr=goodId+'-'+goodNum+':'+color+':'+pack;
    });

    //javascript浮点数乘法有误差，该方法为精确乘法实现
    function accMul(arg1,arg2)
    {
        var m=0,s1=arg1.toString(),s2=arg2.toString();
        try{m+=s1.split(".")[1].length}catch(e){}
        try{m+=s2.split(".")[1].length}catch(e){}
        return Number(s1.replace(".",""))*Number(s2.replace(".",""))/Math.pow(10,m)
    }


      $("#Buy_Now_Confirm").click(function () {

          if(<?php echo $bind?> ==0){
              $(location).attr('href', './bind.php?return=<?php echo $return?>');
              return;
          }


          //generate the Order Id:
          var color=$('#ChangeColor option:selected').text();
          var pack=$('#ChangePack option:selected').text();
          goodListStr=goodId+'-'+goodNum+':'+color+':'+pack;
          console.error(color);
          console.error(pack);
          console.error(goodListStr);

          var subject="<?php echo $result[0]['name']?>"; //商品名称
          var single=<?php echo $result[0]['promotionPrice']?>;
          var money=accMul(accMul(single,goodNum),100); //下单和支付统一用分为单位，并保留小数点后有效数字2位
          var type=0; //0-direct bill;1-cart bill
          $.get("./CreateOrder.php?money="+ money +"&uid=<?php echo $uid?>&client="+client+"&goodListStr="+goodListStr+"&type="+type, function(result){
              //to bill:
              if($.trim(result) == '<?php echo Config::SQL_ERR?>'){
                  var toast = new auiToast();
                  toast.fail({
                      title:"下单失败，请稍后重试！",
                      duration:3000
                  });
              }else {
                  $(location).attr('href', '../php/bill.php?fuid=<?php echo $uid?>&orderId=' + result + '&money='+money+'&subject=' + subject + '&goodId=' + goodId);
              }
          });
      })

      if(<?php echo sizeof($goodList)?>>0){
          $('.aui-badge').show();
          $('.aui-badge').html(<?php echo sizeof($goodList)?>);
      }


</script>

<script type="text/javascript" src="../script/api.js" ></script>
<script type="text/javascript" src="../script/aui-tab.js" ></script>
<script type="text/javascript" src="../script/aui-collapse.js" ></script>
<script type="text/javascript">
    apiready = function(){
        api.parseTapmode();
    }

    var collapse = new auiCollapse({
        autoHide:false //是否自动隐藏已经展开的容器
    });

    var toast = new auiToast();
    function showDefault(type){
        switch (type) {
            case "collect":
                toast.success({
                    title:"收藏成功",
                    duration:2000
                });
                break;
            case "fail":
                toast.fail({
                    title:"提交失败",
                    duration:2000
                });
                break;
            case "order":
                $.getJSON("./EnterCart.php?uid=<?php echo $uid?>&goodId=<?php echo $goodId;?>", function(data) {
                        if(data.state == <?php echo Config::OK?>){
                            $('.aui-badge').show();
                            $('.aui-badge').html(data.result[0].goodIdSum);
                            toast.custom({
                                title:"已加入购物车",
                                html:'<i class="aui-iconfont aui-icon-laud"></i>',
                                duration:2000
                            });
                        }else{
                            toast.fail({
                                title:"加入购物车失败",
                                duration:2000
                            });
                        }
                    });
                break;
            case "loading":
                toast.loading({
                    title:"加载中",
                    duration:2000
                },function(ret){
                    console.log(ret);
                    setTimeout(function(){
                        toast.hide();
                    }, 3000)
                });
                break;
            default:
                // statements_def
                break;
        }
    }

</script>

<script type="text/javascript" src="../script/aui-tab.js" ></script>
<script type="text/javascript">
    var tab = new auiTab({
        element:document.getElementById("footer"),index:1
    },function(ret){
        console.log(ret);

        switch(ret.index)
        {
            case 1:
                $(location).attr('href', '/?fuid=<?php echo $uid?>');
                break;
            case 2:
                $(location).attr('href', './Cart.php?uid=<?php echo $uid?>');
                break;
            case 3:
                $(location).attr('href', './Share.php?fuid=<?php echo $uid?>');
                break;
            case 4:
                $(location).attr('href', './My.php?fuid=<?php echo $uid?>');
                break;
            default:
                $(location).attr('href', '#');

        }


    });

    var tab = new auiTab({
        element:document.getElementById("footer1")
    },function(ret){
        console.log(ret);
    });
</script>

</html>
