<?php 
ini_set('display_errors', '1');
error_reporting (-1);
date_default_timezone_set("PRC");
ini_set('default_charset','utf-8');
include_once "common.php";
include_once "checklogin.php";
include_once "upgrade.php";
include_once "db2new.php";
$zone1= intval(getParam("onek_zone1",""));
$kaifu_time = getParam("kaifu_time","");
$kf_ymd_1 = kfadddayymd($kaifu_time,1);
$kf_ymd_2 = kfadddayymd($kaifu_time,2);
$kf_ymd_3 = kfadddayymd($kaifu_time,3);
$kf_ymd_4 = kfadddayymd($kaifu_time,4);
$kf_ymd_5 = kfadddayymd($kaifu_time,5);
$kf_ymd_6 = kfadddayymd($kaifu_time,6);
$kf_ymd_7 = kfadddayymd($kaifu_time,7);
$kf_ymd_8 = kfadddayymd($kaifu_time,8);
$kf_ymd_9 = kfadddayymd($kaifu_time,9);
$kf_ymd_10 = kfadddayymd($kaifu_time,10);
$kf_ymd_11 = kfadddayymd($kaifu_time,11);
$kf_ymd_12 = kfadddayymd($kaifu_time,12);
$kf_ymd_15 = kfadddayymd($kaifu_time,15);
$kf_ymd_17 = kfadddayymd($kaifu_time,17);
$kf_ymd_13 = kfadddayymd($kaifu_time,13);
$kf_ymd_14 = kfadddayymd($kaifu_time,14);
$kf_ymd_16 = kfadddayymd($kaifu_time,16);
$kf_ymd_19 = kfadddayymd($kaifu_time,19);
$kf_ymd_31 = kfadddayymd($kaifu_time,31);
$kf_ymd_78 = kfadddayymd($kaifu_time,78);
$kf_ymd_61 = kfadddayymd($kaifu_time,61);
$kf_ymd_183 = kfadddayymd($kaifu_time,183);


$_dburl=getDBUrl($zone1); 
$_dbname=getDBName($zone1); 
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

if($zone1 && $kaifu_time)
{

    $desc1 = "<p align='center'><font color='#FFFF00' size='23'>坐騎時裝限時兌換</font></p><br>  <font size='18'>活動期間可用幽靈虎繩碎片道具兌換幽靈虎坐騎時裝，坐騎時裝不但使你的坐騎外形更加酷，還可以大幅度提高你的神通戰力！</font><br> <font size='18'>機會難得，不容錯過哦~</font><br>";
    $desc1 = mysql_real_escape_string($desc1);

    $sql1 = "INSERT INTO `GAMEACTIVITY` 
    (`TYPE`,  `ACTIVITYID`, `TIMESTART`, `TIMEEND`, `TIMEEXPIRE`,  `ICONID`, `TABID`, `TABINFO`, `TABTYPE`, `PICID`, `PICINFO`, `PLATID`, `PLANID`, `PLANINFO`, `WEEK`, `STATUS` ) 
    VALUES 
    ('1',  '14', UNIX_TIMESTAMP('{$kf_ymd_1}'), UNIX_TIMESTAMP('{$kf_ymd_16}')-1, UNIX_TIMESTAMP('{$kf_ymd_16}')-1, 'qirihuodong', '0', '0', '0', '0', '',      '0', '0', '', '127', '0' ),
    ('1',  '15', UNIX_TIMESTAMP('{$kf_ymd_1}'), UNIX_TIMESTAMP('{$kf_ymd_16}')-1, UNIX_TIMESTAMP('{$kf_ymd_16}')-1, 'qirihuodong', '0', '0', '0', '0', '',      '0', '0', '', '127', '0' ),
    ('1',  '16', UNIX_TIMESTAMP('{$kf_ymd_1}'), UNIX_TIMESTAMP('{$kf_ymd_16}')-1, UNIX_TIMESTAMP('{$kf_ymd_16}')-1, 'qirihuodong', '0', '0', '0', '0', '',      '0', '0', '', '127', '0' ),
    ('1',  '17', UNIX_TIMESTAMP('{$kf_ymd_1}'), UNIX_TIMESTAMP('{$kf_ymd_16}')-1, UNIX_TIMESTAMP('{$kf_ymd_16}')-1, 'qirihuodong', '0', '0', '0', '0', '',      '0', '0', '', '127', '0' ),
    ('1',  '18', UNIX_TIMESTAMP('{$kf_ymd_1}'), UNIX_TIMESTAMP('{$kf_ymd_16}')-1, UNIX_TIMESTAMP('{$kf_ymd_16}')-1, 'qirihuodong', '0', '0', '0', '0', '',      '0', '0', '', '127', '0' ),
    ('1',  '19', UNIX_TIMESTAMP('{$kf_ymd_1}'), UNIX_TIMESTAMP('{$kf_ymd_16}')-1, UNIX_TIMESTAMP('{$kf_ymd_16}')-1, 'qirihuodong', '0', '0', '0', '0', '',      '0', '0', '', '127', '0' ),
    ('1',  '20', UNIX_TIMESTAMP('{$kf_ymd_1}'), UNIX_TIMESTAMP('{$kf_ymd_16}')-1, UNIX_TIMESTAMP('{$kf_ymd_16}')-1, 'qirihuodong', '0', '0', '0', '0', '',      '0', '0', '', '127', '0' ),
    ('27',  '5', UNIX_TIMESTAMP('{$kf_ymd_1}'), UNIX_TIMESTAMP('{$kf_ymd_16}')-1, UNIX_TIMESTAMP('{$kf_ymd_16}')-1, 'qirihuodong', '0', '0', '0', '0', '',      '0', '0', '', '127', '0' ),
    ('27',  '28', UNIX_TIMESTAMP('{$kf_ymd_1}'), UNIX_TIMESTAMP('{$kf_ymd_16}')-1, UNIX_TIMESTAMP('{$kf_ymd_16}')-1, 'qirihuodong', '0', '0', '0', '0', '',      '0', '0', '', '127', '0' ),
    ('27',  '35', UNIX_TIMESTAMP('{$kf_ymd_1}'), UNIX_TIMESTAMP('{$kf_ymd_16}')-1, UNIX_TIMESTAMP('{$kf_ymd_16}')-1, 'qirihuodong', '0', '0', '0', '0', '',      '0', '0', '', '127', '0' ),
    ('27',  '37', UNIX_TIMESTAMP('{$kf_ymd_1}'), UNIX_TIMESTAMP('{$kf_ymd_16}')-1, UNIX_TIMESTAMP('{$kf_ymd_16}')-1, 'qirihuodong', '0', '0', '0', '0', '',      '0', '0', '', '127', '0' ),
    ('27',  '41', UNIX_TIMESTAMP('{$kf_ymd_1}'), UNIX_TIMESTAMP('{$kf_ymd_16}')-1, UNIX_TIMESTAMP('{$kf_ymd_16}')-1, 'qirihuodong', '0', '0', '0', '0', '',      '0', '0', '', '127', '0' ),
    ('27',  '42', UNIX_TIMESTAMP('{$kf_ymd_1}'), UNIX_TIMESTAMP('{$kf_ymd_16}')-1, UNIX_TIMESTAMP('{$kf_ymd_16}')-1, 'qirihuodong', '0', '0', '0', '0', '',      '0', '0', '', '127', '0' ),
    ('27',  '44', UNIX_TIMESTAMP('{$kf_ymd_1}'), UNIX_TIMESTAMP('{$kf_ymd_16}')-1, UNIX_TIMESTAMP('{$kf_ymd_16}')-1, 'qirihuodong', '0', '0', '0', '0', '',      '0', '0', '', '127', '0' ),
    ('27',  '45', UNIX_TIMESTAMP('{$kf_ymd_1}'), UNIX_TIMESTAMP('{$kf_ymd_16}')-1, UNIX_TIMESTAMP('{$kf_ymd_16}')-1, 'qirihuodong', '0', '0', '0', '0', '',      '0', '0', '', '127', '0' ),
    ('27',  '46', UNIX_TIMESTAMP('{$kf_ymd_1}'), UNIX_TIMESTAMP('{$kf_ymd_16}')-1, UNIX_TIMESTAMP('{$kf_ymd_16}')-1, 'qirihuodong', '0', '0', '0', '0', '',      '0', '0', '', '127', '0' ),
    ('27',  '47', UNIX_TIMESTAMP('{$kf_ymd_1}'), UNIX_TIMESTAMP('{$kf_ymd_16}')-1, UNIX_TIMESTAMP('{$kf_ymd_16}')-1, 'qirihuodong', '0', '0', '0', '0', '',      '0', '0', '', '127', '0' ),
    ('27',  '48', UNIX_TIMESTAMP('{$kf_ymd_1}'), UNIX_TIMESTAMP('{$kf_ymd_16}')-1, UNIX_TIMESTAMP('{$kf_ymd_16}')-1, 'qirihuodong', '0', '0', '0', '0', '',      '0', '0', '', '127', '0' ),
    ('27',  '49', UNIX_TIMESTAMP('{$kf_ymd_1}'), UNIX_TIMESTAMP('{$kf_ymd_16}')-1, UNIX_TIMESTAMP('{$kf_ymd_16}')-1, 'qirihuodong', '0', '0', '0', '0', '',      '0', '0', '', '127', '0' ),
    ('27',  '50', UNIX_TIMESTAMP('{$kf_ymd_1}'), UNIX_TIMESTAMP('{$kf_ymd_16}')-1, UNIX_TIMESTAMP('{$kf_ymd_16}')-1, 'qirihuodong', '0', '0', '0', '0', '',      '0', '0', '', '127', '0' ),
    ('27',  '51', UNIX_TIMESTAMP('{$kf_ymd_1}'), UNIX_TIMESTAMP('{$kf_ymd_16}')-1, UNIX_TIMESTAMP('{$kf_ymd_16}')-1, 'qirihuodong', '0', '0', '0', '0', '',      '0', '0', '', '127', '0' ),
    ('45',  '2', UNIX_TIMESTAMP('{$kf_ymd_1}'), UNIX_TIMESTAMP('{$kf_ymd_16}')-1, UNIX_TIMESTAMP('{$kf_ymd_16}')-1, 'qirihuodong', '0', '0', '0', '0', '',      '0', '0', '', '127', '0' ),
    ('29',  '1002', UNIX_TIMESTAMP('{$kf_ymd_1}'), UNIX_TIMESTAMP('{$kf_ymd_61}')-1, UNIX_TIMESTAMP('{$kf_ymd_61}')-1, 'chongzhihuodong', '0', '成长基金', '1', '0', '',      '0', '0', '', '127', '0' ),
    ('1',  '2', UNIX_TIMESTAMP('{$kf_ymd_1}'), UNIX_TIMESTAMP('{$kf_ymd_15}')-1, UNIX_TIMESTAMP('{$kf_ymd_15}')-1, 'chongzhihuodong', '0', '酷炫羽翼', '2', '0', '',      '0', '0', '', '127', '0' ),
    ('37',  '4', UNIX_TIMESTAMP('{$kf_ymd_1}'), UNIX_TIMESTAMP('{$kf_ymd_8}')-1, UNIX_TIMESTAMP('{$kf_ymd_10}')-1, 'chongzhihuodong', '0', '消费排行榜', '3', '0', '',      '0', '0', '', '127', '0' ),
    ('14',  '3555', UNIX_TIMESTAMP('{$kf_ymd_1}'), UNIX_TIMESTAMP('{$kf_ymd_8}')-1, UNIX_TIMESTAMP('{$kf_ymd_8}')-1, 'chongzhihuodong', '0', '累计消费', '10', '0', '',      '0', '0', '', '127', '0' ),
    ('37',  '13', UNIX_TIMESTAMP('{$kf_ymd_1}'), UNIX_TIMESTAMP('{$kf_ymd_2}')-1, UNIX_TIMESTAMP('{$kf_ymd_2}')-1, 'chongzhihuodong', '0', '今日充值排行', '6', '0', '',      '0', '0', '', '127', '0' ),
    ('37',  '13', UNIX_TIMESTAMP('{$kf_ymd_2}'), UNIX_TIMESTAMP('{$kf_ymd_3}')-1, UNIX_TIMESTAMP('{$kf_ymd_3}')-1, 'chongzhihuodong', '0', '今日充值排行', '6', '0', '',      '0', '0', '', '127', '0' ),
    ('37',  '13', UNIX_TIMESTAMP('{$kf_ymd_3}'), UNIX_TIMESTAMP('{$kf_ymd_4}')-1, UNIX_TIMESTAMP('{$kf_ymd_4}')-1, 'chongzhihuodong', '0', '今日充值排行', '6', '0', '',      '0', '0', '', '127', '0' ),
    ('37',  '13', UNIX_TIMESTAMP('{$kf_ymd_4}'), UNIX_TIMESTAMP('{$kf_ymd_5}')-1, UNIX_TIMESTAMP('{$kf_ymd_5}')-1, 'chongzhihuodong', '0', '今日充值排行', '6', '0', '',      '0', '0', '', '127', '0' ),
    ('37',  '13', UNIX_TIMESTAMP('{$kf_ymd_5}'), UNIX_TIMESTAMP('{$kf_ymd_6}')-1, UNIX_TIMESTAMP('{$kf_ymd_6}')-1, 'chongzhihuodong', '0', '今日充值排行', '6', '0', '',      '0', '0', '', '127', '0' ),
    ('37',  '13', UNIX_TIMESTAMP('{$kf_ymd_6}'), UNIX_TIMESTAMP('{$kf_ymd_7}')-1, UNIX_TIMESTAMP('{$kf_ymd_7}')-1, 'chongzhihuodong', '0', '今日充值排行', '6', '0', '',      '0', '0', '', '127', '0' ),
    ('37',  '13', UNIX_TIMESTAMP('{$kf_ymd_7}'), UNIX_TIMESTAMP('{$kf_ymd_8}')-1, UNIX_TIMESTAMP('{$kf_ymd_8}')-1, 'chongzhihuodong', '0', '今日充值排行', '6', '0', '',      '0', '0', '', '127', '0' ),
    ('9',  '30294', UNIX_TIMESTAMP('{$kf_ymd_1}'), UNIX_TIMESTAMP('{$kf_ymd_2}')-1, UNIX_TIMESTAMP('{$kf_ymd_2}')-1, 'chongzhihuodong', '0', '首充豪礼', '4', '0', '',      '0', '0', '', '127', '0' ),
    ('9',  '30278', UNIX_TIMESTAMP('{$kf_ymd_1}'), UNIX_TIMESTAMP('{$kf_ymd_2}')-1, UNIX_TIMESTAMP('{$kf_ymd_2}')-1, 'chongzhihuodong', '0', '充值有礼', '5', '0', '',      '0', '0', '', '127', '0' ),
    ('9',  '30299', UNIX_TIMESTAMP('{$kf_ymd_2}'), UNIX_TIMESTAMP('{$kf_ymd_3}')-1, UNIX_TIMESTAMP('{$kf_ymd_3}')-1, 'chongzhihuodong', '0', '首充豪礼', '4', '0', '',      '0', '0', '', '127', '0' ),
    ('9',  '30279', UNIX_TIMESTAMP('{$kf_ymd_2}'), UNIX_TIMESTAMP('{$kf_ymd_3}')-1, UNIX_TIMESTAMP('{$kf_ymd_3}')-1, 'chongzhihuodong', '0', '充值有礼', '5', '0', '',      '0', '0', '', '127', '0' ),
    ('9',  '30295', UNIX_TIMESTAMP('{$kf_ymd_3}'), UNIX_TIMESTAMP('{$kf_ymd_4}')-1, UNIX_TIMESTAMP('{$kf_ymd_4}')-1, 'chongzhihuodong', '0', '首充豪礼', '4', '0', '',      '0', '0', '', '127', '0' ),
    ('9',  '30284', UNIX_TIMESTAMP('{$kf_ymd_3}'), UNIX_TIMESTAMP('{$kf_ymd_4}')-1, UNIX_TIMESTAMP('{$kf_ymd_4}')-1, 'chongzhihuodong', '0', '充值有礼', '5', '0', '',      '0', '0', '', '127', '0' ),
    ('9',  '30298', UNIX_TIMESTAMP('{$kf_ymd_4}'), UNIX_TIMESTAMP('{$kf_ymd_5}')-1, UNIX_TIMESTAMP('{$kf_ymd_5}')-1, 'chongzhihuodong', '0', '首充豪礼', '4', '0', '',      '0', '0', '', '127', '0' ),
    ('9',  '30283', UNIX_TIMESTAMP('{$kf_ymd_4}'), UNIX_TIMESTAMP('{$kf_ymd_5}')-1, UNIX_TIMESTAMP('{$kf_ymd_5}')-1, 'chongzhihuodong', '0', '充值有礼', '5', '0', '',      '0', '0', '', '127', '0' ),
    ('9',  '30296', UNIX_TIMESTAMP('{$kf_ymd_5}'), UNIX_TIMESTAMP('{$kf_ymd_6}')-1, UNIX_TIMESTAMP('{$kf_ymd_6}')-1, 'chongzhihuodong', '0', '首充豪礼', '4', '0', '',      '0', '0', '', '127', '0' ),
    ('9',  '30285', UNIX_TIMESTAMP('{$kf_ymd_5}'), UNIX_TIMESTAMP('{$kf_ymd_6}')-1, UNIX_TIMESTAMP('{$kf_ymd_6}')-1, 'chongzhihuodong', '0', '充值有礼', '5', '0', '',      '0', '0', '', '127', '0' ),
    ('9',  '30297', UNIX_TIMESTAMP('{$kf_ymd_6}'), UNIX_TIMESTAMP('{$kf_ymd_7}')-1, UNIX_TIMESTAMP('{$kf_ymd_7}')-1, 'chongzhihuodong', '0', '首充豪礼', '4', '0', '',      '0', '0', '', '127', '0' ),
    ('9',  '30288', UNIX_TIMESTAMP('{$kf_ymd_6}'), UNIX_TIMESTAMP('{$kf_ymd_7}')-1, UNIX_TIMESTAMP('{$kf_ymd_7}')-1, 'chongzhihuodong', '0', '充值有礼', '5', '0', '',      '0', '0', '', '127', '0' ),
    ('9',  '30295', UNIX_TIMESTAMP('{$kf_ymd_7}'), UNIX_TIMESTAMP('{$kf_ymd_8}')-1, UNIX_TIMESTAMP('{$kf_ymd_8}')-1, 'chongzhihuodong', '0', '首充豪礼', '4', '0', '',      '0', '0', '', '127', '0' ),
    ('9',  '30286', UNIX_TIMESTAMP('{$kf_ymd_7}'), UNIX_TIMESTAMP('{$kf_ymd_8}')-1, UNIX_TIMESTAMP('{$kf_ymd_8}')-1, 'chongzhihuodong', '0', '充值有礼', '5', '0', '',      '0', '0', '', '127', '0' )
    ";
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

