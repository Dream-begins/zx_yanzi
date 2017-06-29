<?php 
ini_set('display_errors', '1');
error_reporting (E_ALL); // Report everything

date_default_timezone_set("PRC");
ini_set('default_charset','utf-8');
include_once "common.php";
include_once "checklogin.php";
include_once "upgrade.php";

include_once "db2new.php";

$action = isset($_GET['action']) ? $_GET['action'] : '';

if($action == 'del')
{
	$zone1=$_POST["zone1"];
	$zone2=$_POST["zone2"];
	$acttype=$_POST["acttype2"];
	$actid=$_POST["actid2"];
	$state=$_POST["actstate2"];
    $stime = strtotime($_POST['stime']);

	for($i=$zone1;$i<=$zone2;$i++)
	{
		$_dburl=getDBUrl($i);
		$_dbname=getDBName($i);
		if(!$_dburl)
		{
			continue;
		}
		$con = mysql_connect($_dburl,$DB_USER,$DB_PASS);
		if(!$con )
		{
			die("mysql connect error");
			continue;
		}
		mysql_query("set names 'utf8'");
		mysql_select_db($_dbname, $con) or die("mysql select db error". mysql_error());
	 	$sql = "update `GAMEACTIVITY`set TIMEEXPIRE=UNIX_TIMESTAMP(now()) where STATUS = '$state' AND TYPE='$acttype' AND ACTIVITYID='$actid' AND TIMESTART='$stime'";
	  errlog($sql);
		$result = mysql_query($sql) or die("Invalid query: " . mysql_error());
	}
}
if($action == 'update')
{
	$dbid = getParam("actdbid",null);
	$type_new = getParam("acttype",null);
	$actid_new = getParam("actid",null);
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

    $zone1=$_GET["zone1"];
	$zone2=$_GET["zone2"];
	$acttype=$_GET["acttype2"];
	$actid=$_GET["actid2"];
	$state=$_GET["actstate2"];
    $stime = strtotime($_GET['stime']);
	
    for($i=$zone1;$i<=$zone2;$i++)
	{
		$_dburl=getDBUrl($i);
		$_dbname=getDBName($i);
		if(!$_dburl)
		{
			continue;
		}
		$con = mysql_connect($_dburl,$DB_USER,$DB_PASS);
		if(!$con )
		{
			die("mysql connect error");
			continue;
		}
		mysql_query("set names 'utf8'");
		mysql_select_db($_dbname, $con) or die("mysql select db error". mysql_error());
	  
	   $sql = "update `GAMEACTIVITY` 
	  		set TIMESTART=UNIX_TIMESTAMP('".$starttime."'),
	  				TIMEEND=UNIX_TIMESTAMP('".$endtime."'),
	  				TIMEEXPIRE=UNIX_TIMESTAMP('".$expiretime."'),
					ICONID='$iconid',
	  				TABID=$tabid,
	  				TABINFO='$tabinfo',
	  				TABTYPE=$tabtype,
	  				PICID=$picid,
					PICINFO='$picinfo',
				    PLATID='$platid'
	  				where STATUS = '$state' AND TYPE='$acttype' AND ACTIVITYID='$actid' AND TIMESTART='$stime' ";

	  errlog($sql);
		$result = mysql_query($sql) or die("Invalid query: " . mysql_error());
	}	
}

function post_param($keyword, $default=null)
{
    $return_data=$default;
    if(isset($_POST[$keyword])){
        $return_data = SS($_POST[$keyword]);
    }
    return $return_data;
}
function get_param($keyword, $default=null)
{
    $return_data=$default;
    if(isset($_GET[$keyword])){
        $return_data = SS($_GET[$keyword]);
    }
    return $return_data;
}

function SS($name)
{
    $name = trim($name);
    if (get_magic_quotes_gpc()) 
    {
        return $name;
    }else
    {
        return mysql_real_escape_string($name);
    }
}
