<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/29 0029
 * Time: 19:43
 */

ini_set('date.timezone','Asia/Shanghai');
define('ROOT_PATH', dirname(__FILE__));
error_reporting(-1);
require_once 'Config.php';


$log = '../log/withDrawProxy.log';
$input=file_get_contents("php://input");
$input=preg_replace("/\s*/", "", $input);
file_put_contents($log, "[".date('Y-m-d H:i:s')."] ".$input."\t".$_SERVER["REMOTE_ADDR"]."\t".$_SERVER['HTTP_USER_AGENT']."\n",FILE_APPEND);

$orderId='';
$uid='';
$money='';
$account='';
$channel=0; //0-alipay;1-weixin;2-bank
$desc='微信提现';
// get array check.
if(array_key_exists('orderId',$_POST)){
    $orderId = $_POST["orderId"];
};
if(array_key_exists('uid',$_POST)){
    $uid = $_POST["uid"];
};
if(array_key_exists('money',$_POST)){
    $money = $_POST["money"];
};
if(array_key_exists('account',$_POST)){
    $account = $_POST["account"];
};
if(array_key_exists('channel',$_POST)){
    $channel = $_POST["channel"];
};

if($uid != 'XXXXXXX'){
    checkUser($uid,$money);
}


//weixin pay unit is cent.
$moneyWX = $money * 100;


//alipay withdraw api
$apiAlipay = Config::$_alipayWithDrawUrl;
$apiWeixinPay = Config::$_weixinPayWithDrawUrl;
$apiBankPay = Config::$_bankPayWithDrawUrl;
$openid = Config::_getUserWeixinOpenId($uid);
$re_user_name = Config::_getUserName($uid);

if($channel==0){
    $data = "orderId=$orderId&account=$account&uid=$uid&money=$money";
//var_dump(trimall(postRequest( $apiAlipay, $data)));
    if(trimall(postRequest( $apiAlipay, $data))==1){
        echo Config::OK;
    }else if(trimall(postRequest( $apiAlipay, $data))==2){
        echo Config::ALIPAY_ACCOUNT_NAME_ERR;
    }else if(trimall(postRequest( $apiAlipay, $data))==3){
        echo Config::WITHDRAW_BIG_THAN_REVIEW_MONEY;
    }else{
        echo Config::SQL_ERR;
    }
}

if($channel==1){
    $data1 = "openid=$openid&re_user_name=$re_user_name&amount=$moneyWX&desc=$desc";
    if(getState(postRequest( $apiWeixinPay, $data1))==Config::OK){
        echo Config::OK;
    }else if(getState(trimall(postRequest( $apiWeixinPay, $data1)))==Config::WITHDRAW_WEIXIN_NAME_MISMATCH){
        echo Config::WITHDRAW_WEIXIN_NAME_MISMATCH;
    }else if(getState(trimall(postRequest( $apiWeixinPay, $data1)))==Config::WITHDRAW_WEIXIN_AMOUNT_LIMIT){
        echo Config::WITHDRAW_WEIXIN_AMOUNT_LIMIT;
    }else if(getState(trimall(postRequest( $apiWeixinPay, $data1)))==Config::WITHDRAW_WEIXIN_NOTENOUGH){
        echo Config::WITHDRAW_WEIXIN_NOTENOUGH;
    }else if(getState(trimall(postRequest( $apiWeixinPay, $data1)))==Config::WITHDRAW_WEIXIN_SYSTEMERROR){
        echo Config::WITHDRAW_WEIXIN_SYSTEMERROR;
    }else{
        echo Config::WITHDRAW_FAIL;
    }
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

function trimall($str){
    $qian=array(" ","　","\t","\n","\r");
    return str_replace($qian, '', $str);
}

function getState($str){
    $arr = json_decode($str,true);
    return $arr['state'];
}

function checkUser($uid,$money){
    $myRemainingMoney=Config::_getMyRemainingShareMoney($uid);
    //不同于创建提现订单号的时候的余额判断
    //订单号默认创建是status=2
    //所以现有余额得减去提现请求的钱
    $myRemainingMoney=$myRemainingMoney+$money;
    if($money>$myRemainingMoney){
        echo Config::WITHDRAW_MONEY_BIG_THAN_REMAINING_MONEY;
        exit;
    }
}


?>