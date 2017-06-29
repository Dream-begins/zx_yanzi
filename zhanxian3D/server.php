<?php 
ini_set('default_charset','utf-8'); 
include_once "common.php";
include_once "checklogin.php";
include_once "upgrade.php";

include_once "newweb/h_header.php";

$DB_HOST=FT_MYSQL_COMMON_HOST; 
$DB_USER=FT_MYSQL_COMMON_ROOT;
$DB_PASS=FT_MYSQL_COMMON_PASS;
$DB_NAME=FT_MYSQL_GAMEDATA_DBNAME;

$con = mysql_connect($DB_HOST,$DB_USER,$DB_PASS);

if(!$con) die("mysql connect error");

mysql_query("set names 'utf8'");


mysql_select_db($DB_NAME, $con) or die("mysql select db error". mysql_error()); 

$PG_NUM = getParam("rows","10");
 
$PG_NUM = intval($PG_NUM);
$spg =getParam("page","1");
$pg = intval($spg)-1;
$zone = getParam("zone",null);
$from = getParam("from",null);
$to = getParam("to",null);
$from = getFrom();
$to = getTo(); 
$export = getParam("export",null)=="1";
if(isset($GLOBALS["tmp"]))
	$GLOBALS["tmp"] = $GLOBALS["tmp"]+1;
else
	$GLOBALS["tmp"] = 1;

$cond="dt>='".date("Y-m-d",$from)."' and dt<'".date("Y-m-d",$to)."'";
if($zone)
{
   $zoneindex=ZoneUtil::indexToZoneOrig($zone); 
   $cond .=" and serverid=".$zoneindex;

}	
 
$sql = "select serverid,ammount*10 as ammount ,cnt,ammount*10/cnt as arpu,dt,qqgame,qzone,qqgamecnt,qzonecnt from ServerRevenue where ".$cond." order by dt desc,serverid desc";
$result = mysql_query($sql,$con)
    or die("Invalid query: " . mysql_error());
$total = mysql_num_rows($result);
//var_dump($result);
$pgcnt = ceil($total/$PG_NUM);
$pg = max(0,min($pg,$pgcnt-1)); 
 
$prev = $pg>0?($pg-1):0;
$next=$pg<($pgcnt-1)?($pg+1):($pgcnt-1);
$data=array();
$data["total"]=$total;
if(!$export)
	$sql =$sql." limit ".$pg*$PG_NUM.",".$PG_NUM; 

$result = mysql_query($sql,$con)
    or die("Invalid query: " . mysql_error());
$rows = array();
if($export)
{
	while ($row = mysql_fetch_row($result)) {
		$row[0] = zoneToIndex($row[0]);
		$has = false;
		for($i=count($rows)-1;$i>=0;$i--)
		{
			if($rows[$i][0] == $row[0] && $rows[$i][4] == $row[4])
			{
				$rows[$i][1] +=$row[1];
				$rows[$i][2] +=$row[2];
				if($rows[$i][2]>0)
					$rows[$i][3] = $rows[$i][1]/$rows[$i][2];
				else
					$rows[$i][3] = 0;	
				$rows[$i][5] +=$row[5];
				$rows[$i][6] +=$row[6];
				$rows[$i][7] +=$row[7];
				$rows[$i][8] +=$row[8];
				$has = true;
			}
		}
		if(!$has)
			array_push($rows,$row);
	}
}
else
{
	while ($row = mysql_fetch_assoc($result)) {
		
		$row["serverid"] = ZoneUtil::zoneToIndexOrig(intval($row["serverid"]));
		array_push($rows,$row);
	
	}
}

$data["rows"] = $rows;
if($export)
{
foreach ($rows as $key => $value) 
{
    $rows[$key][1] = ceil($value[1]);
    $rows[$key][3] = number_format($value[3],2);
}
	$headers=array("服务器","总收入","付费人数","ARPU","时间");
	require_once "exportExcel.php";
	export($headers,$rows,"服务器收入".date("ymd",$from)."-".date("ymd",$to));
}	
else
	echo json_encode((object)$data);
