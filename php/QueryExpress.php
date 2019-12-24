<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/28 0028
 * Time: 12:46
 */

require_once 'Config.php';

$expressNo='';
// get array check.
if(array_key_exists('expressNo',$_GET)){
    $expressNo = $_GET["expressNo"];
};

echo Config::_queryExpress($expressNo);