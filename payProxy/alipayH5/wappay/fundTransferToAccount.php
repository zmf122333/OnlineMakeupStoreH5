<?php
/* *
 * 功能：支付宝手机网站支付接口(alipay.trade.wap.pay)接口调试入口页面
 * 版本：2.0
 * 修改日期：2016-11-01
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 请确保项目文件有可写权限，不然打印不了日志。
 */
ini_set('date.timezone','Asia/Shanghai');
header("Content-type: text/html; charset=utf-8");

error_reporting(E_ERROR);

$log = '../../alipayFundTransferToAccount.log';
$input=file_get_contents("php://input");
$input=preg_replace("/\s*/", "", $input);
file_put_contents($log, "[".date('Y-m-d H:i:s')."] ".$input."\t".$_SERVER["REMOTE_ADDR"]."\t".$_SERVER['HTTP_USER_AGENT']."\n",FILE_APPEND);

require_once dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'./../AopSdk.php';
require dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'./../config.php';
require_once dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'./../aop/AopClient.php';
require_once dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'./../aop/request/AlipayFundTransToaccountTransferRequest.php';
require_once dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'./../aop/request/AlipayFundTransOrderQueryRequest.php';

$parameter=array();
$data=explode("&",$input);
for($i=0;$i<sizeof($data);$i++){
    $temp = explode("=",$data[$i]);
    $parameter[$temp[0]]=$temp[1];
}


if (!empty($parameter['orderId']) && trim($parameter['orderId'])!=""){
    $orderId=$parameter['orderId'];
    $payee_account=$parameter['account'];
    $money=$parameter['money'];
    $uid=$parameter['uid'];

    require('Mysql.php');
    $c=new Mysql("127.0.0.1","xxxxxxxx","xxxxxxxx");
    $c->opendb("xxxxxxxx", "utf8");

    //检查提现订单号
    if(checkOrderId($c,$orderId)==0){
        echo 0;
        exit;
    }

    //获取用户真实姓名
    if(!getUserName($c,$uid)){
        echo 0;
        exit;
    }

    //是否需要审核
    if($money>=$config['withDraw_review_min']){
        echo 3;
        exit;
    }

    $payee_name=getUserName($c,$uid);

    $company=$config['company_name'];
    $remarkInfo=$config['remark_info'];

    $aop = new AopClient ();
    $aop->gatewayUrl = $config['gatewayUrl'];
    $aop->appId = $config['app_id'];
    $aop->rsaPrivateKey = $config['merchant_private_key'];
    $aop->alipayrsaPublicKey=$config['alipay_public_key'];
    $aop->apiVersion = '1.0';
    $aop->signType = 'RSA2';
    $aop->postCharset='UTF-8';
    $aop->format='json';
    $request = new AlipayFundTransToaccountTransferRequest();
    $request->setBizContent("{" .
        "\"out_biz_no\":\"$orderId\"," .
        "\"payee_type\":\"ALIPAY_LOGONID\"," .
        "\"payee_account\":\"$payee_account\"," .
        "\"amount\":\"$money\"," .
        "\"payer_show_name\":\"$company\"," .
        "\"payee_real_name\":\"$payee_name\"," .
        "\"remark\":\"$remarkInfo\"" .
        "}");

    $result = $aop->execute($request);

    $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
    $resultCode = $result->$responseNode->code;

    if(!empty($resultCode)&&$resultCode == 10000){
        echo 1;
    } else if(!empty($resultCode)&&$resultCode == 40004){ //支付宝账号和真实姓名不一致
        echo 2;
    } else{
        echo 0;
    }
}

function checkOrderId($c,$orderId){
    $s = $c->query("SELECT count(orderId) FROM shareWithDraw where orderId='$orderId'");
    $a = $c->to2DArray($s);
    if (count($a) > 0 && $a[0][0] != NULL) {
        return $a[0][0];
    } else {
        return 0;
    }
}

function getUserName($c,$uid){
    $s = $c->query("SELECT name FROM user where uid='$uid'");
    $a = $c->to2DArray($s);
    if (count($a) > 0 && $a[0][0] != NULL) {
        return $a[0][0];
    } else {
        return false;
    }
}

?>
