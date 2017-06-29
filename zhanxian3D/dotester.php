<?php 
ini_set('display_errors', '1');
error_reporting (0);
date_default_timezone_set("PRC");
ini_set('default_charset','utf-8');
include_once "common.php";
include_once "checklogin.php";
include_once "upgrade.php";
include_once "newweb/h_header.php";

$userid = getParam("userid");
$userid = $_POST["tempId"];
echo "id:".$userid;
$DB_GAME=FT_MYSQL_LOGINSERVER_DBNAME;
errlog($DB_GAME);
$con2 = mysql_connect(FT_MYSQL_COMMON_HOST,FT_MYSQL_COMMON_ROOT,FT_MYSQL_COMMON_PASS);
mysql_select_db(FT_MYSQL_LOGINSERVER_DBNAME, $con2) or die("mysql select db error". mysql_error()); 
mysql_query("SET NAMES utf8"); 

$sql2 = "insert into TESTERLIST(ACC) values('$userid')";
errlog($sql2);
$result2 = mysql_query($sql2,$con2) or die("Invalid query: " . mysql_error());
?>

成功增加测试玩家:<?php echo $userid ?>

