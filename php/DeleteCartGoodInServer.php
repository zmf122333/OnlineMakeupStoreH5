<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/26 0026
 * Time: 03:06
 */

ini_set('date.timezone','Asia/Shanghai');
error_reporting(-1);
require_once 'Config.php';

$mysql = Config::_mysql();

$uid = '';
$goodId = '';

if(array_key_exists('uid',$_GET)){
    $uid = $_GET['uid'];
};
if(array_key_exists('goodId',$_GET)){
    $goodId = $_GET['goodId'];
};

if($uid == '' || $goodId == ''){
    echo Config::PAR_ERR;
    exit;
}


function delToCart($uid,$goodId,$c){
    $time = date('YmdHis', time());
    $c->query("delete from cart where uid='$uid' and goodId='$goodId'");
    if(mysql_affected_rows()>0) {
        return Config::OK;
    }else{
        return Config::SQL_ERR;
    }
}

echo delToCart($uid,$goodId,$mysql);
?>