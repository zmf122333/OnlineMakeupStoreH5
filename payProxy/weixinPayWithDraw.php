 <?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(-1);
require_once 'Config.php';


$log = 'weixinPayWithDraw.log';
$input=file_get_contents("php://input");
$input=preg_replace("/\s*/", "", $input);
file_put_contents($log, "[".date('Y-m-d H:i:s')."] ".$input."\t".$_SERVER["REMOTE_ADDR"]."\t"."\n",FILE_APPEND);






// Http parameters
$mch_appid = Config::$_weixinMCH_APPID;
$mchid = Config::$_weixinMCH_ID;//merchant id by Tencent
$nonce_str = random_string(32);  //random string, less than 32 bit
$partner_trade_no = random_string(18); //32 bit random string  as order_id
$openid = 'xxxxxxxx';
$check_name = 'FORCE_CHECK';
$re_user_name ='xxxxxxxx';
$amount = 100;
$desc = 'xxxxxxxx';
$spbill_create_ip = getIP(); //user client ip (+)client send data
$sign = '';  // signature follow https://pay.weixin.qq.com/wiki/doc/api/app/app.php?chapter=4_3

$APIkey =  Config::$_weixinAPIKey; //API key for signature

//weixin pem file
$certFile = Config::$_weixinPayCertificateFile;
$keyFile = Config::$_weixinPayPrivateKeyFile;

//weixin withdraw api
$api = Config::$_weixinWithDrawAPI;

//get http post parameter from input
 $parameter=array();
 $data=explode("&",$input);
 for($i=0;$i<sizeof($data);$i++){
     $temp = explode("=",$data[$i]);
     $parameter[$temp[0]]=$temp[1];
 }
 if (!empty($parameter['openid']) && !empty($parameter['re_user_name']) && trim($parameter['amount'])!="") {
     $openid = $parameter['openid'];
     $re_user_name = $parameter['re_user_name'];
     $amount = $parameter['amount'];
     $desc = $parameter['desc'];
 }


$ref=0;

//check parameters.
if($openid !== '' && $re_user_name !== '' && $amount !== '' && $desc !==''){
	$ref = 1;
}else{
	$ref = 2; // lack of parameters.
	$res['state']=Config::PAR_ERR;
	echo urldecode(json_encode($res));
	exit;
}


if($ref == 1){
	//check the user.
//    checkUser($openid);

	$DATA = array();
    $DATA['amount'] = $amount ;
    $DATA['check_name'] = $check_name ;
    $DATA['desc'] = $desc ;
	$DATA['mch_appid'] = $mch_appid ;
	$DATA['mchid'] = $mchid ;
	$DATA['nonce_str'] = $nonce_str ;
    $DATA['openid'] = $openid ;
	$DATA['partner_trade_no'] = $partner_trade_no ;
	$DATA['re_user_name'] = $re_user_name ;
	$DATA['spbill_create_ip'] = $spbill_create_ip;

	
	$sign = signature($DATA, $APIkey);
	
	
	$xml = httpRequestXMLL($DATA, $sign);
	//prePay to weixin
	$response = postRequest( $api, $xml, $certFile, $keyFile);
 
	$pattern='/SUCCESS/';
	$response = preg_replace('/\s*/', '', $response); 
//    echo $response;
    if(preg_match($pattern,$response)){
		preg_match('/return_code.*\[(.*?)\].*\/return_code/i', $response, $s);
        preg_match('/result_code.*\[(.*?)\].*\/result_code/i', $response, $y);

        if($s[1] == 'SUCCESS' && $y[1] == 'SUCCESS'){
            $res['state']=Config::OK;
            echo urldecode(json_encode($res));
		}else{
            preg_match('/err_code><\!\[CDATA\[(.*?)\].*\/err_code/i', $response, $x);
            //重要提醒：当返回错误码为“SYSTEMERROR”时，请不要更换商户订单号，一定要使用原商户订单号重试，否则可能造成重复支付等资金风险。
            if($x[1] == 'SYSTEMERROR'){
                $res['state']=Config::WITHDRAW_WEIXIN_SYSTEMERROR;
                echo urldecode(json_encode($res));
                exit;
            }
            if($x[1] == 'NOTENOUGH'){
                $res['state']=Config::WITHDRAW_WEIXIN_NOTENOUGH;
                echo urldecode(json_encode($res));
                exit;
            }
            if($x[1] == 'AMOUNT_LIMIT'){
                $res['state']=Config::WITHDRAW_WEIXIN_AMOUNT_LIMIT;
                echo urldecode(json_encode($res));
                exit;
			}
            if($x[1] == 'NAME_MISMATCH'){
                $res['state']=Config::WITHDRAW_WEIXIN_NAME_MISMATCH;
                echo urldecode(json_encode($res));
                exit;
            }

            $res['state']=Config::WITHDRAW_FAIL;
            echo urldecode(json_encode($res));
		}




	//	echo urldecode(json_encode($res));
	}else{
		$res['state']=Config::SYS_ERR;
		echo urldecode(json_encode($res));
	}
}
	
	

function getIP() { 
	if (getenv('HTTP_CLIENT_IP')) {
	$ip = getenv('HTTP_CLIENT_IP');
	}
	elseif (getenv('HTTP_X_FORWARDED_FOR')) {
	$ip = getenv('HTTP_X_FORWARDED_FOR');
	}
	elseif (getenv('HTTP_X_FORWARDED')) {
	$ip = getenv('HTTP_X_FORWARDED');
	}
	elseif (getenv('HTTP_FORWARDED_FOR')) {
	$ip = getenv('HTTP_FORWARDED_FOR');

	}
	elseif (getenv('HTTP_FORWARDED')) {
	$ip = getenv('HTTP_FORWARDED');
	}
	else {
	$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
} 



function postRequest( $api, $xmlData,$certFile, $keyFile, $timeout = 30) {
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $api );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $ch, CURLOPT_POST, 1 );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $xmlData );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );

    curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
	curl_setopt($ch,CURLOPT_SSLCERT,getcwd().$certFile);
	curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
	curl_setopt($ch,CURLOPT_SSLKEY,getcwd().$keyFile);

	curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
		'Content-type: text/xml;charset=UTF-8',
		'Accept: application/json',
	) ); 
	$response = curl_exec( $ch );
	if(curl_errno($ch)){
    	#printcurl_error($ch);
	}
	curl_close( $ch );
	return $response;
}
 
 
 
 
 function ssha_check($text, $hash) {
  $ohash = base64_decode(substr($hash,6));
  $osalt = substr($ohash,20);
  $ohash = substr($ohash,0,20);
  $nhash = pack("H*",sha1($text.$osalt));
  return $ohash == $nhash;
 }
 
 function random_string($bit){
 	$rand = "";
	for ($i=1; $i<=$bit; $i++) {
		$rand .= substr('0123456789ZAQWSXCDERFVBGTYHNMJUIKLOP',rand(0,36),1);
	}
	return $rand;
 }
 
 function ssha_encode($text) {
	$salt = "";
	for ($i=1; $i<=10; $i++) {
		$salt .= substr('0123456789abcdef',rand(0,15),1);
	}
	$hash = "{SSHA}" . base64_encode(pack("H*",sha1($text.$salt)).$salt);
	return $hash;
 }


 function signature($array,$APIkey){
 	//step1 : parameters ASCII order asc 
	$stringA = "amount=".$array['amount']."&check_name=".$array['check_name']."&desc=".$array['desc']."&mch_appid=".$array['mch_appid'];
	$stringA .= "&mchid=".$array['mchid']."&nonce_str=".$array['nonce_str']."&openid=".$array['openid'];
	$stringA .= "&partner_trade_no=".$array['partner_trade_no']."&re_user_name=".$array['re_user_name']."&spbill_create_ip=".$array['spbill_create_ip'];
    $stringSignTemp = "$stringA&key=$APIkey";
	
	$sign = strtoupper(MD5($stringSignTemp));

	return $sign;
 }

 
 function httpRequestXMLL($array,$sign){
 	$xml = "<xml>";
 	$xml .= "<amount>".$array['amount']."</amount>";
	$xml .= "<check_name>".$array['check_name']."</check_name>";
	$xml .= "<desc>".$array['desc']."</desc>";
	$xml .= "<mch_appid>".$array['mch_appid']."</mch_appid>";
	$xml .= "<mchid>".$array['mchid']."</mchid>";
	$xml .= "<nonce_str>".$array['nonce_str']."</nonce_str>";
	$xml .= "<openid>".$array['openid']."</openid>";
	$xml .= "<partner_trade_no>".$array['partner_trade_no']."</partner_trade_no>";
	$xml .= "<re_user_name>".$array['re_user_name']."</re_user_name>";
	$xml .= "<spbill_create_ip>".$array['spbill_create_ip']."</spbill_create_ip>";
	$xml .= "<sign>".$sign."</sign>";
	$xml .= "</xml>";

	return $xml;
 }
 
 function checkUser($openid){
	if(Config::_isValidUser($openid)){
        if(Config::_isRemainingMoneyEnough($openid)){
            return true;
		}else{
            $res['state']=Config::USER_REMAINING_MONEY_LOW;
            echo urldecode(json_encode($res));
            exit;
		}
	}else{
        $res['state']=Config::INVAILD_USER;
        echo urldecode(json_encode($res));
        exit;
	}

 }
 
?>
