<?php
// if( PHP_SAPI !== 'cli' ) exit;
date_default_timezone_set("PRC");
ini_set('default_charset','utf-8');
ini_set("memory_limit", "200M");
ini_set('display_errors', 1);
error_reporting(-1);
set_time_limit(0);


$action = isset($_GET['action']) ? $_GET['action'] : '';

if($action == 'list')
{

    $ymd = isset($_GET['ymd']) ? $_GET['ymd'] : date('Y-m-d', time());
    $reg_ymd_stamp = strtotime( $ymd );


    $dbh_zone_msg = new PDO('mysql:host=10.104.222.134;dbname=fentiansj;port=3306;charset=utf8', 'root', 'hoolai@123');
    $dbh_zone_msg->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh_zone_msg->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh_zone_msg->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh_zone_msg->query("SET NAMES UTF8");


    $dbh_trade = new PDO('mysql:host=117.103.235.92;dbname=BILL;port=3306;charset=utf8', 'root', 'hoolai@123');
    $dbh_trade->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh_trade->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh_trade->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh_trade->query("SET NAMES UTF8");


    $sql = "SELECT * FROM zone_msg WHERE zone_id < 4000";
    $stmt = $dbh_zone_msg->prepare($sql);
    $stmt->execute();
    $zone_msg_arr = $stmt->fetchAll();

    $return_arr = array();

    // 注册
    $get_qd_zhuce = get_qd_zhuce($zone_msg_arr, $dbh_trade, $reg_ymd_stamp);

    foreach ($get_qd_zhuce as $key => $value)
    {
        foreach ($value as $k => $v)
        {
            @$return_arr[$k]['reg'] = $v;
        }
    }

    // 收入
    $arr = get_income_nu($dbh_trade, $ymd_start_stamp='', $ymd_end_stamp='', $qd='');

    foreach ($arr as $key => $value)
    {
        foreach ($value as $k => $v)
        {
            @$return_arr[$k]['income'] = $v;
        }
    }


    // 充值人数
    $arr = get_payuser_nu($dbh_trade, $ymd_start_stamp='', $ymd_end_stamp='', $qd='');

    foreach ($arr as $key => $value)
    {
        foreach ($value as $k => $v)
        {
            @$return_arr[$k]['payuser'] = $v;
        }
    }

    $arr = get_reg_pay($dbh_trade, $reg_ymd_stamp);

    foreach ($arr as $key => $value)
    {
        @$return_arr[my_substr($key)]['reg_pay_user'] = $value['pay_user'];
        @$return_arr[my_substr($key)]['reg_pay_money'] = $value['pay_money'];
    }


    foreach ($return_arr as $key => $value)
    {
        $return_arr[$key]['qd'] = $key;

    }

    sort($return_arr);

    exit( json_encode($return_arr) );

}





/**
 * desc 分渠道 注册统计
 */
function get_qd_zhuce($zone_msg_arr, $dbh_trade, $ymd_start_stamp='', $ymd_end_stamp='', $qd='')
{
    $sys_start_stamp = strtotime('2016-08-19');

    if($ymd_start_stamp <= $sys_start_stamp) $ymd_start_stamp = $sys_start_stamp;

    $return_arr = array();

    $get_all_qd_names = get_all_qd_names($dbh_trade);


    for ($i=$ymd_start_stamp; $i <$ymd_end_stamp ; $i+=86400)
    {
        foreach ($get_all_qd_names as $key => $value)
        {
            $return_arr[date('Y-m-d', $i)][$value] = 0;
        }
    }

    foreach ($zone_msg_arr as $key => $value)
    {
        $dbh_charbase = new PDO('mysql:host='.$value['mysql_ip'].';dbname='.$value['mysql_dbName'].';port='.$value['mysql_port'].';', 'root', 'hoolai@123');
        $dbh_charbase->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh_charbase->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $dbh_charbase->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        $where = ' WHERE 1 ';

        if($ymd_start_stamp) $where .= ' AND CREATETIME >= :ymd_start_stamp ';
        if($ymd_end_stamp) $where .= ' AND CREATETIME <= :ymd_end_stamp ';
        if($qd) $where .= ' AND qd = :qd ';

        $sql = "SELECT count(*) AS nu, SUBSTRING_INDEX(ACCNAME,'-',1) AS qd, FROM_UNIXTIME(CREATETIME, '%Y-%m-%d') AS ymd FROM CHARBASE " . $where . " GROUP BY ymd,qd";

        $stmt = $dbh_charbase->prepare($sql);
        
        if($ymd_start_stamp) $stmt->bindParam(':ymd_start_stamp', $ymd_start_stamp);
        if($ymd_end_stamp) $stmt->bindParam(':ymd_end_stamp', $ymd_end_stamp);
        if($qd) $stmt->bindParam(':qd', $qd);

        $stmt->execute();
        $result = $stmt->fetchAll();

        foreach ($result as $k => $v)
        {
            @$return_arr[$v['ymd']][$v['qd']] += $v['nu'];
        }

    }

    return $return_arr;
}


/**
 * return array 从TRADE总获取的现有所有渠道标识
 */
function get_all_qd_names($dbh_trade)
{
    $sql = 'SELECT PF FROM TRADE WHERE ZONE < 4000 GROUP BY PF';
    $stmt = $dbh_trade->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();

    $return_arr = array();

    foreach ($result as $key => $value)
    {
        $return_arr[] = my_substr($value['PF']);
    }

    return $return_arr;
}


/**
 * return string 截取 $str 中 $sub 之后的数据
 */
function my_substr($str, $sub='-')
{
    $flag = strpos($str, $sub);
    $str = trim(substr($str, $flag), $sub);

    return $str;
}

/**
 * 获取收入
 */
function get_income_nu($dbh_trade, $ymd_start_stamp='', $ymd_end_stamp='', $qd='')
{
    $sys_start_stamp = strtotime('2016-08-19');
    if($ymd_start_stamp <= $sys_start_stamp) $ymd_start_stamp = $sys_start_stamp;

    $where = '';
    if($ymd_start_stamp) $where .= ' AND TIME >= :ymd_start_stamp ';
    if($ymd_end_stamp) $where .= ' AND TIME <= :ymd_end_stamp ';
    if($qd) $where .= ' AND qd = :qd ';

    $sql = "SELECT SUM(AMOUNT) / 100 AS total ,PF, FROM_UNIXTIME(TIME, '%Y-%m-%d') AS ymd FROM TRADE WHERE STATUS = 1 AND ZONE < 4000 " . $where . " GROUP BY ymd,PF ";

    $stmt = $dbh_trade->prepare($sql);
        
    if($ymd_start_stamp) $stmt->bindParam(':ymd_start_stamp', $ymd_start_stamp);
    if($ymd_end_stamp) $stmt->bindParam(':ymd_end_stamp', $ymd_end_stamp);
    if($qd) $stmt->bindParam(':qd', $qd);

    $stmt->execute();
    $result = $stmt->fetchAll();

    $return_arr = array();

    foreach ($result as $key => $value)
    {
        $return_arr[$value['ymd']][my_substr($value['PF'])] = (int)$value['total'];
    }

    return $return_arr;
}


/**
 * 获取付费人数
 */
function get_payuser_nu($dbh_trade, $ymd_start_stamp='', $ymd_end_stamp='', $qd='')
{
    $sys_start_stamp = strtotime('2016-08-19');
    if($ymd_start_stamp <= $sys_start_stamp) $ymd_start_stamp = $sys_start_stamp;

    $where = '';
    if($ymd_start_stamp) $where .= ' AND TIME >= :ymd_start_stamp ';
    if($ymd_end_stamp) $where .= ' AND TIME <= :ymd_end_stamp ';
    if($qd) $where .= ' AND qd = :qd ';

    $sql = "SELECT count(distinct ACC) AS total ,PF, FROM_UNIXTIME(TIME, '%Y-%m-%d') AS ymd FROM TRADE WHERE STATUS = 1 AND ZONE < 4000 " . $where . " GROUP BY ymd,PF ";

    $stmt = $dbh_trade->prepare($sql);
        
    if($ymd_start_stamp) $stmt->bindParam(':ymd_start_stamp', $ymd_start_stamp);
    if($ymd_end_stamp) $stmt->bindParam(':ymd_end_stamp', $ymd_end_stamp);
    if($qd) $stmt->bindParam(':qd', $qd);

    $stmt->execute();
    $result = $stmt->fetchAll();

    $return_arr = array();

    foreach ($result as $key => $value)
    {
        $return_arr[$value['ymd']][my_substr($value['PF'])] = $value['total'];
    }

    return $return_arr;
}

function get_reg_pay($dbh_trade, $reg_ymd_stamp)
{
    $sql = "SELECT SUM(AMOUNT) / 100 AS total, ACC, PF,ZONE FROM TRADE WHERE STATUS = 1 AND ZONE < 4000 AND TIME >= '".(int)$reg_ymd_stamp."' AND TIME <= '".((int)$reg_ymd_stamp + 86399)."' GROUP BY ACC,PF ";
    $stmt = $dbh_trade->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();

    $return_arr = array();

    foreach ($result as $key => $value)
    {
        if(check_create_date($value['ACC'], $reg_ymd_stamp, $value['ZONE'])) @$return_arr[$value['PF']]['pay_user'] ++;
        if(check_create_date($value['ACC'], $reg_ymd_stamp, $value['ZONE'])) @$return_arr[$value['PF']]['pay_money'] += $value['total'];
    }

    return $return_arr;
}

function check_create_date($acc, $checkymd_stamp, $zone)
{
    $dbh_GameData = new PDO('mysql:host=117.103.235.92;dbname=GameData;port=3306;charset=utf8', 'root', 'hoolai@123');
    $dbh_GameData->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh_GameData->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh_GameData->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh_GameData->query("SET NAMES UTF8");


    $dbh_GameData_ios = new PDO('mysql:host=117.103.235.92;dbname=GameData_ios;port=3306;charset=utf8', 'root', 'hoolai@123');
    $dbh_GameData_ios->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh_GameData_ios->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh_GameData_ios->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh_GameData_ios->query("SET NAMES UTF8");

    $dbh_GameData_ly = new PDO('mysql:host=117.103.235.92;dbname=GameData_ly;port=3306;charset=utf8', 'root', 'hoolai@123');
    $dbh_GameData_ly->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh_GameData_ly->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh_GameData_ly->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh_GameData_ly->query("SET NAMES UTF8");


    $table_pre = strtoupper(md5($acc));
    $table_pre = $table_pre[0];

    $sql = "SELECT openid, UNIX_TIMESTAMP(createdate) as time1 FROM AllUser".$table_pre." WHERE openid = :acc";

    if($zone <= 1000) $stmt = $dbh_GameData->prepare($sql);
    if($zone >1000 && $zone <= 2000) $stmt = $dbh_GameData_ly->prepare($sql);
    if($zone >2000 && $zone <= 3000) $stmt = $dbh_GameData_ios->prepare($sql);

    $stmt->bindParam(':acc', $acc);
    $stmt->execute();
    $return = $stmt->fetch();

    return (isset($return['time1']) && $return['time1'] == $checkymd_stamp )? 1 : 0;
}
