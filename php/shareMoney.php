<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/21 0021
 * Time: 19:23
 */
ini_set('date.timezone','Asia/Shanghai');
error_reporting(-1);
require_once 'Config.php';

$uid = $_GET['uid'];

require('Mysql.php');
$c=new Mysql();
$c->opendb();

$r0=$c->query("SELECT todayMoney,thisMonthMoney,allMoney FROM XXXXXXX.shareMoney where uid='$uid'");
$ar0=$c->to2DArray($r0);

$temp = array();

if(count($ar0)==0){  //never use mysql_fetch_array($r0) to check the data, it will retrieve the 1st row to make u fucked.
    $temp[0]['todayMoney'] = 0;
    $temp[0]['thisMonthMoney'] = 0;
    $temp[0]['allMoney'] = 0;
}else{
    $temp[0]['todayMoney'] = $ar0[0][0];
    $temp[0]['thisMonthMoney'] = $ar0[0][1];
    $temp[0]['allMoney'] = $ar0[0][2];
}

$res['state']=config::OK;
$res['result']=$temp;
echo urldecode(json_encode($res));
