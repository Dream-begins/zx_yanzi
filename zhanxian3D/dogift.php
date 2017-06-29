<?php 
ini_set('display_errors', '1');
error_reporting (E_ALL); // Report everything

date_default_timezone_set("PRC");
ini_set('default_charset','utf-8');
include_once "common.php";
include_once "checklogin.php";
include_once "upgrade.php";
include_once "db2new.php";
if(!$con2 )
	die("服务器不存在或已合并:".getParam("zone"));
$zone = indexToZone(intval(getParam("zone","1")));
echo $zone;
echo getParam("zone","1");
mysql_select_db($DB_GAME, $con2) or die("mysql select db error". mysql_error()); 
//mysql_select_db("SessionServer", $con) or die("mysql select db error". mysql_error()); 
mysql_query("SET NAMES utf8",$con2); 
$createtime = getParam("createtime",null);
$lastactive = getParam("lastactive",null);
$createtime2 = getParam("createtime2",null);
$lastactive2 = getParam("lastactive2",null);
$level = getParam("level",null);
$maxlevel = getParam("maxlevel",null);
$startTime=microtime(true);
$sql = "select CHARID,ZONE,NAME from CHARBASE where 1=1 ";
if(!empty($createtime))
	$sql .="and CREATETIME >=  UNIX_TIMESTAMP('".$createtime."')";
if(!empty($createtime2))
	$sql .=" and CREATETIME <  UNIX_TIMESTAMP('".$createtime2."')";
if(!empty($lastactive))
	$sql .= " AND LASTACTIVEDATE > '".$lastactive."'";
if(!empty($lastactive2))
	$sql .=" AND LASTACTIVEDATE < '".$lastactive2."'";
if(!empty($level))
	$sql = $sql ." and LEVEL>=".$level;
if(!empty($maxlevel))
	    $sql = $sql ." and LEVEL<".$maxlevel;
//echo $DB_GAME;

$result = mysql_query($sql,$con2)
    or die("Invalid query: " . mysql_error());
$total = mysql_num_rows($result);
//echo $total;
$time2 = microtime(true);
$sql = "insert into GIFT (CHARID,ZONE,NAME,ITEMID1,ITEMID2,ITEMID3,ITEMNUM1,ITEMNUM2,ITEMNUM3,BIND1,BIND2,BIND3,MAILFROM,MAILTITLE,MAILTEXT) values";
$cnt = 0;
$sqlval="";
while ($row = mysql_fetch_assoc($result)) {
//while ($row = mysql_fetch_array($result)) {
	//$sql2 = "insert into GIFT (CHARID,ZONE,NAME,ITEMID1,ITEMID2,ITEMID3,ITEMNUM1,ITEMNUM2,ITEMNUM3,BIND1,BIND2,BIND3,MAILFROM,MAILTITLE,MAILTEXT) value(".$row["CHARID"].",'".$row["ZONE"]."','".mysql_escape_string($row["NAME"])."',".getParam("objs1","0").",".getParam("objs2","0").",".getParam("objs3","0").",".getParam("nums1","0").",".getParam("nums2","0").",".getParam("nums3","0").",".getParam("binds1","1,1,1").",".getParam("binds2","1,1,1").",".getParam("binds3","1,1,1").",'系统','".getParam("subject","")."','".getParam("content","")."');";
	if($sqlval !="")
		$sqlval = $sqlval.",";
	$sqlval =$sqlval. " (".$row["CHARID"].",'".$row["ZONE"]."','".mysql_escape_string($row["NAME"])."',".getParam("objs1","0").",".getParam("objs2","0").",".getParam("objs3","0").",".getParam("nums1","0").",".getParam("nums2","0").",".getParam("nums3","0").",".getParam("binds1","1,1,1").",".getParam("binds2","1,1,1").",".getParam("binds3","1,1,1").",'系统','".getParam("subject","")."','".getParam("content","")."')";
	$cnt = $cnt+strlen($sqlval);
	if($cnt>=20000)
	{
		$cnt = 0;
		$result2 = mysql_query($sql.$sqlval,$con2)
			or die("Invalid query: " . mysql_error());
		
		$sqlval="";
	}
}
if($cnt>0)
{
	$result2 = mysql_query($sql.$sqlval,$con2)
		    or die("Invalid query: " . mysql_error());
}
if($con2)
	mysql_close($con2);
$endTime = microtime(true);
?>

发放<?php echo getParam("zone");?>成功，共发放<?php echo $total; ?>名玩家 
执行时间1:<?php echo "sss:".round(($endTime - $time2),4);  ?>,<?php echo round(($endTime - $startTime),4);  ?>
