<?php
header("Content-Type:text/html;charset=UTF-8");
ini_set('default_charset','utf-8');
date_default_timezone_set("PRC");
session_start();
error_reporting(-1);
include "h_header.php";

if(strpos(@$_SESSION['priv'],'newweb/v_guozhan')==0)exit;
$dbh = new PDO('mysql:host=117.103.235.92;dbname=CrossServer_yyb;port=3306;charset=utf8', 'root', 'hoolai@123');
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);      
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); 
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbh->query("SET NAMES UTF8");

$action = $_GET['action'];

if($action == "list")
{
    $stmt = $dbh->prepare("SELECT * FROM DRAGONGROUP");
    $stmt->execute();
    $result = $stmt->fetchAll();
    echo json_encode($result);
}

