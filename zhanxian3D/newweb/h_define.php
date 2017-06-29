<?php
define('FT_COMMON_TITLE', '斩仙3D内网');

define('FT_MYSQL_COMMON_HOST',   '117.103.235.92');
//define('FT_MYSQL_COMMON_HOST',   '10.66.148.150');
define('FT_MYSQL_COMMON_PORT',   '3306');
define('FT_MYSQL_COMMON_ROOT',   'root');
define('FT_MYSQL_COMMON_PASS',   'hoolai@123');
//define('FT_MYSQL_COMMON_PASS',   '');

//define('FT_MYSQL_ZONE_MSG_HOST',   '10.104.222.134');
define('FT_MYSQL_ZONE_MSG_HOST',   '117.103.235.92');
define('FT_MYSQL_ZONE_MSG_PORT',   '3306');
define('FT_MYSQL_ZONE_MSG_ROOT',   'root');
define('FT_MYSQL_ZONE_MSG_PASS',   'hoolai@123');

define('FT_MYSQL_GAMEDATA_DBNAME',      'GameData');
define('FT_MYSQL_LOGINSERVER_DBNAME',   'LoginServer');
define('FT_MYSQL_ADMIN_DBNAME',         'admin');
define('FT_MYSQL_ZONE_MSG_DBNAME',      'fentiansj');
define('FT_MYSQL_BILL_DBNAME',          'BILL');
define('FT_MYSQL_EXTGAMESERVER_DBNAME', 'ExtGameServer');
define('FT_MYSQL_SHOUYOU_DBNAME',       'shouyou');

define('FT_URL_START_GS', 'http://119.29.12.209:6789/cgi-bin/start_gs.py');
define('FT_RES',       './jquery-easyui-1.4/');

$flag = get_curl_gameactivity_confs();
$FT_GAMEACTIVITY_ID_TO_NAME_ARR = json_decode($flag,1);

function get_curl_gameactivity_confs()
{
    $token = "as21EDSFDF#2dscs";
    $now = time();
    $url = "http://119.29.222.55:8080/outinterface/out_gameactivity_confs.php?gameselect=xlj&gettime=".$now."&getpwd=".md5($token.$now);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $output = curl_exec($ch);
    curl_close($ch);

    return $output;
}
