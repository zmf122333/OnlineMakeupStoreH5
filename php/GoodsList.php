<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/24 0024
 * Time: 03:38
 */
ini_set('date.timezone','Asia/Shanghai');
error_reporting(-1);
require_once 'Config.php';


$mysql = Config::_mysql();

$category = '';
$subCategory ='';
$keyword ='';
//$offset = 0;
//$rows = 5;

if(array_key_exists('category',$_GET)){
    $category = $_GET["category"];
};
if(array_key_exists('subCategory',$_GET)){
    $subCategory = $_GET["subCategory"];
};
if(array_key_exists('keyword',$_GET)){
    $keyword = $_GET["keyword"];
};
//if(array_key_exists('offset',$_GET)){
//    $offset = $_GET["offset"];
//};
//if(array_key_exists('rows',$_GET)){
//    $rows = $_GET["rows"];
//};


if($keyword != ''){
    echo getGoodsListByKeyword($keyword,$mysql);
}else{
    echo getGoodsListByCategory($category,$subCategory,$mysql);
//    echo getGoodsListByCategory($category,$subCategory,$offset,$rows,$mysql);
}




function getGoodsListByCategory($category,$subCategory,$c){
//function getGoodsListByCategory($category,$subCategory,$offset,$rows,$c){
        $imageDir=Config::$_imageGoodsDefaultDir;
        $thumbnail=Config::$_imageThumbnail;
        $s = $c->query("SELECT goodId,comingSoon,promotionPrice,name,concat('$imageDir/',goodId,'/$thumbnail') as thumbnail,name,shortDesc,promotionInfo,comments,thumbUps,favorites FROM good where category=$category and subCategory=$subCategory order by sales desc");
//        $s = $c->query("SELECT goodId,promotionPrice,name,concat('$imageDir/',goodId,'/$thumbnail') as thumbnail,name,shortDesc,promotionInfo,comments,thumbUps,favorites FROM good where category=$category and subCategory=$subCategory order by sales desc limit $offset,$rows");
        if(mysql_affected_rows()>0){
            $a = $c->to2DArray($s);
            return urldecode(json_encode($a));
        }else{
            return Config::NULL;
        }
}

function getGoodsListByKeyword($keyword,$c){
        $imageDir=Config::$_imageGoodsDefaultDir;
        $thumbnail=Config::$_imageThumbnail;
        $s = $c->query("SELECT goodId,comingSoon,promotionPrice,name,concat('$imageDir/',goodId,'/$thumbnail') as thumbnail,name,shortDesc,promotionInfo,comments,thumbUps,favorites FROM good where name like '%$keyword%' or shortDesc like '%$keyword%' or longDesc like '%$keyword%' order by sales desc");
        if(mysql_affected_rows()>0){
            $a = $c->to2DArray($s);
            return urldecode(json_encode($a));
        }else{
            return Config::NULL;
        }
    }

?>