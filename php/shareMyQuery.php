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
$province = '';
$city = '';
$district = '';
$street = '';
$province_id = '0';
$city_id = '0';
$district_id = '0';

$addr_array = Config::_getAddrInfo($uid);
if(sizeof($addr_array)>0){
    $province = $addr_array[0]['addr_province'];
    $city = $addr_array[0]['addr_city'];
    $district = $addr_array[0]['addr_district'];
    $street = $addr_array[0]['addr_street'];
    $province_id = $addr_array[0]['addr_province_id'];
    $city_id = $addr_array[0]['addr_city_id'];
    $district_id = $addr_array[0]['addr_district_id'];
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
    <script src="../js/Area.js" type="text/javascript"></script>
    <script src="../js/areaData_min.js" type="text/javascript"></script>
    <title>区域查询</title>
    <style type="text/css">
        #seachprov,#seachcity,#seachdistrict{
            width: auto;
            margin: 5px;
        }
    </style>
</head>
<body>
<header class="aui-bar aui-bar-nav">
    <a class="aui-pull-left aui-btn" id="Back">
        <span class="aui-iconfont aui-icon-left">返回</span>
    </a>区域查询
</header>

<div class="aui-list" style="margin-bottom: 5px; background-color: #f5f5f5">
        <div class="aui-col-xs-7">
            <li class="aui-col-xs-12" style="margin-left: 8%">
                省份 <select class="aui-btn aui-btn-info" id="seachprov" name="seachprov" onChange="changeComplexProvince(this.value, sub_array, 'seachcity', 'seachdistrict');"></select><br>
                城市 <select class="aui-btn aui-btn-info" id="seachcity" name="homecity" onChange="changeCity(this.value,'seachdistrict','seachdistrict');"></select><br>
                <span id="seachdistrict_div">区县 <select class="aui-btn aui-btn-info" id="seachdistrict" name="seachdistrict"></select></span><br>
            </li>
        </div>
        <div class="aui-col-xs-5 aui-padded-15">
            <div class="aui-btn aui-btn-warning aui-btn-block" id="give">查询</div>
        </div>
</div>


<!---->
<!--<ul class="aui-list aui-list-in aui-margin-b-15" >-->
<!--    --><?php
//    for($i=0;$i<sizeof($MyBonusSettlementHistory);$i++){
//        ?>
<!--        <li class="aui-list-item" id="myBlanceHistory">-->
<!--            <div class="aui-list-item-inner">-->
<!--                <div class="aui-list-item-title aui-font-size-12">--><?php //echo $MyBonusSettlementHistory[$i]['time']?><!--</div>-->
<!--                <div class="aui-list-item-title aui-font-size-12">￥--><?php //echo $MyBonusSettlementHistory[$i]['bonus']?><!--</div>-->
<!--                <div class="aui-list-item-title aui-font-size-12">--><?php //echo $MyBonusSettlementHistory[$i]['channel']?><!--</div>-->
<!--                <div class="aui-list-item-right">--><?php //echo $MyBonusSettlementHistory[$i]['status']?><!--</div>-->
<!--            </div>-->
<!--        </li>-->
<!--        --><?php
//    }
//    ?>
<!--</ul>-->


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

<script type="text/javascript">
    $(function (){
        initComplexArea('seachprov', 'seachcity', 'seachdistrict', area_array, sub_array, '0', '0', '0');
        initSelect();
    });

    function initSelect() {
        if ( '<?php echo $province_id?>'== '11' || '<?php echo $province_id?>' == '12' || '<?php echo $province_id?>' == '31'
            || '<?php echo $province_id?>' == '71' || '<?php echo $province_id?>' == '50' || '<?php echo $province_id?>' == '81'
            || '<?php echo $province_id?>' == '82') {
            changeComplexProvince('<?php echo $province_id?>', sub_array, 'seachcity', 'seachdistrict');
            $("#seachprov option[value='<?php echo $province_id?>']").attr("selected",true);
            $("#seachcity option[value='<?php echo $city_id?>']").attr("selected",true);
            return;
        }

        changeComplexProvince('<?php echo $province_id?>', sub_array, 'seachcity', 'seachdistrict');
        changeCity('<?php echo $city_id?>','seachdistrict','seachdistrict');
        $("#seachprov option[value='<?php echo $province_id?>']").attr("selected",true);
        $("#seachcity option[value='<?php echo $city_id?>']").attr("selected",true);
        $("#seachdistrict option[value='<?php echo $district_id?>']").attr("selected", true);
    }

    //得到地区码
    function getAreaID(){
        var area = 0;
        if($("#seachdistrict").val() != "0"){
            area = $("#seachdistrict").val();
        }else if ($("#seachcity").val() != "0"){
            area = $("#seachcity").val();
        }else{
            area = $("#seachprov").val();
        }
        return area;
    }


    // function showAreaID() {
    //     //地区码
    //     var areaID = getAreaID();
    //     //地区名
    //     var areaName = getAreaNamebyID(areaID) ;
    //     alert("您选择的地区码：" + areaID + "      地区名：" + areaName);
    // }

    //根据地区码查询地区名
    function getAreaNamebyID(areaID){
        var areaName = "";
        if(areaID.length == 2){
            areaName = area_array[areaID];
        }else if(areaID.length == 4){
            var index1 = areaID.substring(0, 2);
            areaName = area_array[index1] + " " + sub_array[index1][areaID];
        }else if(areaID.length == 6){
            var index1 = areaID.substring(0, 2);
            var index2 = areaID.substring(0, 4);
            areaName = area_array[index1] + " " + sub_array[index1][index2] + " " + sub_arr[index2][areaID];
        }
        return areaName;
    }


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



</html>
