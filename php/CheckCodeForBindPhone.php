<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/25 0025
 * Time: 03:32
 */

require_once 'Config.php';


$code = ''; //6 bit verification code
$phone = '';
$uid = '';

if(array_key_exists('code',$_GET)){
    $code = $_GET['code'];
};
if(array_key_exists('phone',$_GET)){
    $phone = $_GET['phone'];
};
if(array_key_exists('uid',$_GET)){
    $uid = $_GET['uid'];
};

function checkCode($phone,$uid,$code){
    $s = Config::_mysql()->query("SELECT verificationCode,status FROM `sms_verification_code` where verificationCode='$code'");
    $a = Config::_mysql()->to2DArray($s);
    if(count($a)>0 && $a[0][0] != NULL){
        if($a[0]['status'] == 1){
            echo Config::VERIFICATIONCODE_TIMEOUT;
            exit;
        }
        if(bindPhone($phone,$uid)){
            echo Config::OK;
        }else{
            echo Config::SQL_ERR;
        }
    }else{
        echo Config::VERIFICATIONCODE_ERR;
    }
}

function bindPhone($phone,$uid){
    $s = Config::_mysql()->query("select phone from `user` where phone='$phone'");
    $a = Config::_mysql()->to2DArray($s);
    if(count($a)>0 && $a[0][0] != NULL){
        echo Config::PHONE_BINDED;
        exit;
    }else{
        Config::_mysql()->query("update `user` set phone='$phone' where uid='$uid'");
        if (mysql_affected_rows() > 0) {
            Config::_mysql()->query("update `account` set phone='$phone',bindTime=DATE_FORMAT(now(),'%Y%m%d%H%i%s') where uid='$uid'");
            if (mysql_affected_rows() > 0) {
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}


checkCode($phone,$uid,$code);

?>