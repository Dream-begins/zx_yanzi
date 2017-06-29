<?php
ini_set('default_charset','utf-8');
include_once "checklogin.php";
include_once "upgrade.php";
include_once "common.php";
include_once "newweb/h_header.php";

$DB_HOST=FT_MYSQL_COMMON_HOST; 
$DB_USER=FT_MYSQL_COMMON_ROOT;
$DB_PASS=FT_MYSQL_COMMON_PASS;
$DB_NAME=FT_MYSQL_GAMEDATA_DBNAME;

$con = mysql_connect($DB_HOST,$DB_USER,$DB_PASS);

if(!$con) die("mysql connect error");

mysql_query("set names 'utf8'");

mysql_select_db($DB_NAME, $con) or die("mysql SELECT db error". mysql_error()); 

$PG_NUM = getParam("rows", "10");
 
$PG_NUM = intval($PG_NUM);
$spg    = getParam("page", "1");
$export = getParam("export", null)=="1";

$pg     = intval($spg)-1;
$sql    = "SELECT dt,ammount*10 as ammount,cnt,ammount2*10 as ammount2,cnt2,qqgame,qqgamecnt,qqgame2,qqgamecnt2,qzone,qzonecnt,qzone2,qzonecnt2,website,websitecnt,website2,websitecnt2 from TotalRevenue order by dt desc";
$result = mysql_query($sql) or die("Invalid query: " . mysql_error());
$total  = mysql_num_rows($result);
$pgcnt  = ceil($total/$PG_NUM);
 
if($pg>=$pgcnt) $pg = $pgcnt-1;
$prev = $pg>0?($pg-1):0;
$next = $pg<($pgcnt-1)?($pg+1):($pgcnt-1);
$data = array();
$data["total"] = $total;
if(!$export) $sql = $sql." limit ".$pg*$PG_NUM.",".$PG_NUM; 
$result = mysql_query($sql) or die("Invalid query: " . mysql_error());
$rows = array();

if($export)
{
    while ($row = mysql_fetch_row($result))
    {
        array_push($rows,$row);
    }
}
else
{
     while ($row = mysql_fetch_assoc($result))
     {
        foreach($row as $k=>$f)
        {
            if(is_float($f))
                $f =1;
        }
        array_push($rows,$row);
    }
}

$data["rows"] = $rows;

if($export)
{
    require_once "exportExcel.php";
    export(array("时间","总收入","总人数","总收入2(含2Q点)","总人数2","大厅付费","大厅人数","大厅付费2","大厅人数2","空间付费","空间人数","空间付费2","空间人数2","官网付费","官网人数","官网付费2","官网人数2"),$rows,"总收入");
}    

echo json_encode((object)$data);

