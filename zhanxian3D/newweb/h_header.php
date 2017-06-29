<?php
header("Content-Type:text/html;charset=UTF-8");
ini_set('default_charset','utf-8');
date_default_timezone_set("PRC");

//---------------------配置 start---------------------------------------------------------------------
define('FT_COMMON_TITLE', '斩仙3D内网');

//define('FT_MYSQL_COMMON_HOST',   '117.103.235.92');
define('FT_MYSQL_COMMON_HOST',   '127.0.0.1');
define('FT_MYSQL_COMMON_PORT',   '3306');
define('FT_MYSQL_COMMON_ROOT',   'root');
//define('FT_MYSQL_COMMON_PASS',   '');
//define('FT_MYSQL_COMMON_PASS',   'hoolai@123');
define('FT_MYSQL_COMMON_PASS',   'root');

//define('FT_MYSQL_ZONE_MSG_HOST',   '117.103.235.92');
define('FT_MYSQL_ZONE_MSG_HOST',   '127.0.0.1');
define('FT_MYSQL_ZONE_MSG_PORT',   '3306');
define('FT_MYSQL_ZONE_MSG_ROOT',   'root');
//define('FT_MYSQL_ZONE_MSG_PASS',   '');
//define('FT_MYSQL_ZONE_MSG_PASS',   'hoolai@123');
define('FT_MYSQL_ZONE_MSG_PASS',   'root');

define('FT_MYSQL_GAMEDATA_DBNAME',      'gamedata');
define('FT_MYSQL_LOGINSERVER_DBNAME',   'loginserver');
define('FT_MYSQL_ADMIN_DBNAME',         'admin');
define('FT_MYSQL_ZONE_MSG_DBNAME',      'fentiansj');
define('FT_MYSQL_BILL_DBNAME',          'bill');
define('FT_MYSQL_EXTGAMESERVER_DBNAME', 'extgameserver');
define('FT_MYSQL_SHOUYOU_DBNAME',       'shouyou');

define('FT_URL_START_GS', 'http://119.29.12.209:6789/cgi-bin/start_gs.py');
define('FT_RES',       './jquery-easyui-1.4/');

//define('PDO_ZoneMsgInfo_host', 'mysql:host=117.103.235.92;dbname=fentiansj;port=3306;charset=utf8');
define('PDO_ZoneMsgInfo_host', 'mysql:host=127.0.0.1;dbname=fentiansj;port=3306;charset=utf8');
define('PDO_ZoneMsgInfo_root', 'root');
//define('PDO_ZoneMsgInfo_pass', 'hoolai@123');
define('PDO_ZoneMsgInfo_pass', 'root');

//define('PDO_TradeInfo_host', 'mysql:host=117.103.235.92;dbname=BILL;port=3306;charset=utf8');
define(' PDO_TradeInfo_host', 'mysql:host=127.0.0.1;dbname=bill;port=3306;charset=utf8');
define('PDO_TradeInfo_root', 'root');
//define('PDO_TradeInfo_pass', 'hoolai@123');
define('PDO_TradeInfo_pass', 'root');

//define('PDO_Trade_syInfo_host', 'mysql:host=117.103.235.92;dbname=BILL;port=3306;charset=utf8');
define('PDO_Trade_syInfo_host', 'mysql:host=127.0.0.1;dbname=bill;port=3306;charset=utf8');
define('PDO_Trade_syInfo_root', 'root');
//define('PDO_Trade_syInfo_pass', 'hoolai@123');
define('PDO_Trade_syInfo_pass', 'root');

//define('PDO_AllUserInfo_host', 'mysql:host=117.103.235.92;dbname=GameData;port=3306;charset=utf8');
define('PDO_AllUserInfo_host', 'mysql:host=127.0.0.1;dbname=gamedata;port=3306;charset=utf8');
define('PDO_AllUserInfo_root', 'root');
//define('PDO_AllUserInfo_pass', 'hoolai@123');
define('PDO_AllUserInfo_pass', 'root');

//define('PDO_gonggaoInfo_host', 'mysql:host=117.103.235.92;dbname=admin;port=3306;charset=utf8');
define('PDO_gonggaoInfo_host', 'mysql:host=127.0.0.1;dbname=admin;port=3306;charset=utf8');
define('PDO_gonggaoInfo_root', 'root');
//define('PDO_gonggaoInfo_pass', 'hoolai@123');
define('PDO_gonggaoInfo_pass', 'root');

//define('PDO_shouyou_host', 'mysql:host=117.103.235.92;dbname=shouyou;port=3306;charset=utf8');
define('PDO_shouyou_host', 'mysql:host=127.0.0.1;dbname=shouyou;port=3306;charset=utf8');
define('PDO_shouyou_root', 'root');
//define('PDO_shouyou_pass', 'hoolai@123');
define('PDO_shouyou_pass', 'root');

define('PDO_ZONE_ROOT', 'root');
//define('PDO_ZONE_PASS', 'hoolai@123');
define('PDO_ZONE_PASS', 'root');


//---------------------配置 end---------------------------------------------------------------------


//---------------------自定义函数 start---------------------------------------------------------------------
/**
 * @desc 导出CSV文件
 * @param $data array() 数据数组 如 array( array(1,2,3), array(1,2,3) );
 * @param $name string 文件名 如 zone_msg
 * @return 导出CSV文件
 */
function outputCSV($data,$name)
{
    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename={$name}.csv");
    header("Pragma: no-cache");
    header("Expires: 0");

    $outputBuffer = fopen("php://output", 'w');
    foreach($data as $val)
    {
        foreach ($val as $key => $val2) 
        {
            $val[$key] = iconv('utf-8', 'gbk', $val2);// CSV的Excel支持GBK编码，一定要转换，否则乱码 
        }
        fputcsv($outputBuffer, $val);
    }
    fclose($outputBuffer);
}

//获取活动类型
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
