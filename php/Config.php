<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/24 0024
 * Time: 00:04
 */

require_once 'Mysql.php';
require_once 'Guest.php';

class Config
{
    /* stateCode List
     * 200 - OK.
     * 301 - This Username is not exist.
     * 302 - LDAP Error.
     * 303 - Invaild Parameters.
     * 304 - Parameters Error.
     * 305 - Username or Password Error.
     * 306 - This Username is already exist.
     * 307 - Lack of Channel.
     * 308 - Internal Error.
     * 309 - Lack of SMS Code.
     * 310 - Userbane is already exist.
     * 311 - Good list is null.
     * 312 - Order Info error.
     * 313 - User Info error.
     * 314 - Pic save error.
     * 315 - Alipay account name error.
     * 316 - Withdraw money is big than review money.
     * 317 - Phone invalid.
     * 318 - Phone is already bind.
     * 319 - Verification code error.
     * 320 - Verification code timeout.
     * 321 - Withdraw fail.
     * 322 - Invalid user.
     * 323 - User remaining share money is not enough.
     * 324 - 微信提现金额超出限制。低于最小金额1.00元或累计超过20000.00元。
     * 325 - 微信提现提交名字与真实姓名不一致。
     * 326 - 系统繁忙，请稍后再试。微信内部接口调用发生错误。请先调用查询接口，查看此次付款结果，如结果为不明确状态（如订单号不存在），请务必使用原商户订单号进行重试。
     * 327 - 您的付款帐号余额不足或资金未到账。
     * 328 - Weixin Account is already binded on other user。
     */


    const OK = 200;
    const NULL = 300;
    const USR_NOT_EXIST = 301;
    const SQL_ERR = 302;
    const INVAILD_PAR = 303;
    const PAR_ERR = 304;
    const USR_PAS_ERR = 305;
    const USR_EXIST = 306;
    const LACK_CHAN = 307;
    const SYS_ERR = 308;
    const LACK_SMS = 309;
    const USRNAME_EXIST = 310;
    const GOODLIST_NULL = 311;
    const ORDER_INFO_ERR = 312;
    const USER_INFO_NULL = 313;
    const PIC_SAVE_ERR = 314;
    const ALIPAY_ACCOUNT_NAME_ERR = 315;
    const WITHDRAW_BIG_THAN_REVIEW_MONEY = 316;
    const PHONE_INVALID = 317;
    const PHONE_BINDED = 318;
    const VERIFICATIONCODE_ERR = 319;
    const VERIFICATIONCODE_TIMEOUT = 320;
    const WITHDRAW_FAIL = 321;
    const INVAILD_USER = 322;
    const USER_REMAINING_MONEY_LOW= 323;
    const WITHDRAW_WEIXIN_AMOUNT_LIMIT= 324;
    const WITHDRAW_WEIXIN_NAME_MISMATCH= 325;
    const WITHDRAW_WEIXIN_SYSTEMERROR= 326;
    const WITHDRAW_WEIXIN_NOTENOUGH= 327;
    const WEIXIN_ALREADY_BINDED= 328;
    const WITHDRAW_MONEY_BIG_THAN_REMAINING_MONEY = 329;


    public static $vip_bench = 10; //消费满188成为会员
    public static $flower_bench = 100;//下级消费满100即可生成1朵小红花
    public static $point_bench = 100;//自己消费每满100即可生成1个积分
    public static $withDraw_bench = 10;//余额满10元方可提现

    public static $_startupTime = '2018-01-01 00:00:00';

    public static $_weixinLoginOpenAppId = 'XXXX';//open.weixin.qq.com appid
    public static $_weixinLoginMPAppId = 'XXXX';//mp.weixin.qq.com appid
    public static $_weixinLoginOpenAppSecret = 'XXXX';//open.weixin.qq.com appsecret
    public static $_weixinLoginMPAppSecret = 'XXXX';//mp.weixin.qq.com appsecret
    public static $_weixinLoginAPI = 'https://open.weixin.qq.com/connect/qrconnect';
    public static $_weixinLoginAuthAPI = 'https://open.weixin.qq.com/connect/oauth2/authorize';
    public static $_weixinLoginTokenAPI = 'https://api.weixin.qq.com/sns/oauth2/access_token';

    public static $_domain = 'XXXX.com';
    public static $_webName = 'XXXX商城';
    public static $_mainPage = 'http://www.XXXX.com';

    public static $_hotLine = '028-88888888';
    public static $_customerQQ = 'XXXX';
    public static $_customerEMail = 'service@XXXX.com';

    public static $_uidStart = 100000;
    public static $_goodIdStart = 3000;
    public static $_vipDefault = 0;
    public static $_vipLevelMax = 11;
    public static $_imageUsersDefaultDir = '../image/users';
    public static $_imageGoodsDefaultDir = '../image/goods';
    public static $_imageThumbnail = 'thumbnail.jpg';

    public static $_mysqlHost = '127.0.0.1';
    public static $_mysqlPort = 3306;
    public static $_mysqlUser = 'XXXX';
    public static $_mysqlPwd = 'XXXX';
    public static $_mysqlDatabase = 'XXXX';

    public static $_alipayH5Url = 'https://www.XXXX.com/payProxy/alipayH5/wappay/pay.php';
    public static $_weixinPayH5Url = 'https://www.XXXX.com/payProxy/weixinPayH5.php';
    public static $_weixinPayJSAPIUrl = 'https://www.XXXX.com/payProxy/weixinPayJSAPI/jsapi.php';
    public static $_payReturnUrl = 'https://www.XXXX.com/php/bill.php';
    public static $_alipayWithDrawUrl = 'https://www.XXXX.com/payProxy/alipayH5/wappay/fundTransferToAccount.php';
    public static $_weixinPayWithDrawUrl = 'https://www.XXXX.com/payProxy/weixinPayWithDraw.php';
    public static $_bankPayWithDrawUrl = '';
    public static $_weixinGongZongHaoUrl = 'https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=XXXXX==&scene=100&#wechat_redirect';

    public static function _mysql()
    {
        $c = new Mysql(self::$_mysqlHost, self::$_mysqlUser, self::$_mysqlPwd);
        $c->opendb(self::$_mysqlDatabase, "utf8");
        return $c;
    }

    public static function _redirect($url)
    {
        $html = "<script language='javascript' type='text/javascript'>";
        $html .= "window.location.href='$url'";
        $html .= "</script>";
        return $html;
    }

    public static function _isBindUser($uid)
    {
        $s = self::_mysql()->query("SELECT count(uid) as sum FROM account where uid='$uid' and ((userName is not null and userName !='') 
                                        or (weixinUserName is not null and weixinUserName != '') 
                                        or (phone is not null and phone != ''));");
        $a = self::_mysql()->to2DArray($s);
        if ($a[0][0] > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function _isPhoneBindUser($uid)
    {
        $s = self::_mysql()->query("SELECT count(uid) as sum FROM account where uid='$uid' and (phone is not null and phone != '');");
        $a = self::_mysql()->to2DArray($s);
        if ($a[0][0] > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function _isWeixinBindUser($uid)
    {
        $s = self::_mysql()->query("SELECT count(uid) as sum FROM account where uid='$uid' and (weixinUserName is not null and weixinUserName != '');");
        $a = self::_mysql()->to2DArray($s);
        if ($a[0][0] > 0) {
            return true;
        } else {
            return false;
        }
    }


    public static function _haveRightsToShare($uid)
    {
        if(self::_getMyVipId($uid) >= 1){
            return true;
        }else{
            return false;
        }
    }

    public static function _setAnchor(){
        $uid=isset($_COOKIE['uid'])?$_COOKIE['uid']:"";
        $fatherUid=isset($_GET['fuid'])?$_GET['fuid']:"";

        //新的韭菜来了！！！
        if($fatherUid !='' && $uid ==''){
            echo self::_redirect(self::$_mainPage."/?fuid=".$fatherUid);
            exit;
        }
        if($fatherUid !='' && $uid !='' && !Config::_isBindUser($uid) && $fatherUid != $uid) {
            echo self::_redirect(self::$_mainPage."/?fuid=".$fatherUid);
            exit;
        }

    }

    public static function _getMyAlipayAccount($uid)
    {
        $s = self::_mysql()->query("SELECT alipayAccount FROM  user where uid='$uid'");
        $a = self::_mysql()->to2DArray($s);
        if (count($a) > 0 && $a[0][0] != NULL) {
            return $a[0][0];
        } else {
            return self::SQL_ERR;
        }
    }

    public static function _getMyPointDeduct($uid)
    {
        $s = self::_mysql()->query("SELECT sum(deductMoney) FROM pay where uid='$uid' and status=1");
        $a = self::_mysql()->to2DArray($s);
        if (count($a) > 0 && $a[0][0] != NULL) {
            return $a[0][0];
        } else {
            return 0;
        }
    }

    public static function _getMyPoint($uid){
        $MySumMoney=0;
        $s = self::_mysql()->query("select getUserSuccessOrderSumMoney('$uid')");
        $a = self::_mysql()->to2DArray($s);
        if (count($a) > 0 && $a[0][0] != NULL) {
            $MySumMoney+=$a[0][0];
        } else {
            $MySumMoney+=0;
        }
        $pointTotal = floor( $MySumMoney / self::$point_bench );
        $pointUsed = self::_getMyPointDeduct($uid);
        $myPoint = $pointTotal-$pointUsed;
        if($myPoint < 0){
            $myPoint = 0;
        }
        return $myPoint;
    }


    public static function _getMyFlowerDownSent($uid)
    {
        $s = self::_mysql()->query("SELECT sum(fatherSentFlower) FROM sharePersonUp where fatherUid='$uid' and bindStatus=1");
        $a = self::_mysql()->to2DArray($s);
        if (count($a) > 0 && $a[0][0] != NULL) {
            return $a[0][0];
        } else {
            return 0;
        }
    }

    public static function _getMyFlowerDown($uid)
    {
        $MyDownnerSumMoney=0;
        for($i=0;$i<sizeof(self::_getMyDownnerUid($uid));$i++){
            $dUid=self::_getMyDownnerUid($uid)[$i]['uid'];
            $s = self::_mysql()->query("select getUserSuccessOrderSumMoney('$dUid')");
            $a = self::_mysql()->to2DArray($s);
            if (count($a) > 0 && $a[0][0] != NULL) {
                $MyDownnerSumMoney+=$a[0][0];
            } else {
                $MyDownnerSumMoney+=0;
            }
        }
         
        $flowerTotal = floor( $MyDownnerSumMoney / self::$flower_bench );
        $flowerSent = self::_getMyFlowerDownSent($uid);
        $flowerDown = $flowerTotal-$flowerSent;

        return $flowerDown;
    }


    public static function _getMyFlowerUpDeduct($uid)
    {
        $s = self::_mysql()->query("SELECT sum(deductMoney) FROM pay where uid='$uid' and status=1");
        $a = self::_mysql()->to2DArray($s);
        if (count($a) > 0 && $a[0][0] != NULL) {
            return $a[0][0];
        } else {
            return 0;
        }
    }


    public static function _getMyDownnerUid($uid){
        $s = self::_mysql()->query("SELECT uid FROM sharePersonUp where fatherUid='$uid' and bindStatus=1");
        if ($s) {
            return self::_mysql()->to2DArray($s);
        } else {
            $temp = array();
            return $temp;
        }
    }


    public static function _getMyFlowerUp($uid)
    {
        $s = self::_mysql()->query("SELECT fatherSentFlower FROM sharePersonUp where uid='$uid' and bindStatus=1");
        $a = self::_mysql()->to2DArray($s);
        if (count($a) > 0 && $a[0][0] != NULL) {
            return $a[0][0] - self::_getMyFlowerUpDeduct($uid);
        } else {
            return 0;
        }
    }

    public static function _getMyRedbagUp($uid)
    {
        $s = self::_mysql()->query("SELECT fatherSentRedbag FROM sharePersonUp where uid='$uid' and bindStatus=1");
        $a = self::_mysql()->to2DArray($s);
        if (count($a) > 0 && $a[0][0] != NULL) {
            return $a[0][0];
        } else {
            return 0;
        }
    }

    public static function _getMyRedbagUpTransfer($uid)
    {
        $s = self::_mysql()->query("SELECT fatherSentRedbagTransfer FROM sharePersonUp where uid='$uid' and bindStatus=1");
        $a = self::_mysql()->to2DArray($s);
        if (count($a) > 0 && $a[0][0] != NULL) {
            return $a[0][0];
        } else {
            return 0;
        }
    }

    public static function _uidInit()
    {
//        var_dump(isset($_COOKIE['uid']));
        if (isset($_COOKIE['uid'])) {
//            var_dump($_COOKIE['uid']);
            return $_COOKIE['uid'];
        }
        if (!isset($_SERVER['HTTP_REFERER']) || !isset($_COOKIE['uid']) || !strstr($_SERVER['HTTP_REFERER'], self::$_domain)) {
            $uid = Guest::RegisterWithGuest(self::_mysql());
            if ($uid != self::SQL_ERR) {
                setcookie('uid', $uid);//save uid at client via Cookie.
                return $uid;
            } else {
                return self::SQL_ERR;
            }
        }
    }

    public static function _uidHeadPic($uid){
        $image=Config::$_imageUsersDefaultDir."/".$uid."/".Config::$_imageThumbnail;
        if(is_file($image)){
            return $image;
        }
        return Config::$_imageUsersDefaultDir."/".Config::$_imageThumbnail;
    }


    public static function _cleanCookie()
    {
        setcookie('uid', '');
    }


    public static function _getGoodsListByCategory($category,$subCategory){
        $imageDir=Config::$_imageGoodsDefaultDir;
        $thumbnail=Config::$_imageThumbnail;
        $s = self::_mysql()->query("SELECT goodId,comingSoon,promotionPrice,name,concat('$imageDir/',goodId,'/$thumbnail') as thumbnail,name,shortDesc,promotionInfo,comments,thumbUps,favorites FROM good where category=$category and subCategory=$subCategory order by sales desc");
        if ($s) {
            return self::_mysql()->to2DArray($s);
        } else {
            $temp = array();
            return $temp;
        }
    }

    public static function _getGoodsListByKeyword($keyword){
        $imageDir=Config::$_imageGoodsDefaultDir;
        $thumbnail=Config::$_imageThumbnail;
        $s = self::_mysql()->query("SELECT goodId,comingSoon,promotionPrice,name,concat('$imageDir/',goodId,'/$thumbnail') as thumbnail,name,shortDesc,promotionInfo,comments,thumbUps,favorites FROM good where name like '%$keyword%' or shortDesc like '%$keyword%' or longDesc like '%$keyword%' order by sales desc");
        if ($s) {
            return self::_mysql()->to2DArray($s);
        } else {
            $temp = array();
            return $temp;
        }
    }


    public static function _goodList($uid)
    {
        $imageDir = Config::$_imageGoodsDefaultDir;
        $thumbnail = Config::$_imageThumbnail;
        $s = self::_mysql()->query("SELECT a.uid,a.goodId,count(a.goodId) as sum,concat('$imageDir/',a.goodId,'/$thumbnail') as thumbnail,b.name,b.shortDesc,b.price*count(a.goodId) as price,b.pack,b.color
                FROM cart a,good b
                where a.uid='$uid' and a.goodId=b.goodId
                group by a.uid,a.goodId");
        if ($s) {
            return self::_mysql()->to2DArray($s);
        } else {
            $temp = array();
            return $temp;
        }
    }


    public static function _goodListStr($uid)
    {
        $goodList = self::_goodList($uid);
        $goodListStr = '';
        for ($i = 0; $i < sizeof($goodList); ++$i) {
            if ($i == (sizeof($goodList) - 1)) {
                $goodListStr = $goodListStr . $goodList[$i]['goodId'] . "-" . $goodList[$i]['sum'].':'.$goodList[$i]['color'].':'.$goodList[$i]['pack'];
                break;
            }
            $goodListStr = $goodListStr . $goodList[$i]['goodId'] . "-" . $goodList[$i]['sum'].':'.$goodList[$i]['color'].':'.$goodList[$i]['pack']. ',';
        }
        return $goodListStr;
    }

    public static function _removeGoodList($uid, $goodId)
    {
        self::_mysql()->query("delete FROM cart where uid='$uid' and goodId='$goodId'");
        if (mysql_affected_rows() > 0) {
            return self::OK;
        } else {
            return self::SQL_ERR;
        }
    }

    public static function _myShareUrl($uid)
    {
        return self::$_mainPage . "?fuid=" . $uid;
    }

    public static function _userInfo($uid)
    {
        $imageDir = Config::$_imageUsersDefaultDir;
        $thumbnail = Config::$_imageThumbnail;
        $s = self::_mysql()->query("SELECT phone,concat('$imageDir/',uid,'/$thumbnail') as thumbnail,redEnvelope,point,coupons,share FROM user where uid='$uid'");
        if ($s) {
            return self::_mysql()->to2DArray($s);
        } else {
            $temp = array();
            return $temp;
        }
    }

    public static function _getFatherUid($uid)
    {
        $s = self::_mysql()->query("SELECT fatherUid FROM sharePersonUp where uid='$uid' and bindStatus='1'");
        $a = self::_mysql()->to2DArray($s);
        if (count($a) > 0 && $a[0][0] != NULL) {
            return $a[0][0];
        } else {
            return self::SQL_ERR;
        }
    }

    public static function _setFatherUid($uid, $fatherUid)
    {
//        $time = date('YmdHis', time());
        self::_mysql()->query("insert into sharePersonUp(uid,fatherUid,shareTime) values('$uid','$fatherUid',DATE_FORMAT(now(),'%Y%m%d%H%i%s')) ON DUPLICATE KEY UPDATE shareTime=DATE_FORMAT(now(),'%Y%m%d%H%i%s')");
        self::_mysql()->query("commit");
        $s = self::_mysql()->query("SELECT uid FROM sharePersonUp where uid='$uid' and fatherUid='$fatherUid'");
        $a = self::_mysql()->to2DArray($s);
        if (count($a) > 0 && $a[0][0] != NULL) {
            return self::OK;
        } else {
            return self::SQL_ERR;
        }
    }

    public static function _getUserName($uid)
    {
        $s = self::_mysql()->query("SELECT name FROM user where uid='$uid'");
        $a = self::_mysql()->to2DArray($s);
        if (count($a) > 0) {
            return $a[0][0];
        } else {
            return self::SQL_ERR;
        }
    }

    public static function _getUserPhone($uid)
    {
        $s = self::_mysql()->query("SELECT phone FROM user where uid='$uid'");
        $a = self::_mysql()->to2DArray($s);
        if (count($a) > 0) {
            return $a[0][0];
        } else {
            return self::SQL_ERR;
        }
    }

    public static function _getUserWeixinOpenId($uid)
    {
        $s = self::_mysql()->query("SELECT weixinUserName FROM account where uid='$uid'");
        $a = self::_mysql()->to2DArray($s);
        if (count($a) > 0) {
            return $a[0][0];
        } else {
            return self::SQL_ERR;
        }
    }

    public static function _getUserGoodsPhone($uid)
    {
        $s = self::_mysql()->query("SELECT goodsPhone FROM user where uid='$uid'");
        $a = self::_mysql()->to2DArray($s);
        if (count($a) > 0) {
            return $a[0][0];
        } else {
            return self::SQL_ERR;
        }
    }

    public static function _getAddrInfo($uid)
    {
        $s = self::_mysql()->query("SELECT addr_province,addr_province_id,addr_city,addr_city_id,addr_district,addr_district_id,addr_street FROM user where uid='$uid'");
        if ($s) {
            return self::_mysql()->to2DArray($s);
        } else {
            $temp = array();
            return $temp;
        }
    }

    public static function _checkUserInfoNull($uid)
    {
        if (self::_getUserName($uid) == self::SQL_ERR || self::_getUserName($uid) == NULL || self::_getUserGoodsPhone($uid) == self::SQL_ERR
            || self::_getUserGoodsPhone($uid) == NULL || empty(self::_getAddrInfo($uid)) == true || self::_getAddrInfo($uid)[0][0] == NULL) {
            return self::USER_INFO_NULL;
        }
        return self::OK;
    }

    public static function _getOrder($uid, $status)
    {
        $s = self::_mysql()->query("SELECT orderId FROM XXXX.`order` where uid='$uid' and status='$status' order by orderId desc");
        if ($s) {
            return self::_mysql()->to2DArray($s);
        } else {
            $temp = array();
            return $temp;
        }
    }

    public static function _getAllOrder($uid)
    {
        $s = self::_mysql()->query("SELECT orderId,status FROM XXXX.`order` where uid='$uid' order by orderId desc");
        if ($s) {
            return self::_mysql()->to2DArray($s);
        } else {
            $temp = array();
            return $temp;
        }
    }

    public static function _haveOrder_($uid)
    {
        $s = self::_mysql()->query("SELECT orderId FROM XXXX.`order` where uid='$uid'");
        $a = self::_mysql()->to2DArray($s);
        if (count($a) > 0 && $a[0][0] != NULL) {
            return true;
        } else {
            return false;
        }
    }


    public static function _getOrderInfo($orderId, $uid, $status)
    {
        $imageDir = Config::$_imageGoodsDefaultDir;
        $thumbnail = Config::$_imageThumbnail;
        $s = self::_mysql()->query("select  m.statusRecieve,m.deliverTime,m.expressNo,m.status,m.orderId,m.goodId,m.thumbnail,m.name as goodName,m.freight,m.discount,m.deduct,m.goodAmount,m.promotionPrice,m.price,m.color,m.pack,m.time,n.vipId,n.name,n.goodsPhone,n.addr_province,n.addr_city,
                                        n.addr_district,n.addr_street
                                        from (select x.statusRecieve,x.deliverTime,x.expressNo,x.orderId,x.uid,x.ogId,x.goodId,concat('$imageDir/',x.goodId,'/$thumbnail') as thumbnail,y.name,x.freight,x.discount,x.deduct,x.status,x.goodAmount,y.promotionPrice,y.price,x.color,x.pack,x.time from
                                        (SELECT  a.statusRecieve,a.deliverTime,a.expressNo,a.orderId,b.uid,a.ogId,b.goodId,a.freight,a.discount,a.deduct,a.status,b.goodAmount,b.time,b.color,b.pack FROM `order` a 
                                        left join orderedGoods b
                                        on a.ogId=b.ogId
                                        where a.orderId='$orderId' and
                                              a.uid='$uid' and
                                              a.status='$status')x
                                        left join good y
                                        on x.goodId=y.goodId) m
                                        left join user n
                                        on m.uid=n.uid
                                        ");
        if ($s) {
            return self::_mysql()->to2DArray($s);
        } else {
            $temp = array();
            return $temp;
        }
    }

    public static function _setOrderStatus($orderId, $uid, $status, $_status)
    {
        self::_mysql()->query("update `order` set status='$status' where orderId='$orderId' and uid='$uid' and status='$_status'");
        if (mysql_affected_rows() > 0) {
            return self::OK;
        } else {
            return self::SQL_ERR;
        }
    }

    public static function _getMySharePeople($uid){
        $s = self::_mysql()->query("select a.uid,b.name,b.phone,getUserSuccessOrderNum(a.uid) as orderNum, getUserSuccessOrderSumMoney(a.uid) as sumMoney,
                                        getUserSuccessOrderSumMoneyToday(a.uid) as todayMoney, getUserSuccessOrderSumMoneyThisMonth(a.uid) as monthMoney
                                        from (SELECT distinct uid FROM sharePersonUp
                                        where fatherUid='$uid' and bindStatus=1) a left join user b
                                        on a.uid=b.uid
                                        order by sumMoney desc");
        if ($s) {
            return self::_mysql()->to2DArray($s);
        } else {
            $temp = array();
            return $temp;
        }
    }

    public static function _getMyVipId($uid)
    {
        $s = self::_mysql()->query("SELECT vipId FROM user where uid='$uid'");
        $a = self::_mysql()->to2DArray($s);
        if (count($a) > 0 && $a[0][0] != NULL) {
            return $a[0][0];
        } else {
            return 0;
        }
    }

    public static function _isSharedUser($uid){
        $s = self::_mysql()->query("SELECT uid FROM sharePersonUp where uid='$uid' and bindStatus=1");
        $a = self::_mysql()->to2DArray($s);
        if (count($a) > 0 && $a[0][0] != NULL) {
            return true;
        } else {
            return false;
        }
    }

    public static function _getMyVipInfo($vipId){
        $s = self::_mysql()->query("SELECT shortRight,bonus,extraBonus,discount,rebate,shareMin,shareMax,getVipCondition($vipId+1) upgradeCondition,getVipLevel($vipId) myVip,getVipLongRight($vipId) myVipLongRight,
                                        getVipLevel($vipId+1) myVipPlusOneLevel,getVipLongRight($vipId+1) myVipPlusOneLongRight FROM XXXX.vip where vipId=$vipId");
        if ($s) {
            return self::_mysql()->to2DArray($s);
        } else {
            $temp = array();
            return $temp;
        }
    }

    public static function _getMyDistrict($uid)
    {
        if(self::_isSpecialDistrict($uid)){
            $s = self::_mysql()->query("SELECT addr_province,addr_city FROM  user where uid='$uid'");
            $a = self::_mysql()->to2DArray($s);
            if (count($a) > 0 && $a[0][0] != NULL) {
                return $a[0][0].$a[0][1];
            }
        }else{
            $s = self::_mysql()->query("SELECT addr_province,addr_city,addr_district FROM  user where uid='$uid'");
            $a = self::_mysql()->to2DArray($s);
            if (count($a) > 0 && $a[0][0] != NULL) {
                return $a[0][0].$a[0][1].$a[0][2];
            }
        }
        return '北京市海淀区';
    }
    public static function _getMyDistrictId($uid)
    {
        if(self::_isSpecialDistrict($uid)){
            $s = self::_mysql()->query("SELECT addr_city_id FROM  user where uid='$uid'");
            $a = self::_mysql()->to2DArray($s);
            if (count($a) > 0 && $a[0][0] != NULL) {
                return $a[0][0];
            }
        }else{
            $s = self::_mysql()->query("SELECT addr_district_id FROM  user where uid='$uid'");
            $a = self::_mysql()->to2DArray($s);
            if (count($a) > 0 && $a[0][0] != NULL) {
                return $a[0][0];
            }
        }
        return 1101;
    }
    public static function _getMyCity($uid)
    {
        if(self::_isSpecialDistrict($uid)){
            $s = self::_mysql()->query("SELECT addr_province FROM  user where uid='$uid'");
            $a = self::_mysql()->to2DArray($s);
            if (count($a) > 0 && $a[0][0] != NULL) {
                return $a[0][0];
            }
        }else{
            $s = self::_mysql()->query("SELECT addr_province,addr_city FROM  user where uid='$uid'");
            $a = self::_mysql()->to2DArray($s);
            if (count($a) > 0 && $a[0][0] != NULL) {
                return $a[0][0].$a[0][1];
            }
        }
        return '北京市';
    }
    public static function _getMyCityId($uid)
    {
        if(self::_isSpecialDistrict($uid)){
            $s = self::_mysql()->query("SELECT addr_province_id FROM  user where uid='$uid'");
            $a = self::_mysql()->to2DArray($s);
            if (count($a) > 0 && $a[0][0] != NULL) {
                return $a[0][0];
            }
        }else{
            $s = self::_mysql()->query("SELECT addr_city_id FROM  user where uid='$uid'");
            $a = self::_mysql()->to2DArray($s);
            if (count($a) > 0 && $a[0][0] != NULL) {
                return $a[0][0];
            }
        }
        return 11;
    }
    public static function _getMyProvince($uid)
    {
        if(self::_isSpecialDistrict($uid)){
            $s = self::_mysql()->query("SELECT addr_province FROM  user where uid='$uid'");
            $a = self::_mysql()->to2DArray($s);
            if (count($a) > 0 && $a[0][0] != NULL) {
                return $a[0][0];
            }
        }else{
            $s = self::_mysql()->query("SELECT addr_province FROM  user where uid='$uid'");
            $a = self::_mysql()->to2DArray($s);
            if (count($a) > 0 && $a[0][0] != NULL) {
                return $a[0][0];
            }
        }
        return '北京市';
    }
    public static function _getMyProvinceId($uid)
    {
        if(self::_isSpecialDistrict($uid)){
            $s = self::_mysql()->query("SELECT addr_province_id FROM  user where uid='$uid'");
            $a = self::_mysql()->to2DArray($s);
            if (count($a) > 0 && $a[0][0] != NULL) {
                return $a[0][0];
            }
        }else{
            $s = self::_mysql()->query("SELECT addr_province_id FROM  user where uid='$uid'");
            $a = self::_mysql()->to2DArray($s);
            if (count($a) > 0 && $a[0][0] != NULL) {
                return $a[0][0];
            }
        }
        return 11;
    }
    public static function _isSpecialDistrict($uid){
        $s = self::_mysql()->query("SELECT addr_province_id FROM  user where uid='$uid'");
        $a = self::_mysql()->to2DArray($s);
        if (count($a) > 0 && $a[0][0] != NULL) {
            $province_id =  $a[0][0];
        } else {
            $province_id = 11; //游客默认北京市
        }
        if($province_id == 11|| $province_id == 12 ||$province_id == 31||$province_id == 71
            ||$province_id == 50||$province_id == 81||$province_id == 82){
            return true;
        }else{
            return false;
        }
    }

    public static function _getAreaSalesAmount($uid,$areaType,$settleTime)
    {
        $s='';
        if($areaType == 1){
            $areaId=self::_getMyProvinceId($uid);
            $s = self::_mysql()->query("select getAreaSalesAmount($areaId, $areaType, '$settleTime')");
        }
        if($areaType == 2){
            $areaId=self::_getMyCityId($uid);
            $s = self::_mysql()->query("select getAreaSalesAmount($areaId, $areaType, '$settleTime')");
        }
        if($areaType == 3){
            $areaId=self::_getMyDistrictId($uid);
            $s = self::_mysql()->query("select getAreaSalesAmount($areaId, $areaType, '$settleTime')");
        }
        $a = self::_mysql()->to2DArray($s);
        if (count($a) > 0 && $a[0][0] != NULL) {
            return $a[0][0];
        } else {
            return 0;
        }
    }

    public static function _getAreaPeersNum($uid,$areaType,$vipId){
        $s='';
        if($areaType == 1){
            $areaId=self::_getMyProvinceId($uid);
            $s = self::_mysql()->query("select getAreaPeersNum($areaId, $areaType, $vipId)");
        }
        if($areaType == 2){
            $areaId=self::_getMyCityId($uid);
            $s = self::_mysql()->query("select getAreaPeersNum($areaId, $areaType, $vipId)");
        }
        if($areaType == 3){
            $areaId=self::_getMyDistrictId($uid);
            $s = self::_mysql()->query("select getAreaPeersNum($areaId, $areaType, $vipId)");
        }
        $a = self::_mysql()->to2DArray($s);
        if (count($a) > 0 && $a[0][0] != NULL) {
            return $a[0][0];
        } else {
            return 0;
        }
    }

    public static function _getSettleTime()
    {
        $s = self::_mysql()->query("SELECT time FROM  bonus_settlement order by time desc limit 1");
        $a = self::_mysql()->to2DArray($s);
        if (count($a) > 0 && $a[0][0] != NULL) {
            return $a[0][0];
        } else {
            return self::$_startupTime;
        }
    }

    public static function _getMyBonusSettlementHistory($uid)
    {
        $s = self::_mysql()->query("SELECT time,bonus,
                                        CASE WHEN channel = 0 THEN '银行卡'
                                            WHEN channel = 1 THEN '微信'
                                            when channel = 2 then '支付宝'
                                            ELSE '银行卡' END as channel,
                                        CASE WHEN status = 0 THEN '已结算'
                                            WHEN status = 1 THEN '已支付'
                                            when status = 2 then '完成'
                                            ELSE '完成' END as status
                                        FROM bonus_settlement where uid='$uid'");
        if ($s) {
            return self::_mysql()->to2DArray($s);
        } else {
            $temp = array();
            return $temp;
        }
    }

    public static function _getMyCost($uid)
    {
        $s = self::_mysql()->query("select getUserSuccessOrderSumMoney('$uid')");
        $a = self::_mysql()->to2DArray($s);
        if (count($a) > 0 && $a[0][0] != NULL) {
            return $a[0][0];
        } else {
            return 0;
        }
    }

    public static function _getMyShareMoney($uid)
    {
        $s = self::_mysql()->query("SELECT sum(allMoney) FROM XXXX.shareMoney where uid='$uid'");
        $a = self::_mysql()->to2DArray($s);
        if (count($a) > 0 && $a[0][0] != NULL) {
            return $a[0][0];
        } else {
            return 0;
        }
    }

    public static function _getMyRemainingShareMoney($uid)
    {
        $myShareTotalMoney=0;
        $myRebateTotalMoney=0;
        $mySuccessWithDrawMoney=0;
        $mySuccessSentRedbag=0;
        $myVipId=self::_getMyVipId($uid);
        $myVipInfo=self::_getMyVipInfo($myVipId);
        $myRebate=sizeof($myVipInfo)>0?$myVipInfo[0]['rebate']:0;
        $mySharePeople=self::_getMySharePeople($uid);
        for($i=0;$i<sizeof($mySharePeople);$i++){
            if($mySharePeople[$i]['sumMoney']>=0){
                $myShareTotalMoney+=$mySharePeople[$i]['sumMoney'];
            }
        }
        $myRebateTotalMoney=sprintf("%.2f", $myShareTotalMoney*$myRebate);

        //已提现的金额
        $s = self::_mysql()->query("select getUserSuccessWithDrawMoney('$uid')");
        $a = self::_mysql()->to2DArray($s);
        if (count($a) > 0 && $a[0][0] != NULL) {
            $mySuccessWithDrawMoney = $a[0][0];
        } else {
            $mySuccessWithDrawMoney = 0;
        }

        //已发出的红包
        $s = self::_mysql()->query("SELECT sum(fatherSentRedbag) FROM sharePersonUp where fatherUid='$uid' and bindStatus=1");
        $a = self::_mysql()->to2DArray($s);
        if (count($a) > 0 && $a[0][0] != NULL) {
            $mySuccessSentRedbag = $a[0][0];
        } else {
            $mySuccessSentRedbag = 0;
        }

        //已转入的红包
        $mySuccessTransferRedbag = self::_getMyRedbagUpTransfer($uid);

        if($myRebateTotalMoney>=($mySuccessWithDrawMoney+$mySuccessSentRedbag-$mySuccessTransferRedbag)){
            $MyRemainingShareMoney = $myRebateTotalMoney-$mySuccessWithDrawMoney-$mySuccessSentRedbag+$mySuccessTransferRedbag;
            return sprintf("%.2f",$MyRemainingShareMoney);
        }else{
            return 0;
        }
    }


    public static function _updateMyShareMoney($uid){
        $myShareTodayMoney=0;
        $myShareMonthMoney=0;
        $myShareTotalMoney=0;

        $myVipId=self::_getMyVipId($uid);
        $myVipInfo=self::_getMyVipInfo($myVipId);
        $myRebate=sizeof($myVipInfo)>0?$myVipInfo[0]['rebate']:0;
        $mySharePeople=self::_getMySharePeople($uid);
        for($i=0;$i<sizeof($mySharePeople);$i++){
            if($mySharePeople[$i]['sumMoney']>=0){
                $myShareTodayMoney+=$mySharePeople[$i]['todayMoney'];
                $myShareMonthMoney+=$mySharePeople[$i]['monthMoney'];
                $myShareTotalMoney+=$mySharePeople[$i]['sumMoney'];
            }
        }
//        $myRebateMoney=$myShareTotalMoney*$myRebate;
        $myRebateTodayMoney=sprintf("%.2f", $myShareTodayMoney*$myRebate);
        $myRebateMonthMoney=sprintf("%.2f", $myShareMonthMoney*$myRebate);
        $myRebateTotalMoney=sprintf("%.2f", $myShareTotalMoney*$myRebate);
//        var_dump($myRebate.'*'.$myShareTotalMoney.'='.$myRebateMoney);
        self::_mysql()->query("update shareMoney set todayMoney='$myRebateTodayMoney',thisMonthMoney='$myRebateMonthMoney',allMoney='$myRebateTotalMoney' where uid='$uid'");
        if (mysql_affected_rows() > 0) {
            return self::OK;
        } else {
            return self::SQL_ERR;
        }
    }


    public static function _updateMyVipIdAndShare($uid){
        $myVipId=self::_getMyVipId($uid);
        $myCost=self::_getMyCost($uid);
        $mySharePeople=self::_getMySharePeople($uid);
        $myVipInfo=self::_getMyVipInfo($myVipId);
        $shareMaxForUpgrade=sizeof($myVipInfo)>0?$myVipInfo[0]['shareMax']:9999999;
        $myShareVipPeople=0;
        for($i=0;$i<sizeof($mySharePeople);$i++){
            if($mySharePeople[$i]['sumMoney']>=Config::$vip_bench){
                $myShareVipPeople++;
            }
        }
        if($myVipId == 0){
            //消费满188 升级vip1
            if($myCost>=Config::$vip_bench){
                self::_mysql()->query("update user set vipId=1 where uid='$uid'");
                if (mysql_affected_rows() > 0) {
                    return self::OK;
                } else {
                    return self::SQL_ERR;
                }
            }
        }else if($myVipId == 4 || $myVipId == 5){
            //即将申请成为市代/省代 人工后台操作刷新vipId
            //四大直辖市及港澳台地区 最高只能市代
            //当该市有唯一市代后 其他人继续分享 vipId继续需要自动提示
            if(self::_isSpecialDistrict($uid)){
                //属于7大特区 不会再继续提升
            }else{
//                if($myVipId == 7){
//                    $myCityId=self::_getMyCityId($uid);
//                    $s = self::_mysql()->query("SELECT count(uid) FROM XXXX.user where addr_city_id='$myCityId' and vipId>=8");
//                    $a = self::_mysql()->to2DArray($s);
//                    if ($a[0][0] > 0) {
//                        if($myShareVipPeople > $shareMaxForUpgrade){
//                            if($myVipId < Config::$_vipLevelMax){
//                                $myVipId++;
//                            }
//                            self::_mysql()->query("update user set vipId=$myVipId where uid='$uid'");
//                            if (mysql_affected_rows() > 0) {
//                                return self::OK;
//                            } else {
//                                return self::SQL_ERR;
//                            }
//                        }
//                    } else {
//                        return self::SQL_ERR;
//                    }
//                }
                if($myVipId == 4 || $myVipId == 5){
                    //市代/省代需要人工审核
                }
            }

        }else{
            //分享人数大于该等级最大值 即可自动升级
            if($myShareVipPeople > $shareMaxForUpgrade){
                if($myVipId < Config::$_vipLevelMax){
                    $myVipId++;
                }
                self::_mysql()->query("update user set vipId=$myVipId where uid='$uid'");
                if (mysql_affected_rows() > 0) {
                    return self::OK;
                } else {
                    return self::SQL_ERR;
                }
            }
        }
    }

    public static function _queryExpress($expressNo){
        $expressAPI='http://q.kdpt.net/api?id=XXXXXXX&com=zhongtong&show=json&nu='.$expressNo;
        return file_get_contents($expressAPI);
    }
}
?>