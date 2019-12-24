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

$uid = '';
$redbag=0;

if(array_key_exists('uid',$_GET)){
    $uid = $_GET['uid'];
};
if(array_key_exists('redbag',$_GET)){
    $redbag = $_GET['redbag'];
};


Config::_mysql()->query("update sharePersonUp set fatherSentRedbagTransfer=fatherSentRedbagTransfer+$redbag
                             where uid='$uid'");
if(mysql_affected_rows()>0){
        echo Config::OK;
}else{
        echo Config::SQL_ERR;
}


?>
