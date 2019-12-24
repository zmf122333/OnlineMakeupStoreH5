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

$return = '';

if(array_key_exists('return',$_GET)){
    $return = $_GET['return'];
};


if($return == '' ){
    $return = '/?fuid='.$uid;
}

?>

<!DOCTYPE html>
<html lang="zh_CN">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0"/>
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js" ></script>
<!--    <script src="https://cdn.bootcss.com/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>-->
    <script src="https://cdn.bootcss.com/js-cookie/latest/js.cookie.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/aui.css" />
    <script type="text/javascript" src="../script/aui-toast.js" ></script>
    <title>登 录</title>
    <style type="text/css">
        .demo_line_05{
            letter-spacing: -1px;
            color: #ddd;
            margin-left:5% ;
        }
        .demo_line_05 span{
            letter-spacing: 0;
            color: #ddd;
            margin:0 20px;
            font-size: 12px;
        }
    </style>
</head>
<body>
<header class="aui-bar aui-bar-nav">
    登 录|注 册
</header>


<div class="aui-tab" id="tab">
    <div class="aui-tab-item aui-active">手机登录</div>
    <div class="aui-tab-item">用户名密码登录</div>
</div>

<div id="CellPhoneLogin">
    <div class="aui-content aui-margin-b-15">
        <ul class="aui-list aui-form-list">
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-label-icon">
                        <i class="aui-iconfont aui-icon-phone"></i>
                    </div>
                    <div class="aui-list-item-input">
                        <input id="CellPhone" type="text" placeholder="手机号">
                    </div>
                </div>
            </li>
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-label-icon">
                        <i class="aui-iconfont aui-icon-info"></i>
                    </div>
                    <div class="aui-list-item-input">
                        <input id="VerificationCode" type="text" placeholder="验证码">
                    </div>
                    <div class="aui-content aui-col-xs-12 aui-padded-5 aui-margin-l-15">
                        <input type="button" class="aui-btn aui-btn-primary aui-btn-block" value="获取验证码" id="getVerificationCode"></input>
                    </div>
                </div>
            </li>
        </ul>
    </div>

    <div class="aui-content aui-col-xs-12 aui-padded-10">
        <div class="aui-btn aui-btn-primary aui-btn-block" id="loginByPhone">登 录</div>
    </div>
    <div class="aui-content aui-padded-10">
        <div class="aui-btn aui-btn-warning aui-btn-block" id="back1">返 回</div>
    </div>
</div>


<div id="UserPasswordLogin" style="display: none">
    <div class="aui-content aui-margin-b-15">
        <ul class="aui-list aui-form-list">
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-label-icon">
                        <i class="aui-iconfont aui-icon-my"></i>
                    </div>
                    <div class="aui-list-item-input">
                        <input id="UerName" type="text" placeholder="请输入6位及以上长度的用户名">
                    </div>
                </div>
            </li>
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-label-icon">
                        <i class="aui-iconfont aui-icon-lock"></i>
                    </div>
                    <div class="aui-list-item-input">
                        <input id="PassWord" type="password" placeholder="请输入6位及以上长度的密码">
                    </div>
                    <div class="aui-list-item-label-icon" id="showPassword">
                        <i class="aui-iconfont aui-icon-display"></i>
                    </div>
                </div>
            </li>
        </ul>
    </div>
    <div class="aui-content aui-col-xs-6 aui-padded-10">
        <div class="aui-btn aui-btn-primary aui-btn-block" id="register">注 册</div>
    </div>
    <div class="aui-content aui-col-xs-6 aui-padded-10">
        <div class="aui-btn aui-btn-primary aui-btn-block" id="login">登 录</div>
    </div>
    <div class="aui-content aui-padded-10">
        <div class="aui-btn aui-btn-warning aui-btn-block" id="back2">返 回</div>
    </div>
</div>

<br/>
<br/>
<br/>

<div class="demo_line_05">————————<span>其他方式</span>————————</div>

<div class="aui-content aui-padded-10">
    <div class="aui-btn aui-btn-primary aui-btn-block" id="loginByWeixin">微信登录</div>
</div>

</body>


<script type="text/javascript" src="../script/api.js" ></script>
<script type="text/javascript" src="../script/aui-tab.js" ></script>
<script type="text/javascript">
    apiready = function(){
        api.parseTapmode();
    }

    var tab = new auiTab({
        element:document.getElementById("tab"),
    },function(ret){
        switch(ret.index)
        {
            case 1:
                $('#CellPhoneLogin').show();
                $('#UserPasswordLogin').hide();
                break;
            case 2:
                $('#CellPhoneLogin').hide();
                $('#UserPasswordLogin').show();
                break;
            default:
                $('#CellPhoneLogin').show();
                $('#UserPasswordLogin').hide();

        }
    });

</script>


<script type="text/javascript">

    function isPoneAvailable(str) {
        var myreg=/^1([358][0-9]|4[579]|66|7[0135678]|9[89])[0-9]{8}$/;
        if (!myreg.test(str)) {
            return false;
        } else {
            return true;
        }
    }

    var countdown=60;
    function settime(val) {
        if (countdown == 0) {
            val.removeAttribute("disabled");
            $(val).css('background', '#00bcd4');
            val.value="获取验证码";
            countdown = 60;
            return;
        } else {
            val.setAttribute("disabled", true);
            $(val).css('background','#bdbdbd')
            val.value="重新获取(" + countdown + "s)";
            countdown--;
        }
        setTimeout(function() {
            settime(val)
        },1000)
    }


    $('#getVerificationCode').on('click',function () {
        var phone = $('#CellPhone').val();
        var toast = new auiToast();

        if( !isPoneAvailable(phone) ){
            toast.fail({
                title:"无效手机号码！",
                duration:2000
            });
        }else{
            that = this;
            $.get("./VerificationCode.php?phone="+phone,function(result){
                if($.trim(result) == <?php echo Config::OK;?>){
                    settime(that);
                    toast.success({
                        title:"获取成功！",
                        duration:2000
                    });
                }else if($.trim(result) == <?php echo Config::PHONE_INVALID;?>){
                    toast.fail({
                        title:"非法手机号码！",
                        duration:2000
                    });
                }else{
                    toast.fail({
                        title:"验证码获取异常，请稍后重试！",
                        duration:2000
                    });
                }
            });
        }
    })

    $('#loginByPhone').on('click',function () {
        var phone = $('#CellPhone').val();
        var code = $('#VerificationCode').val();
        var toast = new auiToast();
        console.error(code);
        $.getJSON("./CheckCode.php?&phone="+phone+"&code="+code,function(data){
            if(data.state == <?php echo Config::OK;?>){
                Cookies.remove('token');
                Cookies.set('token', data.token);
                Cookies.remove('uid');
                Cookies.set('uid', data.uid);
                toast.success({
                    title:"登录成功！",
                    duration:2000
                });
                $(location).attr('href', './My.php?fuid=<?php echo $uid?>');
            }else{
                toast.fail({
                    title:"登录失败！",
                    duration:2000
                });
            }
        });
    })


</script>


<script type="text/javascript">
    
    $('#showPassword').on('click',function () {
        if($('#PassWord').attr('type') == 'password'){
            $('#PassWord').attr('type', 'text');
        }else{
            $('#PassWord').attr('type', 'password');
        }
    })

    $("#loginByWeixin").click(function(){
        $(location).attr('href', './LoginByWeixin.php');
    });
    
    $('#register').on('click',function () {
        var uid = '<?php echo $uid;?>';
        var userName = $('#UerName').val();
        var passWord = $('#PassWord').val();
        var toast = new auiToast();

        if(userName == '' || passWord == '' || userName.length <6 || passWord.length <6){
            toast.fail({
                title:"用户名密码为空或低于6位！",
                duration:2000
            });
        }else{
            $.get("./BindByUserPassword.php?uid="+uid+"&userName="+userName+"&passWord="+passWord,function(result){
                if($.trim(result) == <?php echo Config::OK;?>){
                    toast.success({
                        title:"绑定成功！",
                        duration:2000
                    });
                    $(location).attr('href', '<?php echo $return?>');
                }else if($.trim(result) == <?php echo Config::USRNAME_EXIST;?>){
                    toast.fail({
                        title:"用户名已被占用！",
                        duration:2000
                    });
                }else{
                    toast.fail({
                        title:"服务器错误，请稍后重试！",
                        duration:2000
                    });
                }
            });
        }
    })

    $('#login').on('click',function () {
        var type=1;
        var userName = $('#UerName').val();
        var passWord = $('#PassWord').val();
        var toast = new auiToast();

        if(userName == '' || passWord == '' || userName.length <6 || passWord.length <6){
            toast.fail({
                title:"用户名密码为空或低于6位！",
                duration:2000
            });
        }else{
            $.get("./Login.php?type="+type+"&userName="+userName+"&passWord="+passWord,function(result){
                if($.trim(result) != <?php echo Config::USR_PAS_ERR;?>){
                    //reset the login uid to the cookie.
                    Cookies.remove('uid');
                    Cookies.set('uid', $.trim(result));
                    toast.success({
                        title:"登录成功！",
                        duration:2000
                    });
                    $(location).attr('href', '<?php echo $return?>');
                }else{
                    toast.fail({
                        title:"用户名或密码错误！",
                        duration:2000
                    });
                }
            });
        }
    })

    $('#back1').on('click',function () {
        $(location).attr('href', '/?fuid=<?php echo $uid?>');
    })
    $('#back2').on('click',function () {
        $(location).attr('href', '/?fuid=<?php echo $uid?>');
    })
</script>

</html>