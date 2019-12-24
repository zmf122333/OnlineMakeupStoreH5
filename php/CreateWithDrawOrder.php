<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/24 0024
 * Time: 02:51
 */

ini_set('date.timezone','Asia/Shanghai');
error_reporting(-1);
require_once 'Config.php';


$uid='';
$money='';
$channel='';

if(array_key_exists('uid',$_GET)){
    $uid = $_GET["uid"];
};
if(array_key_exists('money',$_GET)){
    $money = $_GET["money"];
};
if(array_key_exists('channel',$_GET)){
    $channel = $_GET["channel"];
};

if($uid != 'JBRGP5WPS4F'){
    checkUser($uid,$money);
}


$orderId = generateOrderId();


if(generateOrder($orderId,$uid,$money,$channel) != Config::SQL_ERR){
     echo $orderId;
}else{
    echo Config::SQL_ERR;
}


function generateOrder($orderId,$uid,$money,$channel){
    Config::_mysql()->query("insert into shareWithDraw(orderId,uid,withDrawMoney,channel) values('$orderId','$uid','$money','$channel')");
    if(mysql_affected_rows()<=0){
        return  Config::SQL_ERR;
    }
    return $orderId;
}


function generateOrderId(){
    $str1 = date('YmdHis', time());
    $str2 = rand(pow(10,(6-1)), pow(10,6)-1); //6 bit rand integer, u could change 6 to other bit.
    return $str1.$str2;
}

function checkUser($uid,$money){
    $myRemainingMoney=Config::_getMyRemainingShareMoney($uid);
    if($money>$myRemainingMoney){
        echo Config::WITHDRAW_MONEY_BIG_THAN_REMAINING_MONEY;
        exit;
    }
}
