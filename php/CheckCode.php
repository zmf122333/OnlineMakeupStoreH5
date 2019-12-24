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
$token = checkCode($code);
$res['state']=Config::OK;
$res['token']=$token;
$res['uid']=getUid($phone);

if($token == Config::SQL_ERR){
    $res['state']=Config::SQL_ERR;
}

echo urldecode(json_encode($res));


function checkCode($code){
    $s = Config::_mysql()->query("SELECT verificationCode FROM `sms_verification_code` where verificationCode='$code' and status=0");
    $a = Config::_mysql()->to2DArray($s);
    if(count($a)>0 && $a[0][0] != NULL){
        $token = generateToken();
        if($token != Config::SQL_ERR){
            return $token;
        }else{
            return Config::SQL_ERR;
        }
    }else{
        return Config::SQL_ERR;
    }
}

function getUid($phone){
    $s = Config::_mysql()->query("SELECT uid FROM `account` where phone='$phone'");
    $a = Config::_mysql()->to2DArray($s);
    if(count($a)>0 && $a[0][0] != NULL){
        return $a[0][0];
    }else{
        $uid=Config::_uidInit();
        Config::_mysql()->query("update account set phone='$phone',bindTime=DATE_FORMAT(now(),'%Y%m%d%H%i%s') where uid='$uid';");
        if (mysql_affected_rows() > 0) {
            Config::_mysql()->query("insert into user(uid,phone) VALUES('$uid','$phone')");
            if (mysql_affected_rows() > 0) {
                $s0 = Config::_mysql()->query("select fatherUid from  sharePersonUp where uid='$uid' order by shareTime desc limit 1");
                $a0 = Config::_mysql()->to2DArray($s0);
                $fuid = $a0[0][0];
                if ($fuid != null) {
                    Config::_mysql()->query("update sharePersonUp set bindStatus='1',bindTime=DATE_FORMAT(now(),'%Y%m%d%H%i%s') 
                          where uid='$uid' and fatherUid='$fuid';");
                    if (mysql_affected_rows() > 0) {
//                    echo Config::OK;
                    } else {
                        echo Config::SQL_ERR;
                        exit;
                    }
                } else {
                    //do nothing.
//                echo Config::OK;
                }
            }
            return $uid;
        }else{
            echo Config::SQL_ERR;
            exit;
        }
    }
}


function generateToken(){
    $token = random_string(18);
//    $time = date('YmdHis',time());
    $s = Config::_mysql()->query("insert into sms_token(token,createTime) values('$token',concat(date_format(now(),'%Y%m%d%H%i%s')))");
    if(mysql_affected_rows()>0) {
        return $token;
    }else{
        return Config::SQL_ERR;
    }

}

function random_string($bit){
    $rand = "";
    for ($i=1; $i<=$bit; $i++) {
        $rand .= substr('0123456789ZAQWSXCDERFVBGTYHNMJUIKLOP',rand(0,36),1);
    }
    return $rand;
}

?>