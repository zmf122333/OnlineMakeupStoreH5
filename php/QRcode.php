<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/21 0021
 * Time: 18:59
 */
ini_set('date.timezone','Asia/Shanghai');
error_reporting(-1);
require_once 'Config.php';
require_once 'phpqrcode.php';

$uid = $_COOKIE['uid'];

$value = $_GET['url'];//二维码内容
$errorCorrectionLevel = 'L';//容错级别
$matrixPointSize = 8;//生成图片大小
$color=array(237,215,124);
//生成二维码图片
QRcode::png($value, $color , '../image/qrcode/'.$uid.'.png', $errorCorrectionLevel, $matrixPointSize, 2);
$logo = '../image/logoforQRcode.png';//准备好的logo图片
$QR = '../image/qrcode/'.$uid.'.png';//已经生成的原始二维码图

if ($logo !== FALSE) {
    $QR = imagecreatefromstring(file_get_contents($QR));
    $logo = imagecreatefromstring(file_get_contents($logo));
    $QR_width = imagesx($QR);//二维码图片宽度
    $QR_height = imagesy($QR);//二维码图片高度
    $logo_width = imagesx($logo);//logo图片宽度
    $logo_height = imagesy($logo);//logo图片高度
    $logo_qr_width = $QR_width / 8;
    $scale = $logo_width/$logo_qr_width;
    $logo_qr_height = $logo_height/$scale;
    $from_width = ($QR_width - $logo_qr_width) / 2;
    //重新组合图片并调整大小
    imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
        $logo_qr_height, $logo_width, $logo_height);
}
//输出图片
//Header("Content-type: image/png");
imagepng($QR, '../image/qrcode/'.$uid.'.png');

$QR1 = '../image/qrcode/'.$uid.'.png';
$res['state']=Config::OK;
$res['result']=$QR1;
echo urldecode(json_encode($res));