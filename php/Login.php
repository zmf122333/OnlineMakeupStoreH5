<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/24 0024
 * Time: 01:33
 */

require_once 'Config.php';

$userName='';
$passWord='';
$phone='';
$codeSMS='';
$type='';//1-users,passwd login;2-phone,SMS login


if(array_key_exists('userName',$_GET)){
    $userName = $_GET['userName'];
};
if(array_key_exists('passWord',$_GET)){
    $passWord = $_GET['passWord'];
};
if(array_key_exists('phone',$_GET)){
    $phone = $_GET['phone'];
};
if(array_key_exists('codeSMS',$_GET)){
    $codeSMS = $_GET['codeSMS'];
};
if(array_key_exists('type',$_GET)){
    $type = $_GET['type'];
};

echo loginWith1($userName,$passWord);


function loginWith1($userName,$passWord){
        $s = Config::_mysql()->query("SELECT uid FROM account where userName='$userName' and passWord='$passWord'");
        if(mysql_affected_rows()>0){
            $a = Config::_mysql()->to2DArray($s);
            //new uid need clean cookie.
            setcookie("uid", '');
            return $a[0][0];
        }else{
            return Config::USR_PAS_ERR;
        }
}

function loginWith2($u,$p){

}

