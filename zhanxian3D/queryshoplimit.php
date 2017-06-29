<?php 
ini_set('display_errors', '1');
error_reporting (E_ALL); // Report everything

date_default_timezone_set("PRC");
ini_set('default_charset','utf-8');
include_once "checklogin.php";
include_once "upgrade.php";
include_once "common.php";
include_once "db2new.php";
if(!$con2)
{
	$data=array();
	$data["total"]=0;
	$rows=array();
	$data["rows"]=$rows;
	echo json_encode((object)$data);
	return;
}
mysql_select_db($DB_GAME, $con2) or die("mysql select db error". mysql_error());
$PG_NUM = $_POST["rows"] or "10";
$PG_NUM = intval($PG_NUM);
$spg = $_POST["page"] or "1";
$pg = intval($spg)-1;
$sql = "select WEIGHT,TITLE,ICON,ID,INDEXID,PAGE,OBJID,OBJNAME,SUPERMARKETPOS,MONEYTYPE,ORIGINALPRICE,DISCONTPRICE,ISBIND,SINGLECANBUYNUM,TOTALBUYLIMITNUM,OPENTYPE,OPENTTIME,CLOSETIME,LIMITEDPURCHASESTARTTIME,LIMITEDPURCHASEENDTIME,TAGTYPE,NEEDVIPLEVEL,AWARDS,STAGEPRICES,PRESHOW,`DESC`,MONEYID from SHOPLIMIT where LIMITEDPURCHASEENDTIME >= curdate()-2 order by ID ";
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
