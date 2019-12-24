<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/20 0020
 * Time: 16:25
 */

ini_set('date.timezone','Asia/Shanghai');
error_reporting(-1);
require_once 'Config.php';
$mysql = Config::_mysql();

$uid = '';
$goodId ='';
if(array_key_exists('uid',$_GET)){
    $uid = $_GET["uid"];
};
if(array_key_exists('goodId',$_GET)){
    $goodId = $_GET["goodId"];
};

function delete($mysql,$uid,$goodId){
    $mysql->query("delete from cart where uid='$uid' and goodId='$goodId' limit 1 ");
    if(mysql_affected_rows()>0){
        return Config::OK;
    }else{
        return Config::SQL_ERR;
    }
}

echo delete($mysql,$uid,$goodId);

?>