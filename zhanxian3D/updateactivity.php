<?php
 
ini_set('display_errors', 'on');
error_reporting (E_ALL); // Report everything

date_default_timezone_set("PRC");
ini_set('default_charset','utf-8');
include_once "common.php";
include_once "checklogin.php";
include_once "upgrade.php";
include_once "db2new.php";
//查询所有区
$dbh = new PDO("mysql:host=10.104.222.134;dbname=fentiansj;port=3306;charset=utf8", 'root', 'hoolai@123');
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbh->query("SET NAMES UTF8");

$sql = "SELECT zone_id, zones, domians,mysql_ip,mysql_port,mysql_dbName FROM zone_msg ";
$stmt = $dbh->prepare($sql);
//$stmt->bindParam(':ctime', $ctime);
$stmt->execute();
$result = $stmt->fetchAll();
//print_r($result);

echo '区总数'.count($result).'<br/>';
//循环
foreach ( $result as $val){
    $ip = $val['mysql_ip'];
    $mysql_dbName = $val['mysql_dbName'];
    $port = $val['mysql_port'];
    $dbh = new PDO("mysql:host=$ip;dbname=$mysql_dbName;port=$port;charset=utf8", 'root', 'hoolai@123');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->query("SET NAMES UTF8");

    $sql = "SELECT ID, TYPE, ACTIVITYID,  concat(TYPE,'-',ACTIVITYID) as flag1, FROM_UNIXTIME(TIMESTART) as ts, FROM_UNIXTIME(TIMEEND) as te, FROM_UNIXTIME(TIMEEXPIRE) as tp, STATUS ,ICONID, TABID,TABINFO,TABTYPE,PICID,PICINFO,PLATID from GAMEACTIVITY WHERE TYPE=4 AND ACTIVITYID=15 AND ICONID='leijidenglu'AND STATUS=1 ";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $res =$stmt->fetch();
    if(!empty($res)){
        $total +=1 ;
        $res[0]['zone'] = $val['zone_id'];
        print_r($res);
    }
//延期10个月
//    $sql = "update GAMEACTIVITY set TIMEEND=TIMEEND+25920000,TIMEEXPIRE=TIMEEXPIRE+25920000 where TYPE=4 AND ACTIVITYID=15 AND ICONID='leijidenglu'AND STATUS=1";
//
//    $res =$dbh->exec($sql);
//    if(!empty($res)){
//        $total +=1 ;
//    }
}
echo "<br/>符合条件的区".$total;
//echo "<br/>更新区总数".$total;
//更新时间的sql,这里按取原数值增加60天时间戳
//$sql = "update GAMEACTIVITY set TIMEEND=TIMEEND+5184000,TIMEEXPIRE=TIMEEXPIRE+5184000 where TYPE=4 AND ACTIVITYID=15 AND ICONID='leijidenglu'AND STATUS = 1";





















