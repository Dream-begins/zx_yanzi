<?php
include "h_header.php";

$action = isset($_GET['action']) ? $_GET['action'] : NULL;

if($action == "list")
{
    $page   = isset($_POST['page'])      ? (int)$_POST['page']                  : 1;
    $rows   = isset($_POST['rows'])      ? (int)$_POST['rows']                  : 20;
    $sort   = isset($_POST['sort'])      ?  htmlspecialchars( $_POST['sort'] )  : 'zone_id';
    $order  = isset($_POST['order'])     ?  htmlspecialchars( $_POST['order'] ) : 'desc';

    $result = array();
    if($page <= 0) $page = 1;

    $dbh = new PDO(PDO_AllUserInfo_host, PDO_AllUserInfo_root, PDO_AllUserInfo_pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //设置异常模式为抛出异常
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); //为语句设置默认获取方式
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);              //false关闭本地预处理 使用mysql预处理
    $dbh->query("SET NAMES UTF8");

    $limit = ' LIMIT ' . ($page-1)*$rows . ',' . $rows;
    $sql = "SELECT `id`, `openid`, `ip`, `addDate`, `content`, `serverId` FROM suggest ORDER BY id DESC $limit ";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $result['rows'] = $stmt->fetchAll();

    $sql = "SELECT COUNT(*) AS nu FROM suggest";
    $stmt = $dbh->prepare($sql);
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
