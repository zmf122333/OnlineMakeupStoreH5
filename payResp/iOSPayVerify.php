<?php   
 define('ROOT_PATH', dirname(__FILE__));
 error_reporting(0);

 /* stateCode List
  * 200 - OK.
  * 301 - This Username is not exist.
  * 302 - LDAP Error.
  * 303 - Invaild Parameters.
  * 304 - Parameters Error.
  * 305 - Username or Password Error.
  * 306 - This Username is already exist.
  * 307 - Lack of Channel.
  * 308 - The SMS Code Error.
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
 const SMS_ERR = 308;
 const LACK_SMS = 309;
 const iOS_ERR = 310;


require('Mysql.php');
$c=new Mysql("127.0.0.1","XXXXXXX","XXXXXXX");
$c->opendb("XXXXXXX", "utf8");

 // Http parameters
//$receipt   = $_REQUEST['receipt'];   
//$isSandbox = (bool) $_REQUEST['sandbox'];   
//$orderId =  $_REQUEST['orderId'];

//print_r($_SERVER); 

$input = file_get_contents('php://input');
$jsonStr = json_decode($input);
$isSandbox = false;
$receipt = $jsonStr->receipt;
$orderId = $jsonStr->orderId;
$money = $jsonStr->money;

    function getReceiptData($receipt, $isSandbox = false) {   
        if ($isSandbox) {   
            $endpoint = 'https://sandbox.itunes.apple.com/verifyReceipt';   
        }   
        else {   
            $endpoint = 'https://buy.itunes.apple.com/verifyReceipt';   
        }   
       // echo  $endpoint; 
        $postData = json_encode(   
            array('receipt-data' => $receipt)   
        );   
      //  echo  $postData; 
        $ch = curl_init($endpoint);   
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
        curl_setopt($ch, CURLOPT_POST, true);   
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);   
    
        $response = curl_exec($ch);   
        $errno    = curl_errno($ch);   
        $errmsg   = curl_error($ch);   
        curl_close($ch);   
   
        //echo $response; 
        if ($errno != 0) { 
            if($isSandbox == false){
                return false;
	    }
           // throw new Exception($errmsg, $errno);   
        }   
    
        $data = json_decode($response);   
    
        if (!is_object($data)) {
	 	if($isSandbox == false){
    	            return false;
                }

		$res['state']=iOS_ERR;
                echo urldecode(json_encode($res));
                exit; 
        }   
    
        if (!isset($data->status) || $data->status != 0) {  
            	if($isSandbox == false){
        	        return false;
	         }
          
		$res['state']=INVAILD_PAR;
		echo urldecode(json_encode($res));
		exit;	 
        }   
    
       /* return array(   
            'quantity'       =>  $data->receipt->quantity,   
            'product_id'     =>  $data->receipt->product_id,   
            'transaction_id' =>  $data->receipt->transaction_id,   
            'purchase_date'  =>  $data->receipt->purchase_date,   
            'app_item_id'    =>  $data->receipt->app_item_id,   
            'bid'            =>  $data->receipt->bid,   
            'bvrs'           =>  $data->receipt->bvrs   
        );
	*/
	return true; 
    }   
    

    
if(!getReceiptData($receipt, $isSandbox)){
	getReceiptData($receipt, true);
}
$money=$money/100; 
$c->query("update recharge set status=1,money='$money',pay_channel='iOS' where orderId='$orderId' and status=0 ");
if(strpos($_SERVER['HTTP_USER_AGENT'], "Darwin")){
        $r0=$c->query("update recharge set device='iOS' where orderId='$orderId';");
}else{
        $r0=$c->query("update recharge set device='Android' where orderId='$orderId';");
}

$c->query("commit");
//验证购买有效
echo 1;

?>  


