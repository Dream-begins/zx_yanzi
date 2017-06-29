<?php
exit;
error_reporting(0);
include "h_header.php";
//----自定义参数
$ymd_7days_before = date('Y-m-d', strtotime('-7 days'));
$ymd_today = date('Y-m-d');
$token = "SGAW!EWD!S#%";
$now_time = time();
$return_arr = array();

//----获取参数
$ymd_start = get_param('ymd_start', $ymd_7days_before);
$ymd_end = get_param('ymd_end', $ymd_today);
$action = get_param('action','');
//----配置参数
$pwd = md5($token.$now_time);
$ymd_start_stamp = strtotime( $ymd_start );
$ymd_end_stamp = strtotime( $ymd_end ) + 86399;

//---- 执行逻辑
for ($i=$ymd_start_stamp; $i<=$ymd_end_stamp ; $i+=86400)
{
    $flag_ymd = date('Y-m-d', $i);
    $return_arr[$flag_ymd] = array(
        'ymd'=>$flag_ymd,
        'all_pay_money'=>'',
        'all_pay_user'=>'',
        'all_pay_arppu'=>'',
        'an_yyb_pay_money'=>'',
        'an_yyb_pay_user'=>'',
        'an_yyb_pay_arppu'=>'',
        'an_ly_pay_money'=>'',
        'an_ly_pay_user'=>'',
        'an_ly_pay_arppu'=>'',
        'ios_zb_pay_money'=>'',
        'ios_zb_pay_user'=>'',
        'ios_zb_pay_arppu'=>'',
        'all_zhuce'=>'',
        'an_yyb_zhuce'=>'',
        'an_ly_zhuce'=>'',
        'ios_zb_zhuce'=>'',
    );
}

//--应用宝 每日 付费额 付费人数
$url = "http://119.29.13.96:8085/int_common/int_all_pay_ymd.php?ymd_start=".$ymd_start."&ymd_end=".$ymd_end."&gettime=".$now_time."&getpwd=".$pwd;
$result = get_curl($url);
$result = json_decode($result,1);

foreach ($result as $key => $value) 
{
    $return_arr[$value['ymd']]['an_yyb_pay_money'] = $value['paymoney']/10;
    $return_arr[$value['ymd']]['an_yyb_pay_user'] = $value['payuser'];
    $return_arr[$value['ymd']]['an_yyb_pay_arppu'] = ($value['payuser'] > 0) ? number_format($value['paymoney']/10/$value['payuser'],2) : 0;

    $return_arr[$value['ymd']]['all_pay_money'] += $value['paymoney']/10;
    $return_arr[$value['ymd']]['all_pay_user'] += $value['payuser'];
}

//--联运 每日 付费额 付费人数
$url = "http://203.195.162.36:8085/int_common/int_all_pay_ymd.php?ymd_start=".$ymd_start."&ymd_end=".$ymd_end."&gettime=".$now_time."&getpwd=".$pwd;
$result = get_curl($url);
$result = json_decode($result,1);

foreach ($result as $key => $value) 
{
    $return_arr[$value['ymd']]['an_ly_pay_money'] = $value['paymoney']/100;
    $return_arr[$value['ymd']]['an_ly_pay_user'] = $value['payuser'];
    $return_arr[$value['ymd']]['an_ly_pay_arppu'] = ($value['payuser'] > 0) ? number_format($value['paymoney']/100/$value['payuser'],2) : 0;

    $return_arr[$value['ymd']]['all_pay_money'] += $value['paymoney']/100;
    $return_arr[$value['ymd']]['all_pay_user'] += $value['payuser'];
}

//--IOS正版 每日 付费额 付费人数
$url = "http://203.195.176.101:8085/int_common/int_all_pay_ymd.php?ymd_start=".$ymd_start."&ymd_end=".$ymd_end."&gettime=".$now_time."&getpwd=".$pwd;
$result = get_curl($url);
$result = json_decode($result,1);

foreach ($result as $key => $value) 
{
    $return_arr[$value['ymd']]['ios_zb_pay_money'] = $value['paymoney']/100;
    $return_arr[$value['ymd']]['ios_zb_pay_user'] = $value['payuser'];
    $return_arr[$value['ymd']]['ios_zb_pay_arppu'] = ($value['payuser'] > 0) ? number_format($value['paymoney']/100/$value['payuser'],2) : 0;

    $return_arr[$value['ymd']]['all_pay_money'] += $value['paymoney']/100;
    $return_arr[$value['ymd']]['all_pay_user'] += $value['payuser'];
}

//--应用宝 注册人数(不含滚服)
$url = "http://119.29.13.96:8085/int_common/int_all_zhuce_ymd.php?ymd_start=".$ymd_start."&ymd_end=".$ymd_end."&gettime=".$now_time."&getpwd=".$pwd;
$result = get_curl($url);
$result = json_decode($result,1);

foreach ($result as $key => $value)
{
    $return_arr[$key]['an_yyb_zhuce'] = $value;
    $return_arr[$key]['all_zhuce'] += $value;
}

//--联运 注册人数(不含滚服)
$url = "http://203.195.162.36:8085/int_common/int_all_zhuce_ymd.php?ymd_start=".$ymd_start."&ymd_end=".$ymd_end."&gettime=".$now_time."&getpwd=".$pwd;
$result = get_curl($url);
$result = json_decode($result,1);

foreach ($result as $key => $value)
{
    $return_arr[$key]['an_ly_zhuce'] = $value;
    $return_arr[$key]['all_zhuce'] += $value;
}

//--IOS正版 注册人数(不含滚服)
$url = "http://203.195.176.101:8085/int_common/int_all_zhuce_ymd.php?ymd_start=".$ymd_start."&ymd_end=".$ymd_end."&gettime=".$now_time."&getpwd=".$pwd;
$result = get_curl($url);
$result = json_decode($result,1);

foreach ($result as $key => $value)
{
    $return_arr[$key]['ios_zb_zhuce'] = $value;
    $return_arr[$key]['all_zhuce'] += $value;
}

//--总 arpu arppu计算
foreach ($return_arr as $key => $value)
{
    $return_arr[$key]['all_pay_arppu'] = ($value['all_pay_user'] > 0) ? number_format($value['all_pay_money']/$value['all_pay_user'],2) : 0;
}

sort($return_arr);

if($action =='list')
{
    echo json_encode($return_arr);
}elseif($action == 'putcsv')
{
    array_unshift($return_arr, array("日期","收入[总]","付费人数[总]","ARPPU[总]","收入[应用宝]","付费人数[应用宝]","ARPPU[应用宝]","收入[联运]","付费人数[联运]","ARPPU[联运]","收入[IOS]","付费人数[IOS]","ARPPU[IOS]","注册[总]","注册[应用宝]","注册[联运]","注册[IOS]") );
    outputCSV($return_arr,'焚天手游数据汇总'.$ymd_start.'-'.$ymd_end);
}



//----自定义函数
/**
 * 获取 POST GET 传参 优先级 POST > GET > default
 */
function get_param($get, $default='')
{
    $return = (isset( $_GET[$get] ) && $_GET[$get] !='')  ? $_GET[$get] : $default;
    $return = (isset( $_POST[$get] ) && $_POST[$get] !='') ? $_POST[$get] : $return;
    return $return;
}

function get_curl($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $output = curl_exec($ch);
    curl_close($ch);

    return $output;
}


