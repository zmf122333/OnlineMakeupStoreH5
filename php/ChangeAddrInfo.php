<?php
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

$phone = Config::_getUserGoodsPhone($uid);

$bind=0;
if(Config::_isBindUser($uid)){
    //already binded.
    //pass to next step: can create order and pay
    $bind=1;
}else{
    //unbinded user.
    $bind=0;
}

$return = '';

if(array_key_exists('return',$_GET)){
    $return = $_GET['return'];
};

if($return == '' ){
    $return = './My.php?fuid='.$uid;
}


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
    <title>我的收货地址</title>
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
        <!--        <span class="aui-iconfont aui-icon-left">返回</span>-->
    </a>我的收货地址
</header>

<div class="aui-content aui-margin-b-15">
    <ul class="aui-list aui-form-list">
        <li class="aui-list-item aui-border-b">
            <div class="aui-list-item-label">
                收货人：
            </div>
            <div class="aui-list-item-input">
                <input type="text" placeholder="请输入您的姓名" id="myName" value="<?php echo Config::_getUserName($uid)?>">
            </div>
        </li>
        <li class="aui-list-item aui-border-b">
            <div class="aui-list-item-label">
                手机号：
            </div>
            <div class="aui-list-item-input">
                <input type="text" placeholder="请输入您的手机号" id="myPhone" value="<?php echo Config::_getUserGoodsPhone($uid)?>">
            </div>
        </li>
        <li class="aui-list-item aui-border-b">
            <div class="aui-list-item-label">收货地址：</div>
        </li>
        <li class="aui-col-xs-12 " style="margin-left: 8%">
            省份 <select class="aui-btn aui-btn-info" id="seachprov" name="seachprov" onChange="changeComplexProvince(this.value, sub_array, 'seachcity', 'seachdistrict');"></select><br>
            城市 <select class="aui-btn aui-btn-info" id="seachcity" name="homecity" onChange="changeCity(this.value,'seachdistrict','seachdistrict');"></select><br>
            <span id="seachdistrict_div">区县 <select class="aui-btn aui-btn-info" id="seachdistrict" name="seachdistrict"></select></span><br>
        </li>
        <li class="aui-list-item aui-border-b" style="margin-left: 4%">
            <div class="aui-list-item-input">
                乡镇/街道/门牌<input type="text" placeholder="请输入详细的乡镇/街道/门牌号" id="street_info" value="<?php echo $street?>">
            </div>
        </li>
        <div class="aui-content aui-padded-10">
            <div class="aui-btn aui-btn-primary aui-btn-block" id="change_name_addr">保 存</div>
        </div>
        <div class="aui-content aui-padded-10">
            <div class="aui-btn aui-btn-warning aui-btn-block" id="Back">返 回</div>
        </div>
        <li class="aui-list-item">
        </li>
        <li class="aui-list-item">
        </li>
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
                var addrId = getAreaID();
                var street = $("#street_info").val();
                var name = $('#myName').val();
                var phone = $('#myPhone').val();
                if(addrId == '' || addrId.length == 2 || name == '' || phone == '' || phone.length != 11|| street==''){
                    toast.fail({
                        title:"姓名、手机号、地址需补全",
                        duration:2000
                    });
                    return;
                }
                if( addrId == "<?php echo $district_id?>" && street== "<?php echo $street?>" && phone == "<?php echo $phone?>" && name == "<?php echo Config::_getUserName($uid)?>" ) {
                    toast.custom({
                        title:"姓名、手机号、地址未变化",
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
                        var province_id = $("#seachprov").val();
                        var city_id = $("#seachcity").val();
                        var district_id = $("#seachdistrict").val();
                        var province = area_array[province_id];
                        var city = '';
                        var district = '';
                        var street = $("#street_info").val();
                        if(addrId.length == 2){
                            province = area_array[province_id];
                        }else if(addrId.length == 4){
                            var index1 = addrId.substring(0, 2);
                            province = area_array[province_id];
                            city = sub_array[index1][city_id];
                        }else if(addrId.length == 6){
                            var index1 = addrId.substring(0, 2);
                            var index2 = addrId.substring(0, 4);
                            province = area_array[province_id];
                            city = sub_array[index1][city_id];
                            district = sub_arr[index2][district_id];
                        }

                        $.get("./ChangeNamePhoneAddr.php?name="+name+"&phone="+phone+"&province="+province+"&city="+city+
                            "&district="+district+"&street="+street+"&province_id="+province_id+"&city_id="+
                            city_id+"&district_id="+district_id+"&uid=<?php echo $uid?>", function(result){
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
