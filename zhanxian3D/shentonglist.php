<?php
header("Content-Type:text/html;charset=UTF-8");
ini_set('default_charset','utf-8');
date_default_timezone_set("PRC");
ini_set('display_errors', 1);
error_reporting(-1);

$dbh = new PDO('mysql:host=10.104.222.134;dbname=fentiansj;port=3306;"', 'root', 'hoolai@123');
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbh->query("SET NAMES UTF8");

$dbhbill = new PDO('mysql:host=117.103.235.92;dbname=BILL;port=3306;"', 'root', 'hoolai@123');
$dbhbill->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbhbill->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$dbhbill->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbhbill->query("SET NAMES UTF8");


$sql = "SELECT mysql_ip,mysql_port,mysql_dbName,zone_id,zones FROM zone_msg WHERE zone_id < 1000 ";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$zone_msgs = $stmt->fetchAll();
$int = 0;

$sql = "SELECT zone_id, domians FROM zone_msg";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$zone_msg_arr = $stmt->fetchAll();

$z_arr = array();

foreach ($zone_msg_arr as $key => $value) {
    $domians_arr = explode(',', trim( $value['domians'], ',' ) );

    foreach ($domians_arr as $k => $v) {
        $z_arr[$v] = $value['zone_id'];
    }
}
$return_arr = array();


foreach ($zone_msgs as $key => $value)
{
    $dbh = new PDO("mysql:host=".$value['mysql_ip'].";dbname=".$value['mysql_dbName'].";port=".$value['mysql_port'].";", "root", "hoolai@123");
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->query("SET NAMES UTF8");

    $sql = "SELECT ZHENQI, ACCNAME, ZONE, NAME FROM CHARBASE WHERE ZHENQI >= '".$int."'  ORDER BY ZHENQI DESC limit 100 ";

    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();

    foreach($result as $key => $value){
        $return_arr[$value['ZONE'].'-'.$value['NAME'].'-'.$value['ACCNAME']] = $value['ZHENQI'];
    }
    $int =  end($result);
    $int = (int)$int['ZHENQI'];
}
arsort($return_arr);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8">
<title></title>
<style type="text/css">
body {background-color: #ffffff; color: #000000;}
body, td, th, h1, h2 {font-family: sans-serif;}
pre {margin: 0px; font-family: monospace;}
a:link {color: #000099; text-decoration: none; background-color: #ffffff;}
a:hover {text-decoration: underline;}
table {border-collapse: collapse;}
.center {text-align: center;}
.center table { margin-left: auto; margin-right: auto; text-align: left;}
.center th { text-align: center !important; }
td, th { border: 1px solid #000000; font-size: 75%; vertical-align: baseline;}
h1 {font-size: 150%;}
h2 {font-size: 125%;}
.p {text-align: left;}
.e {background-color: #ccccff; font-weight: bold; color: #000000;}
.h {background-color: #9999cc; font-weight: bold; color: #000000;}
.v {background-color: #cccccc; color: #000000;}
.vr {background-color: #cccccc; text-align: right; color: #000000;}
img {float: right; border: 0px;}
hr {width: 600px; background-color: #cccccc; border: 0px; height: 1px; color: #000000;}
</style>
</head>
<body>
<div class="center">
<?php
$return_arr = array_slice($return_arr,0,100);
echo '<table border="0" cellpadding="3" width="600">';
echo '<tr class="h" ><th>排名</th><th>合并后区</th><th>openid</th><th>角色名</th><th>神通值</th><th>7日内充值rmb(元)</th><th>本月充值rmb(元)</th></tr>';



$i = 1;
$TIME7day = strtotime(date('Y-m-d', time()))-86400*7;
$TIME1M   = strtotime(date('Y-m', time()));
foreach ($return_arr as $key => $value)
{
    $flag = explode('-', $key );

    $sql = "SELECT SUM(NUM*PRICE) AS SUM_YB, SUM(AMOUNT+EXTRA*10) AS SUM_AMOUNT, FROM_UNIXTIME(TIME) AS YMD, PF, ACC, NAME, ZONE FROM TRADE WHERE STATUS = 1 AND PRICE >=50 AND ACC=:ACC AND ZONE=:ZONE AND TIME >= :TIME GROUP BY ZONE,ACC LIMIT 1";

    $ACC = $flag['2'];
    $ZONE = $flag['0'];

    $stmt = $dbhbill->prepare($sql);
    $stmt->bindParam(':ACC', $ACC);
    $stmt->bindParam(':ZONE', $ZONE);
    $stmt->bindParam(':TIME', $TIME7day);

    $stmt->execute();
    $pay_7day = $stmt->fetch();

    $stmt = $dbhbill->prepare($sql);
        $stmt->bindParam(':ACC', $ACC);
        $stmt->bindParam(':ZONE', $ZONE);
            $stmt->bindParam(':TIME', $TIME1M);

            $stmt->execute();
                $pay_1m = $stmt->fetch();

    echo "<tr><td class='v'>".$i."</td><td class='v'>".$z_arr[$flag['0']]."</td><td class='v'>".$flag['1']."</td><td class='v'>".$flag['2']."</td><td class='v'>{$value}</td><td class='v'>".($pay_7day['SUM_AMOUNT']/100)."</td><td class='v'>".($pay_1m['SUM_AMOUNT']/100)."</td></tr>";
    $i++;

}

echo '</table><br />';

