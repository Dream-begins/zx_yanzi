<?php 
ini_set('display_errors', '1');
error_reporting (0);
date_default_timezone_set("PRC");
ini_set('default_charset','utf-8');
include_once "common.php";
include_once "checklogin.php";
include_once "upgrade.php";
include_once "db2new.php";

if(!$con2) die("服务器不存在或已合服:".getParam("zone"));

mysql_select_db($DB_GAME, $con2) or die("mysql select db error:". mysql_error().",服:".getParam("zone")); 

mysql_query("SET NAMES utf8",$con2); 

$sql = "delete from GIFT where MAILTITLE='".$_GET["subject"]."'";

$result = mysql_query($sql,$con2) or die("Invalid query: " . mysql_error());

echo  '清除'.getParam("zone")."服 ".mysql_affected_rows().'条记录';

mysql_close($con2);

