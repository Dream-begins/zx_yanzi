<?php
include "h_header.php";

$dbh = new PDO(PDO_gonggaoInfo_host,PDO_gonggaoInfo_root,PDO_gonggaoInfo_pass);

$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);      //设置异常模式为抛出异常
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); //为语句设置默认获取方式
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);              //false关闭本地预处理 使用mysql预处理
$dbh->query("SET NAMES UTF8");

$action = isset($_GET['action']) ? $_GET['action'] : '';

if($action == "list")
{
    $return_arr = array();

    $stmt = $dbh->prepare("SELECT * FROM gonggao");
    $stmt->execute();
    $result = $stmt->fetchAll();

    foreach ($result as $key => $value) 
    {
        $result[$key]['ymd_start'] = date('Y-m-d H:i:s', $value['ymd_start']);
        $result[$key]['ymd_end'] = date('Y-m-d H:i:s', $value['ymd_end']);
        $result[$key]['time_start'] = date('H:i', $value['time_start']+57600);
        $result[$key]['time_end'] = date('H:i', $value['time_end']+57600);
    }

    $return_arr['rows'] = $result;

    echo json_encode($return_arr);
}

if($action == 'add')
{
    $ymd_start = isset($_POST['ymd_start']) ? strtotime($_POST['ymd_start']) : NULL;
    $ymd_end = isset($_POST['ymd_end']) ? strtotime($_POST['ymd_end'])+86399 : NULL;
    $time_start = isset($_POST['time_start']) ? strtotime($_POST['time_start']) -strtotime(date('y-m-d',time())) : NULL;
    $time_end = isset($_POST['time_end']) ? strtotime($_POST['time_end'])-strtotime(date('y-m-d',time())) : NULL;
    $contents = isset($_POST['contents']) ? $_POST['contents'] : NULL;
    $zones = isset($_POST['zones']) ? $_POST['zones'] : NULL;
    $times = isset($_POST['times']) ? $_POST['times'] : NULL;

    if( $ymd_start && $ymd_end && $time_end && $contents && $zones && $times)
    {
        $stmt = $dbh->prepare("INSERT INTO `gonggao`(ymd_start,ymd_end,time_start,time_end,contents,zones,times) VALUES(:ymd_start,:ymd_end,:time_start,:time_end,:contents,:zones,:times) ");
        $stmt->bindParam(':ymd_start', $ymd_start);
        $stmt->bindParam(':ymd_end', $ymd_end);
        $stmt->bindParam(':time_start', $time_start);
        $stmt->bindParam(':time_end', $time_end);
        $stmt->bindParam(':contents', $contents);
        $stmt->bindParam(':zones', $zones);
        $stmt->bindParam(':times', $times);
        echo $stmt->execute();
    }
}

if($action == 'edit')
{
    $ymd_start = isset($_POST['ymd_start']) ? strtotime($_POST['ymd_start']) : NULL;
    $ymd_end = isset($_POST['ymd_end']) ? strtotime($_POST['ymd_end'])+86399 : NULL;
    $time_start = isset($_POST['time_start']) ? strtotime($_POST['time_start']) -strtotime(date('y-m-d',time())) : NULL;
    $time_end = isset($_POST['time_end']) ? strtotime($_POST['time_end'])-strtotime(date('y-m-d',time())) : NULL;
    $contents = isset($_POST['contents']) ? $_POST['contents'] : NULL;
    $zones = isset($_POST['zones']) ? $_POST['zones'] : NULL;
    $times = isset($_POST['times']) ? $_POST['times'] : NULL;
    $id = isset($_GET['id']) ? (int)$_GET['id'] : NULL;

    if( $ymd_start && $ymd_end && $time_end && $contents && $zones && $times && $id)
    {
        $stmt = $dbh->prepare("UPDATE `gonggao` SET ymd_start=:ymd_start, ymd_end=:ymd_end, time_start=:time_start, time_end=:time_end, contents=:contents, zones=:zones, times=:times WHERE id = :id");
        $stmt->bindParam(':ymd_start', $ymd_start);
        $stmt->bindParam(':ymd_end', $ymd_end);
        $stmt->bindParam(':time_start', $time_start);
        $stmt->bindParam(':time_end', $time_end);
        $stmt->bindParam(':contents', $contents);
        $stmt->bindParam(':zones', $zones);
        $stmt->bindParam(':times', $times);
        $stmt->bindParam(':id', $id);
        echo $stmt->execute();
    }
}

if($action == 'del')
{
    $id = isset($_POST['id']) ? (int)$_POST['id'] : NULL;

    if($id == 0) exit;

    $stmt = $dbh->prepare("DELETE FROM gonggao WHERE id = :id ");
    $stmt->bindParam(":id", $id);

    echo $stmt->execute();
}
