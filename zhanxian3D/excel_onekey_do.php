<?php 
ini_set('display_errors', '1');
date_default_timezone_set("PRC");
ini_set('default_charset','utf-8');
include_once "common.php";
include_once "checklogin.php";
include_once "upgrade.php";
include_once "db2new.php";
include_once "newweb/h_header.php";

$zone1= intval(getParam("onek_zone1",""));
$kaifu_time = getParam("kaifu_time","");

$_dburl=getDBUrl($zone1); 
$_dbname=getDBName($zone1); 
error_reporting (0);
if(!$_dburl)
{
    echo "选择服务器错误";
}
$con = mysql_connect($_dburl,$DB_USER,$DB_PASS);
if(!$con )
{
    die("mysql connect error");
}

mysql_query("set names 'utf8'");
mysql_select_db($_dbname, $con) or die("mysql select db error". mysql_error()); 
mysql_query("SET NAMES utf8");

$get_conf_datas = get_conf_datas();

if($zone1 && $kaifu_time)
{

    $sql1 = "INSERT INTO `GAMEACTIVITY` 
    (`TYPE`,  `ACTIVITYID`, `TIMESTART`, `TIMEEND`, `TIMEEXPIRE`,  `ICONID`, `TABID`, `TABINFO`, `TABTYPE`, `PICID`, `PICINFO`, `PLATID`, `PLANID`, `PLANINFO`, `WEEK`, `STATUS` ) 
    VALUES ";

    $sqlvalues = '';
    foreach ($get_conf_datas as $key => $value)
    {
        $sqlvalues .= "( '".$value['TYPE']."','".$value['ACTIVITYID']."',UNIX_TIMESTAMP('".kfadddayymd($kaifu_time ,$value['TIMESTART'])."'),UNIX_TIMESTAMP('".kfadddayymd($kaifu_time ,$value['TIMEEND']+1)."')-1, UNIX_TIMESTAMP('".
            kfadddayymd($kaifu_time ,$value['TIMEEXPIRE']+1)."')-1,'".$value['ICONID']."','".$value['TABID']."','".$value['TABINFO']."','".$value['TABTYPE']."','".
            $value['PICID']."','".$value['PICINFO']."','".$value['PLATID']."', '0','','127','0' ),"; 
    }

    if($sqlvalues == '') exit('配置为空');

    $sqlvalues = trim($sqlvalues,',');

    $sql1 .= $sqlvalues;

    $flag1=mysql_query($sql1).mysql_error();
    
    if($flag1)
    {
        echo "ok";
    }else{
        echo "error 联系洪涛";
    }

}else{
    echo '请填全日期';
}

function kfadddayymd($kf_ymd,$addday)
{
    if(!$kf_ymd) exit;

    $kf_ymd_stamp = strtotime($kf_ymd);
    $kf_ymd_0 = date('Y-m-d',$kf_ymd_stamp);
    $kf_0_stamp = strtotime($kf_ymd_0);

    $return_ymd_stamp = $kf_0_stamp + ($addday-1) * 86400;
    $return_ymd = date("Y-m-d H:i:s",$return_ymd_stamp);

    return $return_ymd;
}

function get_conf_datas()
{
    $dbh = new PDO('mysql:host='.FT_MYSQL_COMMON_HOST.';dbname='.FT_MYSQL_SHOUYOU_DBNAME.';port='.FT_MYSQL_COMMON_PORT.';charset=utf8', FT_MYSQL_COMMON_ROOT, FT_MYSQL_COMMON_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->query("SET NAMES UTF8");

    $sql = "SELECT TYPE,ACTIVITYID,TIMESTART,TIMEEND,TIMEEXPIRE,ICONID,TABID,TABINFO,TABTYPE,PICID,PICINFO,PLATID FROM sy_onekey_confs";

    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    $return = $stmt->fetchAll();

    return $return;
}
