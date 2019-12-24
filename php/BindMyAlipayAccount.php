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

$alipayAccount='';
if(array_key_exists('alipayAccount',$_GET)){
    $alipayAccount = $_GET['alipayAccount'];
};

function BindAlipayAccount($uid,$alipayAccount){
    Config::_mysql()->query("update user set alipayAccount='$alipayAccount' where uid='$uid'");
    if(mysql_affected_rows()>0){
        return Config::OK;
    }else{
        return Config::SQL_ERR;
    }
}

echo BindAlipayAccount($uid,$alipayAccount);

?>
