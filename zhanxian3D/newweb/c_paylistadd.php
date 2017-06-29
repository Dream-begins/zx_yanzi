<?php exit; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <title>安卓-支付单子补充功能</title>
    <link rel="stylesheet" type="text/css" href="jquery-easyui-1.4/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="jquery-easyui-1.4/themes/icon.css">
    <script type="text/javascript" src="jquery-easyui-1.4/jquery.min.js"></script>
    <script type="text/javascript" src="jquery-easyui-1.4/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="jquery-easyui-1.4/locale/easyui-lang-zh_CN.js"></script>
</head>
<body>
<script type="text/javascript">
function back1()
{
    history.back(-1);
}
</script>
<?php
session_start();
include "h_header.php";

//$dbh = new PDO('mysql:host=localhost;dbname=admin;port=3306;charset=utf8', 'root', '');
$dbh = new PDO('mysql:host=117.103.235.92;dbname=BILL;port=3306;charset=utf8', 'root', 'hoolai@123');//手游-安卓
//$dbh = new PDO('mysql:host=10.66.148.150;dbname=shouyou;port=3306;charset=utf8', 'root', 'hoolai@123');//手游-安卓
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbh->query("SET NAMES UTF8");

$ACC   = isset( $_POST['ACC'] ) ? $_POST['ACC'] : '';
$ZONE  = isset( $_POST['ZONE'] ) ? $_POST['ZONE'] : '';
$NAME  = isset( $_POST['NAME'] ) ? $_POST['NAME'] : '';
$OBJID = isset( $_POST['OBJID'] ) ? $_POST['OBJID'] : '';
$PRICE = isset( $_POST['PRICE'] ) ? ($_POST['PRICE']*100) : '';
$TIME  = isset( $_POST['TIME'] ) ? strtotime($_POST['TIME']) : '';
// $PF    = isset( $_POST['PF'] ) ? $_POST['PF'] : '';

echo '<a href="javascript:void(0)" onclick="back1()" id="sub1">回退继续添加</a><br>';

if(!$ACC || !$ZONE || !$NAME || !$PRICE) exit('参数不能为空');

$AMOUNT = $PRICE/10 ;
$BILLNO = '10-'.$TIME.'-'.rand(0,10);
$USRID = $ZONE*1000000+1;
$STATUS = 1;
$PF = 'ATX';

$sql = "INSERT INTO TRADE(`ACC`, `ZONE`, `USRID`, `USRLEVEL`, `OBJID`, `NUM`, `PRICE`, `AMOUNT`, `TIME`, `STATUS`, `ERRCODE`, `BILLNO`, `EXTRA`, `NAME`, `PF` ) 
        VALUES(:ACC, :ZONE, :USRID, '0', :OBJID, '1', :PRICE, :AMOUNT, :TIME, '1', '0', :BILLNO, '', :NAME, :PF)";

$stmt = $dbh->prepare( $sql );

$stmt->bindParam(':ACC', $ACC);
$stmt->bindParam(':ZONE', $ZONE);
$stmt->bindParam(':USRID', $USRID);
$stmt->bindParam(':OBJID', $OBJID);
$stmt->bindParam(':PRICE', $PRICE);
$stmt->bindParam(':AMOUNT', $AMOUNT);
$stmt->bindParam(':TIME', $TIME);
$stmt->bindParam(':BILLNO', $BILLNO);
$stmt->bindParam(':NAME', $NAME);
$stmt->bindParam(':PF', $PF);

$stmt->execute();

$result =  $stmt->rowCount();

echo $str = '成功添加 '. $result .'条';

$doadmin = $_SESSION['xwusername'];

$msg = $doadmin . ' - ' . json_encode( $_POST, 1 ) . ' - BILLNO['. $BILLNO .'] - USRID['.$USRID.']';
error_log(date("[Y-m-d H:i:s]")." :".$msg."\n", 3, "/var/log/phpadminlog/paylist_".date('Y_m_d').".log");

$dbh = null;
