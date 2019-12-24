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

if(array_key_exists('orderId',$_GET)){
    $orderId = $_GET['orderId'];
};



Config::_mysql()->query("update `order` set status='3',statusRecieve='1' where orderId='$orderId'");
if(mysql_affected_rows()>0){
        echo Config::OK;
}else{
        echo Config::SQL_ERR;
}


?>
