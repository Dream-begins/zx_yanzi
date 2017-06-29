<?php
include_once "checklogin.php"; 
include_once "newweb/h_header.php";
error_reporting('0');
$admin_name = isset($_SESSION['xwusername']) ? $_SESSION['xwusername'] : '';
$con = mysql_connect(FT_MYSQL_ZONE_MSG_HOST.':'.FT_MYSQL_ZONE_MSG_PORT,FT_MYSQL_ZONE_MSG_ROOT,FT_MYSQL_ZONE_MSG_PASS) or exit('<script>$("#monitor_check").prop("checked",false);</script>'."<font color='red'>error:数据库连接失败</font><br>".mysql_error());
mysql_select_db(FT_MYSQL_ZONE_MSG_DBNAME,$con) or exit('<script>$("#monitor_check").prop("checked",false);</script>'."<font color='red'>error:数据库选择失败1</font><br>");
mysql_set_charset('utf8');
$sql = "SELECT `zone_id`,`zones` FROM `zone_msg`";
$result = mysql_query($sql);
$all_zong_id2zones = array();
$all_zong_id2zones_new = array();
while ($row = mysql_fetch_assoc($result)) 
{
  $all_zong_id2zones[] = $row;
}
$row = '';
$sql = '';
$result = '';
mysql_close();

foreach ($all_zong_id2zones as $key => $value) 
{
  $all_zong_id2zones_new[$value['zone_id']] = explode(',', $value['zones']);
}

/**
 * $zones_arr array(1,2,3,4)
 * $all_zong_id2zones_new array(20=>array(1,2,3),21=>array(4),22=>array(5,6,7))
 * zones_arr 前端区 real_zone 和服后服
 */
function zones_arr2real_zone($zones_arr,$all_zong_id2zones_new)
{
  //所有前端区 合并数组
  $real_zons_arr = array();

  //前段区 转 合并区
  $return_arr = array();
  foreach ($all_zong_id2zones_new as $key => $value) 
  {
    foreach ($value as $k => $v) 
    {
      if(in_array($v, $zones_arr))
      {
        $return_arr[$key] = $key;
      }
    }
  }

  if(count($return_arr) == 0)
  {
    echo "<font color='red'>区转服后 服数为0</font>";
    echo '<script>$("#monitor_check").prop("checked",false);</script>';
  }

  return $return_arr;
}

$con = mysql_connect(FT_MYSQL_COMMON_HOST,FT_MYSQL_COMMON_ROOT,FT_MYSQL_COMMON_PASS) or exit('<script>$("#monitor_check").prop("checked",false);</script>'."<font color='red'>error:数据库连接失败</font><br>".mysql_error());
mysql_select_db(FT_MYSQL_EXTGAMESERVER_DBNAME,$con) or exit('<script>$("#monitor_check").prop("checked",false);</script>'."<font color='red'>error:ExtGameServer数据库选择失败</font><br>");
mysql_set_charset('utf8');

$action = h_get_param('action','');

if($action == 'dogmcode')
{
  $gm_code  = trim(h_post_param("gm_code",''));
  $gm_zones = h_post_param("gm_zones",'');
  $zones_arr = str_format_arr_1($gm_zones);
  $zones_arr = zones_arr2real_zone($zones_arr,$all_zong_id2zones_new);
  if(!is_array($zones_arr))
  {
    die("<font color='red'>服务器参数不正确</font>");
  }
  if($gm_code == '')
  {
    exit("<font color='red'>GM指令不能为空</font>");
  }

  $FromIP   = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : ''; 
  $Command  = ltrim($gm_code,'/');
  $FlagId = time();
  $_SESSION['FlagId'] = $FlagId;
  $CmdLength = strlen($Command);
  $sql = 'INSERT INTO `GM_COMMAND`(`SN`,`FromIP`,`ServerID`,`Command`,`Status`,`OptTimeStamp`,`FlagId`,`CmdLength`,`ADMIN`) VALUES';
  $values = '';
  foreach ($zones_arr as $key => $value) 
  {
    $OptTimeStamp = time();
    $SN = md5(uniqid(rand(), true));
	$ServerID = $value;
	$values .= "('{$SN}','{$FromIP}','{$ServerID}','{$Command}','1','{$OptTimeStamp}','{$FlagId}','{$CmdLength}','{$admin_name}'),";
  }

  $values = trim($values,',');
  if($values != '')
  {
    $sql .= $values;
    $result_flag = mysql_query($sql) or die("<font color='red'>".mysql_error()."</font>");
    if($result_flag)
    {
      echo '命令入库成功 请等待后端执行。。。。。。</br>';
      echo 'FlagId：'.$FlagId;
      echo '<script>$("#FlagId").val('.$FlagId.')</script>';
    }
  }else{
    echo '<script>$("#FlagId").val("")</script>';
    echo '<script>$("#monitor_check").prop("checked",false);</script>';
  }



}

if($action == 'monitor_list')
{
  $result_arr = array();
  $FlagId = h_get_param('FlagId','');

  if($FlagId)
  {
    $sql = "SELECT * FROM `GM_COMMAND` WHERE `FlagId` = '{$FlagId}'";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_assoc($result)) 
    {
      $result_arr[] = $row;
    }
  }else{
    echo '<script>$("#monitor_check").prop("checked",false);</script>';
    return ;
  }

  $num_total = count($result_arr);
  $num_wait = 0;
  $num_running = 0;
  $num_done = 0;

  foreach ($result_arr as $key => $value) 
  {
    switch ($value['Status']) 
    {
      case '1':
          $num_wait++;
        break;
      case '2':
          $num_done++;
        break;
      case '3':
          $num_done++;
        break;
    }
  }

  echo "[FlagId:{$FlagId}]<br>";
  echo "总计:{$num_total},等待执行:{$num_wait},<font color='green'>执行完成:{$num_done}</font><br>";

  $result_message_arr = array();
  $message_arr_new = array();
  
  $sql = "select count(*) as num from `GM_RESULT_RECORD` WHERE `FlagId` = '{$FlagId}'";
  $result = mysql_query($sql);
  $row = mysql_fetch_assoc($result);
  $result_codes_num = $row['num']; 
 
  if($num_total>0 && $num_wait==0 && $result_codes_num == $num_total)
  {
    echo "========执行反馈如下========<br>";
    $sql = "SELECT * FROM `GM_RESULT_RECORD` WHERE `FlagId` = '{$FlagId}'";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_assoc($result)) 
    {
      $result_message_arr[] = $row;
    }

    foreach ($result_message_arr as $key => $value) 
    {
      $message_arr_new[$value['SN']] = $result_message_arr[$key];
    }

    foreach ($result_arr as $key => $value) 
    {
      $message_arr_new[$value['SN']]['RowID'] = $value['RowID'];
      $message_arr_new[$value['SN']]['FromIP'] = $value['FromIP'];
      $message_arr_new[$value['SN']]['ServerID'] = $value['ServerID'];
      $message_arr_new[$value['SN']]['Command'] = $value['Command'];
      $message_arr_new[$value['SN']]['Status'] = $value['Status'];
      $message_arr_new[$value['SN']]['OptTimeStamp'] = $value['OptTimeStamp'];
      $message_arr_new[$value['SN']]['FlagId'] = $value['FlagId'];
    }

    $zones_arr = array();
    foreach ($message_arr_new as $key => $value) 
    {
      if($value['ReturnCode'] == '1'|| $value['ReturnCode'] == '2' || $value['ReturnCode'] == '3'|| $value['ReturnCode'] == '-10001' || $value['ReturnCode'] == '-10002' )
      {
         $zones_arr[$value['ReturnCode']]['zones'][] =  $value['ServerID'];
         $zones_arr[$value['ReturnCode']]['ReturnCode'] =  $value['ReturnCode'];
         $zones_arr[$value['ReturnCode']]['ReturnInfo'] =  $value['ReturnInfo'];
      }else{
         echo "[区:".$value['ServerID']."] --  状态码:".$value['ReturnCode']." --  描述:".$value['ReturnInfo']."<br>";
   
      } 
    }

   foreach ($zones_arr as $key => $value)
    {  
      echo "[区:".format_num_arr($value['zones'])."] --  状态码:".$value['ReturnCode']." --  描述:".$value['ReturnInfo']."<br>";
    }

    echo '<script>$("#monitor_check").prop("checked",false);</script>';
    
  }else
  {
    echo "5秒后自动刷新当前命令监控状态。。。。。。"; 
    echo '<script>$("#monitor_check").prop("checked",true);</script>';
  }

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
 * @param string like '1,3,4-10,14'
 * @return array like array(1,3,4,5,6,7,8,9,10,12)
 */
function str_format_arr_1($in_str)
{
  $in_str = trim($in_str);
  $str_flag1 = explode(',',$in_str);  
  $return_arr = array();
  foreach ($str_flag1 as $key => $value) 
  {
    if(strpos($value,'-') !== false)
    {
      $value_flag1 = explode('-',$value);
      if(count($value_flag1) != 2 || strlen($value_flag1[0]) != strlen(intval($value_flag1[0])) || strlen($value_flag1[1]) != strlen(intval($value_flag1[1])))
      {
        return false;
      }else{
        for($i = $value_flag1[0];$i<=$value_flag1[1]; $i++)
        {
          $return_arr[] = $i;
        }
      }
    }else
    {
      $return_arr[] =trim($value);
    }
  }

  foreach ($return_arr as $key => $value) 
  {
    if( strlen($value) != strlen(intval($value)) )
    {
      return false;
    }
  }
  
  $value_counts = array_count_values($return_arr);
  foreach ($value_counts as $key => $value) 
  {
    if($value > 1)
    {
      return false;
    }
  }

  return $return_arr;
}

function format_num_arr($arr)
{
  if(!is_array($arr) || count($arr)<1) return '';
  sort($arr);
  
  $string = '';
  $new_arr = array();

  foreach ($arr as $key => $value) 
  {
    if($value-1 == $arr[$key -1])
    {
      $string .= "@".$value;
    }else{
      $string .= "#".$value;
    }
  }

  $new_arr = explode('#',$string);
  $new_str = '';
  foreach ($new_arr as $key => $value) 
  {
    $value_arr = explode('@',$value);
    if(count($value_arr) == 1)
    {
      $new_str .= ','.$value_arr[0];
    }elseif(count($value_arr) > 1)
    {
      $new_str .= ",".$value_arr[0]."-".array_pop($value_arr);
    }
  }

  $new_str = trim($new_str,',');
  return $new_str;
}

