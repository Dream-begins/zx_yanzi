<?php 
header("Content-Type:text/html;charset=UTF-8");
ini_set('default_charset','utf-8');
date_default_timezone_set("PRC");
ini_set('display_errors', 1); 
error_reporting(0);

include "newweb/h_header.php";
include "checklogin.php";

$dbh = new PDO('mysql:host='.FT_MYSQL_COMMON_HOST.';dbname='.FT_MYSQL_ADMIN_DBNAME.';port='.FT_MYSQL_COMMON_PORT.';charset=utf8', FT_MYSQL_COMMON_ROOT, FT_MYSQL_COMMON_PASS);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbh->query("SET NAMES UTF8");

$action = isset($_GET['action']) ? $_GET['action'] : 'list';


if($action == 'list')
{
    $openid = isset($_POST['openid']) ? $_POST['openid'] : '';
    $page   = isset($_POST['page'])   ? (int)$_POST['page']   : 1;
    $rows   = isset($_POST['rows'])   ? (int)$_POST['rows']   : 10;

    $limit = ' LIMIT ' . ($page-1)*$rows . ',' . $rows;

    $GLOBALS["tmp"] = (isset($GLOBALS["tmp"])) ? ($GLOBALS["tmp"]+1) : 1;

    $cond = "1=1 ";
    $total = 1;
    $data = array();

    if($openid)
    {
        $cond .=" AND ACC=:acc ";
        $sql = "SELECT COUNT(*) AS nu FROM MAIL";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        $total = isset($result['nu']) ? $result['nu'] : 1;
    }

    $data["total"] = $total;

     
    $sql = "SELECT * FROM MAIL  WHERE ".$cond." ORDER BY id DESC" . $limit;

    $stmt = $dbh->prepare($sql);
    if($openid) $stmt->bindParam(':acc', $openid);
    $stmt->execute();
    $result =  $stmt->fetchAll();
    $data['rows'] = $result;

    //exit(json_encode($data));
    print_r($data);
}


