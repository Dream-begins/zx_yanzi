<?php
date_default_timezone_set("PRC");
ini_set('default_charset','utf-8');
include_once "common.php";
include_once "checklogin.php";
include_once "newweb/h_header.php";

if(strpos($_SESSION['priv'],'uuseracc')==0)exit;

$action = isset($_POST['action']) ? $_POST['action'] : '';

$acc  = isset($_POST['acc']) ? $_POST['acc'] : '';

if($action == 'add' && $acc)
{
    $con2 = mysql_connect(FT_MYSQL_COMMON_HOST,FT_MYSQL_COMMON_ROOT,FT_MYSQL_COMMON_PASS);
    mysql_select_db(FT_MYSQL_LOGINSERVER_DBNAME, $con2) or die("mysql select db error". mysql_error());
    mysql_query('set name utf8', $con2);

    $now = time();
    $deltime = $now - 86400*7; 

    $sql = "DELETE FROM GMLIST where UNIX_TIMESTAMP(LASTTIME) <= '{$deltime}'  ";
    mysql_query($sql, $con2);

    $sql = "INSERT INTO GMLIST(ACC) VALUES('{$acc}')";
    mysql_query($sql, $con2);

    $total = mysql_affected_rows();

    if($total) echo "<font color='red'>添加成功请从电脑板登录</font>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <title><?php echo FT_COMMON_TITLE;?></title>
</head>
<body>
    <h1>登入玩家功能 </h1>
    <form action='' method='post'>
        <table>
            <tr>
                <td>玩家ID: <input type='text' name='acc'></td>
            </tr>
            <tr>
                <td><input type='submit' value='添加玩家帐号后通过电脑版进入'></td>
            </tr>
            <input type='hidden' name='action' value='add' >
        </table>
    </form>
<hr>
</body>
</html>
