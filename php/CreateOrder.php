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
$client='';
$goodListStr='';
$type=1;  //0-direct bill; 1-cart bill

if(array_key_exists('uid',$_GET)){
    $uid = $_GET["uid"];
};
if(array_key_exists('money',$_GET)){
    $money = $_GET["money"];
};
if(array_key_exists('client',$_GET)){
    $client = $_GET["client"];
};
if(array_key_exists('goodListStr',$_GET)){
    $goodListStr = $_GET["goodListStr"];
};
if(array_key_exists('type',$_GET)){
    $type = $_GET["type"];
};

if($goodListStr == ''){
    echo Config::GOODLIST_NULL;
    exit;
}
$orderId = generateOrderId();

if(generatePay($orderId,$uid,$money,$client) != Config::SQL_ERR){
    if(generateOrder($orderId,$uid,generateOrderedGoods($uid,$goodListStr,$type)) != Config::SQL_ERR){
        echo $orderId;
        exit;
    }
}else{
    echo Config::SQL_ERR;
}


function generateOrder($orderId,$uid,$ogId){
    Config::_mysql()->query("insert into `order`(orderId,uid,ogId) values('$orderId','$uid','$ogId')");
    if(mysql_affected_rows()<=0){
        return  Config::SQL_ERR;
    }
    return $orderId;
}

function generateOrderedGoods($uid,$goodListStr,$type){
    $goods = explode(',',$goodListStr);
    $ogId = random_string(11);
    for($index=0;$index<count($goods);$index++)
    {
        $time = date('YmdHis', time());
        $good = explode('-',$goods[$index]);
        $goodId=$good[0];
        $goodInfo=explode(':',$good[1]);
        $goodAmount=$goodInfo[0];
        $goodColor=$goodInfo[1];
        $goodPack=$goodInfo[2];
        Config::_mysql()->query("insert into orderedGoods(ogId,uid,goodId,goodAmount,color,pack,time) values('$ogId','$uid','$goodId','$goodAmount','$goodColor','$goodPack','$time')");
        if(mysql_affected_rows()<=0){
            echo Config::SQL_ERR;
            exit;
        }
        //remove good from Cart.
        if($type == 1){
            Config::_removeGoodList($uid,$good[0]);
        }
    }
    return $ogId;
}
function generatePay($orderId,$uid,$money,$client){
    $money=sprintf("%.2f",$money/100);
    Config::_mysql()->query("insert into pay(orderId,uid,money,status,device) values('$orderId','$uid','$money',0,'$client')");
    if(mysql_affected_rows()<=0){
        return Config::SQL_ERR;
    }
    return Config::OK;
}

function generateOrderId(){
    $str1 = date('YmdHis', time());
    $str2 = rand(pow(10,(5-1)), pow(10,5)-1); //5 bit rand integer, u could change 5 to other bit.
    return $str1.$str2;
}

function random_string($bit){
    $rand = "";
    for ($i=1; $i<=$bit; $i++) {
        $rand .= substr('0123456789ZAQWSXCDERFVBGTYHNMJUIKLOP',rand(0,36),1);
    }
    return $rand;
}

