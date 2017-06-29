<?php
header("Content-Type:text/html;charset=UTF-8");
ini_set('default_charset','utf-8');
date_default_timezone_set("PRC");
session_start();
ini_set('display_errors', 0);
error_reporting(0);
include_once "newweb/h_header.php";

$from =isset($_POST['from']) ?  $_POST['from'] : date('Y-m-d',strtotime('-7 days'));
$to =isset($_POST['to']) ?  $_POST['to'] : date('Y-m-d');
$action = $_GET['export'];
if($action == "1")
{
    $from =isset($_GET['from']) ?  $_GET['from'] : date('Y-m-d',strtotime('-7 days'));
    $to =isset($_GET['to']) ?  $_GET['to'] : date('Y-m-d');
}

$zhuce_arr = get_zhuce();
$paymonth_arr = get_paymonth();

$end_arr = array();

foreach ($zhuce_arr as $key => $value)
{
    $end_arr[$key]['dt'] = $key;
    $end_arr[$key]['user'] = $value;
}


foreach ($paymonth_arr as $key => $value)
{
    $end_arr[$key]['dt1']  = $value['1'];
    $end_arr[$key]['avg1'] = $value['1'] / $zhuce_arr[$key];
    $end_arr[$key]['dt2']  = $value['2'];
    $end_arr[$key]['avg2'] = $value['2'] / $zhuce_arr[$key];
    $end_arr[$key]['rate2']  = 100*($value[1] - $value[2])/$value[1];
    $end_arr[$key]['dt3']  = $value['3'];
    $end_arr[$key]['avg3'] = $value['3'] / $zhuce_arr[$key];
    $end_arr[$key]['rate3']  = 100*($value[2] - $value[3])/$value[2];
    $end_arr[$key]['dt4']  = $value['4'];
    $end_arr[$key]['avg4'] = $value['4'] / $zhuce_arr[$key];
    $end_arr[$key]['rate4']  = 100*($value[3] - $value[4])/$value[3];
    $end_arr[$key]['dt5']  = $value['5'];
    $end_arr[$key]['avg5'] = $value['5'] / $zhuce_arr[$key];
    $end_arr[$key]['rate5']  = 100*($value[4] - $value[5])/$value[4];
    $end_arr[$key]['dt6']  = $value['6'];
    $end_arr[$key]['avg6'] = $value['6'] / $zhuce_arr[$key];
    $end_arr[$key]['rate6']  = 100*($value[5] - $value[6])/$value[5];
    $end_arr[$key]['dt7']  = $value['7'];
    $end_arr[$key]['avg7'] = $value['7'] / $zhuce_arr[$key];
    $end_arr[$key]['rate7']  = 100*($value[6] - $value[7])/$value[6];
    $end_arr[$key]['dt8']  = $value['8'];
    $end_arr[$key]['avg8'] = $value['8'] / $zhuce_arr[$key];
    $end_arr[$key]['rate8']  = 100*($value[7] - $value[8])/$value[7];
    $end_arr[$key]['dt9']  = $value['9'];
    $end_arr[$key]['avg9'] = $value['9'] / $zhuce_arr[$key];
    $end_arr[$key]['rate9']  = 100*($value[8] - $value[9])/$value[8];
    $end_arr[$key]['dt10'] = $value['10'];
    $end_arr[$key]['avg10'] = $value['10'] / $zhuce_arr[$key];
    $end_arr[$key]['rate10']  = 100*($value[9] - $value[10])/$value[9];
    $d1 = new DateTime($key);
    $d2 = new DateTime(date());
    $int = $d1->diff($d2)->days;
    $flag = ceil($int/30);

    $end_arr[$key]['rate1']  = 1;
    
    for( $i=2; $i<=$flag; $i++ )
    {
        $end_arr[$key]['rate'.$i]  = 100*($value[$i-1] - $value[$i])/$value[$i-1];
    }

}

$flag_arr = array();

foreach ($end_arr as $key => $value)
{
    $t1 = strtotime($from);
    $t2 = strtotime($to);
    $t3 = strtotime($key);
 
    if($t3 > $t2 || $t3 < $t1) continue;
   
    $flag_arr[] = $value;
}

$return = array('total'=>'0','rows'=>$flag_arr);

$action = $_GET['export'];

if($action == "1")
{
    array_unshift($return['rows'], array('日期','注册数','第1月','第1月注收比','第2月','第2月注收比','第2月衰减','第3月','第3月注收比','第3月衰减','第4月','第4月注收比','第4月衰减','第5月','第5月注收比','第5月衰减','第6月','第6月注收比','第6月衰减','第7月','第7月注收比','第7月衰减','第8月','第8月注收比','第8月衰减','第9月','第9月注收比','第9月衰减','第10月','第10月注收比','第10月衰减',) );
    outputCSV($return['rows'],'月付费分析');

}else{
    echo json_encode((object)$return);
}


function get_zhuce()
{
    $dbhzhuce = new PDO('mysql:host='.FT_MYSQL_COMMON_HOST.';dbname='.FT_MYSQL_GAMEDATA_DBNAME.';port='.FT_MYSQL_COMMON_PORT.';', FT_MYSQL_COMMON_ROOT, FT_MYSQL_COMMON_PASS);
    $dbhzhuce->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbhzhuce->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbhzhuce->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbhzhuce->query("SET NAMES utf8");

    $pre_arr = array('0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F');

    $newzhuce_datas = array();

    foreach ($pre_arr as $key => $value) 
    {
        $sql = "SELECT count(*) as nu ,FROM_UNIXTIME(UNIX_TIMESTAMP(createtime),'%Y-%m-%d') as ymd FROM AllUser".$value." GROUP BY ymd ";
        $stmt = $dbhzhuce->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetchAll();
        foreach ($result as $k => $v) 
        {
            $newzhuce_datas[$v['ymd']] += $v['nu'];
        }
    }

    $dbhzhuce = null;

    return $newzhuce_datas;    
}

function get_paymonth()
{
    $dbh_sy_ptzonedatas = new PDO('mysql:host='.FT_MYSQL_COMMON_HOST.';dbname='.FT_MYSQL_SHOUYOU_DBNAME.';port='.FT_MYSQL_COMMON_PORT.';', FT_MYSQL_COMMON_ROOT, FT_MYSQL_COMMON_PASS);
    $dbh_sy_ptzonedatas->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh_sy_ptzonedatas->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh_sy_ptzonedatas->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh_sy_ptzonedatas->query("SET NAMES utf8");

    $sql = "SELECT sum(sum_amount)/1000 AS m_total, count(1) AS c_total, payymd, CREATETIME 
            FROM sy_payymdacc
            GROUP BY payymd, CREATETIME";

    $stmt = $dbh_sy_ptzonedatas->prepare($sql);
    $stmt->execute();
    $datas30 = $stmt->fetchAll();

    $return_arr = array();

    foreach ($datas30 as $key => $value)
    {
        $d1 = new DateTime($value['CREATETIME']);
        $d2 = new DateTime($value['payymd']);
        $int = $d1->diff($d2)->days;
        $int += 1;
        $flag = ceil($int/30);

        $return_arr[$value['CREATETIME']][$flag] += $value['m_total']/10;
    }

    $dbh_sy_ptzonedatas = null;

    return $return_arr;
}


function outputCSV($data,$name)
{
    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename={$name}.csv");
    header("Pragma: no-cache");
    header("Expires: 0");

    $outputBuffer = fopen("php://output", 'w');
    foreach($data as $val)
    {
        foreach ($val as $key => $val2) 
        {
            $val[$key] = iconv('utf-8', 'gbk', $val2);// CSV的Excel支持GBK编码，一定要转换，否则乱码 
        }
        fputcsv($outputBuffer, $val);
    }
    fclose($outputBuffer);
}

