<?php 
include "h_header.php";
include "m_zone_msg.php";
$zone_msg = new ZoneMsgInfo;

ini_set('display_errors', 1);
error_reporting(0);
$dbh = new PDO(PDO_TradeInfo_host,PDO_TradeInfo_root,PDO_TradeInfo_pass);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbh->query("SET NAMES utf8");

$zone   = isset($_GET['zone']) ? (int)$_GET['zone'] : '';
$from   = (isset($_GET['from']) && $_GET['from']) ? $_GET['from']  : date('Y-m-d', time());
$to     = (isset($_GET['to'])   && $_GET['to']  ) ? $_GET['to']    : date('Y-m-d', time());

$zones_info = $zone_msg->zone_id2infos($zone);
//var_dump($zones_info);exit;
$zone       = isset($zones_info['server_id']) ? $zones_info['server_id'] : '';
$from       = strtotime(date('Y-m-d', strtotime($from)));
$to         = strtotime(date('Y-m-d', strtotime($to)))+86400;
if(!$zone)
{
    $sql = "SELECT MAX(ZONE) AS maxzone FROM trade ";
    
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch();

    $zone = isset($zone['maxzone']) ? $zone['maxzone'] : '';
}

if(!$zone) exit('请填写正确的 合并后区');

//总收入
    $sql  = "SELECT SUM(AMOUNT/100) AS total FROM TRADE WHERE STATUS = 1 AND time >= :timefrom AND time < :timeto AND zone = :zone ";
    $sql  = "SELECT SUM(AMOUNT/100) AS total FROM TRADE WHERE STATUS = 1 AND time >= :timefrom AND time < :timeto AND zone = :zone ";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':timefrom', $from);
    $stmt->bindParam(':timeto', $to);
    $stmt->bindParam(':zone',$zone);
    $stmt->execute();
    $result = $stmt->fetch();
    $total = isset($result['total']) ? $result['total'] : '';

//付费人数

	$sql = "SELECT COUNT(DISTINCT ACC) AS paycnt FROM TRADE WHERE STATUS = 1 AND time >= :timefrom AND time < :timeto AND zone = :zone ";
	$stmt = $dbh->prepare($sql);
        $stmt->bindParam(':timefrom', $from);
 	$stmt->bindParam(':timeto', $to);
	$stmt->bindParam(':zone',$zone);

	$stmt->execute();
    $result = $stmt->fetch();
    $paycnt = isset($result['paycnt']) ? $result['paycnt'] : '';

$dbh = null;
$zone_msg_info = $zone_msg->server_id2infos($zone);

if(!$zone_msg_info) exit;
$dbh = new PDO('mysql:host='.$zone_msg_info['mysql_ip'].';dbname='.$zone_msg_info['mysql_dbName'].';port='.$zone_msg_info['mysql_port'].';charset=utf8', PDO_ZONE_ROOT, PDO_ZONE_PASS);

$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbh->query("SET NAMES utf8");

//创建账号数
    $sql  = "SELECT COUNT(*) AS accnumtotal FROM CHARBASE WHERE CREATETIME >= :timefrom and CREATETIME < :timeto ";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':timefrom', $from);
    $stmt->bindParam(':timeto', $to);
    $stmt->execute();
    $result = $stmt->fetch();
    $accnumtotal = isset($result['accnumtotal']) ? $result['accnumtotal'] : '';
//最近5分钟创建账号数
    $nowTime     = time();
    $fiveMBefore = $nowTime-300;

    $sql  = "SELECT COUNT(*) AS fiveaccnum FROM CHARBASE WHERE CREATETIME >= :fiveMBefore and CREATETIME < :timeto ";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':fiveMBefore', $fiveMBefore);
    $stmt->bindParam(':timeto', $to);
    $stmt->execute();
    $result = $stmt->fetch();
    $fiveaccnum = isset($result['fiveaccnum']) ? $result['fiveaccnum'] : '';

//总创建账号数
    $sql = "SELECT COUNT(*) AS accalltotal FROM CHARBASE";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch();
    $accalltotal = isset($result['accalltotal']) ? $result['accalltotal'] : '';

//蓝钻总创建账号数
    $sql = "SELECT COUNT(*) AS lzaccalltotal FROM CHARBASE WHERE ACCPRIV = '31' ";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch();
    $lzaccalltotal = isset($result['lzaccalltotal']) ? $result['lzaccalltotal'] : '';

//除去新增DAU
    $sql = "SELECT COUNT(*) AS dau FROM CHARBASE WHERE LASTACTIVEDATE > DATE( FROM_UNIXTIME( :timefrom )  ) AND CREATETIME < :timefrom2  ";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':timefrom', $from);
    $stmt->bindParam(':timefrom2', $from);
    $stmt->execute();
    $result = $stmt->fetch();
    $dau = isset($result['dau']) ? $result['dau'] : '';


// DAU
    $sql = "SELECT COUNT(*) AS dau FROM CHARBASE WHERE LASTACTIVEDATE > DATE( FROM_UNIXTIME( :timefrom )  ) ";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':timefrom', $from);
    $stmt->execute();
    $result = $stmt->fetch();
    $dau2 = isset($result['dau']) ? $result['dau'] : '';

//分类型创建账号数
    $sql = "SELECT COUNT(*) AS nu, ACCPRIV FROM CHARBASE WHERE CREATETIME > :timefrom AND  CREATETIME < :timeto GROUP BY ACCPRIV ";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':timefrom', $from);
    $stmt->bindParam(':timeto', $to);
    $stmt->execute();
    $typecounts = $stmt->fetchAll();

//V3分IP创建账号数
    $sql = "SELECT COUNT(*) AS ct,createip FROM CHARBASE WHERE ACCPRIV = '13' GROUP BY createip ORDER BY ct DESC LIMIT 10 ";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $ipcounts = $stmt->fetchAll();

//开服时间
    $sql = "SELECT FROM_UNIXTIME( MIN(CREATETIME), '%Y-%m-%d %H:%i:%s' ) AS createtime FROM CHARBASE WHERE CREATETIME > 0 ";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch();
    $createtime = isset($result['createtime']) ? $result['createtime'] : '';

//VIP账号数
    $sql = "SELECT COUNT(*) AS nu,VIP FROM CHARBASE WHERE CREATETIME < :timeto GROUP BY VIP ";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':timeto', $to);
    $stmt->execute();
    $vipcounts = $stmt->fetchAll();

//蓝钻总VIP数
    $sql = "SELECT COUNT(*) AS lzvip FROM CHARBASE WHERE ACCPRIV = '31' AND VIP > '0' ";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch();
    $lzvip = isset($result['lzvip']) ? $result['lzvip'] : '';

//V3总VIP数
    $sql = "SELECT COUNT(*) AS v3vip FROM CHARBASE WHERE ACCPRIV = '13' AND VIP > 0";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch();
    $v3vip = isset($result['v3vip']) ? $result['v3vip'] : '';

//留存
    $createtime0 = strtotime(date('Y-m-d', strtotime($createtime)));
    $createtime1 = strtotime(date('Y-m-d', strtotime($createtime))) + 86400;
    $today0      = strtotime(date('Y-m-d',time()));

    $sql = "SELECT COUNT(*) AS remain FROM CHARBASE WHERE CREATETIME < :createtime1 AND CREATETIME > :createtime0 AND LASTACTIVEDATE > FROM_UNIXTIME(:today0) ";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':createtime0', $createtime0);
    $stmt->bindParam(':createtime1', $createtime1);
    $stmt->bindParam(':today0', $today0);
    $stmt->execute();
    $result = $stmt->fetch();
    $remain = isset($result['remain']) ? $result['remain'] : '';

//开服当日创角数
    $sql = "SELECT COUNT(*) AS remaintotal FROM CHARBASE WHERE CREATETIME < :createtime1 AND CREATETIME > :createtime0";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':createtime0', $createtime0);
    $stmt->bindParam(':createtime1', $createtime1);
    $stmt->execute();
    $result = $stmt->fetch();
    $remaintotal = isset($result['remaintotal']) ? $result['remaintotal'] : '';    

//蓝钻留存
    $sql = "SELECT COUNT(*) AS lzremain FROM CHARBASE WHERE ACCPRIV = '31' AND CREATETIME < :createtime1 AND CREATETIME > :createtime0 AND LASTACTIVEDATE > FROM_UNIXTIME(:today0) ";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':createtime0', $createtime0);
    $stmt->bindParam(':createtime1', $createtime1);
    $stmt->bindParam(':today0', $today0);
    $stmt->execute();
    $result = $stmt->fetch();
    $lzremain = isset($result['lzremain']) ? $result['lzremain'] : '';

//开服当天蓝钻总数
    $sql = "SELECT COUNT(*) AS lzremaintotal FROM CHARBASE WHERE ACCPRIV = '31' AND CREATETIME < :createtime1 AND CREATETIME > :createtime0 ";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':createtime0', $createtime0);
    $stmt->bindParam(':createtime1', $createtime1);
    $stmt->execute();
    $result = $stmt->fetch();
    $lzremaintotal = isset($result['lzremaintotal']) ? $result['lzremaintotal'] : '';

//开服天数
    $d1 = new DateTime();
    $d2 = new DateTime($createtime);
    $interval = $d1->diff($d2)->days;

$dbh = null;
?>
    <table border="0" cellpadding="3" width="600">
        <tr>
            <td class='e'>服务器:</td>
            <td class='v'><?php echo isset($_GET['zone']) ? $_GET['zone'] : '' ?></td>
        </tr>
        <tr>
            <td class='e'>开服时间:</td>
            <td class='v'><?php echo $createtime;?></td>
        </tr>
        <tr>
            <td class='e'>开服天数:</td>
            <td class='v'><?php echo $interval;?></td>
        </tr>
        <tr>
            <td class='e'>总收入:</td>
            <td class='v'><?php echo $total;?></td>
        </tr>
        <tr>
            <td class='e'>付费人数:</td>
            <td class='v'><?php echo $paycnt;?></td>
        </tr>
        <tr>
            <td class='e'>创建账号数:</td>
            <td class='v'><?php echo $accnumtotal;?></td>
        </tr>
        <tr>
            <td class='e'>总创建账号数:</td>
            <td class='v'><?php echo $accalltotal;?></td>
        </tr>
        <tr>
            <td class='e'>蓝钻总创建账号数:</td>
            <td class='v'><?php echo $lzaccalltotal;?></td>
        </tr>
        <tr>
            <td class='e'>蓝钻总VIP数:</td>
            <td class='v'><?php echo $lzvip;?></td>
        </tr>
        <tr>
            <td class='e'>V3总VIP数:</td>
            <td class='v'><?php echo $v3vip;?></td>
        </tr>
        <tr>
            <td class='e'>留存:</td>
            <td class='v'><?php echo $remain , ',' , number_format( $remain * 100 / $remaintotal, 2) , '%' ;?></td>
        </tr>
        <tr>
            <td class='e'>蓝钻留存:</td>
            <td class='v'><?php echo $lzremain , ',' , number_format( $lzremain * 100 / $lzremaintotal, 2) , '%' ;?></td>
        </tr>
        <tr>
            <td class='e'>V3分IP创建账号数:</td>
            <td class='v'><?php foreach ($ipcounts as $key => $value) { echo $value['createip'] , ':' , $value['ct'] , '<br/>';} ?></td>
        </tr>
        <tr>
            <td class='e'>分类型创建账号数:</td>
            <td class='v'><?php foreach ($typecounts as $key => $value) { echo $value['ACCPRIV'] , ':' , $value['nu'] , '<br/>';} ?></td>
        </tr>
        <tr>
            <td class='e'>VIP账号数:</td>
            <td class='v'><?php foreach ($vipcounts as $key => $value) { echo $value['VIP'] , ':' , $value['nu'] , '<br/>';} ?></td>
        </tr>
        <tr>
            <td class='e'>DAU(除新增):</td>
            <td class='v'><?php echo $dau;?></td>
        </tr>
        <tr>
            <td class='e'>DAU:</td>
            <td class='v'><?php echo $dau2;?></td>
        </tr>
        <tr>
            <td class='e'>最近5分钟创建账号数:</td>
            <td class='v'><?php echo $fiveaccnum;?></td>
        </tr>
        <tr>
            <td class='e'>当前时间:</td>
            <td class='v'><?php echo date('Y-m-d H:i:s', time());?></td>
        </tr>
    </table>
</body>
</html>
