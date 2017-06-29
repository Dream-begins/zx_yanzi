<?php
header("Content-Type:text/html;charset=UTF-8");
ini_set('default_charset','utf-8');
date_default_timezone_set("PRC");
ini_set('display_errors', 1);
ini_set("memory_limit", "300M"); 
error_reporting(0);
include_once "newweb/h_header.php";

$time_start = isset($_POST['from']) ? $_POST['from'] : date('Y-m-d',strtotime('-7 days'));
$time_end = isset($_POST['to']) ? $_POST['to'] : date('Y-m-d');

$export = $_GET['export'];
$days = isset($_GET['days']) ? $_GET['days'] : '30';

if($export)
{
    $time_start = isset($_GET['from']) ? $_GET['from'] : date('Y-m-d',strtotime('-7 days'));
    $time_end = isset($_GET['to']) ? $_GET['to'] : date('Y-m-d');
}

$day_start = strtotime($time_start);
$day_end   = strtotime($time_end);

$today_start = strtotime(date('Y-m-d'));
$today_end   = $today_start + 86399;
$today_ymd   = date('Y-m-d', $today_start);

$dbhzhuce = new PDO('mysql:host='.FT_MYSQL_COMMON_HOST.';dbname='.FT_MYSQL_GAMEDATA_DBNAME.';port='.FT_MYSQL_COMMON_PORT.';', FT_MYSQL_COMMON_ROOT, FT_MYSQL_COMMON_PASS);
$dbhzhuce->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbhzhuce->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$dbhzhuce->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbhzhuce->query("SET NAMES utf8");

$pre_arr = array('0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F');
$newzhuce_datas = array();
$nu = 0;
foreach ($pre_arr as $key => $value) 
{
    $sql = "SELECT count(*) as nu FROM AllUser".$value." WHERE UNIX_TIMESTAMP(createtime) >= :today_start AND UNIX_TIMESTAMP(createtime) <= :today_end ";
    $stmt = $dbhzhuce->prepare($sql);
    $stmt->bindParam(':today_start', $today_start);
    $stmt->bindParam(':today_end', $today_end);
    $stmt->execute();
    $result = $stmt->fetch();

    $nu += $result['nu'];
}

$dbh = new PDO('mysql:host='.FT_MYSQL_COMMON_HOST.';dbname='.FT_MYSQL_SHOUYOU_DBNAME.';port='.FT_MYSQL_COMMON_PORT.';', FT_MYSQL_COMMON_ROOT, FT_MYSQL_COMMON_PASS);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbh->query("SET NAMES utf8");

//获取注册
$sql = "SELECT ymd,nu FROM yyb_zhuce";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll();
$zhuce_arr = array();
foreach ($result as $key => $value)
{
    $zhuce_arr[$value['ymd']] = $value['nu'];
}

$zhuce_arr[$today_ymd] = $nu;

$sql = "SELECT sum(sum_amount) AS m_total, count(1) AS c_total, payymd, CREATETIME 
        FROM sy_payymdacc
        WHERE UNIX_TIMESTAMP(CREATETIME) >= $day_start 
        GROUP BY payymd, CREATETIME
        ORDER BY CREATETIME,payymd ASC
";
$stmt = $dbh->prepare($sql);
$stmt->execute();

$result = $stmt->fetchAll();

$datas30_result = array(); //array( array('注册日期'=>array( '支付日期'=>array('rmb'=>'100','accnu'=>'100').. ) ) )
foreach($result as $k => $v)
{
    $datas30_result[$v['CREATETIME']][$v['payymd']]['rmb']  = $v['m_total']/10;
    $datas30_result[$v['CREATETIME']][$v['payymd']]['accnu'] = $v['c_total'];
}

$return_arr = array();
$return_arr['total'] = 100;

for ($i=$day_start; $i <=$day_end ; $i+=86400)
{ 
    $ymd = date('Y-m-d', $i);
    $return_arr['rows'][$ymd]['dt'] = $ymd;
    $return_arr['rows'][$ymd]['user'] = (int)$zhuce_arr[$ymd];

    for ($j=1; $j <=$days ; $j++)
    {
        $dtymd = date('Y-m-d',($i + ($j-1)*86400));
        $return_arr['rows'][$ymd]['dt'.$j] = (float)$datas30_result[$ymd][$dtymd]['rmb'];
    }

}
sort($return_arr['rows']);

if($export != '1')
{
    echo json_encode($return_arr);
}else
{
    $flag = array('日期','注册');
    for($i=1;$i<=$days;$i++)
    {
        $flag[] = '第'.$i.'天';
    }

    array_unshift($return_arr['rows'],$flag);

    outputCSV($return_arr['rows'],'安卓应用宝30日付费');
}


/**
 * @desc 导出CSV文件
 * @param $data array() 数据数组 如 array( array(1,2,3), array(1,2,3) );
 * @param $name string 文件名 如 zone_msg
 * @return 导出CSV文件
 */
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

