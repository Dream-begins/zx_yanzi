<?php 
header("Content-Type:text/html;charset=UTF-8");
ini_set('default_charset','utf-8');
date_default_timezone_set("PRC");
ini_set('display_errors', 1); 
error_reporting(-1);

session_start();

include "newweb/h_header.php";

$dbh = new PDO('mysql:host='.FT_MYSQL_COMMON_HOST.';dbname='.FT_MYSQL_ADMIN_DBNAME.';port='.FT_MYSQL_COMMON_PORT.';charset=utf8', FT_MYSQL_COMMON_ROOT, FT_MYSQL_COMMON_PASS);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbh->query("SET NAMES UTF8");


$newpass = isset($_POST['passId']) ? $_POST['passId'] : '';
$username = $_SESSION['xwusername'];

$sql = "UPDATE `ADMIN` SET pass=:newpass, passChange=1 WHERE user=:user ";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':newpass', $newpass);
$stmt->bindParam(':user', $username);
echo $stmt->execute();
$_SESSION['ischangepass'] = 1;

