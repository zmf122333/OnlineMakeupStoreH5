<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/29 0029
 * Time: 22:14
 */

ini_set('date.timezone','Asia/Shanghai');
error_reporting(-1);
require_once 'Config.php';
$uid = Config::_uidInit();

$orderId='';
$deductMoney=0;

if(array_key_exists('orderId',$_GET)){
    $orderId = $_GET['orderId'];
};
if(array_key_exists('deductMoney',$_GET)){
    $deductMoney = $_GET['deductMoney'];
};



Config::_mysql()->query("update pay set deductMoney='$deductMoney' where orderId='$orderId'");
if(mysql_affected_rows()>0){
        echo Config::OK;
}else{
        echo Config::SQL_ERR;
}


?>
