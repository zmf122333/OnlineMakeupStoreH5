<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/26 0026
 * Time: 00:18
 */
require_once 'Config.php';
$uid = Config::_uidInit();

$base64_image_content = $_POST['imgBase64'];

if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
    $type = $result[2];
    $new_file = "../image/users/$uid";
    if (!file_exists($new_file)) {
        mkdir($new_file, 0700);
    }
    $new_file = $new_file . "/thumbnail.jpg";
    if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
        echo Config::OK;
    } else {
        echo Config::PIC_SAVE_ERR;
    }
}
?>