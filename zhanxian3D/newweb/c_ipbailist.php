<?php
include "h_header.php";
$action = isset($_GET['action']) ? $_GET['action'] : NULL;

define('C_LOGINSERVER_HOST','117.103.235.92');
define('C_LOGINSERVER_DBNAME','LoginServer');
define('C_LOGINSERVER_PORT','3306');
define('C_LOGINSERVER_ROOT','root');
define('C_LOGINSERVER_PASS','hoolai@123');

if($action == 'add')
{
    //----获取参数
    $COMPLANY = trim(get_param('COMPLANY', ''));
    $IP = trim(get_param('IP', ''));

    if(!$IP || !$COMPLANY) exit('参数不能为空');
    if( !checkIp($IP) ) exit('ip 格式不对');

    $dbh = new PDO('mysql:host='.C_LOGINSERVER_HOST.';dbname='.C_LOGINSERVER_DBNAME.';port='.C_LOGINSERVER_PORT.';', C_LOGINSERVER_ROOT, C_LOGINSERVER_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->query("SET NAMES UTF8");


    $sql = "INSERT INTO IPLIST(`COMPLANY`,`IP`) VALUES(:COMPLANY, :IP)";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(":COMPLANY", $COMPLANY);
    $stmt->bindParam(":IP", $IP);
    echo $stmt->execute();
}

if($action == 'list')
{
    $dbh = new PDO('mysql:host='.C_LOGINSERVER_HOST.';dbname='.C_LOGINSERVER_DBNAME.';port='.C_LOGINSERVER_PORT.';', C_LOGINSERVER_ROOT, C_LOGINSERVER_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->query("SET NAMES UTF8");

    $sql = "SELECT COMPLANY,IP,TIMES FROM IPLIST";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();

    echo json_encode($result);
}

//----自定义函数
/**
 * 获取 POST GET 传参 优先级 POST > GET > default
 */
function get_param($get, $default='')
{
    $return = (isset( $_GET[$get] ) && $_GET[$get] !='')  ? $_GET[$get] : $default;
    $return = (isset( $_POST[$get] ) && $_POST[$get] !='') ? $_POST[$get] : $return;
    return $return;
}

function checkIp($ip)
{
    $arr=explode('.',$ip);
    if(count($arr) != 4)
    {
        return false;
    }else
    {
        for($i = 0;$i < 4;$i++)
        {
            if(($arr[$i] <'0') || ($arr[$i] > '255'))
            {
                return false;
            }
        }
    }
    return true;
}
