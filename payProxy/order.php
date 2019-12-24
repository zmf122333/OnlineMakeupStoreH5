<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(-1);


$money = 0;//单位:分
$uid = 9999;
$client = '';


// get array check.
if(array_key_exists('money',$_GET)){
    $money = $_GET["money"];
};
if(array_key_exists('uid',$_GET)){
    $uid = $_GET["uid"];
};
if(array_key_exists('client',$_GET)){
    $client = $_GET["client"];
};

$ref=0;

//check parameters.
if($money >0 && $uid !== "" && $client !== "" &&count($_GET) == 3){
        $ref = 1;
}else{
        $ref = 2; // lack of parameters.
        echo "PAR_ERR";
        exit;
}

//alipay
if($ref == 1){
	    $orderId = generateOrderId();
		require('Mysql.php');
        $c=new Mysql("127.0.0.1","xxxxxxxx","xxxxxxxx");
        $c->opendb("xxxxxxxx", "utf8");
		$money=sprintf("%.2f",$money/100);
	    $c->query("insert into pay(orderId,uid,money,status,device) values('$orderId','$uid','$money',0,'$client')");
		if(mysql_affected_rows()<=0){
        	echo "SYS_ERR";
			exit;
		}
	    print_r($orderId);
}

function generateOrderId(){
       $str1 = date('YmdHis', time());
       $str2 = rand(pow(10,(5-1)), pow(10,5)-1); //5 bit rand integer, u could change 5 to other bit.
       return $str1.$str2;	

}


?>
