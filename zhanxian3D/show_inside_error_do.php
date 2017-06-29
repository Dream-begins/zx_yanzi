<?php

header("Content-Type:text/html;charset=UTF-8");
ini_set('default_charset','utf-8');
date_default_timezone_set("PRC");
#ini_set('display_errors', 1);
error_reporting(0);
session_start();

include_once "checklogin.php";
$dbh = new PDO('mysql:host=10.66.125.231;dbname=shouyou', 'root', 'hoolai@123');
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);      //设置异常模式为抛出异常
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); //为语句设置默认获取方式
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);              //false关闭本地预处理 使用mysql预处理
$dbh->query("SET NAMES UTF8");

$action = $_GET['action'];

if($action == "zone_list")
{

    $ymd = (isset($_POST['ymd_start']) && $_POST['ymd_start']) ? $_POST['ymd_start'] : date('Y-m-d');
    $ymd = strtotime($ymd);
    $ymd = date('Y_m_d', $ymd);

    $page = (int)$_POST['page'];
    if($page <= 0) {
        $page = 1;
    }
    $rows = (int)$_POST['rows'];
    $sort = isset($_POST['sort']) ? mysql_real_escape_string( htmlspecialchars( $_POST['sort'] ) ) : 'id';
    $order = isset($_POST['order']) ? mysql_real_escape_string( htmlspecialchars( $_POST['order'] ) ) : 'desc';

    $s_ptname = isset($_POST['s_ptname']) ? trim( mysql_real_escape_string( htmlspecialchars( $_POST['s_ptname'] ) ) ) : '';

    $where = ' WHERE 1 ';

    $orderby = ' ORDER BY ' . $sort . ' ' .$order;
    $limit = ' LIMIT ' . ($page-1)*$rows . ',' . $rows;

    $stmt = $dbh->prepare("SELECT * FROM int_error_inside_{$ymd} " . $orderby . $limit);
    
    if($s_ptname)
    {
        $stmt->bindParam(':s_ptname' , $s_ptname);
    }

    $stmt->execute();

    $result = array();
    $flag = $stmt->fetchAll();

    $result['rows'] = $flag;

    $stmt = $dbh->prepare("SELECT COUNT(*) AS total FROM int_error_inside_{$ymd} ");
    
    if($s_ptname)
    {
        $stmt->bindParam(':s_ptname' , $s_ptname);
    }

    $stmt->execute();
    $total = $stmt->fetch();

    $result['total'] = $total['total']; 

    echo json_encode($result);
}
