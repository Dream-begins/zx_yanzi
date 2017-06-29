<?php
/**
 * Created by PhpStorm.
 * User: chenyunhe
 * Date: 2016/12/2
 * Time: 14:58
 */
header("Content-Type:text/html;charset=UTF-8");
ini_set('default_charset','utf-8');
date_default_timezone_set("PRC");
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('memory_limit', '1G');
set_time_limit(0);
include "h_header.php";
$dbh = new PDO(PDO_ZoneMsgInfo_host,PDO_ZoneMsgInfo_root,PDO_ZoneMsgInfo_pass);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);      //设置异常模式为抛出异常
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); //为语句设置默认获取方式
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);              //false关闭本地预处理 使用mysql预处理
$dbh->query("SET NAMES utf8");


$tradedb = new PDO(PDO_TradeInfo_host,PDO_TradeInfo_root,PDO_TradeInfo_pass);
$tradedb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$tradedb->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$tradedb->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$tradedb->query("SET NAMES utf8");

$sql = "SELECT mysql_ip,mysql_dbName,mysql_port FROM zone_msg ORDER BY id";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$serarr = $stmt->fetchAll();
$file = file("user.csv");
$r = array();
foreach($file as $k=>$v){
    $varr = explode(',',$v);
    $openid=trim($varr['0']);
    foreach($serarr as $val){
        $gamedb = new PDO('mysql:host='.$val['mysql_ip'].';dbname='.$val['mysql_dbName'].';port='.$val['mysql_port'].';charset=utf8', PDO_ZONE_ROOT, PDO_ZONE_PASS);
        $gamedb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);      //设置异常模式为抛出异常
        $gamedb->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); //为语句设置默认获取方式
        $gamedb->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);              //false关闭本地预处理 使用mysql预处理
        $gamedb->query("SET NAMES utf8");

        $sql = "SELECT  ACCNAME,NAME, ZONE, LEVEL, FROM_UNIXTIME(CREATETIME) AS CREATETIME  FROM CHARBASE WHERE ACCNAME='HOOLAI-{$openid}'";
        $gt = $gamedb->prepare($sql);
        $gt->execute();
        $row= $gt->fetch();
        if(!empty($row)){
            $trsql = "SELECT SUM(`AMOUNT`/100) AS TOTAL FROM TRADE WHERE ZONE='{$row['ZONE']}' AND ACC='{$row['ACCNAME']}'";
            $tramt = $tradedb->prepare($trsql);
            $tramt->execute();
            $trow = $tramt->fetch();
            $row['CHARGE']= (int)$trow['TOTAL'];
            $row['phone']=$varr[1];
            $r[] = $row;
        }else{
            continue;
        }
    }
}


array_unshift($r,array('ACCNAME','NAME','ZONE','LEVEL','CREATETIME','CHARGE','phone'));
moutputCSV($r,'用户信息调取');

function moutputCSV($data,$name)
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






