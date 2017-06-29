<?php
/**
 * Created by PhpStorm.
 * User: chenyunhe
 * Date: 2017/1/16
 * Time: 13:38
 */

include "h_header.php";
$dbh = new PDO(PDO_ZoneMsgInfo_host,PDO_ZoneMsgInfo_root,PDO_ZoneMsgInfo_pass);

$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);      //设置异常模式为抛出异常
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); //为语句设置默认获取方式
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);              //false关闭本地预处理 使用mysql预处理
$dbh->query("SET NAMES UTF8");

$action = isset($_GET['action']) ? $_GET['action'] : '';

if($action == "list")
{
    $return_arr = array();

    $stmt = $dbh->prepare("SELECT * FROM zone_msg");
    $stmt->execute();
    $result = $stmt->fetchAll();
    $return_arr['rows'] = $result;

    echo json_encode($return_arr);
}

if($action == 'add')
{
    $zone_id = isset($_POST['zone_id']) ? $_POST['zone_id'] : '';
    $zones = isset($_POST['zones']) ? $_POST['zones'] : '';
    $msyql_ip = isset($_POST['mysql_ip']) ? $_POST['mysql_ip'] : '';
    $mysql_port = isset($_POST['mysql_port']) ? $_POST['mysql_port'] : '';
    $mysql_dbname = isset($_POST['mysql_dbName']) ? $_POST['mysql_dbName'] : '';

    if( $zone_id && $zones && $msyql_ip && $mysql_port && $mysql_dbname)
    {
        $stmt = $dbh->prepare("INSERT INTO `zone_msg`(zone_id,zones,mysql_ip,mysql_port,mysql_dbname) VALUES(:zone_id,:zones,:mysql_ip,:mysql_port,:mysql_dbname) ");
        $stmt->bindParam(':zone_id', $zone_id);
        $stmt->bindParam(':zones', $zones);
        $stmt->bindParam(':mysql_ip', $msyql_ip);
        $stmt->bindParam(':mysql_port', $mysql_port);
        $stmt->bindParam(':mysql_dbname', $mysql_dbname);
        echo $stmt->execute();
    }
}

if($action == 'edit')
{
    $zone_id = isset($_POST['zone_id']) ? $_POST['zone_id'] : '';
    $zones = isset($_POST['zones']) ? $_POST['zones'] : '';
    $msyql_ip = isset($_POST['mysql_ip']) ? $_POST['mysql_ip'] : '';
    $mysql_port = isset($_POST['mysql_port']) ? $_POST['mysql_port'] : '';
    $mysql_dbname = isset($_POST['mysql_dbName']) ? $_POST['mysql_dbName'] : '';
    $id = isset($_GET['id']) ? $_GET['id'] : '';

    if( $zone_id && $zones && $msyql_ip && $mysql_port && $mysql_dbname && $id)
    {
        $stmt = $dbh->prepare("UPDATE `zone_msg` SET zone_id=:zone_id, zones=:zones, mysql_ip=:mysql_ip, mysql_port=:mysql_port, mysql_dbname=:mysql_dbname WHERE id = :id");
        $stmt->bindParam(':zone_id', $zone_id);
        $stmt->bindParam(':zones', $zones);
        $stmt->bindParam(':mysql_ip', $msyql_ip);
        $stmt->bindParam(':mysql_port', $mysql_port);
        $stmt->bindParam(':mysql_dbname', $mysql_dbname);
        $stmt->bindParam(':id', $id);
        echo $stmt->execute();
    }
}

if($action == 'del')
{
    $id = isset($_POST['id']) ? (int)$_POST['id'] : NULL;

    if($id == 0) exit;

    $stmt = $dbh->prepare("DELETE FROM zone_msg WHERE id = :id ");
    $stmt->bindParam(":id", $id);

    echo $stmt->execute();
}
