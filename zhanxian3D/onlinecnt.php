<?php

ini_set('default_charset','utf-8');
include_once "common.php";
include_once "checklogin.php";
include_once "upgrade.php";
include_once "db2new.php";
mysql_select_db($DB_GAME, $con2) or die("mysql select db error". mysql_error());

    $from = getParam("from",null);
    $date_time_array = getdate( time());
    if(!$from)
        $from = mktime(0, 0,0,$date_time_array ["mon"], $date_time_array["mday" ],$date_time_array[ "year"]);
    else
        $from = strtotime($from);

$sql = "select FROM_UNIXTIME(cast((TIME-".$from.")/300 as unsigned)*300+".$from.",'%Y-%m-%d %H:%i:%S') as tm,ONLINE from GAMEONLINE where TIME>=".$from." and TIME<".getDateDiff($from,1);
$result = mysql_query($sql,$con2) or die("Invalid query: " . mysql_error());
$data= array();
$data[0] = array();
$data[1] = array();
$data[2] = array();
$data[3] = array();
$cnt=0;
while ($row = mysql_fetch_assoc($result)) {
	if($cnt % 2 == 0)
	{
	$data[0][] = $row["tm"];
	$data[1][] = intval($row["ONLINE"]);
	}
	$cnt = $cnt +1;
}
$to = getParam("to",null);
if($to)
{
    $to = strtotime($to);
    $sql = "select FROM_UNIXTIME(cast((TIME-".$to.")/300 as unsigned)*300+".$to.",'%Y-%m-%d %H:%i:%S') as tm,ONLINE from GAMEONLINE where TIME>=".$to." and TIME<".getDateDiff($to,1);
    $result = mysql_query($sql,$con2) or die("Invalid query: " . mysql_error());
    $cnt=0;
    while ($row = mysql_fetch_assoc($result)) {
        if($cnt % 2 == 0)
        {
        $data[2][] = $row["tm"];
        $data[3][] = intval($row["ONLINE"]);
        }
        $cnt = $cnt +1;
    }
$data[3] = add_point($data[2][0],$data[3]);
}
$data[1] = add_point($data[0][0],$data[1]);
function add_point($point, $neadadd_arr)
{
    if($point)
    {
        $data00totmp = strtotime($point);
    }

    $daystartstamp = strtotime(date('Y-m-d',$data00totmp));

    $longtime = $data00totmp - $daystartstamp;
    $add_point = ceil($longtime/10/60)-1;
    for($i=1;$i<=$add_point;$i++)
    {
        array_unshift($neadadd_arr,0);
    }
    return $neadadd_arr;
}


echo json_encode($data);
