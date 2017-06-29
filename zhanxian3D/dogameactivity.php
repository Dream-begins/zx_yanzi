<?php
 
ini_set('display_errors', 'on');
error_reporting (E_ALL); // Report everything

date_default_timezone_set("PRC");
ini_set('default_charset','utf-8');
include_once "common.php";
include_once "checklogin.php";
include_once "upgrade.php";
include_once "db2new.php";

@$_POST = all_safe_sql(@$_POST);
@$_GET = all_safe_sql(@$_GET);

function all_safe_sql($var)
{
    if (is_array($var))
    {
        return array_map('all_safe_sql', $var);
    }
    else
    {
        $flag =  str_replace("\'", "'", $var);
        $flag = str_replace("'", "\'", $flag);
        return $flag;
    }
}

$zone1= intval(getParam("zone1","1"));
$zone2= intval(getParam("zone2","1"));
$isadd=intval(getParam("isadd","1"));
echo $isadd."      ";

$dbid = getParam("actdbid",null);

$acttype = getParam("acttype",null);
$actid = getParam("actid",null);
$starttime = getParam("starttime",null);
$endtime = getParam("endtime",null);
$expiretime = getParam("expiretime",null);
$iconid = getParam("iconid",null);
$tabid = getParam("tabid",null);
$tabinfo = getParam("tabinfo",null);
$tabtype = getParam("tabtype",null);
$picid = getParam("picid",null);
$picinfo = getParam("picinfo",null);
$platid = getParam("platid",null);

for($i=$zone1; $i<=$zone2; $i++)
{
      $_dburl=getDBUrl($i);
	  $_dbname=getDBName($i);
	  if(!$_dburl)
	  {
		  echo "请检查是否合过服";
		  continue;
	  }
		$con = mysql_connect($_dburl,$DB_USER,$DB_PASS);
	if(!$con )
	{
	    die("mysql connect error");
	}
	mysql_query("set names 'utf8'");
	mysql_select_db($_dbname, $con) or die("mysql select db error". mysql_error());
	mysql_query("SET NAMES utf8");
    $sql="";
	if ($isadd == 1)
		$sql = "insert into `GAMEACTIVITY`(TYPE,ACTIVITYID,WEEK,TIMESTART,TIMEEND,TIMEEXPIRE,STATUS,ICONID,TABID,TABINFO,TABTYPE,PICID,PICINFO,PLATID,PLANID,PLANINFO) values('".$acttype."','".$actid."',127,UNIX_TIMESTAMP('".$starttime."'),UNIX_TIMESTAMP('".$endtime."'),UNIX_TIMESTAMP('".$expiretime."'),0,'$iconid',$tabid,'$tabinfo',$tabtype,$picid,'$picinfo',$platid,0,'')";
	else if($isadd == 2){
        //$sql = "update `GAMEACTIVITY` set TIMESTART=UNIX_TIMESTAMP('".$starttime."'),TIMEEND=UNIX_TIMESTAMP('".$endtime."'),TIMEEXPIRE=UNIX_TIMESTAMP('".$expiretime."'),ICONID='$iconid',TABID=$tabid,TABINFO='$tabinfo',TABTYPE=$tabtype,PICID=$picid,PICINFO='$picinfo' where ID=$dbid";
        $sql = "update `GAMEACTIVITY` set TIMESTART=UNIX_TIMESTAMP('".$starttime."'),TIMEEND=UNIX_TIMESTAMP('".$endtime."'),TIMEEXPIRE=UNIX_TIMESTAMP('".$expiretime."'),ICONID='$iconid',TABID=$tabid,TABINFO='$tabinfo',TABTYPE=$tabtype,PICID=$picid,PICINFO='$picinfo' where TYPE=$acttype AND ACTIVITYID=$actid";
    }

    $result = mysql_query($sql)
	    or die("Invalid query: " . mysql_error());
  echo "$i 服操作一条活动\n\t活动类型：$acttype,	活动编号：$actid\n";
}


$zone3= intval(getParam("zone3","1"));
$zone4= intval(getParam("zone4","1"));
if($zone3>0 && $zone4>0 && $zone3<=$zone4){
    for ($i=$zone3; $i<=$zone4; $i++)
    {
        $_dburl=getDBUrl($i);
        $_dbname=getDBName($i);
        if(!$_dburl)
        {
            echo "请检查是否合过服";
            continue;
        }
        $con = mysql_connect($_dburl,$DB_USER,$DB_PASS);
        if(!$con )
        {
            die("mysql connect error");
        }
        mysql_query("set names 'utf8'");
        mysql_select_db($_dbname, $con) or die("mysql select db error". mysql_error());
        mysql_query("SET NAMES utf8");

        $sql="";
        if ($isadd == 1)
            $sql = "insert into `GAMEACTIVITY`(TYPE,ACTIVITYID,WEEK,TIMESTART,TIMEEND,TIMEEXPIRE,STATUS,ICONID,TABID,TABINFO,TABTYPE,PICID,PICINFO,PLATID,PLANID,PLANINFO) values('".$acttype."','".$actid."',127,UNIX_TIMESTAMP('".$starttime."'),UNIX_TIMESTAMP('".$endtime."'),UNIX_TIMESTAMP('".$expiretime."'),0,'$iconid',$tabid,'$tabinfo',$tabtype,$picid,'$picinfo',$platid,0,'')";
        else if($isadd == 2){
            //$sql = "update `GAMEACTIVITY` set TIMESTART=UNIX_TIMESTAMP('".$starttime."'),TIMEEND=UNIX_TIMESTAMP('".$endtime."'),TIMEEXPIRE=UNIX_TIMESTAMP('".$expiretime."'),ICONID='$iconid',TABID=$tabid,TABINFO='$tabinfo',TABTYPE=$tabtype,PICID=$picid,PICINFO='$picinfo' where ID=$dbid";
            $sql = "update `GAMEACTIVITY` set TIMESTART=UNIX_TIMESTAMP('".$starttime."'),TIMEEND=UNIX_TIMESTAMP('".$endtime."'),TIMEEXPIRE=UNIX_TIMESTAMP('".$expiretime."'),ICONID='$iconid',TABID=$tabid,TABINFO='$tabinfo',TABTYPE=$tabtype,PICID=$picid,PICINFO='$picinfo' where TYPE=$acttype AND ACTIVITYID=$actid";
        }

        $result = mysql_query($sql)
        or die("Invalid query: " . mysql_error());
        echo "$i 服操作一条活动\n\t活动类型：$acttype,	活动编号：$actid\n";
    }
}


$zone5= intval(getParam("zone5","1"));
$zone6= intval(getParam("zone6","1"));
if($zone5>0 && $zone6>0 && $zone5<=$zone6) {
    for ($i = $zone5; $i <= $zone6; $i++) {
        $_dburl = getDBUrl($i);
        $_dbname = getDBName($i);
        if (!$_dburl) {
            echo "请检查是否合过服";
            continue;
        }
        $con = mysql_connect($_dburl, $DB_USER, $DB_PASS);
        if (!$con) {
            die("mysql connect error");
        }
        mysql_query("set names 'utf8'");
        mysql_select_db($_dbname, $con) or die("mysql select db error" . mysql_error());
        mysql_query("SET NAMES utf8");

        $sql = "";
        if ($isadd == 1)
            $sql = "insert into `GAMEACTIVITY`(TYPE,ACTIVITYID,WEEK,TIMESTART,TIMEEND,TIMEEXPIRE,STATUS,ICONID,TABID,TABINFO,TABTYPE,PICID,PICINFO,PLATID,PLANID,PLANINFO) values('" . $acttype . "','" . $actid . "',127,UNIX_TIMESTAMP('" . $starttime . "'),UNIX_TIMESTAMP('" . $endtime . "'),UNIX_TIMESTAMP('" . $expiretime . "'),0,'$iconid',$tabid,'$tabinfo',$tabtype,$picid,'$picinfo',$platid,0,'')";
        else if ($isadd == 2){
            //$sql = "update `GAMEACTIVITY` set TIMESTART=UNIX_TIMESTAMP('".$starttime."'),TIMEEND=UNIX_TIMESTAMP('".$endtime."'),TIMEEXPIRE=UNIX_TIMESTAMP('".$expiretime."'),ICONID='$iconid',TABID=$tabid,TABINFO='$tabinfo',TABTYPE=$tabtype,PICID=$picid,PICINFO='$picinfo' where ID=$dbid";
            $sql = "update `GAMEACTIVITY` set TIMESTART=UNIX_TIMESTAMP('".$starttime."'),TIMEEND=UNIX_TIMESTAMP('".$endtime."'),TIMEEXPIRE=UNIX_TIMESTAMP('".$expiretime."'),ICONID='$iconid',TABID=$tabid,TABINFO='$tabinfo',TABTYPE=$tabtype,PICID=$picid,PICINFO='$picinfo' where TYPE=$acttype AND ACTIVITYID=$actid";
        }
        $result = mysql_query($sql)
        or die("Invalid query: " . mysql_error());
        echo "$i 服操作一条活动\n\t活动类型：$acttype,	活动编号：$actid\n";
    }
}

$zone7= intval(getParam("zone7","1"));
$zone8= intval(getParam("zone8","1"));
if($zone7>0 && $zone8>0 && $zone7<=$zone8) {
    for ($i = $zone7; $i <= $zone8; $i++) {
        $_dburl = getDBUrl($i);
        $_dbname = getDBName($i);
        if (!$_dburl) {
            echo "请检查是否合过服";
            continue;
        }
        $con = mysql_connect($_dburl, $DB_USER, $DB_PASS);
        if (!$con) {
            die("mysql connect error");
        }
        mysql_query("set names 'utf8'");
        mysql_select_db($_dbname, $con) or die("mysql select db error" . mysql_error());
        mysql_query("SET NAMES utf8");

        $sql = "";
        if ($isadd == 1)
            $sql = "insert into `GAMEACTIVITY`(TYPE,ACTIVITYID,WEEK,TIMESTART,TIMEEND,TIMEEXPIRE,STATUS,ICONID,TABID,TABINFO,TABTYPE,PICID,PICINFO,PLATID,PLANID,PLANINFO) values('" . $acttype . "','" . $actid . "',127,UNIX_TIMESTAMP('" . $starttime . "'),UNIX_TIMESTAMP('" . $endtime . "'),UNIX_TIMESTAMP('" . $expiretime . "'),0,'$iconid',$tabid,'$tabinfo',$tabtype,$picid,'$picinfo',$platid,0,'')";
        else if ($isadd == 2){
            //$sql = "update `GAMEACTIVITY` set TIMESTART=UNIX_TIMESTAMP('".$starttime."'),TIMEEND=UNIX_TIMESTAMP('".$endtime."'),TIMEEXPIRE=UNIX_TIMESTAMP('".$expiretime."'),ICONID='$iconid',TABID=$tabid,TABINFO='$tabinfo',TABTYPE=$tabtype,PICID=$picid,PICINFO='$picinfo' where ID=$dbid";
            $sql = "update `GAMEACTIVITY` set TIMESTART=UNIX_TIMESTAMP('".$starttime."'),TIMEEND=UNIX_TIMESTAMP('".$endtime."'),TIMEEXPIRE=UNIX_TIMESTAMP('".$expiretime."'),ICONID='$iconid',TABID=$tabid,TABINFO='$tabinfo',TABTYPE=$tabtype,PICID=$picid,PICINFO='$picinfo' where TYPE=$acttype AND ACTIVITYID=$actid";
        }
        $result = mysql_query($sql)
        or die("Invalid query: " . mysql_error());
        echo "$i 服操作一条活动\n\t活动类型：$acttype,	活动编号：$actid\n";
    }
}

$zone9= intval(getParam("zone9","1"));
$zone10= intval(getParam("zone10","1"));
if($zone9>0 && $zone10>0 && $zone9<=$zone10) {
    for ($i = $zone9; $i <= $zone10; $i++) {
        $_dburl = getDBUrl($i);
        $_dbname = getDBName($i);
        if (!$_dburl) {
            echo "请检查是否合过服";
            continue;
        }
        $con = mysql_connect($_dburl, $DB_USER, $DB_PASS);
        if (!$con) {
            die("mysql connect error");
        }
        mysql_query("set names 'utf8'");
        mysql_select_db($_dbname, $con) or die("mysql select db error" . mysql_error());
        mysql_query("SET NAMES utf8");

        $sql = "";
        if ($isadd == 1)
            $sql = "insert into `GAMEACTIVITY`(TYPE,ACTIVITYID,WEEK,TIMESTART,TIMEEND,TIMEEXPIRE,STATUS,ICONID,TABID,TABINFO,TABTYPE,PICID,PICINFO,PLATID,PLANID,PLANINFO) values('" . $acttype . "','" . $actid . "',127,UNIX_TIMESTAMP('" . $starttime . "'),UNIX_TIMESTAMP('" . $endtime . "'),UNIX_TIMESTAMP('" . $expiretime . "'),0,'$iconid',$tabid,'$tabinfo',$tabtype,$picid,'$picinfo',$platid,0,'')";
        else if ($isadd == 2){
            //$sql = "update `GAMEACTIVITY` set TIMESTART=UNIX_TIMESTAMP('".$starttime."'),TIMEEND=UNIX_TIMESTAMP('".$endtime."'),TIMEEXPIRE=UNIX_TIMESTAMP('".$expiretime."'),ICONID='$iconid',TABID=$tabid,TABINFO='$tabinfo',TABTYPE=$tabtype,PICID=$picid,PICINFO='$picinfo' where ID=$dbid";
            $sql = "update `GAMEACTIVITY` set TIMESTART=UNIX_TIMESTAMP('".$starttime."'),TIMEEND=UNIX_TIMESTAMP('".$endtime."'),TIMEEXPIRE=UNIX_TIMESTAMP('".$expiretime."'),ICONID='$iconid',TABID=$tabid,TABINFO='$tabinfo',TABTYPE=$tabtype,PICID=$picid,PICINFO='$picinfo' where TYPE=$acttype AND ACTIVITYID=$actid";
        }
        $result = mysql_query($sql)
        or die("Invalid query: " . mysql_error());
        echo "$i 服操作一条活动\n\t活动类型：$acttype,	活动编号：$actid\n";
    }
}

















