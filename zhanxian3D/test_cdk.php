<?php 
exit;
ini_set('display_errors', '1');
error_reporting (-1);
set_time_limit(0); 
date_default_timezone_set("PRC");
ini_set('default_charset','utf-8');

include_once "common.php";
//include_once "checklogin.php";
require_once "reader.php";
include_once "newweb/h_header.php";
ini_set("memory_limit", "8800M"); 
$filename = getParam("file2",null);

$startTime=microtime(true);

$temp = strrpos($filename,"\\");
$filename = substr($filename,$temp+1);

$a = $argv['1'];

$filename= $a.'.csv';
$filename = "excel/".$filename;

$con = mysql_connect(FT_MYSQL_COMMON_HOST,FT_MYSQL_COMMON_ROOT,FT_MYSQL_COMMON_PASS);
mysql_select_db(FT_MYSQL_EXTGAMESERVER_DBNAME);
mysql_set_charset('utf8');

echo $sql = "load data local infile '".$filename."' ignore into table CDKEYDUIHUAN character set utf8
fields terminated by ','
lines terminated by '\n'
ignore 1 lines 
(`CDKEY`,`TYPE`,`GIFTID`,`FLAG`);";

$result = mysql_query($sql);

$total = mysql_affected_rows();

echo '成功导入'.$total.'条';

