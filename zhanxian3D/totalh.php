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
 
$from = strtotime(getParam("from",null));
$date_time_array = getdate( $from);

$from1 = mktime(0, 0,0,$date_time_array ["mon"], $date_time_array["mday" ]+1,$date_time_array[ "year"]);

$to = strtotime(getParam("to",null));
$date_time_array = getdate( $to);
$to1 = mktime(0, 0,0,$date_time_array ["mon"], $date_time_array["mday" ]+1,$date_time_array[ "year"]);

$sql = "select * from HourRevenue where dt='".date("Y-m-d 00:00:00",$from)."' order by hour";
$result = mysql_query($sql)
    or die("Invalid query: " . mysql_error());
$data = array();
$data[0] = array();
$data[1] = array();
$data[2] = array();
$data[3] = array();
$data[4] = array();
$data[5] = array();
while ($row = mysql_fetch_assoc($result)) {
        array_push($data[0],floatval($row["ammount"]*10));
	array_push($data[2],floatval($row["qqgame"]*10));
	array_push($data[4],floatval($row["qzone"]*10));
}

$sql = "select * from HourRevenue where dt='".date("Y-m-d 00:00:00",$to)."' order by hour";
$result = mysql_query($sql)
    or die("Invalid query: " . mysql_error());
 

while ($row = mysql_fetch_assoc($result)) {
	array_push($data[1],floatval($row["ammount"]*10));
	array_push($data[3],floatval($row["qqgame"]*10));
	array_push($data[5],floatval($row["qzone"]*10));
}

echo json_encode($data);
