<?php
ini_set('display_errors', 1); 
error_reporting(-1);
date_default_timezone_set("PRC");
ini_set('default_charset','utf-8');
include_once "checklogin.php";
ini_set("memory_limit", "500M");

$putcsv = isset($_GET['putcsv'])? $_GET['putcsv'] : '';

if($putcsv)
{
    
    $ymd = (isset($_GET['ymd']) && $_GET['ymd'])  ? $_GET['ymd'] : date('Y-m-d',time());
    $ymd2 = date('Y_m_d',strtotime($ymd));
    $table = 'int_error_inside_'.$ymd2;

    $dbh = new PDO('mysql:host=10.66.125.231;dbname=shouyou', 'root', 'hoolai@123');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //设置异常模式为抛出异常
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); //为语句设置默认获取方式
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);              //false关闭本地预处理 使用mysql预处理
    $dbh->query("SET NAMES UTF8");

     $sql = "SELECT id,msg,tp,appVer,device,userid,mtime,ip from {$table} WHERE appVer='1.0.0' AND msg not like '%SYS_ERROR%' ORDER BY id desc limit 2000";    
    //$sql = "SELECT id,msg,tp,appVer,device,userid,mtime,ip from {$table} ORDER BY id desc limit 1000";
    $stmt = $dbh->prepare($sql);    
    $stmt->execute();
    $result = $stmt->fetchAll();
    $dbh = null; //关闭连接


    array_unshift($result,array('id','msg','tp','appVer','device','userid','mtime','ip'));
    #$result[] = array('合并后区','合并后服','合并前区','合并前服');
//    $result2 = array_reverse($result);
//    $result = format2($result2);

    outputCSV($result,$table);
}

function outputCSV($data,$name)
{
    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename={$name}.csv");
    header("Pragma: no-cache");
    header("Expires: 0");

    $outputBuffer = fopen("php://output", 'w');
    foreach($data as $val)
    {
        foreach ($val as $key => $val2)
        {
            $val[$key] = iconv('utf-8', 'gbk', $val2);// CSV的Excel支持GBK编码，一定要转换，否则乱码
        }
        fputcsv($outputBuffer, $val);
    }
    fclose($outputBuffer);
}

//function outputCSV($data)
//{
//    $outputBuffer = fopen("php://output", 'w');
//    foreach($data as $val) {
//    foreach ($val as $key => $val2) {
//     $val[$key] = iconv('utf-8', 'gbk', $val2);// CSV的Excel支持GBK编码，一定要转换，否则乱码
//     }
//        fputcsv($outputBuffer, $val,'	');
//    }
//    fclose($outputBuffer);
//}

function format2($var)
{
    if (is_array($var))
    {
        return array_map('format2', $var);
    }
    else
    {
        return str_replace(',', '-', $var);
    }
}

