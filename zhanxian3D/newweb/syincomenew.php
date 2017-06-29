<?php
header("Content-Type:text/html;charset=UTF-8");
ini_set('default_charset','utf-8');
date_default_timezone_set("PRC");
ini_set('display_errors', 1);
error_reporting(0);

$ymd_7days_before = date('Y-m-d', strtotime('-7 days'));
$ymd_today = date('Y-m-d');

$pt = get_param('pt', '全部');
$zone = get_param('zone', '全部');
$ymd_start = get_param('ymd_start', $ymd_7days_before);
$ymd_end = get_param('ymd_end', $ymd_today);

$ymd_start_stamp = strtotime( $ymd_start );
$ymd_end_stamp = strtotime( $ymd_end ) + 86399 ;
?>
<DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="jquery-easyui-1.4/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="jquery-easyui-1.4/themes/icon.css">
    <script type="text/javascript" src="jquery-easyui-1.4/jquery.min.js"></script>
    <script type="text/javascript" src="jquery-easyui-1.4/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="jquery-easyui-1.4/locale/easyui-lang-zh_CN.js"></script>
    <style type="text/css">
        body {background-color: #ffffff; color: #000000;}
        body, td, th, h1, h2 {font-family: sans-serif;}
        pre {margin: 0px; font-family: monospace;}
        a:link {color: #000099; text-decoration: none; background-color: #ffffff;}
        a:hover {text-decoration: underline;}
        table {border-collapse: collapse;}
        .center {text-align: center;}
        .center table { margin-left: auto; margin-right: auto; text-align: left;}
        .center th { text-align: center !important; }
        td, th { border: 1px solid #000000; font-size: 75%; vertical-align: baseline;}
        h1 {font-size: 150%;}
        h2 {font-size: 125%;}
        .p {text-align: left;}
        .e {background-color: #ccccff; font-weight: bold; color: #000000;}
        .h {background-color: #9999cc; font-weight: bold; color: #000000;}
        .v {background-color: #cccccc; color: #000000;}
        .vr {background-color: #cccccc; text-align: right; color: #000000;}
        img {float: right; border: 0px;}
        hr {width: 600px; background-color: #cccccc; border: 0px; height: 1px; color: #000000;}
    </style>
</head>
<body>
    <form action='' method='post'>
        平台选择: 
        <select name='pt'>
            <option value='all'>全部</option>
            <option value='ATX-1'>ATX-1</option>
            <option value='ATX-2'>ATX-2</option>
            <option value='AMD-HOOLAIYYB'>AMD-HOOLAIYYB</option>
            <option value='AMD-HOOLAIYYB-gdt2'>AMD-HOOLAIYYB-gdt2</option>
            <option value='AMD-HOOLAIYYB-gdt3'>AMD-HOOLAIYYB-gdt3</option>
            <option value='AMD-HOOLAIYYB-gdt4'>AMD-HOOLAIYYB-gdt4</option>
        </select>
        区选择(默认全部):
        <input type='text' name='zone' value='<?php echo $_POST['zoneselect']?>' >
        查询日期:
        <input class="easyui-datebox" editable='fasle' size='10' name='ymd_start' id='ymd_start' value='<?php echo $ymd_start;?>'  > ~ 
        <input class="easyui-datebox" editable='fasle' size='10' name='ymd_end' id='ymd_end' value='<?php echo $ymd_end;?>' >
        <input type='submit'>
    </form>
    
    <a href="#" title="每日收入->注册人数:当日不含滚服注册人数<br>应用宝 注册 无法区分渠道<br>30日付费->45日:第31~45日付费和 60,90,120同理" class="easyui-tooltip">指标说明</a>
    <div class="center">

<?php

if( $pt == "全部" && $zone == "全部" )
{
    $zhuce_datas_all = get_zhuce_datas($ymd_start_stamp, $ymd_end_stamp); //获取 注册人数(不含滚服)
    $pay_datas_old = get_pay_datas_old($ymd_start, $ymd_end); //获取充值数据 (不含当天)
    $pay_datas_today = get_pay_datas_today(); //获取充值数据(当天)

    $pay_datas_old[$ymd_today] = $pay_datas_today[$ymd_today];

    $table_arr = array();

    for($i=$ymd_start_stamp; $i<=$ymd_end_stamp; $i+=86400  )
    {
        $ymd = date('Y-m-d', $i);
        $table_arr[$ymd] = $pay_datas_old[$ymd];
        $table_arr[$ymd]['zhuce'] = $zhuce_datas_all[$ymd];
    }

    echo table_tmp1($table_arr, $zone, $pt );
}

if( $pt != "全部" && $zone == "全部" )
{
    $pay_datas_old = get_pay_datas_old($ymd_start, $ymd_end, $pt); //获取充值数据 (不含当天)
    $pay_datas_today = get_pay_datas_today($pt); //获取充值数据(当天)

    $pay_datas_old[$ymd_today] = $pay_datas_today[$ymd_today];

    $table_arr = array();

    for($i=$ymd_start_stamp; $i<=$ymd_end_stamp; $i+=86400  )
    {
        $ymd = date('Y-m-d', $i);
        $table_arr[$ymd] = $pay_datas_old[$ymd];
    }

    echo table_tmp1($table_arr, $zone, $pt );
}

if( $pt == "全部" && $zone != "全部" )
{
    $zhuce_datas_all = get_zhuce_datas($ymd_start_stamp, $ymd_end_stamp, $zone); //获取 注册人数(不含滚服)
    
    $pay_datas_old = get_pay_datas_old($ymd_start, $ymd_end, '', $zone); //获取充值数据 (不含当天)
    $pay_datas_today = get_pay_datas_today('',$zone); //获取充值数据(当天)

    $pay_datas_old[$ymd_today] = $pay_datas_today[$ymd_today];

    $table_arr = array();

    for($i=$ymd_start_stamp; $i<=$ymd_end_stamp; $i+=86400  )
    {
        $ymd = date('Y-m-d', $i);
        $table_arr[$ymd] = $pay_datas_old[$ymd];
        $table_arr[$ymd]['zhuce'] = $zhuce_datas_all[$ymd];
    }

    echo table_tmp1($table_arr, $zone, $pt );
}

if( $pt != "全部" && $zone != "全部" )
{
    $pay_datas_old = get_pay_datas_old($ymd_start, $ymd_end, $pt, $zone); //获取充值数据 (不含当天)
    $pay_datas_today = get_pay_datas_today($pt ,$zone); //获取充值数据(当天)

    $pay_datas_old[$ymd_today] = $pay_datas_today[$ymd_today];

    $table_arr = array();

    for($i=$ymd_start_stamp; $i<=$ymd_end_stamp; $i+=86400  )
    {
        $ymd = date('Y-m-d', $i);
        $table_arr[$ymd] = $pay_datas_old[$ymd];
    }

    echo table_tmp1($table_arr, $zone, $pt );
}


function table_tmp1($arr, $zone, $pt)
{
    $tmp_str = '
    <table border="0" cellpadding="3" width="600">
        <tr class="h" colspan="3">每日收入</tr>
        <tr class="h">
            <th>日期</th>
            <th>区</th>
            <th>平台</th>
            <th>RMB</th>
            <th>ARPPU</th>
            <th>注册人数</th>
            <th>付费人数</th>
            <th>新注册付费</th>
        </tr>';

    foreach ($arr as $key => $value)
    {
        $arppu = $value['sum_payusers'] ? number_format($value['sum_paymoney']/$value['sum_payusers'],2) : '';
        $tmp_str .= "
            <tr>
                <td class='e'> " . $key . " </td>
                <td class='v'> " . $zone . " </td>
                <td class='v'> " . $pt . " </td>
                <td class='v'> " . $value['sum_paymoney'] . " </td>
                <td class='v'> " . $arppu . " </td>
                <td class='v'> " . $value['zhuce'] . " </td>
                <td class='v'> " . $value['sum_payusers'] . " </td>
                <td class='v'> " . $value['sum_newregpay'] . " </td>
            </tr>
        ";
    }

    $tmp_str .= '</table>';

    return $tmp_str;
}

/**
 * 获取 历史充值 相关数据
 */
function get_pay_datas_old($ymd_start, $ymd_end, $pt='', $zone='')
{
    $dbh = new PDO('mysql:host=117.103.235.92;dbname=shouyou;port=3306;', 'root', 'hoolai@123');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->query("SET NAMES UTF8");

    if( $pt == '' )
    {
        if( $zone == '' )
        {
            $sql = "SELECT ymd, SUM(payusers) AS sum_payusers, SUM(paymoney)*10 AS sum_paymoney, SUM(newregpay) AS sum_newregpay 
                FROM sy_ptzonedatas 
                WHERE ymd >= :ymd_start AND ymd <= :ymd_end 
                GROUP BY ymd 
                ORDER BY ymd ASC";
        }else
        {
            $sql = "SELECT ymd, SUM(payusers) AS sum_payusers, SUM(paymoney)*10 AS sum_paymoney, SUM(newregpay) AS sum_newregpay 
                FROM sy_ptzonedatas 
                WHERE ymd >= :ymd_start AND ymd <= :ymd_end AND zone = :zone 
                GROUP BY ymd 
                ORDER BY ymd ASC";
        }
    }else
    {
        if( $zone == '' )
        {
            $sql = "SELECT ymd, SUM(payusers) AS sum_payusers, SUM(paymoney)*10 AS sum_paymoney
                FROM sy_ptzonedatas 
                WHERE ymd >= :ymd_start AND ymd <= :ymd_end AND pt = :pt
                GROUP BY ymd 
                ORDER BY ymd ASC";
        }else
        {
            $sql = "SELECT ymd, SUM(payusers) AS sum_payusers, SUM(paymoney)*10 AS sum_paymoney
                FROM sy_ptzonedatas 
                WHERE ymd >= :ymd_start AND ymd <= :ymd_end AND pt = :pt  AND zone = :zone 
                GROUP BY ymd 
                ORDER BY ymd ASC";            
        }
    }

    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(":ymd_start", $ymd_start);
    $stmt->bindParam(":ymd_end", $ymd_end);
    if( $pt != '' ) $stmt->bindParam(":pt", $pt);
    if( $zone != '' ) $stmt->bindParam(":zone", $zone);
    $stmt->execute();

    $result = $stmt->fetchAll();

    $return_arr = array();

    foreach ($result as $key => $value)
    {
        $return_arr[$value['ymd']] = $value;
    }

    $dbh = null;

    return $return_arr;
}

/**
 * 获取 今天 充值相关 数据
 */
function get_pay_datas_today($pt='',$zone='')
{

    $ymd = date('Y-m-d', time());
    $ymd_start_stamp = strtotime($ymd);
    $ymd_end_stamp = $ymd_start_stamp + 86399;

    $dbh = new PDO('mysql:host=117.103.235.92;dbname=BILL;port=3306;', 'root', 'hoolai@123');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->query("SET NAMES utf8");

    if($pt == '')
    {
        if($zone == '')
        {
            $sql = "SELECT COUNT(DISTINCT ACC) AS sum_payusers, CEIL(SUM(AMOUNT)/100) AS sum_paymoney 
                FROM TRADE 
                WHERE STATUS = 1 AND TIME >= :ymd_start_stamp AND TIME <= :ymd_end_stamp";
        }else
        {
            $sql = "SELECT COUNT(DISTINCT ACC) AS sum_payusers, CEIL(SUM(AMOUNT)/100) AS sum_paymoney 
                FROM TRADE 
                WHERE STATUS = 1 AND TIME >= :ymd_start_stamp AND TIME <= :ymd_end_stamp AND ZONE = :zone ";
        }
    }else
    {
        if($zone == '')
        {
            $sql = "SELECT COUNT(DISTINCT ACC) AS sum_payusers, CEIL(SUM(AMOUNT)/100) AS sum_paymoney 
                FROM TRADE 
                WHERE STATUS = 1 AND TIME >= :ymd_start_stamp AND TIME <= :ymd_end_stamp AND PF = :pt ";
        }else
        {
            $sql = "SELECT COUNT(DISTINCT ACC) AS sum_payusers, CEIL(SUM(AMOUNT)/100) AS sum_paymoney 
                FROM TRADE 
                WHERE STATUS = 1 AND TIME >= :ymd_start_stamp AND TIME <= :ymd_end_stamp AND PF = :pt AND ZONE = :zone ";
        }
    }
    
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':ymd_start_stamp', $ymd_start_stamp);
    $stmt->bindParam(':ymd_end_stamp', $ymd_end_stamp);
    if( $pt != '' ) $stmt->bindParam(":pt", $pt);
    if( $zone != '' ) $stmt->bindParam(":zone", $zone);
    $stmt->execute();

    $result = $stmt->fetch();

    $return_arr[$ymd] = $result;
    $dbh = null;

    if($pt == '')
    {
        $dbh = new PDO('mysql:host=117.103.235.92;dbname=shouyou;port=3306;', 'root', 'hoolai@123');
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $dbh->query("SET NAMES utf8");

        if($zone == '')
        {
            $sql = "SELECT COUNT(*) AS nu FROM sy_payymdacc WHERE payymd = CREATETIME AND CREATETIME = :ymd ";
        }else
        {
            $sql = "SELECT COUNT(*) AS nu FROM sy_payymdacc WHERE payymd = CREATETIME AND CREATETIME = :ymd AND ZONE = :zone ";
        }

        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':ymd', $ymd);
        if( $zone != '' ) $stmt->bindParam(":zone", $zone);
        $stmt->execute();
        $result = $stmt->fetch();

        $dbh = null;

        $return_arr[$ymd]['sum_newregpay'] = $result['nu'];
    }

    return $return_arr;
}

/**
 * 获取指定日期 注册人数
 */
function get_zhuce_datas($ymd_start_stamp, $ymd_end_stamp, $zone='')
{
    $dbh = new PDO('mysql:host=117.103.235.92;dbname=GameData;port=3306;', 'root', 'hoolai@123');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->query("SET NAMES utf8");

    $pre_arr = array('0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F');
    $newzhuce_datas = array();
    $new_zone = "%,".$zone.",%";

    foreach ($pre_arr as $key => $value) 
    {
        if($zone == '')
        {
            $sql = "SELECT count(*) AS nu, FROM_UNIXTIME(UNIX_TIMESTAMP(createtime),'%Y-%m-%d') AS ymd 
                FROM AllUser" . $value . " 
                WHERE UNIX_TIMESTAMP(createtime) >= :ymd_start_stamp AND UNIX_TIMESTAMP(createtime) <= :ymd_end_stamp
                GROUP BY ymd ";
        }else
        {
            $sql = "SELECT count(*) AS nu, FROM_UNIXTIME(UNIX_TIMESTAMP(createtime),'%Y-%m-%d') AS ymd 
                FROM AllUser" . $value . " 
                WHERE UNIX_TIMESTAMP(createtime) >= :ymd_start_stamp AND UNIX_TIMESTAMP(createtime) <= :ymd_end_stamp AND CONCAT(',',zones,',') LIKE :zone
                GROUP BY ymd ";            
        }

        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(":ymd_start_stamp", $ymd_start_stamp);
        $stmt->bindParam(":ymd_end_stamp", $ymd_end_stamp);
        if($zone != '') $stmt->bindParam(":zone", $new_zone);

        $stmt->execute();

        $result = $stmt->fetchAll();
        foreach ($result as $k => $v) 
        {
            $newzhuce_datas[$v['ymd']] += $v['nu'];
        }
    }

    $dbh = null;

    return $newzhuce_datas;
}

//////////////////////-- 公共函数 --////////////////////////////////////////////
/**
 * 获取 POST GET 传参 优先级 POST > GET > default
 */
function get_param($get, $default='')
{
    $return = (isset( $_GET[$get]) && $_GET[$get] !=''  ) ? $_GET[$get] : $default;
    $return = (isset( $_POST[$get]) && $_POST[$get] != ''  ) ? $_POST[$get] : $return;
    return $return;
}

