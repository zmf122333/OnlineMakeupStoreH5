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


function addToCart($uid,$goodId,$c){
    $time = date('YmdHis', time());
    $c->query("insert into cart(uid,goodId,time) VALUES('$uid','$goodId',$time)");
    if(mysql_affected_rows()>0) {
        $r=$c->query("select count(distinct goodId) as goodIdSum from XXXXXXX.cart where uid='$uid'");
        $a = $c->to2DArray($r);
        $res['state']=Config::OK;
        $res['result']=$a;
        return urldecode(json_encode($res));
    }else{
        $res['state']=Config::SQL_ERR;
        return urldecode(json_encode($res));
    }
}

echo addToCart($uid,$goodId,$mysql);
?>