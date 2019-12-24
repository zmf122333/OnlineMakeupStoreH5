<?php
/*
 * Created on 2013-8-19.
 * (c) 2013 Coboqo Inc.
 *
*/

 //Turn off all deprecated warnings including them from mysql_*:
// or you change the code regard as this page says:
// http://stackoverflow.com/questions/21797118/deprecated-mysql-connect						
//error_reporting(E_ALL ^ E_DEPRECATED);
error_reporting(0);
class Mysql {
	private $host;
	private $name;
	private $password;
	private $dbname;
	private $db;
	private $link;
	public  $errmsg;
	public function __construct($server,$dbuser,$psw){
		$this->open($server,$dbuser,$psw);
	}
	private function open($server,$dbuser,$psw){
		$this->host=$server;
		$this->name=$dbuser;
		$this->password=$psw;
		$this->link=mysql_connect($this->host,$this->name,$this->password) or die("connect hostname fail.");
	}
 	public function to2DArray($result){
 		$_2DArray=Array();
 		$arr=new ArrayObject($_2DArray);
 		while($row=mysql_fetch_array($result)){
 			$arr->append($row);
 		}
 		return $arr ;
 	}
 	public function opendb($database,$charset){
		$this->dbname=$database;
		mysql_query("set names ".$charset);
		$this->db=mysql_select_db($this->dbname,$this->link);
		if (!$this->db) {
			$this->errmsg="connect dabase error.";
		}
	}
	public function query($sql) {
		$result=mysql_query($sql);
		if (!$result) {
			$this->errmsg="handler running error";
		}
		return $result;
	}
}
?>


