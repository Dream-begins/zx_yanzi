<?php
include "h_header.php";

$dbh = new PDO(PDO_AllUserInfo_host,PDO_AllUserInfo_root,PDO_AllUserInfo_pass);

$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);      //设置异常模式为抛出异常
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); //为语句设置默认获取方式
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);              //false关闭本地预处理 使用mysql预处理
$dbh->query("SET NAMES UTF8");

$action = isset($_GET['action']) ? $_GET['action'] : '';

if($action == "list")
{
    $regtime = isset($_POST['ymd_start']) ? $_POST['ymd_start']     : '';
    $page    = isset($_POST['page'])      ? (int)$_POST['page']     : 1;
    $rows    = isset($_POST['rows'])      ? (int)$_POST['rows']     : 20;

    if($page <= 0) $page = 1;
    $limit = ' LIMIT ' . ($page-1)*$rows . ',' . $rows;

    if(!$regtime) exit(json_encode(array()));
    $regtime = date('Y-m-d H:i:s', strtotime($regtime));

    $return_arr = array();

    $stmt = $dbh->prepare("SELECT dt,ammount,cnt,qqgame,qqgamecnt FROM `RegDayPay` WHERE regtime = :regtime ORDER BY dt $limit ");
    $stmt->bindParam('regtime', $regtime);
    $stmt->execute();
    $result = $stmt->fetchAll();

    $stmt = $dbh->prepare("SELECT count(1) AS total FROM `RegDayPay` WHERE regtime = :regtime ");
    $stmt->bindParam('regtime', $regtime);
    $stmt->execute();
    $total = $stmt->fetch();

    $total = isset($total['total']) ? (int)$total['total'] : 0;
    $return_arr['total'] = $total;

    $return_arr['rows'] = $result;
    echo json_encode($return_arr);
}

if($action == "putcsv")
{
    $regtime = isset($_GET['ymd_start']) ? $_GET['ymd_start'] : '';

    if(!$regtime) exit(json_encode(array()));
    $regtime2 = date('Y-m-d H:i:s', strtotime($regtime));

    $return_arr = array();

    $stmt = $dbh->prepare("SELECT dt,ammount,cnt,qqgame,qqgamecnt FROM `RegDayPay` WHERE regtime = :regtime ORDER BY dt ");
    $stmt->bindParam('regtime', $regtime2);
    $stmt->execute();
    $result = $stmt->fetchAll();

    array_unshift($result, array('天数','总收入','付费人数','大厅','大厅人数') );
    outputCSV($result,'新注册付费'.$regtime);
}
