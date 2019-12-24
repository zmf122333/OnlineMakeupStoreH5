<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/24 0024
 * Time: 02:21
 */

require_once 'Config.php';

class Guest
{
    public static function RegisterWithGuest($c){
            $uid = Guest::random_string(11);
            $time = date('YmdHis', time());
            $c->query("insert into account(uid,registerTime) VALUES('$uid',$time)");
            if(mysql_affected_rows()>0) {
                $c->query("insert into shareMoney(uid) VALUES('$uid') ON DUPLICATE KEY UPDATE uid='$uid';");
                return $uid;
            }else{
                return Config::SQL_ERR;
            }

    }

    private static function random_string($bit){
        $rand = "";
        for ($i=1; $i<=$bit; $i++) {
            $rand .= substr('0123456789ZAQWSXCDERFVBGTYHNMJUIKLOP',rand(0,36),1);
        }
        return $rand;
    }

}