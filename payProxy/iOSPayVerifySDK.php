<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/31 0031
 * Time: 03:36
 */

$orderId ='';

if(array_key_exists('orderId',$_GET)){
    $orderId = $_GET["orderId"];
};

require('Mysql.php');
$c=new Mysql("127.0.0.1","xxxxxxxx","xxxxxxxx");
$c->opendb("account", "utf8");
$r0 = $c->query("SELECT status FROM account.recharge where orderId='$orderId' and status='1'");

$ar0=$c->to2DArray($r0);
if(count($ar0)==0){  //never use mysql_fetch_array($r0) to check the data, it will retrieve the 1st row to make u fucked.
        echo 0;
        exit;
    }else{
        echo 1;
}
