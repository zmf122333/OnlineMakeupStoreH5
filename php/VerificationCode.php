<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/24 0024
 * Time: 01:33
 */
date_default_timezone_set("Asia/Shanghai");
require_once 'Config.php';


require_once "../aliyun-dysms-php-sdk-lite/SignatureHelper.php";
use Aliyun\DySDKLite\SignatureHelper;


$phone='XXXXXXX';
$codeBit = 6; //6 bit verification code

if(array_key_exists('phone',$_GET)){
    $phone = $_GET['phone'];
};



$code=GenerateCode($codeBit);

if(SendSMS($phone,$code) == 'OK'){
//        $time = date('YmdHis',time());
        $s = Config::_mysql()->query("insert into sms_verification_code(verificationCode,createTime) values('$code',concat(date_format(now(),'%Y%m%d%H%i%s')))");
        if(mysql_affected_rows()>0) {
            echo Config::OK;
        }else{
            echo Config::SQL_ERR;
        }
    }else{
        echo Config::SQL_ERR;
}


function SendSMS($phone,$code){
    $params = array ();

    // *** 需用户填写部分 ***

    // fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
    $accessKeyId = "XXXXXXX";
    $accessKeySecret = "XXXXXXX";

    // fixme 必填: 短信接收号码
    $params["PhoneNumbers"] = "$phone";

    // fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
    $params["SignName"] = "XXXXXXX";

    // fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
    $params["TemplateCode"] = "SMS_XXXXXXX";

    // fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
    $params['TemplateParam'] = Array (
        "code" => "$code"
    );

    // fixme 可选: 设置发送短信流水号
    $params['OutId'] = "XXXXXXX";

    // fixme 可选: 上行短信扩展码, 扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段
    $params['SmsUpExtendCode'] = "XXXXXXX";


    // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
    if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
        $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
    }

    // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
    $helper = new SignatureHelper();

    // 此处可能会抛出异常，注意catch
    $content = $helper->request(
        $accessKeyId,
        $accessKeySecret,
        "dysmsapi.aliyuncs.com",
        array_merge($params, array(
            "RegionId" => "cn-hangzhou",
            "Action" => "SendSms",
            "Version" => "2017-05-25",
        ))
    );
//    var_dump($content);
    return $content["Message"];
}

function CheckPhone($phone){
//    $s = Config::_mysqlAccount()->query("SELECT phone FROM `sms_user` where phone='$phone'");
//    $a = Config::_mysqlAccount()->to2DArray($s);
//    if(count($a)>0 && $a[0][0] != NULL){
//        return true;
//    }else{
//        return false;
//    }
    return true;
}

function GenerateCode($bit){
    $min = pow(10 , ($bit - 1));
    $max = pow(10, $bit) - 1;
    return mt_rand($min, $max);
}
