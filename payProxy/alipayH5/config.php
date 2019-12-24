<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "xxxxxxxx",

		//公司名字
        'company_name' => "xxxxxxxx",

		//提现备注信息
        'remark_info' => "美丽分享金提现",

		//需要审核的提现金额下限
        'withDraw_review_min' => 5000,

		//商户私钥，您的原始格式RSA私钥
		'merchant_private_key' => "XXXXX"
		
		//异步通知地址
		'notify_url' => "https://www.xxxxxxxx.com/payResp/resp.php",
		
		//同步跳转
		'return_url' => "https://www.xxxxxxxx.com/php/bill_ok.php",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "xxxxxxxx"
	
);
