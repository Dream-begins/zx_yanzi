<?php
include "h_header.php";

$action = isset($_GET['action']) ? $_GET['action'] : NULL;


$zonename_arr = get_zonename();

if($action == "list")
{
    $page       = isset($_POST['page'])      ? (int)$_POST['page']                  : 1;
    $rows       = isset($_POST['rows'])      ? (int)$_POST['rows']                  : 20;
    $sort       = isset($_POST['sort'])      ?  htmlspecialchars( $_POST['sort'] )  : 'zone_id';
    $order      = isset($_POST['order'])     ?  htmlspecialchars( $_POST['order'] ) : 'desc';
    $zone_id    = isset($_POST['zone_id'])   ? (int)$_POST['zone_id']               : '';
    $zone    = isset($_POST['zone'])   ? (int)$_POST['zone']               : '';
    $qudao      = (!empty($_POST['qudao'])) ?  $_POST['qudao'] : '';

    if($page <= 0) $page = 1;
    $result = array();

    include "m_zone_msg.php";
    $zone_msg = new ZoneMsgInfo;
    if($zone_id)
    {
        $result['total'] = '1';
        $row = $zone_msg->zone_id2infos( $zone_id );
        $row['zone_name'] = $zonename_arr[$row['zone_id']];
        $result['rows'] = array($row);
        exit( json_encode($result) );
    }

    if($zone)
    {
        $result['total'] = '1';
        $row = $zone_msg->zones2infos( $zone );
        $row['zone_name'] = $zonename_arr[$row['zone_id']];
        $result['rows'] = array($row);
        exit( json_encode($result) );
    }

    if(!$qudao){
        $orderby = ' ORDER BY ' . $sort . '*1  ' .$order;
        $limit = ' LIMIT ' . ($page-1)*$rows . ',' . $rows;

        $result['total'] = $zone_msg->zone_msg_total();
        $result['rows'] = $zone_msg->zone_msg_list( $orderby, $limit );
        foreach ($result['rows'] as $key => $value)
        {
            $result['rows'][$key]['zone_name'] = @$zonename_arr[$value['zone_id']];
        }
        exit( json_encode($result) );
    }else{
        $orderby = ' ORDER BY ' . $sort . '*1  ' .$order;
        $limit = ' LIMIT ' . ($page-1)*$rows . ',' . $rows;

        $qudaoarr = explode('-',$_POST['qudao']);
        $where = " WHERE zone_id BETWEEN {$qudaoarr[0]} AND {$qudaoarr[1]}";

        $totalsql = "SELECT COUNT(*) AS TOTAL FROM zone_msg $where";
        $stmts = $zone_msg->dbh->prepare($totalsql);
        $stmts->execute();
        $row = $stmts->fetch();

        $result['total'] = $row['TOTAL'];

        $sql = "SELECT * FROM zone_msg  $where $orderby  $limit";

        $stmts = $zone_msg->dbh->prepare($sql);
        $stmts->execute();

        $result['rows'] = $stmts->fetchAll();
        foreach ($result['rows'] as $key => $value)
        {
            $result['rows'][$key]['zone_name'] = @$zonename_arr[$value['zone_id']];
        }
        exit( json_encode($result) );
    }

}

function get_zonename()
{
    $r = array();
	$dbh = new PDO(PDO_shouyou_host, PDO_shouyou_root, PDO_shouyou_pass);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$dbh->query("SET NAMES utf8");

	$stmt = $dbh->prepare("SELECT zid,zname FROM sy_zones");
	$stmt->execute();
	$result = $stmt->fetchAll();

	foreach ($result as $key => $value)
	{
        $r[$value['zid']] = $value['zname'];
	}

    $dbh = new PDO(PDO_shouyou_ly_host, PDO_shouyou_ly_root, PDO_shouyou_ly_pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->query("SET NAMES utf8");

    $stmt = $dbh->prepare("SELECT zid,zname FROM sy_zones");
    $stmt->execute();
    $result = $stmt->fetchAll();

    foreach ($result as $key => $value)
    {
        $r[$value['zid']] = $value['zname'];
    }

    $dbh = new PDO(PDO_shouyou_ios_host, PDO_shouyou_ios_root, PDO_shouyou_ios_pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->query("SET NAMES utf8");

    $stmt = $dbh->prepare("SELECT zid,zname FROM sy_zones");
    $stmt->execute();
    $result = $stmt->fetchAll();

    foreach ($result as $key => $value)
    {
        $r[$value['zid']] = $value['zname'];
    }
    
    return $r;
}
/*
function get_zonename()
{
	$dbh = new PDO(PDO_shouyou_host, PDO_shouyou_root, PDO_shouyou_pass);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$dbh->query("SET NAMES utf8");

	$stmt = $dbh->prepare("SELECT zid,zname FROM sy_zones");
	$stmt->execute();
	$result = $stmt->fetchAll();

	$return_arr = array();

	foreach ($result as $key => $value)
	{
		$return_arr[$value['zid']] = $value['zname'];
	}

	return $return_arr;
}
*/
