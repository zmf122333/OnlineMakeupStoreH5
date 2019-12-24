<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(-1);
$file  = 'log.txt';
$order_id='888888';
$money = 0;
$status = '';


$input=file_get_contents("php://input");
$input=preg_replace("/\s*/", "", $input);
file_put_contents($file, "[".date('Y-m-d H:i:s')."] "."$input\n",FILE_APPEND);

$regex_status_weixin = "/<result_code><\!\[CDATA\[(.*?)\]\]><\/result_code>/";
$regex_status_alipay = "/&trade_status=(.*?)&/";

$regex_orderId_weixin = "/<out_trade_no><\!\[CDATA\[(.*?)\]\]><\/out_trade_no>/";
$regex_orderId_alipay = "/&out_trade_no=(.*?)&/";

$regex_money_weixin = "/<total_fee>(.*?)<\/total_fee>/";
$regex_money_alipay = "/total_amount=(.*?)&/";

$matches = array();
$status1 = "";
$status2 = "";

if(preg_match($regex_status_alipay, $input, $matches)){
    $status1=$matches[1];
}
$matches = array();
if(preg_match($regex_status_weixin, $input, $matches)){
    $status2=$matches[1];
}


//alipay
if($status1 == 'TRADE_SUCCESS'){
	$matches = array();
	if(preg_match($regex_money_alipay, $input, $matches)){
	    $money1=$matches[1];
	    
	    $tmp = array();
	    if(preg_match($regex_orderId_alipay, $input, $tmp)){
            	$order_id1=$tmp[1];
		
		file_put_contents($file, "[".date('Y-m-d H:i:s')."] status_alipay: $status1\torderId: $order_id1\tmoney: $money1\n",FILE_APPEND);
		require('Mysql.php');
        	$c=new Mysql("127.0.0.1","xxxxxxxx","xxxxxxxx");
        	$c->opendb("xxxxxxxx", "utf8");
	        $c->query("update pay set status=1,money='$money1',payChannel='alipay' where orderId='$order_id1' and status='0' ");
        	$c->query("commit");
            $c->query("update `order` set status=1 where orderId='$order_id1' and status='0' ");
            $c->query("commit");

	        print_r('success');
            }
	}	
}
//weixin
if($status2 == 'SUCCESS'){
	$matches= array();
        if(preg_match($regex_money_weixin, $input, $matches)){
            $money2=$matches[1]/100;

            $tmp = array();
            if(preg_match($regex_orderId_weixin, $input, $tmp)){
                $order_id2=$tmp[1];
		
		file_put_contents($file, "[".date('Y-m-d H:i:s')."] status_weixin: $status2\torderId: $order_id2\tmoney: $money2\n",FILE_APPEND);
		require('Mysql.php');
	        $c=new Mysql("127.0.0.1","xxxxxxxx","xxxxxxxx");
        	$c->opendb("xxxxxxxx", "utf8");
	        $c->query("update pay set status=1,money='$money2',payChannel='weixin' where orderId='$order_id2' and status='0' ");
	        $c->query("commit");
            $c->query("update `order` set status=1 where orderId='$order_id2' and status='0' ");
            $c->query("commit");

	        echo exit('<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>');
//	        print_r('<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>');
	    }
	}
}



?>
