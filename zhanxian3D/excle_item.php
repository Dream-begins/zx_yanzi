<?php
ini_set('default_charset','utf-8');
include_once "checklogin.php";
include_once "upgrade.php";
include_once "common.php";
include_once "newweb/h_header.php";

$DB_HOST='127.0.0.1'; 
$DB_USER='root';
$DB_PASS='root';
$DB_NAME='zx3d';

$con = @mysql_connect($DB_HOST,$DB_USER,$DB_PASS);

if(!$con) die("mysql connect error");

mysql_query("set names 'utf8'");

mysql_select_db($DB_NAME, $con) or die("mysql SELECT db error". mysql_error()); 

$PG_NUM = getParam("rows", "10");
 
$PG_NUM = intval($PG_NUM);
$spg    = getParam("page", "1");
$export = getParam("export", null)=="1";

$pg     = intval($spg)-1;
$sql    = "SELECT from_unixtime(TIME,'%Y-%m-%d %H:%i:%S') as datetime,SERVERID,ZONEID,USERID,ACCNAME,NAME,LEVEL,PARA1,PARA2,PARA3,PARA4,PARA5,EXTRA,ITEMTYPE,ITEMEVENT,EVENTINFO,ITEMID,ITEMCOUNT,ITEMNAME from db_zxitem order by datetime desc";
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
//var_dump($data['rows']);exit;
if($export)
{
    require_once "exportExcel.php";
    export(array("时间","服务器id","区域id","玩家id","账号名","玩家名","玩家等级","参数1","参数2","参数3","参数4","参数5","额外信息","物品类型","物品事件","掉落id","物品id","物品数量","物品名称"),$rows,"斩仙物品");
}    

echo json_encode((object)$data);

