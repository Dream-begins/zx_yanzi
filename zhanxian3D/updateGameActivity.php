<?php 
ini_set('display_errors', '1');
error_reporting (0);

date_default_timezone_set("PRC");
ini_set('default_charset','utf-8');
include_once "common.php";
include_once "checklogin.php";
include_once "upgrade.php";
include_once "db2new.php";

if(!$con2) die("服务器找不到或已合并");

$server = intval(getParam("zone","1"));
$record = intval(getParam("record",null));

mysql_select_db($DB_GAME, $con2) or die("mysql select db error". mysql_error());
mysql_query("SET NAMES utf8"); 	

if(isset($_POST['allact']) && $_POST['allact']==1){
    $sql = "update `GAMEACTIVITY`set TIMEEXPIRE=UNIX_TIMESTAMP(now())";
    errlog($sql);
    $result = mysql_query($sql,$con2) or die("Invalid query: " . mysql_error());
    echo "$server 服 所有活动过期";
}else{
    $sql = "update `GAMEACTIVITY`set TIMEEXPIRE=UNIX_TIMESTAMP(now()) where ID='".$record."'";
    errlog($sql);
    $result = mysql_query($sql,$con2) or die("Invalid query: " . mysql_error());
    echo "$server 服结束一条活动，ID：$record\n";
}
