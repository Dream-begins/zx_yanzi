<?php
include 'h_header.php';
include 'h_define.php';
include "m_zone_msg.php";

$action = isset($_GET['action']) ? $_GET['action'] : NULL;
error_reporting(-1);

if($action == "list")
{
    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $rows = isset($_POST['rows']) ? (int)$_POST['rows'] : 20;
    $zone = isset($_POST['zone']) ? (int)$_POST['zone'] : 0;
    $acc  = isset($_POST['acc'])  ? $_POST['acc'] : 0;
    $usrname  = isset($_POST['usrname'])  ? $_POST['usrname'] : 0;


    $limit = ' LIMIT ' . ($page-1)*$rows . ' , ' . $rows;
    $orderby = ' ORDER BY id DESC';

    $dbh = new PDO('mysql:host='.FT_MYSQL_COMMON_HOST.';dbname='.FT_MYSQL_SHOUYOU_DBNAME.';port='.FT_MYSQL_COMMON_PORT.';charset=utf8', FT_MYSQL_COMMON_ROOT, FT_MYSQL_COMMON_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->query("SET NAMES UTF8");

    $sql = 'SELECT * FROM suggest WHERE 1 ';

    if($zone) $sql .= ' AND serverId = :zone ';
    if($acc)  $sql .= ' AND openid = :acc ';

    $sql .= $orderby . $limit;
    $stmt = $dbh->prepare($sql);

    if($zone) $stmt->bindParam(':zone', $zone);
    if($zone) $stmt->bindParam(':acc', $acc);

    $stmt->execute();
    $result = $stmt->fetchAll();

    $sql = 'SELECT COUNT(1) AS nu FROM suggest';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $total = $stmt->fetch();
    $total = isset($total['nu']) ? $total['nu'] : 0;

    $datas['total'] = $total;
    $datas['rows'] = $result;

    exit(json_encode($datas));
}

if($action == 'reply')
{
    $server = isset($_POST['server']) ? (int)$_POST['server'] : 0;
    $openid = isset($_POST['openid']) ? $_POST['openid'] : 0;
    $title = isset($_POST['title']) ? $_POST['title'] : 0;
    $content = isset($_POST['content']) ? $_POST['content'] : 0;
    $ID = isset($_POST['ID']) ? (int)$_POST['ID'] : 0;

    $zone_msg = new ZoneMsgInfo;
    $zone_msg_info = $zone_msg->domians2infos($server);
    if(!$zone_msg_info) exit('no zone error');
    if(!isset($zone_msg_info['mysql_ip'])) exit('no mysql_ip error');

    $dbh = new PDO('mysql:host='.$zone_msg_info['mysql_ip'].';dbname='.$zone_msg_info['mysql_dbName'].';port='.$zone_msg_info['mysql_port'].';charset=utf8', FT_MYSQL_COMMON_ROOT, FT_MYSQL_COMMON_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->query("SET NAMES utf8");

    $sql = 'SELECT NAME, CHARID FROM CHARBASE WHERE ACCNAME = :openid AND ZONE = :server ';
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':openid', $openid);
    $stmt->bindParam(':server', $server);
    $stmt->execute();
    $result = $stmt->fetch();
    $username = $result['NAME'];
    $charid   = $result['CHARID'];

    if(!$username || !$charid) exit('此openid无法找到对应角色 请找程序核实');

    $toID     = $server * 1000000 + $charID;
    $tempFrom = time();
    $tempTo   = time() + 86400*3;

    $sql = "INSERT INTO `MAIL` (STATE, FROMNAME, TOZONE, TONAME, TITLE, TYPE, TEXT, CREATETIME, DELTIME, TOID)
            VALUES(1, '系统', :server, :userName, :title, 3, :content, :tempFrom, :tempTo, :toID)";

    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':server', $server);
    $stmt->bindParam(':userName', $username);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':tempFrom', $tempFrom);
    $stmt->bindParam(':tempTo', $tempTo);
    $stmt->bindParam(':toID', $toID);
    $stmt->execute();

    $dbh = new PDO('mysql:host='.FT_MYSQL_COMMON_HOST.';dbname='.FT_MYSQL_SHOUYOU_DBNAME.';port='.FT_MYSQL_COMMON_PORT.';charset=utf8', FT_MYSQL_COMMON_ROOT, FT_MYSQL_COMMON_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->query("SET NAMES UTF8");

    $sql = 'UPDATE suggest SET isReply = 1 AND hftime = :tempFrom AND htfontent = :content WHERE ID = :ID';
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':tempFrom', $tempFrom);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':ID', $ID);
    $stmt->execute();
}elseif($action=='export'){

    $dbh = new PDO('mysql:host='.FT_MYSQL_COMMON_HOST.';dbname='.FT_MYSQL_SHOUYOU_DBNAME.';port='.FT_MYSQL_COMMON_PORT.';charset=utf8', FT_MYSQL_COMMON_ROOT, FT_MYSQL_COMMON_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->query("SET NAMES UTF8");

    $sql = 'SELECT ID,openid,addDate,ip,serverId,content FROM suggest';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();

    $headers=array("ID","玩家openid","添加日期","玩家ip","服务器ID","建议内容");
    array_unshift($result,$headers);
    outputCSV($result,'玩家建议反馈');

}
