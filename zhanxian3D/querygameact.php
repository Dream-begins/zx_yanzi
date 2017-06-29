<?php
 
ini_set('display_errors', '1');
error_reporting(-1);
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
$option=$_POST["opName"];
mysql_select_db($DB_GAME, $con2) or die("mysql select db error". mysql_error()); 
$PG_NUM = $_POST["rows"] or "10";
$PG_NUM = intval($PG_NUM);
$spg = $_POST["page"] or "1";
$pg = intval($spg)-1;

$pingbi = isset($_POST['pingbi']) ? $_POST['pingbi'] : 0;


$where = ' WHERE 1 ';

/*if($pingbi=='true')
{
$where .="AND (";
	$pingbi_arr = get_pingbi_arr();
	foreach ($pingbi_arr as $key => $value){
		if($value['type'] && $value['bianhao'])
			$where .= " (TYPE <> '".(int)$value['type']."' AND ACTIVITYID <> '".(int)$value['bianhao']."') OR";
	}
$where = rtrim($where,'OR');
$where .=') ';

}
echo $where;
*/
if($pingbi=='true')
{
    $where .="AND concat(TYPE,'-',ACTIVITYID) not in(";
	$pingbi_arr = get_pingbi_arr();
	foreach ($pingbi_arr as $key => $value){
		if($value['type'] && $value['bianhao'])
			$where .= " '".(int)$value['type']."-".(int)$value['bianhao']."',";
	}
    $where = rtrim($where, ',');
    $where .=') ';

}


if($option=="over")
    $sql = "SELECT ID, TYPE, ACTIVITYID, concat(TYPE,'-',ACTIVITYID) as flag1, FROM_UNIXTIME(TIMESTART) as ts, FROM_UNIXTIME(TIMEEND) as te, FROM_UNIXTIME(TIMEEXPIRE) as tp, STATUS ,ICONID, TABID,TABINFO,TABTYPE,PICID,PICINFO,PLATID 
			from GAMEACTIVITY ".$where." AND STATUS = 3 order by TYPE,ACTIVITYID,ID ";
else
    $sql = "SELECT ID, TYPE, ACTIVITYID,concat(TYPE,'-',ACTIVITYID) as flag1,  FROM_UNIXTIME(TIMESTART) as ts, FROM_UNIXTIME(TIMEEND) as te, FROM_UNIXTIME(TIMEEXPIRE) as tp, STATUS ,ICONID, TABID,TABINFO,TABTYPE,PICID,PICINFO,PLATID 
			from GAMEACTIVITY ".$where." AND STATUS < 3 order by TYPE,ACTIVITYID,ID ";

//echo $sql;

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


function get_pingbi_arr()
{
	include_once "newweb/h_header.php";
$dbh = new PDO('mysql:host='.FT_MYSQL_COMMON_HOST.';dbname='.FT_MYSQL_ADMIN_DBNAME.';port='.FT_MYSQL_COMMON_PORT.';charset=utf8', FT_MYSQL_COMMON_ROOT, FT_MYSQL_COMMON_PASS);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);          
$dbh->query("SET NAMES UTF8");

$sql = "SELECT * FROM ft_gapingbi_conf";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();

    return $result;
}
