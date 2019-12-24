 <?php
ini_set('date.timezone','Asia/Shanghai'); 
define('ROOT_PATH', dirname(__FILE__));
error_reporting(-1);

 /* stateCode List
  * 200 - OK.
  * 301 - This Username is not exist.
  * 302 - LDAP Error.
  * 303 - Invaild Parameters.
  * 304 - Parameters Error.
  * 305 - Username or Password Error.
  * 306 - This Username is already exist.
  * 307 - Lack of Channel.
  * 308 - Internal Error.
  * 309 - Lack of SMS Code.
  */
  
 const OK = 200;
 const USR_NOT_EXIST = 301;
 const LDAP_ERR = 302;
 const INVAILD_PAR = 303;
 const PAR_ERR = 304;
 const USR_PAS_ERR = 305;
 const USR_EXIST = 306;
 const LACK_CHAN = 307;
 const SYS_ERR = 308;
 const LACK_SMS = 309;
 

$log = 'weixinPayH5.log';
$input=file_get_contents("php://input");
$input=preg_replace("/\s*/", "", $input);
file_put_contents($log, "[".date('Y-m-d H:i:s')."] ".$input."\t".$_SERVER["REMOTE_ADDR"]."\t".$_SERVER['HTTP_USER_AGENT']."\n",FILE_APPEND);




// Http parameters
$appid = 'xxxxxxxx';
$attach = 'xxxxxxxx';   //reserver for channel id, default our own channel
$subject = 'xxxxxxxx';
$device_info = 'WEB';
$mch_id = 'xxxxxxxx';//merchant id by Tencent
$nonce_str = random_string(32);  //random string, less than 32 bit
$notify_url = 'https://www.xxxxxxxx.com/payResp/resp.php'; // xxxxxxxx notifiy url
$out_trade_no = random_string(18); //32 bit random string  as order_id
$spbill_create_ip = getIP(); //user client ip (+)client send data
$total_fee = 6;       //user charge number (+)client send data
$trade_type = 'MWEB'; //H5-MWEB; APP-APP

$sign = '';  // signature follow https://pay.weixin.qq.com/wiki/doc/api/app/app.php?chapter=4_3
$APIkey =  'xxxxxxxx'; //API key


$partnerid = $mch_id;
$prepayid = 'xxxxxxxx';
$package = 'Sign=WXPay';
$noncestr2 = random_string(32);
list($usec, $sec) = explode(" ", microtime());  
$timestamp = $sec;

$sign2 = '';

$scene_info = '{"h5_info":  {"type": "H5","wap_url": "https://www.xxxxxxxx.com","wap_name": "xxxxxxxx"}}';


//weixin api
$api = 'https://api.mch.weixin.qq.com'; //mob.com HttpAPI auth code addr

 // get array check.
 if(array_key_exists('total_fee',$_GET)){
     $total_fee = $_GET["total_fee"];
 };
 if(array_key_exists('out_trade_no',$_GET)){
     $out_trade_no = $_GET["out_trade_no"];
 };
 if(array_key_exists('return_url',$_GET)){
     $return_url = $_GET["return_url"];
 };
 if(array_key_exists('subject',$_GET)){
     $subject = $_GET["subject"];
 };




$ref=0;

//check parameters.
if($total_fee !== '' && $out_trade_no !== '' && $return_url !== '' && $subject !=='' && count($_GET) == 4){
	$ref = 1;
}else{
	$ref = 2; // lack of parameters.
	$res['state']=PAR_ERR;
	echo urldecode(json_encode($res));
	exit;
}

 $return_url=$return_url.'?money='.$total_fee.'&orderId='.$out_trade_no;


if($ref == 1){

	$DATA = array();
	$DATA['appid'] = $appid ;
	$DATA['attach'] = $attach ;
	$DATA['body'] = $subject ;
	$DATA['device_info'] = $device_info ;
	$DATA['mch_id'] = $mch_id ;
	$DATA['nonce_str'] = $nonce_str ;
	$DATA['notify_url'] = $notify_url ;
	$DATA['out_trade_no'] = $out_trade_no ;
	$DATA['scene_info'] = $scene_info;
	$DATA['spbill_create_ip'] = $spbill_create_ip;
	$DATA['total_fee'] = $total_fee ;
	$DATA['trade_type'] = $trade_type ;
	
	$sign = signature($DATA, $APIkey);
	
	
	$xml = httpRequestXMLL($DATA, $sign);
	//prePay to weixin
	$response = postRequest( $api . '/pay/unifiedorder', $xml);
 
	$pattern='/SUCCESS/';
	$response = preg_replace('/\s*/', '', $response); 
//    echo $response;
    if(preg_match($pattern,$response)){
		preg_match('/prepay_id.*\[(.*?)\].*\/prepay_id/i', $response, $s);
        preg_match('/mweb_url.*\[(.*?)\].*\/mweb_url/i', $response, $y);
		# 1st: got the prepay_id
		# 2nd: return the request bundle
		$DATA2 = array();
		$DATA2['appid'] = $appid ;
		$DATA2['partnerid'] = $partnerid ;
		$DATA2['prepayid'] = $s[1] ;
		$DATA2['package'] = $package ;
		$DATA2['noncestr'] = $noncestr2 ;
		$DATA2['timestamp'] = $timestamp ;
		$sign2 = signature2($DATA2, $APIkey);
		$res['state']=OK;
		$res['appid']=$appid;
		$res['partnerid']=$partnerid;
		$res['prepayid']=$s[1];
		$res['package']=$package;
		$res['noncestr']=$noncestr2;
		$res['timestamp']=$timestamp;
		$res['sign']=$sign2;
		$murl=$y[1]."&redirect_url=".urlencode($return_url.'&subject='.$subject);
		$res['mweb_url']=$murl;


		echo "<script language='javascript' type='text/javascript'>";
		echo "window.location.href='$murl'";
		echo "</script>";


	//	echo urldecode(json_encode($res));
	}else{
		$res['state']=SYS_ERR;
		echo urldecode(json_encode($res));
	}
	exit;
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



function postRequest( $api, $xmlData, $timeout = 30 ) {
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $api );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $ch, CURLOPT_POST, 1 );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $xmlData );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
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
	$stringA = "appid=".$array['appid']."&attach=".$array['attach']."&body=".$array['body']."&device_info=".$array['device_info'];
	$stringA .= "&mch_id=".$array['mch_id']."&nonce_str=".$array['nonce_str'];
	$stringA .= "&"."notify_url=".$array['notify_url'];
	$stringA .= "&out_trade_no=".$array['out_trade_no']."&scene_info=".$array['scene_info']."&spbill_create_ip=".$array['spbill_create_ip'];
	$stringA .= "&total_fee=".$array['total_fee']."&trade_type=".$array['trade_type'];
    $stringSignTemp = "$stringA&key=$APIkey";
	
	$sign = strtoupper(MD5($stringSignTemp));

	return $sign;
 }
 
 function signature2($array,$APIkey){
 	//step1 : parameters ASCII order asc 
	$stringA = "appid=".$array['appid']."&noncestr=".$array['noncestr']."&package=".$array['package']."&partnerid=".$array['partnerid'];
	$stringA .= "&prepayid=".$array['prepayid']."&timestamp=".$array['timestamp'];

    $stringSignTemp = "$stringA&key=$APIkey";
	
	$sign = strtoupper(MD5($stringSignTemp));

	return $sign;
 }
 
 function httpRequestXMLL($array,$sign){
 	$xml = "<xml>";
 	$xml .= "<appid>".$array['appid']."</appid>";
	$xml .= "<attach>".$array['attach']."</attach>";
	$xml .= "<body>".$array['body']."</body>";
	$xml .= "<device_info>".$array['device_info']."</device_info>";
	$xml .= "<mch_id>".$array['mch_id']."</mch_id>";
	$xml .= "<nonce_str>".$array['nonce_str']."</nonce_str>";
	$xml .= "<notify_url>".$array['notify_url']."</notify_url>";
	$xml .= "<out_trade_no>".$array['out_trade_no']."</out_trade_no>";
	$xml .= "<scene_info>".$array['scene_info']."</scene_info>";
	$xml .= "<spbill_create_ip>".$array['spbill_create_ip']."</spbill_create_ip>";
	$xml .= "<total_fee>".$array['total_fee']."</total_fee>";
	$xml .= "<trade_type>".$array['trade_type']."</trade_type>";
	$xml .= "<sign>".$sign."</sign>";
	$xml .= "</xml>";

	return $xml;
 }
 
 
 
?>
