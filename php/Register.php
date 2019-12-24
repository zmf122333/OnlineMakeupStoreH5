<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/24 0024
 * Time: 02:21
 */

require_once 'Config.php';

class Register
{
    private $userName;
    private $passWord;
    private $phone;
    private $codeSMS;
    private $type; //1-users,passwd login;2-phone,SMS login

    public function __construct($u,$p,$t){
        $this->type = $t;
        if($t == 2){
            $this->phone = $u;
            $this->codeSMS = $p;
        }else{
            $this->userName = $u;
            $this->passWord = $p;
        }
    }

    public function RegisterWith1($c){
        $c->query("SELECT uid FROM account where userName='$this->userName' and passWord='$this->passWord'");
        if(mysql_affected_rows()>0){
            return Config::USR_EXIST;
        }else{
            $uid = $this->random_string(11);
            $c->query("insert into account(uid,userName,passWord,registerTime) VALUES('$uid','$this->userName','$this->passWord',date('YmdHis', time()))");
            if(mysql_affected_rows()>0) {
                return $uid;
            }else{
                return Config::SQL_ERR;
            }
        }
    }

    public function RegisterWith2($u,$p){

    }

    private  function random_string($bit){
        $rand = "";
        for ($i=1; $i<=$bit; $i++) {
            $rand .= substr('0123456789ZAQWSXCDERFVBGTYHNMJUIKLOP',rand(0,36),1);
        }
        return $rand;
    }

}