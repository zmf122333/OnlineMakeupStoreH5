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

$fuid='';
$cuid='';
$flower=0;

if(array_key_exists('fuid',$_GET)){
    $fuid = $_GET['fuid'];
};
if(array_key_exists('cuid',$_GET)){
    $cuid = $_GET['cuid'];
};
if(array_key_exists('flower',$_GET)){
    $flower = $_GET['flower'];
};


Config::_mysql()->query("update sharePersonUp set fatherSentFlower=fatherSentFlower+$flower where fatherUid='$fuid' and uid='$cuid'");
if(mysql_affected_rows()>0){
        echo Config::OK;
}else{
        echo Config::SQL_ERR;
}


?>
