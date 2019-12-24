<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/14 0014
 * Time: 05:14
 */

require_once 'Config.php';

$uid='';
$name='';
$phone='';
$province='';
$city='';
$district='';
$street='';
$province_id='';
$city_id='';
$district_id='';
// get array check.
if(array_key_exists('uid',$_GET)){
    $uid = $_GET["uid"];
};
if(array_key_exists('name',$_GET)){
    $name = $_GET["name"];
};
if(array_key_exists('phone',$_GET)){
    $phone = $_GET["phone"];
};
if(array_key_exists('province',$_GET)){
    $province = $_GET["province"];
};
if(array_key_exists('city',$_GET)){
    $city = $_GET["city"];
};
if(array_key_exists('district',$_GET)){
    $district = $_GET["district"];
};
if(array_key_exists('street',$_GET)){
    $street = $_GET["street"];
};
if(array_key_exists('province_id',$_GET)){
    $province_id = $_GET["province_id"];
};
if(array_key_exists('city_id',$_GET)){
    $city_id = $_GET["city_id"];
};
if(array_key_exists('district_id',$_GET)){
    $district_id = $_GET["district_id"];
};


Config::_mysql()->query("update user set name='$name',goodsPhone='$phone', addr_province='$province',addr_city='$city', 
                                              addr_district='$district',addr_street='$street',addr_province_id='$province_id',
                                              addr_city_id='$city_id',addr_district_id='$district_id' where uid='$uid'");
if(mysql_affected_rows()>0){
        echo Config::OK;
}else{
        echo Config::SQL_ERR;
}

?>