<?php
/**
 * Created by PhpStorm.
 * User: zhaomf
 * Date: 2018/2/10
 * Time: 上午10:14
 */


require_once 'Config.php';

if(!array_key_exists('code', $_GET) && strstr($_SERVER['HTTP_REFERER'],"weixin.qq.com")){
    echo Config::_redirect('./My.php');
    exit;
}




$uid = Config::_uidInit();

//weixin login api
$apiWeixinLogin = Config::$_weixinLoginAPI;
$apiWeixinAuthLogin = Config::$_weixinLoginAuthAPI;
$apiWeixinLoginToken = Config::$_weixinLoginTokenAPI;
$appId = Config::$_weixinLoginMPAppId;
$appSecret = Config::$_weixinLoginMPAppSecret;

//weixin login redirect uri
$redirectURI = 'https://www.XXXXXXX.com/php/LoginByWeixin.php';

//weixin login scope
$scope = 'snsapi_base';
//$scope = 'snsapi_login';


//step2 get access_token
if( array_key_exists('code', $_GET) && array_key_exists('state',$_GET)){
    $code = $_GET['code'];
    $state = $_GET['state'];
    $data = "appid=$appId&secret=$appSecret&code=$code&grant_type=authorization_code";
    $result =  postRequest( $apiWeixinLoginToken, $data);
//    var_dump($result);
//    exit;
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
        //already bind, login success, return uid
        Config::_cleanCookie();
        setcookie('uid', $a[0][0]);
        $uid = $a[0][0];
    }else{
        //first login, need bind
        $passwd = random_string(8);
        Config::_mysql()->query("update account set weixinUserName='$openid',weixinPassWord='$passwd',bindTime=DATE_FORMAT(now(),'%Y%m%d%H%i%s') where uid='$uid';");
        if (mysql_affected_rows() > 0) {
            //insert uid into user table
            Config::_mysql()->query("insert into user(uid) VALUES('$uid')");
            if(mysql_affected_rows()>0) {
//                echo Config::OK;
                $s0 = Config::_mysql()->query("select fatherUid from  sharePersonUp where uid='$uid' order by shareTime desc limit 1");
                $a0 = Config::_mysql()->to2DArray($s0);
                $fuid = $a0[0][0];
                if($fuid != null){
                    Config::_mysql()->query("update sharePersonUp set bindStatus='1',bindTime=DATE_FORMAT(now(),'%Y%m%d%H%i%s') 
                          where uid='$uid' and fatherUid='$fuid';");
                    if (mysql_affected_rows() > 0) {
//                        echo Config::OK;
                    }else{
//                        $status=Config::SQL_ERR;
//                        echo Config::SQL_ERR;
                    }
                }else{
                    //do nothing.
//                    echo Config::OK;
                }
            }
        } else {
            $status=Config::SQL_ERR;
        }
    }
    echo Config::_redirect("My.php?uid=$uid&status=$status");
}


//step1 get code from weixin, and redirect
$data = "appid=$appId&redirect_uri=$redirectURI&response_type=code&scope=$scope&state=$uid&connect_redirect=1#wechat_redirect";
//$data = "appid=$appId&redirect_uri=".urlencode($redirectURI)."&response_type=code&scope=$scope&state=$uid#wechat_redirect";
//postRequest( $apiWeixinAuthLogin, $data);
echo Config::_redirect($apiWeixinAuthLogin.'?'.$data);
//echo $result;
//exit;
//$result =  postRequest( $apiWeixinLogin, $data);
//$result1 =  triNewLine($result);
//echo preg_replace('/qrcode lightBorder" src="(.*?)"/','qrcode lightBorder" src="https://open.weixin.qq.com\1"',$result1);
//exit;
//$result = trimall($result);
//$regex = '/qrcodelightBorder"src="\/connect\/qrcode\/(.*?)"/i';
//$matches = array();
//
//$qrcodeURI='';
//if(preg_match($regex, $result, $matches)){
//    $qrcodeURI = $matches[1];
//    $qrcodeContent='https://open.weixin.qq.com/connect/confirm?uuid='.$qrcodeURI."&scope=snsapi_userinfo";
//    echo Config::_redirect($qrcodeContent);
//}



function downfile() {
    $filename=realpath("resume.html"); //文件名
    $date=date("Ymd-H:i:m");
    Header( "Content-type:  application/octet-stream ");
    Header( "Accept-Ranges:  bytes ");
    Header( "Accept-Length: " .filesize($filename));
    header( "Content-Disposition:  attachment;  filename= {$date}.doc");
    echo file_get_contents($filename);
    readfile($filename);
}


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

//function trimall($str){
//    $qian=array(" ","　","\t","\n","\r");
//    return str_replace($qian, '', $str);
//}
//
//function triNewLine($str){
//    $qian=array("\n","\r");
//    return str_replace($qian, '', $str);
//}
?>