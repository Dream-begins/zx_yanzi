<?php 
include_once "newweb/h_header.php";
date_default_timezone_set("PRC");
ini_set('display_errors', '1');
require_once("ZoneUtil.php");
error_reporting (E_ALL);

function getParam($name,$default="")
{
    return isset($_POST[$name])?$_POST[$name]:(isset($_GET[$name])?$_GET[$name]:$default);
}

function getFrom()
{
    $from = getParam("from",null);
    $date_time_array = getdate( time());
    if(!$from)
        $from = mktime(0, 0,0,$date_time_array ["mon"], $date_time_array["mday" ]-6,$date_time_array[ "year"]);
    else
        $from = strtotime($from);    
    return $from;
}

function getFrom2()
{
    $from = getParam("from",null);
    $date_time_array = getdate( time());
    
    if(!$from)
        $from = mktime(0, 0,0,$date_time_array ["mon"], $date_time_array["mday"],$date_time_array[ "year"]);
    else
        $from = strtotime($from);
    
    return $from;
}

function getTo()
{
    $to = getParam("to",null);
    $date_time_array = getdate( time());
    if(!$to)
        $to =  mktime(0, 0,0,$date_time_array ["mon"], $date_time_array["mday" ]+1,$date_time_array[ "year"]);
    else
    {
        $date_time_array = getdate( strtotime($to));
        $to = mktime(0, 0,0,$date_time_array ["mon"], $date_time_array["mday" ]+1,$date_time_array[ "year"]);
    }
    return $to;
}

function getTo2()
{
    $to = getParam("to",null);
    $date_time_array = getdate( time());
    if(!$to)
        $to =  mktime(23,59,59,$date_time_array ["mon"], $date_time_array["mday" ],$date_time_array[ "year"]);
    else
    {
        $date_time_array = getdate( strtotime($to));
        $to = mktime(23,59,59,$date_time_array ["mon"], $date_time_array["mday" ],$date_time_array[ "year"]);
    }
    return $to;
}

function getDateDiff($from,$diff)
{
    $date_time_array = getdate($from);
    return mktime(0,0,0,$date_time_array["mon"],$date_time_array["mday"]+$diff,$date_time_array["year"]);

}

function errlog($msg)
{
     error_log(date("[Y-m-d H:i:s]").$msg."\n", 3, "/tmp/admin_err_".date('Y_m_d').".log");
}

$indexToZone=array(
    1,4,
    57,67,
    82,93,
    102,114,
    130,144,
    149,164,
    150,167,
    190,209,201,221,231,255,281,311
);

$extra = array(10001=>166,10002=>251,10003=>252,10004=>253,10005=>254,10006=>305,10007=>306,10008=>307,10009=>308,10010=>309,20002=>310);

function indexToZone($idx)
{
    return ZoneUtil::indexToZone(intval($idx));

    global $extra;

    if(array_key_exists($idx, $extra)) return $extra[$idx];
    
    global $indexToZone;
    
    $idx=intval($idx);
    
    for($i=0;$i<count($indexToZone)/2;$i++)
    {
        if($indexToZone[$i*2] == $idx)
            return $indexToZone[$i*2+1];
        elseif($indexToZone[$i*2] >$idx)
        {
            return $indexToZone[($i-1)*2+1] +($idx - $indexToZone[($i-1)*2]);
        }
    }
    
    return $indexToZone[($i-1)*2+1] +($idx - $indexToZone[($i-1)*2]);
}

function zoneToIndex($zone)
{
    return ZoneUtil::zoneToIndex($zone);
    global $extra;
    global $indexToZone;
    
    foreach($extra as $k=>$v)
    {
        if($v == $zone)
            return $k;
    }
    for($i=0;$i<count($indexToZone)/2;$i++)
    {
        if($indexToZone[$i*2+1] == $zone)
            return $indexToZone[$i*2];
        elseif($indexToZone[$i*2+1] >$zone)
        {
            return $indexToZone[($i-1)*2] +($zone - $indexToZone[($i-1)*2+1]);
        }
    }

    return $indexToZone[($i-1)*2] +($zone - $indexToZone[($i-1)*2+1]);
}

$_zoneCfg=null;
$_zoneCfgRead=false;

function getIndexCfg($idx)
{
    $_zoneCfg = null;            
    $conzone = mysql_connect(FT_MYSQL_ZONE_MSG_HOST.':'.FT_MYSQL_ZONE_MSG_PORT,FT_MYSQL_ZONE_MSG_ROOT,FT_MYSQL_ZONE_MSG_PASS);
    if($conzone)
    {
        mysql_query("set names 'utf8'");
        mysql_select_db(FT_MYSQL_ZONE_MSG_DBNAME, $conzone) or die("mysql select db error". mysql_error());
        $sql = "select * from zone_msg where zone_id=".$idx;
        $result = mysql_query($sql,$conzone);
        if(!$result)
        {
            echo "no result\n";
            return;
        }
        $_zoneCfg = mysql_fetch_assoc($result);
    }
    else
        echo mysql_error();
    if($conzone != null)
        mysql_close($conzone);

    $_zoneCfgRead=true;
    return $_zoneCfg;
}

function getIndexCfg2($idx)
{
    $_zoneCfg = null;
    $conzone = mysql_connect(FT_MYSQL_ZONE_MSG_HOST.':'.FT_MYSQL_ZONE_MSG_PORT,FT_MYSQL_ZONE_MSG_ROOT,FT_MYSQL_ZONE_MSG_PASS);
    if($conzone)
    {
        mysql_query("set names 'utf8'");
        mysql_select_db(FT_MYSQL_ZONE_MSG_DBNAME, $conzone) or die("mysql select db error". mysql_error());
        $sql = "select * from zone_msg where zone_id=".$idx." or concat(',',zones,',') like '%".",".$idx.",%'";
        $result = mysql_query($sql,$conzone);
        if(!$result)
        {
            echo "no result\n";
            return;
        }
        $_zoneCfg = mysql_fetch_assoc($result);
    }
    else
        echo mysql_error();
    if($conzone != null)
        mysql_close($conzone);

    $_zoneCfgRead=true;
    return $_zoneCfg;
}

function getZoneCfg($zone)
{
    global $_zoneCfg;
    global $_zoneCfgRead;
    if($_zoneCfg==null&&!$_zoneCfgRead)
    {
        $conzone = mysql_connect(FT_MYSQL_ZONE_MSG_HOST.':'.FT_MYSQL_ZONE_MSG_PORT,FT_MYSQL_ZONE_MSG_ROOT,FT_MYSQL_ZONE_MSG_PASS);
        if($conzone)
        {
            mysql_query("set names 'utf8'");
            mysql_select_db(FT_MYSQL_ZONE_MSG_DBNAME, $conzone) or die("mysql select db error". mysql_error());
            $sql = "select * from zone_msg where server_id=".$zone;
            $result = mysql_query($sql,$conzone);
            if(!$result)
            {
                echo "no result\n";
                return;
            }
            $_zoneCfg = mysql_fetch_assoc($result);
        }
        else
            echo mysql_error();
        if($conzone != null)
            mysql_close($conzone);
        
        $_zoneCfgRead=true;
    }
    return $_zoneCfg;
}

function getZoneCfg2($zone)
{
    $_zoneCfg = null;
    $conzone = mysql_connect(FT_MYSQL_ZONE_MSG_HOST.':'.FT_MYSQL_ZONE_MSG_PORT,FT_MYSQL_ZONE_MSG_ROOT,FT_MYSQL_ZONE_MSG_PASS);
    if($conzone)
    {
        mysql_query("set names 'utf8'");
        mysql_select_db(FT_MYSQL_ZONE_MSG_DBNAME, $conzone) or die("mysql select db error". mysql_error());
        $sql = "select * from zone_msg where server_id=".$zone." or concat(',',domians,',') like '%".",".$zone.",%'";
        $result = mysql_query($sql,$conzone);
        if(!$result)
        {
            echo "no result\n";
            return;
        }
        $_zoneCfg = mysql_fetch_assoc($result);
    }else
        echo mysql_error();
    if($conzone != null)
        mysql_close($conzone);
    
    return $_zoneCfg;
}
