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
$status=0;
if(array_key_exists('orderId',$_GET)){
    $orderId = $_GET['orderId'];
};
if(array_key_exists('status',$_GET)){
    $status = $_GET['status'];
};

function updateStatus($orderId,$status){
    Config::_mysql()->query("update shareWithDraw set status=$status where orderId='$orderId'");
    if(mysql_affected_rows()>0){
        return Config::OK;
    }else{
        return Config::SQL_ERR;
    }
}

echo updateStatus($orderId,$status);

?>
