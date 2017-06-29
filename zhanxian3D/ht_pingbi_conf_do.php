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

$dbh = new PDO('mysql:host='.FT_MYSQL_COMMON_HOST.';dbname='.FT_MYSQL_ADMIN_DBNAME.';port='.FT_MYSQL_COMMON_PORT.';charset=utf8', FT_MYSQL_COMMON_ROOT, FT_MYSQL_COMMON_PASS);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbh->query("SET NAMES UTF8");

$de_flag = $dbh->query('delete from ft_gapingbi_conf ');

if($de_flag) echo "活动配置屏蔽 已清空\r\n";

$mktime = date('Y-m-d H:i:s', time());

for($i=2;$i<=$data->sheets[0]['numRows'];$i++)
{

    $type = @$data->sheets[0]['cells'][$i][1];
    $bianhao = @$data->sheets[0]['cells'][$i][2];

    $sql = "INSERT INTO ft_gapingbi_conf values(:type, :bianhao)";

    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':type' , $type);
    $stmt->bindParam(':bianhao' , $bianhao);

    $flag = $stmt->execute();

    if($flag)$cnt++;
}
$endTime = microtime(true);

echo '重新插入'.$cnt .'条';

