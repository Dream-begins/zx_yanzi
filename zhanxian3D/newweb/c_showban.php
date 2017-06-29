<?php
define('WITHOUT_AUTH',1);
include "h_header.php";

$action = isset($_GET['action']) ? $_GET['action'] : NULL;

if($action == "list")
{
    $page   = isset($_POST['page'])      ? (int)$_POST['page']                  : 1;
    $rows   = isset($_POST['rows'])      ? (int)$_POST['rows']                  : 20;
    $sort   = isset($_POST['sort'])      ?  htmlspecialchars( $_POST['sort'] )  : 'zone_id';
    $order  = isset($_POST['order'])     ?  htmlspecialchars( $_POST['order'] ) : 'desc';

	$zone = isset($_POST['zone']) ? (int)$_POST['zone'] : '';
	$acc = isset($_POST['acc']) ? $_POST['acc'] : '';
	$usrname = isset($_POST['usrname']) ? $_POST['usrname'] : '';

    $result = array();
    if($page <= 0) $page = 1;

    $dbh = new PDO(PDO_AllUserInfo_host, PDO_AllUserInfo_root, PDO_AllUserInfo_pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->query("SET NAMES UTF8");

    $where = 'WHERE 1 ';
	if($zone) $where .=' AND serverId=:zone ';
	if($acc) $where .=' AND opid=:acc ';
	if($usrname) $where .=' AND username=:usrname ';

	$limit = ' LIMIT ' . ($page-1)*$rows . ',' . $rows;
	$sql = "SELECT * FROM baninfo $where ORDER BY id DESC $limit ";
    $stmt = $dbh->prepare($sql);
	if($zone) $stmt->bindParam(':zone',$zone);
	if($acc) $stmt->bindParam(':acc',$acc);
	if($usrname) $stmt->bindParam(':usrname',$usrname);
    $stmt->execute();
    $result['rows'] = $stmt->fetchAll();

    $sql = "SELECT COUNT(*) AS nu FROM baninfo $where";
	$stmt = $dbh->prepare($sql);
    if($zone) $stmt->bindParam(':zone',$zone);
	if($acc) $stmt->bindParam(':acc',$acc);
	if($usrname) $stmt->bindParam(':usrname',$usrname);
    $stmt->execute();
    $nu = $stmt->fetch();
    $result['total'] = isset($nu['nu']) ? $nu['nu'] : 0;

    $result = html_escape($result);

    echo json_encode($result);

    $dbh = null;
}

function html_escape($var)
{
    if (is_array($var))
    {
        return array_map('html_escape', $var);
    }
    else
    {
        return htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
    }
}
