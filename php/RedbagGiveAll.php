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
$redbag=0;

if(array_key_exists('fuid',$_GET)){
    $fuid = $_GET['fuid'];
};

if(array_key_exists('redbag',$_GET)){
    $redbag = $_GET['redbag'];
};

$number=0;
$money=0;

$shareList = Config::_getMySharePeople($uid);
$prettyShare=array();
$j=0;
for($i=0;$i<sizeof($shareList);$i++){
    if($shareList[$i]['sumMoney'] >= Config::$vip_bench){
        $prettyShare[$j]=$shareList[$i];
        $j++;
    }
}
$number = sizeof($prettyShare);

if($number>0){
    $money=sprintf("%.2f",$redbag/sizeof($prettyShare));
    for($i=0;$i<sizeof($prettyShare);$i++){
        $uid=$prettyShare[$i]['uid'];
        Config::_mysql()->query("update sharePersonUp set fatherSentRedbag=round(fatherSentRedbag+$money,2) where fatherUid='$fuid' and uid='$uid'");
    }
    if(mysql_affected_rows()>0){
        echo Config::OK;
    }else{
        echo Config::SQL_ERR;
    }
}else{
    echo Config::SQL_ERR;
}



?>
