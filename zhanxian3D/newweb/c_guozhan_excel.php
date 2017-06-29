<?php 
header("Content-Type:text/html;charset=UTF-8");
ini_set('default_charset','utf-8');
date_default_timezone_set("PRC");
session_start();
error_reporting(-1);

$filename = isset($_POST['file2']) ? $_POST['file2'] : '';
$startTime=microtime(true);

if($filename == null)
{
    echo "请上传文件";
    return;
}
$temp = strrpos($filename,"\\");

$filename = substr($filename,$temp+1);
$filename = "../excel/".$filename;
$excel2array = excel2array($filename);

////////////////////////////////////////////zone_msg
$dbh = new PDO('mysql:host=10.104.222.134;dbname=fentiansj;port=3306;charset=utf8','root','hoolai@123');
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);      
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); 
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);              
$dbh->query("SET NAMES utf8");

$sql = "SELECT zone_id,zones,domians FROM zone_msg";
$stmt = $dbh->prepare($sql);
$stmt->execute();

$zone_msg_arr = $stmt->fetchAll();

$dbh = null;
$new_array = array();

foreach ($zone_msg_arr as $key => $value) 
{

    $new_array[$value['zone_id']] = $value;
}
unset($zone_msg_arr);
/////////////////////////////////////////////

$return_arr = array();
$values = '';
$zhanqu_arr = array();

$dbh2 = new PDO('mysql:host=117.103.235.92;dbname=CrossServer_yyb;port=3306;charset=utf8', 'root', 'hoolai@123');
$dbh2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh2->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$dbh2->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbh2->query("SET NAMES UTF8");
$dbh2->query('delete from DRAGONGROUP');

$sql = "INSERT INTO `DRAGONGROUP` VALUES";

foreach ($excel2array as $key => $value) 
{
    $zone = $value['1'];
    $zhanqu = $value['2'];
    $zhanqu_arr[] = $value['2'];
    //$zones_arr = explode(',',$new_array[$zone]['zones']);
    //$domians_arr = explode(',',$new_array[$zone]['domians']);
    //$con = array_combine($zones_arr, $domians_arr);

    //foreach ($con as $k => $v) 
    //{
        //if($k && $v) $values .="('{$k}','{$zhanqu}'),";
    //}
    $values .="('{$key}','{$value[2]}'),";
}
$sql .= trim($values,',');
$dbh2->query($sql);
$zhanqu_arr = array_unique($zhanqu_arr);

function excel2array($filename)
{
    $return_arr = array();
    require_once "../reader.php";
    $data = new Spreadsheet_Excel_Reader();
    $data->setOutputEncoding('utf-8');
    $data->read( $filename );

    $return_arr = $data->sheets['0']['cells'];
    return $return_arr;
}
