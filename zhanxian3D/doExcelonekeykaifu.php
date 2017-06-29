<?php 
ini_set('display_errors', '1');
error_reporting (E_ALL);
set_time_limit(0); 

date_default_timezone_set("PRC");
ini_set('default_charset','utf-8');
include_once "common.php";
include_once "checklogin.php";
include_once "upgrade.php";
require_once "reader.php";
include_once "newweb/h_header.php";

$admin_name = isset($_SESSION['xwusername']) ? $_SESSION['xwusername'] : '';
$filename = getParam("file2",null);
$startTime=microtime(true);
if($filename == null)
{
    echo "请上传文件";
    return;
}
$temp = strrpos($filename,"\\");

$filename = substr($filename,$temp+1);
$filename = "excel/".$filename;

$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('utf-8');
$data->read("$filename");

$time2 = microtime(true);
$cnt = 0;

$dbh = new PDO('mysql:host='.FT_MYSQL_COMMON_HOST.';dbname='.FT_MYSQL_SHOUYOU_DBNAME.';port='.FT_MYSQL_COMMON_PORT.';charset=utf8', FT_MYSQL_COMMON_ROOT, FT_MYSQL_COMMON_PASS);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbh->query("SET NAMES UTF8");

$de_flag = $dbh->query('delete from sy_onekey_confs ');

if($de_flag) echo "一键开服配置 已清空\r\n";

$mktime = date('Y-m-d H:i:s', time());

for($i=2;$i<=$data->sheets[0]['numRows'];$i++)
{

    $TYPE = @$data->sheets[0]['cells'][$i][1];//活动类型
    $ACTIVITYID = @$data->sheets[0]['cells'][$i][2];//活动编号
    $TIMESTART = @$data->sheets[0]['cells'][$i][3];    //开启
    $TIMEEND = @$data->sheets[0]['cells'][$i][4];    //结束
    $TIMEEXPIRE = @$data->sheets[0]['cells'][$i][5];    //过期
    $ICONID = @$data->sheets[0]['cells'][$i][6];    //活动图标
    $TABID = @$data->sheets[0]['cells'][$i][7];    //标签图标
    $TABINFO = @$data->sheets[0]['cells'][$i][8]; //标签标题
    $TABTYPE = @$data->sheets[0]['cells'][$i][9]; //标签排序位置
    $PICID = @$data->sheets[0]['cells'][$i][10];//描述背景图
    $PICINFO = @$data->sheets[0]['cells'][$i][11];//活动描述
    $PLATID = @$data->sheets[0]['cells'][$i][12];//开放平台

    $sql = "INSERT INTO sy_onekey_confs(TYPE,ACTIVITYID,TIMESTART,TIMEEND,TIMEEXPIRE,ICONID,TABID,TABINFO,TABTYPE,PICID,PICINFO,PLATID,mktime,doadmin) 
            VALUES(:TYPE, :ACTIVITYID, :TIMESTART, :TIMEEND, :TIMEEXPIRE, :ICONID, :TABID, :TABINFO, :TABTYPE, :PICID, :PICINFO, :PLATID, :mktime, :doadmin) ";

    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':TYPE' , $TYPE);
    $stmt->bindParam(':ACTIVITYID' , $ACTIVITYID);
    $stmt->bindParam(':TIMESTART' , $TIMESTART);
    $stmt->bindParam(':TIMEEND' , $TIMEEND);
    $stmt->bindParam(':TIMEEXPIRE' , $TIMEEXPIRE);
    $stmt->bindParam(':ICONID' , $ICONID);
    $stmt->bindParam(':TABID' , $TABID);
    $stmt->bindParam(':TABINFO' , $TABINFO);
    $stmt->bindParam(':TABTYPE' , $TABTYPE);
    $stmt->bindParam(':PICID' , $PICID);
    $stmt->bindParam(':PICINFO' , $PICINFO);
    $stmt->bindParam(':PLATID' , $PLATID);
    $stmt->bindParam(':mktime' , $mktime);
    $stmt->bindParam(':doadmin' , $admin_name);

    $flag = $stmt->execute();

    if($flag)$cnt++;
}
$endTime = microtime(true);

echo '重新插入'.$cnt .'条';

