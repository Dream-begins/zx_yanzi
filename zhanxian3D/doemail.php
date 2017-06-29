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
	die("服务器已合并或找不到服务器");
$zone = indexToZone(intval(getParam("zone","1")));
mysql_select_db($DB_GAME, $con2) or die("mysql select db error". mysql_error());
mysql_query("SET NAMES utf8",$con2); 
$openid = getParam("username",null);
$sql = "select * from CHARBASE where  NAME = '".$openid."'";
errlog($sql);
$result = mysql_query($sql,$con2)
    or die("Invalid query: " . mysql_error());
$total = mysql_num_rows($result);

while ($row = mysql_fetch_assoc($result)) {
	$sql2 = "insert into GIFT (CHARID,ZONE,NAME,ITEMID1,ITEMID2,ITEMID3,ITEMNUM1,ITEMNUM2,ITEMNUM3,BIND1,BIND2,BIND3,MAILFROM,MAILTITLE,MAILTEXT) value(".$row["CHARID"].",'".$row["ZONE"]."','".mysql_escape_string($row["NAME"])."',".getParam("objs1","0").",".getParam("objs2","0").",".getParam("objs3","0").",".getParam("nums1","0").",".getParam("nums2","0").",".getParam("nums3","0").",".getParam("binds1","1,1,1").",".getParam("binds2","1,1,1").",".getParam("binds3","1,1,1").",'系统','".getParam("subject","")."','".getParam("content","")."');";
	errlog($sql2);
	$result2 = mysql_query($sql2,$con2)
		or die("Invalid query: " . mysql_error());
}

?>

发放成功，共发放<?php echo $total; ?>名玩家 
