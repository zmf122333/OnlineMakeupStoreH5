<?php
/**
 * Created by PhpStorm.
 * User: zhaomf
 * Date: 2018/2/10
 * Time: 上午10:14
 */


require_once 'Config.php';


$uid = Config::_uidInit();

//weixin login api
$apiWeixinLogin = Config::$_weixinLoginAPI;
$apiWeixinAuthLogin = Config::$_weixinLoginAuthAPI;
$apiWeixinLoginToken = Config::$_weixinLoginTokenAPI;
$appId = Config::$_weixinLoginMPAppId;
$appSecret = Config::$_weixinLoginMPAppSecret;

//weixin login scope
$scope = 'snsapi_base';
//$scope = 'snsapi_login';

//weixin login redirect uri
$redirectURI = 'https://www.XXXXXXX.com/php/BindMyWeixinAccount.php';


//step2 get access_token
if( array_key_exists('code', $_GET) && array_key_exists('state',$_GET)){
    $code = $_GET['code'];
    $state = $_GET['state'];
    $data = "appid=$appId&secret=$appSecret&code=$code&grant_type=authorization_code";
    $result =  postRequest( $apiWeixinLoginToken, $data);

    $arr = json_decode($result,true);
    $access_token = $arr['access_token'];
    $openid = $arr['openid'];
    $unionid = $arr['unionid'];

    //we use user weixin openid as XXXXXXX account username and unionid as password
    //bind user openid to XXXXXXX account database
    $time = date('YmdHis', time());

    $status=Config::OK;
    $s = Config::_mysql()->query("SELECT uid FROM account where weixinUserName='$openid';");
    $a = Config::_mysql()->to2DArray($s);
    if(count($a) > 0 && $a[0][0] != NULL && $a[0][0] != ''){
        //already bind.
        $status=Config::WEIXIN_ALREADY_BINDED;
    }else{
        //bind
        $passwd = random_string(8);
        Config::_mysql()->query("update account set weixinUserName='$openid',weixinPassWord='$passwd',bindTime=DATE_FORMAT(now(),'%Y%m%d%H%i%s') where uid='$uid';");
        if (mysql_affected_rows() > 0) {
            $status=Config::OK;
        }else{
            $status=Config::SYS_ERR;
        }
    }
    echo Config::_redirect("My.php?uid=$uid&status2=$status");
}

$data = "appid=$appId&redirect_uri=$redirectURI&response_type=code&scope=$scope&state=$uid&connect_redirect=1#wechat_redirect";
echo Config::_redirect($apiWeixinAuthLogin.'?'.$data);

function postRequest( $api, $data, $timeout = 30 ) {
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $api );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt( $ch, CURLOPT_POST, 1 );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
    curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
        'Content-type: text/html;charset=UTF-8',
        'Accept: text/html',
    ) );
    $response = curl_exec( $ch );
    if(curl_errno($ch)){
        #printcurl_error($ch);
    }
    curl_close( $ch );
    return $response;
}

function random_string($bit){
    $rand = "";
    for ($i=1; $i<=$bit; $i++) {
        $rand .= substr('0123456789ZAQWSXCDERFVBGTYHNMJUIKLOP',rand(0,36),1);
    }
    return $rand;
}

?>