<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/26 0026
 * Time: 03:31
 */

ini_set('date.timezone','Asia/Shanghai');
error_reporting(-1);
require_once 'Config.php';

$uid = "";
$userName = '';
$passWord = '';

if(array_key_exists('uid',$_GET)){
    $uid = $_GET['uid'];
};
if(array_key_exists('userName',$_GET)){
    $userName = $_GET['userName'];
};
if(array_key_exists('passWord',$_GET)){
    $passWord = $_GET['passWord'];
};

if($uid == '' ){
    echo Config::PAR_ERR;
    exit;
}
//$time = date('YmdHis', time());

$s = Config::_mysql()->query("SELECT count(uid) as sum FROM account where userName='$userName';");
$a = Config::_mysql()->to2DArray($s);
if($a[0][0]>0){
    echo Config::USRNAME_EXIST;
}else{
    Config::_mysql()->query("update account set userName='$userName',passWord='$passWord',bindTime=DATE_FORMAT(now(),'%Y%m%d%H%i%s') where uid='$uid';");
    if (mysql_affected_rows() > 0) {
        //insert uid into user table
        Config::_mysql()->query("insert into user(uid) VALUES('$uid')");
        if(mysql_affected_rows()>0) {
            $s0 = Config::_mysql()->query("select fatherUid from  sharePersonUp where uid='$uid' order by shareTime desc limit 1");
            $a0 = Config::_mysql()->to2DArray($s0);
            $fuid = $a0[0][0];
            if($fuid != null){
                Config::_mysql()->query("update sharePersonUp set bindStatus='1',bindTime=DATE_FORMAT(now(),'%Y%m%d%H%i%s') 
                          where uid='$uid' and fatherUid='$fuid';");
                if (mysql_affected_rows() > 0) {
                    echo Config::OK;
                }else{
                    echo Config::SQL_ERR;
                }
            }else{
                //do nothing.
                echo Config::OK;
            }

        }
    } else {
        echo Config::SQL_ERR;
    }
}