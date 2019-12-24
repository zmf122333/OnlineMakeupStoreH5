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

$log = '../../alipayH5.log';
$input=file_get_contents("php://input");
$input=preg_replace("/\s*/", "", $input);
file_put_contents($log, "[".date('Y-m-d H:i:s')."] ".$input."\t".$_SERVER["REMOTE_ADDR"]."\t".$_SERVER['HTTP_USER_AGENT']."\n",FILE_APPEND);

require_once dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'service/AlipayTradeService.php';
require_once dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'buildermodel/AlipayTradeWapPayContentBuilder.php';
require dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'./../config.php';
if (!empty($_GET['orderId'])&& trim($_GET['orderId'])!=""){
    //商户订单号，商户网站订单系统中唯一订单号，必填
    $out_trade_no = $_GET['orderId'];

    //订单名称，必填
    $subject = $_GET['subject'];

    //付款金额，必填
    $total_amount = $_GET['money'];
    $moneyAliPay=sprintf("%.2f",$total_amount/100);

    //商品描述，可空
    $body = $_GET['WIDbody'];

    $return_url = $_GET['return_url'];
    $return_url = $return_url.'&money='.$total_amount.'&orderId='.$out_trade_no.'&subject='.$subject;
    //超时时间
    $timeout_express="1m";

    $payRequestBuilder = new AlipayTradeWapPayContentBuilder();
    $payRequestBuilder->setBody($body);
    $payRequestBuilder->setSubject($subject);
    $payRequestBuilder->setOutTradeNo($out_trade_no);
    $payRequestBuilder->setTotalAmount($moneyAliPay);
    $payRequestBuilder->setTimeExpress($timeout_express);

    $payResponse = new AlipayTradeService($config);
    $result=$payResponse->wapPay($payRequestBuilder,$return_url,$config['notify_url']);




    return ;
}

?>
