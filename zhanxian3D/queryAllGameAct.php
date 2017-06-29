<?php 
ini_set('display_errors', '1');
error_reporting (E_ALL); // Report everything
date_default_timezone_set("PRC");
ini_set('default_charset','utf-8');
include_once "checklogin.php";
include_once "upgrade.php";
include_once "common.php";
include_once "db2new.php";
$zone1=$_POST["zone1"];
$zone2=$_POST["zone2"];
$acttype=$_POST["acttype2"];
$actid=$_POST["actid2"];
$actS=$_POST["actstate2"];
$stime = strtotime($_POST['stime']);
$totalArr = array();
for($i=$zone1;$i<=$zone2;$i++)
{
	$_dburl=getDBUrl($i);
	$_dbname=getDBName($i);
	if(!$_dburl)
	{
		continue;
	}
	$con = mysql_connect($_dburl,$DB_USER,$DB_PASS);
	if(!$con )
	{
		die("mysql connect error");
	}
	mysql_query("set names 'utf8'");
	mysql_select_db($_dbname, $con) or die("mysql select db error". mysql_error());
	if(isset($_POST['stime']) && $_POST['stime'])
	{
		$sql = "select ID, TYPE, ACTIVITYID, FROM_UNIXTIME(TIMESTART) as ts, FROM_UNIXTIME(TIMEEND) as te, FROM_UNIXTIME(TIMEEXPIRE) as tp, STATUS ,ICONID, TABID,TABINFO,TABTYPE,PICID,PICINFO,PLATID 
		from GAMEACTIVITY where STATUS=$actS AND TYPE=$acttype AND ACTIVITYID=$actid AND TIMESTART='$stime' order by TYPE,ACTIVITYID,ID ";
		
	}else{
		$sql = "select ID, TYPE, ACTIVITYID, FROM_UNIXTIME(TIMESTART) as ts, FROM_UNIXTIME(TIMEEND) as te, FROM_UNIXTIME(TIMEEXPIRE) as tp, STATUS ,ICONID, TABID,TABINFO,TABTYPE,PICID,PICINFO,PLATID 
		from GAMEACTIVITY where STATUS=$actS AND TYPE=$acttype AND ACTIVITYID=$actid  order by TYPE,ACTIVITYID,ID ";
	}


	$result = mysql_query($sql) or die("Invalid query: " . mysql_error());
	while($row=mysql_fetch_assoc($result))
	{
		$row["SID"]=$i;
		array_push($totalArr,$row);
	}
}

$PG_NUM = $_POST["rows"] or "10";
$PG_NUM = intval($PG_NUM);
$spg = $_POST["page"] or "1";
$pg = intval($spg)-1;


$total = count($totalArr);;
$pgcnt = ceil($total/$PG_NUM);
 
if($pg>=$pgcnt && $pgcnt >0)
	$pg = $pgcnt-1;
$prev = $pg>0?($pg-1):0;
$next=$pg<($pgcnt-1)?($pg+1):($pgcnt-1);
$data=array();
$data["total"]=$total;
$data["rows"] = $totalArr;
echo json_encode((object)$data);
