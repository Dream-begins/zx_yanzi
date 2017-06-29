<?php
ini_set('display_errors', '1');
error_reporting (E_ALL); // Report everything
date_default_timezone_set("PRC");
ini_set('default_charset','utf-8');


$PG_NUM = $_POST["rows"] or "20";
$PG_NUM = intval($PG_NUM);
$spg = $_POST["page"] or "1";
$pg = intval($spg)-1;

$SERVERID=2;
$DB_HOST="1.4.13.144:1025"; 
$DB_USER="hoolai";
$DB_PASS="hoolai";
$DB_NAME="GameData";

$con = mysql_connect($DB_HOST,$DB_USER,$DB_PASS);
if(!$con)
{
  die("mysql connect error");
}
mysql_query("set names 'utf8'");
mysql_select_db($DB_NAME, $con) or die("mysql select db error". mysql_error());
mysql_query("set names 'utf8'",$con);
$zoneId="";
$accId="";
$usrName="";
$sql = "select * from baninfo";
if(isset($_POST["zone"]))
{
    $zoneId = $_POST["zone"];
    $sql = "select * from baninfo where serverId=$zoneId";
}
if(isset($_POST["acc"]))
{
    $accId = $_POST["acc"];
    if($zoneId)
	$sql=$sql." and opid='".$accId."'";
    else
   	$sql = "select * from baninfo where opid='".$accId."'";
}
if(isset($_POST["usrname"]))
{
  $usrName = $_POST["usrname"];
  if($zoneId||$accId)
	 $sql = $sql." and username='".$usrName."'";
  else
 	 $sql = "select * from baninfo where username='".$usrName."'";
}

if(isset($_POST["from"]))
{
   $from=$_POST["from"];
   echo "1:".$from;
   $from = strtotime($from);
   echo "2:".$to;
   if($zoneId||$usrName||$accId)
     $sql = $sql." and dt>='".date("Y-m-d",$from)."'";
   else
     $sql = "select * from baninfo where dt>=".date("Y-m-d",$from)."'";
}
if(isset($_POST["to"]))
{
  $to=$_POST["to"];
  $to = strtotime($to);
  if($zoneId||$usrName||$from||$accId)
        $sql = $sql." and dt<='".date("Y-m-d",$to)."'";
  else
        $sql = "select * from baninfo where dt<=".date("Y-m-d",$from)."'";
}
$sql=$sql." order by id desc";
$result = mysql_query($sql)
    or die("Invalid query: " . mysql_error());
$total = mysql_num_rows($result);
$pgcnt = ceil($total/$PG_NUM);

if($pg>=$pgcnt && $pgcnt >0)
    $pg = $pgcnt-1;
$prev = $pg>0?($pg-1):0;
$next=$pg<($pgcnt-1)?($pg+1):($pgcnt-1);
$data=array();
$data["total"]=$total;
$sql =$sql." limit ".$pg*$PG_NUM.",".$PG_NUM;
$result = mysql_query($sql)
    or die("Invalid query: " . mysql_error());
$rows = array();
while ($row = mysql_fetch_assoc($result)) {
    array_push($rows,$row);
}
$data["rows"] = $rows;
echo json_encode((object)$data);
?>
