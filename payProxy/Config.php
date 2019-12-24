<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/1 0001
 * Time: 04:19
 */
ini_set('date.timezone','Asia/Shanghai');
define('ROOT_PATH', dirname(__FILE__));
error_reporting(-1);
require_once 'Mysql.php';

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


    //mysql.
    public static $_mysqlHost = '127.0.0.1';
    public static $_mysqlPort = 3306;
    public static $_mysqlUser = 'xxxxxxxx';
    public static $_mysqlPwd = 'xxxxxxxx';
    public static $_mysqlDatabase = 'xxxxxxxx';

    //weixin pay
    public static $_weixinMCH_APPID = 'xxxxxxxx';
    public static $_weixinMCH_ID = 'xxxxxxxx';
    public static $_weixinAPIKey = 'xxxxxxxx';

    //weixin withdraw api.
    public static $_weixinWithDrawAPI = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
    //weixin pem file.
    public static $_weixinPayCertificateFile = '/weixinPayJSAPI/cert/apiclient_cert.pem';
    public static $_weixinPayPrivateKeyFile = '/weixinPayJSAPI/cert/apiclient_key.pem';

    public static function _mysql()
    {
        $c = new Mysql(self::$_mysqlHost, self::$_mysqlUser, self::$_mysqlPwd);
        $c->opendb(self::$_mysqlDatabase, "utf8");
        return $c;
    }

    public static function _isValidUser($openId)
    {
        $s = self::_mysql()->query("SELECT count(userName) as sum FROM account where userName='$openId';");
        $a = self::_mysql()->to2DArray($s);
        if ($a[0][0] > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function _getUidByOpenId($openId)
    {
        $s = self::_mysql()->query("SELECT uid FROM account where userName='$openId';");
        $a = self::_mysql()->to2DArray($s);
        if (count($a)>0 && $a[0][0] != NULL) {
            return $a[0][0];
        } else {
            return '';
        }
    }

    public static function _isRemainingMoneyEnough($openId){
        $uid = self::_getUidByOpenId($openId);
        if($uid != ''){
            if(self::_getMyRemainingShareMoney($uid)>0){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
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

    public static function _getMyVipInfo($vipId){
        $s = self::_mysql()->query("SELECT shortRight,bonus,discount,rebate,shareMin,shareMax,getVipCondition($vipId+1) upgradeCondition,getVipLevel($vipId) myVip,getVipLongRight($vipId) myVipLongRight,
                                        getVipLevel($vipId+1) myVipPlusOneLevel,getVipLongRight($vipId+1) myVipPlusOneLongRight FROM XXXXXXX.vip where vipId=$vipId");
        if ($s) {
            return self::_mysql()->to2DArray($s);
        } else {
            $temp = array();
            return $temp;
        }
    }

    public static function _getMySharePeople($uid){
        $s = self::_mysql()->query("select a.uid,b.name,b.phone,getUserSuccessOrderNum(a.uid) as orderNum, getUserSuccessOrderSumMoney(a.uid) as sumMoney,
                                        getUserSuccessOrderSumMoneyToday(a.uid) as todayMoney, getUserSuccessOrderSumMoneyThisMonth(a.uid) as monthMoney
                                        from (SELECT distinct uid FROM sharePersonUp
                                        where fatherUid='$uid') a left join user b
                                        on a.uid=b.uid
                                        order by sumMoney desc");
        if ($s) {
            return self::_mysql()->to2DArray($s);
        } else {
            $temp = array();
            return $temp;
        }
    }

    public static function _getMyRedbagUpTransfer($uid)
    {
        $s = self::_mysql()->query("SELECT fatherSentRedbagTransfer FROM sharePersonUp where uid='$uid'");
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
        $myRebateTotalMoney=sprintf("%.3f", $myShareTotalMoney*$myRebate);

        //已提现的金额
        $s = self::_mysql()->query("select getUserSuccessWithDrawMoney('$uid')");
        $a = self::_mysql()->to2DArray($s);
        if (count($a) > 0 && $a[0][0] != NULL) {
            $mySuccessWithDrawMoney = $a[0][0];
        } else {
            $mySuccessWithDrawMoney = 0;
        }

        //已发出的红包
        $s = self::_mysql()->query("SELECT sum(fatherSentRedbag) FROM sharePersonUp where fatherUid='$uid' ");
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
            return sprintf("%.3f",$MyRemainingShareMoney);
        }else{
            return 0;
        }
    }
}