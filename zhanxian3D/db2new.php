<?php
include_once "newweb/h_header.php";
include_once "common.php";

$DB_HOST=FT_MYSQL_COMMON_HOST; 
$DB_USER=FT_MYSQL_COMMON_ROOT;
$DB_PASS=FT_MYSQL_COMMON_PASS;
$DB_NAME=FT_MYSQL_BILL_DBNAME;

function getDBURL($_zonenum)
{
    $cfg = getIndexCfg($_zonenum);
    
    if($cfg) return $cfg["mysql_ip"].":".$cfg["mysql_port"];

    return null;
}

function getDBName($z)
{
    $cfg = getIndexCfg($z);
    if($cfg) return $cfg["mysql_dbName"];
    
    return null;
}

$con2=null;

if(getParam("zone") != "")
{
    $cfg = getIndexCfg(getParam("zone"));
    
    if($cfg)
    {
        $_zonenum= intval(getParam("zone","1"));
        $_dburl = $cfg["mysql_ip"].":".$cfg["mysql_port"];
        $DB_GAME=$cfg["mysql_dbName"];
        $con2 = mysql_connect($_dburl,$DB_USER,$DB_PASS);
        
        if( !$con2) die("mysql connect error");
        
        mysql_query("set names 'utf8'");
    }
//else
//    echo "unknown zone:".getParam("zone");
}

