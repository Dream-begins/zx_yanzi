<?php 
ini_set('display_errors', '1');
error_reporting (E_ALL); // Report everything
set_time_limit(0); 
date_default_timezone_set("PRC");
ini_set('default_charset','utf-8');
include_once "common.php";
include_once "checklogin.php";
include_once "upgrade.php";
require_once "reader.php";
//include_once "newweb/nhead.php";
ini_set("memory_limit", "400M"); 
//php中读取excel表格
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
echo $filename;


$con = mysql_connect('117.103.235.92','root','hoolai@123');
mysql_select_db('ExtGameServer');
mysql_set_charset('utf8');

$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('utf-8');
$data->read("$filename");//读取上传到当前目录下的名叫$filename的文件

$time2 = microtime(true);
$cnt = 0;
echo "[TOTAL:".$data->sheets[0]['numRows']."]";

for($i=2;$i<=$data->sheets[0]['numRows'];$i++)
{
	$datasss['CDKEY'] = @$data->sheets[0]['cells'][$i][1];
	$datasss['TYPE'] = @$data->sheets[0]['cells'][$i][2];
	$datasss['GIFTID'] = @$data->sheets[0]['cells'][$i][3];
	$datasss['FLAG'] = @$data->sheets[0]['cells'][$i][4];
	$datasss['ITEM'] = @$data->sheets[0]['cells'][$i][5];
	$flag = dosqloption2($datasss,$con);
	if($flag) $cnt++;
}
$endTime = microtime(true);
?>
导入<?php echo getParam("zone");?>成功，共导入<?php echo $cnt; ?>

执行时间1:<?php echo "sss:".round(($endTime - $time2),4);  ?>,<?php echo round(($endTime - $startTime),4); 

function dosqloption2($datasss,$con)
{
	$sql = "INSERT INTO CDKEYDUIHUAN(`CDKEY`,`TYPE`,`GIFTID`,`FLAG`,`ITEM`) VALUES('".$datasss['CDKEY']."','".$datasss['TYPE']."','".$datasss['GIFTID']."','".$datasss['FLAG']."','".trim($datasss['ITEM'],'"')."')";
	$result = mysql_query($sql);

    $nu = mysql_affected_rows();
    return $nu;
}
