<?php
date_default_timezone_set("PRC");
ini_set('default_charset','utf-8'); 

include_once "checklogin.php";
include_once "upgrade.php";
include_once "newweb/h_header.php";

$action = isset($_GET['action']) ? $_GET['action'] : '';
$start_ymd_1 = isset($_POST['start_ymd_1'])?$_POST['start_ymd_1'] : '';
$start_ymd_2 = isset($_POST['start_ymd_2'])?$_POST['start_ymd_2'] : '';
$start_stamp_1 = strtotime($start_ymd_1);
$end_stamp_1 = strtotime($start_ymd_1) + 86399;
$start_stamp_2 = strtotime($start_ymd_2);
$end_stamp_2 = strtotime($start_ymd_2) + 86399;

if($action == 'online_nums' && $start_ymd_1 != '')
{
  $con = mysql_connect(FT_MYSQL_ZONE_MSG_HOST,FT_MYSQL_ZONE_MSG_ROOT,FT_MYSQL_ZONE_MSG_PASS) or exit("<font color='red'>error:数据库连接失败</font><br>".mysql_error());
  mysql_select_db(FT_MYSQL_ZONE_MSG_DBNAME,$con) or exit("<font color='red'>error:数据库选择失败</font><br>");

  mysql_set_charset('utf8');
  $sql = "SELECT * FROM `zone_msg` where zone_id <= 1000 ";
  $result = mysql_query($sql);
  mysql_close();
  $zones_arr = array();
  while ($row = mysql_fetch_assoc($result)) 
  {
    $zones_arr[] = $row;
  }

  //按库查在线======================================
  $lidu = 60*10;
  $max_point = 60*60*24 / $lidu;
  $online_num = array();
  $online_num_2 = array();
  for ($i=0; $i < $max_point; $i++) 
  {
    $online_num[$i] = 0; 
    $online_num_2[$i] = 0; 
  }

  foreach ($zones_arr as $key => $value) 
  {
    $online_arr = array();
    $online_arr_2 = array();
    $mysql_ip = $value['mysql_ip'];
    $mysql_port = $value['mysql_port'];
    $mysql_dbName = $value['mysql_dbName'];

    $con = mysql_connect($mysql_ip.":".$mysql_port,FT_MYSQL_COMMON_ROOT,FT_MYSQL_COMMON_PASS);
    mysql_select_db($mysql_dbName,$con) or exit("<font color='red'>error:数据库选择失败</font><br>");
    
    $sql = "SELECT ONLINE AS num,FLOOR((TIME-{$start_stamp_1}) / {$lidu}) AS flag FROM `GAMEONLINE` WHERE `TIME`>= '{$start_stamp_1}' AND `TIME`<='{$end_stamp_1}' GROUP BY flag ";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_assoc($result)) 
    {
      $online_arr[] = $row;
    }
    foreach ($online_arr as $k => $v) 
    {
      $online_num[$v['flag']] += $v['num'];
    }

    if(isset($_POST['start_ymd_2']) && $_POST['start_ymd_2'] != '')
    {
      $sql = "SELECT ONLINE AS num,FLOOR((TIME-{$start_stamp_2}) / {$lidu}) AS flag FROM `GAMEONLINE` WHERE `TIME`>= '{$start_stamp_2}' AND `TIME`<='{$end_stamp_2}' GROUP BY flag ";
      $result = mysql_query($sql);
      while ($row = mysql_fetch_assoc($result)) 
      {
        $online_arr_2[] = $row;
      }
      foreach ($online_arr_2 as $k => $v) 
      {
        $online_num_2[$v['flag']] += $v['num'];
      }
    }

    mysql_close();
  }
  

  $ck_datas = del_arr_end0empty($online_num);
  if($start_stamp_1 == strtotime(date('Y-m-d',time()))) array_pop($ck_datas);
  $db_datas = del_arr_end0empty($online_num_2);
  if($start_stamp_2 == strtotime(date('Y-m-d',time()))) array_pop($db_datas);
  $echojson_arr  = array('ck_datas'=>$ck_datas,'db_datas'=>$db_datas);
  echo json_encode($echojson_arr);

}

//common function 

function h_post_param($keyword, $default=null)
{
    $return_data=$default;
    if(isset($_POST[$keyword])){
        $return_data = SS($_POST[$keyword]);
    }
    return $return_data;
}

function h_get_param($keyword, $default=null)
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

/**
 * 去除数组后端0和''
 */
function del_arr_end0empty($arr)
{
  $arr = array_reverse($arr);
  $new_arr = array();
  foreach ($arr as $key => $value) 
  {
    if($value)
    {
      break;
    }else
    {
      unset($arr[$key]);
    }
  }
  $arr = array_reverse($arr);
  foreach ($arr as $key => $value) 
  {
    $new_arr[] = $value;
  }
  return $new_arr;
}

