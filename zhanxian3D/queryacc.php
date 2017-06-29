<?php 
ini_set('default_charset','utf-8'); 
include_once "common.php";
include_once "upgrade.php";
include_once "db2new.php";

$zone = intVal(getParam("zone","1"))+3;

if($con2 == null) die("{}");

mysql_select_db($DB_GAME, $con2) or die("mysql select db error". mysql_error());

$zone = indexToZone(getParam("zone"),"1"); 
$cond = "NAME='".getParam("username","1")."'"; 

$sql = "SELECT CHARID,ACCNAME,(convert(ZONE , SIGNED)-3) as ZONE,NAME,LEVEL,VIP,MONEY1,MONEY2,MONEY3,MONEY4,MONEY5,MONEY6,MONEY9,MONEY10,MONEY13,FROM_UNIXTIME(CREATETIME) as CREATETIME,LASTACTIVEDATE,LINQI,CREATEIP from CHARBASE where ".$cond;
$result = mysql_query($sql,$con2) or die("Invalid query: " . mysql_error($con2));
$data = mysql_fetch_assoc($result);
$sepid = intval($zone)*1000000+$data["CHARID"];

$sql = "SELECT SEPTID from SEPTMEMBER where SEPTMBRID=".$sepid;
$result2 = mysql_query($sql,$con2) or die("Invalid query: " . mysql_error($con2));
$row = mysql_fetch_row($result2);
$data["familyname"]="";

if($row)
{
    $sql = "SELECT SEPTNAME from SEPT where SEPTID=".$row[0];
    $result2 = mysql_query($sql,$con2) or die("Invalid query: " . mysql_error($con2));
    $row = mysql_fetch_row($result2);
    if($row) $data["familyname"] = $row[0];
}

echo json_encode((object)$data);

