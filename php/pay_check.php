<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/14 0014
 * Time: 05:14
 */

require_once 'Config.php';

// get array check.
if(array_key_exists('orderId',$_GET)){
    $orderId = $_GET["orderId"];
};
if(array_key_exists('uid',$_GET)){
    $uid = $_GET["uid"];
};


if(pay_check($orderId,Config::_mysql())){
    //支付成功 及时刷新用户状态
//    config::_updateMyShareMoney($uid);
    config::_updateMyVipIdAndShare($uid);
    echo "true";
}else{
    echo "false";
}

function pay_check($orderId,$c){
    $c->query("SELECT orderId FROM pay where orderId=$orderId and status=1");
    if(mysql_affected_rows()>0){
        return true;
    }else{
        return false;
    }
}

?>