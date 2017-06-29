<?php
header("Content-Type:text/html;charset=UTF-8");
ini_set('default_charset','utf-8');
date_default_timezone_set("PRC");
session_start();
ini_set('display_errors', 1);
error_reporting(1);
include_once "newweb/h_header.php";

$dbh = new PDO('mysql:host='.FT_MYSQL_COMMON_HOST.';dbname='.FT_MYSQL_ADMIN_DBNAME.';port='.FT_MYSQL_COMMON_PORT.';charset=utf8',FT_MYSQL_COMMON_ROOT,FT_MYSQL_COMMON_PASS);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbh->query("SET NAMES utf8");

$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$result   = array();

$sql = "SELECT priv,passChange FROM ADMIN WHERE user = :user AND pass = :pass ";
$stmt = $dbh->prepare($sql);

$stmt->bindParam(':user', $username);
$stmt->bindParam(':pass', $password);
$stmt->execute();
$result = $stmt->fetchAll();

$total = count($result);

function checkPasswdRate($passwd)
{
    $len = strlen($passwd);

    if ( $len <=  8) return '密码太短 要大于8位';

    if ($passwd == '') 
    {
        return '密码不能为空';
    }

    if(eregi('^[0-9]*$',$passwd)) return '密码不能由纯数字组成';

    return 'ok';
}

if($total == 1)
{
    $_SESSION['ischangepass']   = isset($result[0]['passChange']) ? $result[0]['passChange'] : '';
    $flag = checkPasswdRate($password);

    if($flag !='ok') 
    {
       echo "<script>alert('$flag');</script>";
       $_SESSION['ischangepass'] = 0;
    }

    $_SESSION['logined']        = 1;
    $_SESSION['xwusername']     = $username;

    $_SESSION['priv']           = isset($result[0]['priv'])       ? $result[0]['priv']       : '';
    header('Location: main.php');
}else
{
    unset($_SESSION['logined']);
    unset($_SESSION['xwusername']);
    unset($_SESSION['ischangepass']);
    echo "<script>window.location.href='index.php';alert('帐号或密码不正确')</script>";
}

