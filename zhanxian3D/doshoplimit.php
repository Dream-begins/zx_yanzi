<?php
date_default_timezone_set("PRC");
ini_set('default_charset','utf-8');
include_once "common.php";
include_once "checklogin.php";
include_once "upgrade.php";
include_once "db2new.php";
//ini_set('display_errors', '1');
error_reporting (0); // Report everything
$zone1= intval(getParam("sp_zone1","1"));
$zone2= intval(getParam("sp_zone2","1"));
	$page=getParam("page",null);
	$objid=getParam("objid",null);
	$objname=getParam("objname",null);
	//$supermarketpos=getParam("supermarketpos",null);
	$moneytype=getParam("moneytype",null);
	$originalprice=getParam("originalprice",null);
	$discontprice=getParam("discontprice",null);
	$isbind=getParam("isbind",null);
	$singlecanbuynum=getParam("singlecanbuynum",null);
	$totalbuylimitnum=getParam("totalbuylimitnum",null);
	$opentype=getParam("opentype",null);
	$openttime=getParam("openttime",null);
	$closetime=getParam("closetime",null);
	$limitedpurchasestarttime=getParam("limitedpurchasestarttime",null);
	$limitedpurchaseendtime=getParam("limitedpurchaseendtime",null);
	$tagtype=getParam("tagtype",null);
	$needviplevel=getParam("needviplevel",null);	
	$isadd=intval(getParam("isadd","1"));
	$dbid=getParam("dbid",null);
	$indexid=getParam("indexid",null);
    $tabIcon=(int)getParam("tabIcon",null);
	$tabTitle=getParam("tabTitle",null);
	$weight=getParam("weight",null);
	$uniqid = time();

    $MONEYID=(int)getParam("MONEYID","0");
    $AWARDS=getParam("AWARDS",null);
    $STAGEPRICES=getParam("STAGEPRICES",null);
    $PRESHOW=(isset($_POST['PRESHOW']) && $_POST['PRESHOW']=='on') ? 1 : 0;
    $DESC=getParam("DESC",null);
for ($i=$zone1; $i<=$zone2; $i++) 
{

            $cfg = getIndexCfg($i);

		$dburl = getDBUrl($i);
		if(!$dburl)
			continue;
		  $con = mysql_connect($dburl,$DB_USER,$DB_PASS);
	      if(!$con )
		      {
			          die("mysql connect error"); 
	      }
	      mysql_query("set names 'utf8'");
              mysql_select_db($cfg['mysql_dbName'], $con) or die("mysql select db error". mysql_error());
		  mysql_query("set names 'utf8'",$con);
		  //	mysql_query("SET NAMES utf8",$con); 	
	if ($isadd == 1)
	$sql = "insert into `SHOPLIMIT`(ICON,TITLE,WEIGHT,PAGE,INDEXID,OBJID,OBJNAME,MONEYTYPE,ORIGINALPRICE,DISCONTPRICE,ISBIND,SINGLECANBUYNUM,TOTALBUYLIMITNUM,OPENTYPE,OPENTTIME,CLOSETIME,LIMITEDPURCHASESTARTTIME,LIMITEDPURCHASEENDTIME,TAGTYPE,NEEDVIPLEVEL,MONEYID,AWARDS,STAGEPRICES,PRESHOW,`DESC`) 
		values('".$tabIcon."','".$tabTitle."','".$weight."','".$page."','".$uniqid."','".$objid."','".$objname."','".$moneytype."','".$originalprice."','".$discontprice."','".$isbind."','".$singlecanbuynum."',	'".$totalbuylimitnum."'
		,'".$opentype."','".$openttime."','".$closetime."','".$limitedpurchasestarttime."','".$limitedpurchaseendtime."','".$tagtype."','".$needviplevel."','".$MONEYID."','".$AWARDS."','".$STAGEPRICES."','".$PRESHOW."','".$DESC."')";
	else if($isadd == 2)
		$sql = "update `SHOPLIMIT` 
		set PAGE='".$page."'
		,OBJID='".$objid."'
		,OBJNAME='".$objname."'
		,MONEYTYPE='".$moneytype."'
		,ORIGINALPRICE='".$originalprice."'
		,DISCONTPRICE='".$discontprice."'
		,ISBIND='".$isbind."'
		,SINGLECANBUYNUM='".$singlecanbuynum."'
		,TOTALBUYLIMITNUM='".$totalbuylimitnum."'
		,OPENTYPE='".$opentype."'
		,OPENTTIME='".$openttime."'
		,CLOSETIME='".$closetime."'
		,LIMITEDPURCHASESTARTTIME='".$limitedpurchasestarttime."'
		,LIMITEDPURCHASEENDTIME='".$limitedpurchaseendtime."'
		,TAGTYPE='".$tagtype."'
		,NEEDVIPLEVEL='".$needviplevel."'
		,ICON='".$tabIcon."' 
		,TITLE='".$tabTitle."' 	
		,WEIGHT='".$weight."' 
		,MONEYID='".$MONEYID."' 
		,AWARDS='".$AWARDS."' 	
		,STAGEPRICES='".$STAGEPRICES."' 
		,PRESHOW='".$PRESHOW."' 
		,`DESC`='".$DESC."' 
		 where INDEXID='".$indexid."' and OBJID='".$objid."'";
	else if($isadd == 3)
			$sql = "delete from `SHOPLIMIT` where INDEXID= '".$indexid."' and OBJID='".$objid."'";
	else if($isadd == 4)
		$sql = "delete from `SHOPLIMIT`";
        elseif($isadd == 5)
                $sql = "delete from `SHOPLIMIT` where OBJID='".$objid."'";

        
		
	errlog($sql);
	echo $sql;
	$result = mysql_query($sql,$con)
	    or die("Invalid query: " . mysql_error());
  echo "$i 服操作限购物品成功\n\t";
}

$zone3= intval(getParam("sp_zone3","1"));
$zone4= intval(getParam("sp_zone4","1"));
if($zone3>0 && $zone4>0 && $zone3<=$zone4 && $isadd!=4) {
    for ($i = $zone3; $i <= $zone4; $i++) {
          $cfg = getIndexCfg($i);
        $dburl = getDBUrl($i);
        if (!$dburl)
            continue;
        $con = mysql_connect($dburl, $DB_USER, $DB_PASS);
        if (!$con) {
            die("mysql connect error");
        }
        mysql_query("set names 'utf8'");
        mysql_select_db($cfg['mysql_dbName'], $con) or die("mysql select db error" . mysql_error());
        mysql_query("set names 'utf8'", $con);
        //	mysql_query("SET NAMES utf8",$con);
        if ($isadd == 1)
            $sql = "insert into `SHOPLIMIT`(ICON,TITLE,WEIGHT,PAGE,INDEXID,OBJID,OBJNAME,MONEYTYPE,ORIGINALPRICE,DISCONTPRICE,ISBIND,SINGLECANBUYNUM,TOTALBUYLIMITNUM,OPENTYPE,OPENTTIME,CLOSETIME,LIMITEDPURCHASESTARTTIME,LIMITEDPURCHASEENDTIME,TAGTYPE,NEEDVIPLEVEL,MONEYID,AWARDS,STAGEPRICES,PRESHOW,`DESC`) 
		values('".$tabIcon."','".$tabTitle."','".$weight."','".$page."','".$uniqid."','".$objid."','".$objname."','".$moneytype."','".$originalprice."','".$discontprice."','".$isbind."','".$singlecanbuynum."',	'".$totalbuylimitnum."'
		,'".$opentype."','".$openttime."','".$closetime."','".$limitedpurchasestarttime."','".$limitedpurchaseendtime."','".$tagtype."','".$needviplevel."','".$MONEYID."','".$AWARDS."','".$STAGEPRICES."','".$PRESHOW."','".$DESC."')";
        else if ($isadd == 2)
            $sql = "update `SHOPLIMIT` 
		set PAGE='" . $page . "'
		,OBJID='" . $objid . "'
		,OBJNAME='" . $objname . "'
		,SUPERMARKETPOS='" . $supermarketpos . "'
		,MONEYTYPE='" . $moneytype . "'
		,ORIGINALPRICE='" . $originalprice . "'
		,DISCONTPRICE='" . $discontprice . "'
		,ISBIND='" . $isbind . "'
		,SINGLECANBUYNUM='" . $singlecanbuynum . "'
		,TOTALBUYLIMITNUM='" . $totalbuylimitnum . "'
		,OPENTYPE='" . $opentype . "'
		,OPENTTIME='" . $openttime . "'
		,CLOSETIME='" . $closetime . "'
		,LIMITEDPURCHASESTARTTIME='" . $limitedpurchasestarttime . "'
		,LIMITEDPURCHASEENDTIME='" . $limitedpurchaseendtime . "'
		,TAGTYPE='" . $tagtype . "'
		,NEEDVIPLEVEL='" . $needviplevel . "'
		,ICON='" . $tabIcon . "' 
		,TITLE='" . $tabTitle . "' 	
		,WEIGHT='" . $weight . "' 
		,MONEYID='".$MONEYID."' 
		,AWARDS='".$AWARDS."' 	
		,STAGEPRICES='".$STAGEPRICES."' 
		,PRESHOW='".$PRESHOW."' 
		,`DESC`='".$DESC."' 
		 where INDEXID='" . $indexid . "' and OBJID='" . $objid . "'";
        else if ($isadd == 3)
            $sql = "delete from `SHOPLIMIT` where INDEXID= '" . $indexid . "' and OBJID='" . $objid . "'";
        else if ($isadd == 4)
            $sql = "delete from `SHOPLIMIT`";
        elseif ($isadd == 5)
            $sql = "delete from `SHOPLIMIT` where OBJID='" . $objid . "'";

        errlog($sql);
        echo $sql;
        $result = mysql_query($sql, $con)
        or die("Invalid query: " . mysql_error());
        echo "$i 服操作限购物品成功\n\t";
    }
}

$zone5= intval(getParam("sp_zone5","1"));
$zone6= intval(getParam("sp_zone6","1"));
if($zone5>0 && $zone6>0 && $zone5<=$zone6 && $isadd!=4){
    for ($i=$zone5; $i<=$zone6; $i++)
    {
        $cfg = getIndexCfg($i);
        $dburl = getDBUrl($i);
        if(!$dburl)
            continue;
        $con = mysql_connect($dburl,$DB_USER,$DB_PASS);
        if(!$con )
        {
            die("mysql connect error");
        }
        mysql_query("set names 'utf8'");
        mysql_select_db($cfg['mysql_dbName'], $con) or die("mysql select db error". mysql_error());
        mysql_query("set names 'utf8'",$con);
        //	mysql_query("SET NAMES utf8",$con);
        if ($isadd == 1)
            $sql = "insert into `SHOPLIMIT`(ICON,TITLE,WEIGHT,PAGE,INDEXID,OBJID,OBJNAME,MONEYTYPE,ORIGINALPRICE,DISCONTPRICE,ISBIND,SINGLECANBUYNUM,TOTALBUYLIMITNUM,OPENTYPE,OPENTTIME,CLOSETIME,LIMITEDPURCHASESTARTTIME,LIMITEDPURCHASEENDTIME,TAGTYPE,NEEDVIPLEVEL,MONEYID,AWARDS,STAGEPRICES,PRESHOW,`DESC`) 
		values('".$tabIcon."','".$tabTitle."','".$weight."','".$page."','".$uniqid."','".$objid."','".$objname."','".$moneytype."','".$originalprice."','".$discontprice."','".$isbind."','".$singlecanbuynum."',	'".$totalbuylimitnum."'
		,'".$opentype."','".$openttime."','".$closetime."','".$limitedpurchasestarttime."','".$limitedpurchaseendtime."','".$tagtype."','".$needviplevel."','".$MONEYID."','".$AWARDS."','".$STAGEPRICES."','".$PRESHOW."','".$DESC."')";
        else if($isadd == 2)
            $sql = "update `SHOPLIMIT` 
		set PAGE='".$page."'
		,OBJID='".$objid."'
		,OBJNAME='".$objname."'
		,SUPERMARKETPOS='".$supermarketpos."'
		,MONEYTYPE='".$moneytype."'
		,ORIGINALPRICE='".$originalprice."'
		,DISCONTPRICE='".$discontprice."'
		,ISBIND='".$isbind."'
		,SINGLECANBUYNUM='".$singlecanbuynum."'
		,TOTALBUYLIMITNUM='".$totalbuylimitnum."'
		,OPENTYPE='".$opentype."'
		,OPENTTIME='".$openttime."'
		,CLOSETIME='".$closetime."'
		,LIMITEDPURCHASESTARTTIME='".$limitedpurchasestarttime."'
		,LIMITEDPURCHASEENDTIME='".$limitedpurchaseendtime."'
		,TAGTYPE='".$tagtype."'
		,NEEDVIPLEVEL='".$needviplevel."'
		,ICON='".$tabIcon."' 
		,TITLE='".$tabTitle."' 	
		,WEIGHT='".$weight."' 
		,MONEYID='".$MONEYID."' 
		,AWARDS='".$AWARDS."' 	
		,STAGEPRICES='".$STAGEPRICES."' 
		,PRESHOW='".$PRESHOW."' 
		,`DESC`='".$DESC."' 
		 where INDEXID='".$indexid."' and OBJID='".$objid."'";
        else if($isadd == 3)
            $sql = "delete from `SHOPLIMIT` where INDEXID= '".$indexid."' and OBJID='".$objid."'";
        else if($isadd == 4)
            $sql = "delete from `SHOPLIMIT`";
        elseif($isadd == 5)
            $sql = "delete from `SHOPLIMIT` where OBJID='".$objid."'";

        errlog($sql);
        echo $sql;
        $result = mysql_query($sql,$con)
        or die("Invalid query: " . mysql_error());
        echo "$i 服操作限购物品成功\n\t";
    }
}


$zone7= intval(getParam("sp_zone7","1"));
$zone8= intval(getParam("sp_zone8","1"));
if($zone7>0 && $zone8>0 && $zone7<=$zone8 && $isadd!=4){
    for ($i=$zone7; $i<=$zone8; $i++)
    {
        $cfg = getIndexCfg($i);
        $dburl = getDBUrl($i);
        if(!$dburl)
            continue;
        $con = mysql_connect($dburl,$DB_USER,$DB_PASS);
        if(!$con )
        {
            die("mysql connect error");
        }
        mysql_query("set names 'utf8'");
        mysql_select_db($cfg['mysql_dbName'], $con) or die("mysql select db error". mysql_error());
        mysql_query("set names 'utf8'",$con);
        //	mysql_query("SET NAMES utf8",$con);
        if ($isadd == 1)
            $sql = "insert into `SHOPLIMIT`(ICON,TITLE,WEIGHT,PAGE,INDEXID,OBJID,OBJNAME,MONEYTYPE,ORIGINALPRICE,DISCONTPRICE,ISBIND,SINGLECANBUYNUM,TOTALBUYLIMITNUM,OPENTYPE,OPENTTIME,CLOSETIME,LIMITEDPURCHASESTARTTIME,LIMITEDPURCHASEENDTIME,TAGTYPE,NEEDVIPLEVEL,MONEYID,AWARDS,STAGEPRICES,PRESHOW,`DESC`) 
		values('".$tabIcon."','".$tabTitle."','".$weight."','".$page."','".$uniqid."','".$objid."','".$objname."','".$moneytype."','".$originalprice."','".$discontprice."','".$isbind."','".$singlecanbuynum."',	'".$totalbuylimitnum."'
		,'".$opentype."','".$openttime."','".$closetime."','".$limitedpurchasestarttime."','".$limitedpurchaseendtime."','".$tagtype."','".$needviplevel."','".$MONEYID."','".$AWARDS."','".$STAGEPRICES."','".$PRESHOW."','".$DESC."')";
        else if($isadd == 2)
            $sql = "update `SHOPLIMIT` 
		set PAGE='".$page."'
		,OBJID='".$objid."'
		,OBJNAME='".$objname."'
		,SUPERMARKETPOS='".$supermarketpos."'
		,MONEYTYPE='".$moneytype."'
		,ORIGINALPRICE='".$originalprice."'
		,DISCONTPRICE='".$discontprice."'
		,ISBIND='".$isbind."'
		,SINGLECANBUYNUM='".$singlecanbuynum."'
		,TOTALBUYLIMITNUM='".$totalbuylimitnum."'
		,OPENTYPE='".$opentype."'
		,OPENTTIME='".$openttime."'
		,CLOSETIME='".$closetime."'
		,LIMITEDPURCHASESTARTTIME='".$limitedpurchasestarttime."'
		,LIMITEDPURCHASEENDTIME='".$limitedpurchaseendtime."'
		,TAGTYPE='".$tagtype."'
		,NEEDVIPLEVEL='".$needviplevel."'
		,ICON='".$tabIcon."' 
		,TITLE='".$tabTitle."' 	
		,WEIGHT='".$weight."' 
		,MONEYID='".$MONEYID."' 
		,AWARDS='".$AWARDS."' 	
		,STAGEPRICES='".$STAGEPRICES."' 
		,PRESHOW='".$PRESHOW."' 
		,`DESC`='".$DESC."' 
		 where INDEXID='".$indexid."' and OBJID='".$objid."'";
        else if($isadd == 3)
            $sql = "delete from `SHOPLIMIT` where INDEXID= '".$indexid."' and OBJID='".$objid."'";
        else if($isadd == 4)
            $sql = "delete from `SHOPLIMIT`";
        elseif($isadd == 5)
            $sql = "delete from `SHOPLIMIT` where OBJID='".$objid."'";

        errlog($sql);
        echo $sql;
        $result = mysql_query($sql,$con)
        or die("Invalid query: " . mysql_error());
        echo "$i 服操作限购物品成功\n\t";
    }
}

$zone9= intval(getParam("sp_zone9","1"));
$zone10= intval(getParam("sp_zone10","1"));
if($zone9>0 && $zone10>0 && $zone9<=$zone10 && $isadd!=4){
    for ($i=$zone9; $i<=$zone10; $i++)
    {
        $cfg = getIndexCfg($i);
        $dburl = getDBUrl($i);
        if(!$dburl)
            continue;
        $con = mysql_connect($dburl,$DB_USER,$DB_PASS);
        if(!$con )
        {
            die("mysql connect error");
        }
        mysql_query("set names 'utf8'");
        mysql_select_db($cfg['mysql_dbName'], $con) or die("mysql select db error". mysql_error());
        mysql_query("set names 'utf8'",$con);
        //	mysql_query("SET NAMES utf8",$con);
        if ($isadd == 1)
            $sql = "insert into `SHOPLIMIT`(ICON,TITLE,WEIGHT,PAGE,INDEXID,OBJID,OBJNAME,MONEYTYPE,ORIGINALPRICE,DISCONTPRICE,ISBIND,SINGLECANBUYNUM,TOTALBUYLIMITNUM,OPENTYPE,OPENTTIME,CLOSETIME,LIMITEDPURCHASESTARTTIME,LIMITEDPURCHASEENDTIME,TAGTYPE,NEEDVIPLEVEL,MONEYID,AWARDS,STAGEPRICES,PRESHOW,`DESC`) 
		values('".$tabIcon."','".$tabTitle."','".$weight."','".$page."','".$uniqid."','".$objid."','".$objname."','".$moneytype."','".$originalprice."','".$discontprice."','".$isbind."','".$singlecanbuynum."',	'".$totalbuylimitnum."'
		,'".$opentype."','".$openttime."','".$closetime."','".$limitedpurchasestarttime."','".$limitedpurchaseendtime."','".$tagtype."','".$needviplevel."','".$MONEYID."','".$AWARDS."','".$STAGEPRICES."','".$PRESHOW."','".$DESC."')";
        else if($isadd == 2)
            $sql = "update `SHOPLIMIT` 
		set PAGE='".$page."'
		,OBJID='".$objid."'
		,OBJNAME='".$objname."'
		,SUPERMARKETPOS='".$supermarketpos."'
		,MONEYTYPE='".$moneytype."'
		,ORIGINALPRICE='".$originalprice."'
		,DISCONTPRICE='".$discontprice."'
		,ISBIND='".$isbind."'
		,SINGLECANBUYNUM='".$singlecanbuynum."'
		,TOTALBUYLIMITNUM='".$totalbuylimitnum."'
		,OPENTYPE='".$opentype."'
		,OPENTTIME='".$openttime."'
		,CLOSETIME='".$closetime."'
		,LIMITEDPURCHASESTARTTIME='".$limitedpurchasestarttime."'
		,LIMITEDPURCHASEENDTIME='".$limitedpurchaseendtime."'
		,TAGTYPE='".$tagtype."'
		,NEEDVIPLEVEL='".$needviplevel."'
		,ICON='".$tabIcon."' 
		,TITLE='".$tabTitle."' 	
		,WEIGHT='".$weight."' 
		,MONEYID='".$MONEYID."' 
		,AWARDS='".$AWARDS."' 	
		,STAGEPRICES='".$STAGEPRICES."' 
		,PRESHOW='".$PRESHOW."' 
		,`DESC`='".$DESC."' 
		 where INDEXID='".$indexid."' and OBJID='".$objid."'";
        else if($isadd == 3)
            $sql = "delete from `SHOPLIMIT` where INDEXID= '".$indexid."' and OBJID='".$objid."'";
        else if($isadd == 4)
            $sql = "delete from `SHOPLIMIT`";
        elseif($isadd == 5)
            $sql = "delete from `SHOPLIMIT` where OBJID='".$objid."'";

        errlog($sql);
        echo $sql;
        $result = mysql_query($sql,$con)
        or die("Invalid query: " . mysql_error());
        echo "$i 服操作限购物品成功\n\t";
    }
}
