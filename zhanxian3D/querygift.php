<?php 
ini_set('display_errors', '1');
error_reporting (E_ALL); // Report everything

date_default_timezone_set("PRC");
ini_set('default_charset','utf-8');
include_once "common.php";
include_once "checklogin.php";
include_once "upgrade.php";
include_once "db2new.php";
if(!$con2)
	die("服务器不存在或已合并:".getParam("zone"));

//mysql_select_db("SessionServer", $con) or die("mysql select db error". mysql_error()); 
mysql_select_db($DB_GAME, $con2) or die("mysql select db error". mysql_error()); 
mysql_query("SET NAMES utf8",$con2); 
 
$sql = "select count(*) from GIFT where MAILTITLE='".$_GET["subject"]."'";
$result = mysql_query($sql,$con2)
    or die("Invalid query: " . mysql_error());
$row = mysql_fetch_row($result); 
echo  $row[0];
mysql_close($con2);

?> 
