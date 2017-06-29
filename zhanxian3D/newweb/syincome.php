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
<?php
header("Content-Type:text/html;charset=UTF-8");
ini_set('default_charset','utf-8');
date_default_timezone_set("PRC");
ini_set('display_errors', 1);
ini_set("memory_limit", "300M"); 

error_reporting(0);

if(isset($_POST['ptselect']) && $_POST['ptselect']==''  ) $_POST['ptselect'] = 'all';

$dbhzhuce = new PDO('mysql:host=117.103.235.92;dbname=GameData;port=3306;', 'root', 'hoolai@123');
$dbhzhuce->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbhzhuce->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$dbhzhuce->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbhzhuce->query("SET NAMES utf8");

$new_liucun_arr = array();
$sql = "SELECT cymd, lymd, nu FROM sy_all_liucun GROUP BY  cymd,lymd  ";
$stmt = $dbhzhuce->prepare($sql);
$stmt->execute();
$new_liycun_arr = $stmt->fetchAll();

foreach ($new_liycun_arr as $key => $value) 
{
    $d1 = new DateTime($value['cymd']);
    $d2 = new DateTime($value['lymd']);

    $int = $d1->diff($d2)->days;

    $new_liucun_arr[$value['cymd']][$int] = $value['nu'];
}

$pre_arr = array('0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F');

$newzhuce_datas = array();

foreach ($pre_arr as $key => $value) 
{
    $sql = "SELECT count(*) as nu ,FROM_UNIXTIME(UNIX_TIMESTAMP(createtime),'%Y-%m-%d') as ymd FROM AllUser".$value." GROUP BY ymd ";
    if($_POST['ptselect'] != 'all') $sql = "SELECT count(*) as nu ,FROM_UNIXTIME(UNIX_TIMESTAMP(createtime),'%Y-%m-%d') as ymd FROM AllUser".$value." where UPPER(SUBSTRING_INDEX(openid,'-',1)) = '{$_POST['ptselect']}'  GROUP BY ymd ";
    $stmt = $dbhzhuce->prepare($sql);
    $stmt->execute();

    $result = $stmt->fetchAll();
    foreach ($result as $k => $v) 
    {
        $newzhuce_datas[$v['ymd']] += $v['nu'];
    }
}



$ymd_start = $_POST['ymd_start'] ? $_POST['ymd_start'] : date('Y-m-d',time()-86400*7);
$ymd_end = $_POST['ymd_end'] ? $_POST['ymd_end'] : date('Y-m-d',time());
//汇总库 sy_ptzonedatas sy_payymdacc
$dbh_sy_ptzonedatas = new PDO('mysql:host=117.103.235.92;dbname=shouyou;port=3306;', 'root', 'hoolai@123');
$dbh_sy_ptzonedatas->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh_sy_ptzonedatas->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$dbh_sy_ptzonedatas->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbh_sy_ptzonedatas->query("SET NAMES utf8");

$sql = "SELECT ymd, pt, zone, zhuce, payusers, paymoney, newregpay FROM sy_ptzonedatas where zone<4000 ";
$stmt = $dbh_sy_ptzonedatas->prepare($sql);
$stmt->execute();
$datas = $stmt->fetchAll();

$today_datas = today_datas();

$datas_zhuce = array();
$flag_arr = array();

foreach ($datas as $key => $value) 
{
$value['pt'] = rtrim($value['pt'],'-0');
$flag = strpos($value['pt'], 'IMD-');
if($flag !== false)
    $value['pt'] = substr($value['pt'],$flag+4);
    $pt_arr[rtrim($value['pt'],'-0')] = '';
    //$pt_arr[$value['pt']] = '';
    if($_POST['ptselect'] != 'all' &&  $_POST['ptselect'] != $value['pt']){ unset($datas[$key]); continue;  }
    if($_POST['zoneselect'] != '' && $_POST['zoneselect'] != $value['zone']  ){ unset($datas[$key]); continue;  }
    if($value['pt']==$_POST['ptselect']) $datas_zhuce[$value['ymd']] = $value['zhuce'];

    if($_POST['zoneselect'] == '')
    {
        $value['pt'] = rtrim($value['pt']);
        $flag_arr[$value['ymd']][$value['pt']]['ymd'] = $value['ymd'];
        $flag_arr[$value['ymd']][$value['pt']]['pt'] = $value['pt'];
        $flag_arr[$value['ymd']][$value['pt']]['zone'] = '全部';
        $flag_arr[$value['ymd']][$value['pt']]['zhuce'] += $value['zhuce'];
        $flag_arr[$value['ymd']][$value['pt']]['payusers'] += $value['payusers'];
        $flag_arr[$value['ymd']][$value['pt']]['paymoney'] += $value['paymoney'];
        $flag_arr[$value['ymd']][$value['pt']]['newregpay'] += $value['newregpay'];
    }

    if($_POST['ptselect'] != 'all')$datas_zhuce[$value['ymd']] = $value['zhuce'];
    if($_POST['ptselect'] == 'all')$datas_zhuce[$value['ymd']] += $value['zhuce'];
}

if(count($flag_arr))
{
    $datas = array();
    foreach($flag_arr as $key => $value)
    {
        foreach($value as $k => $v){$datas[] = $v;}
    }
}

$flag_arr = array();
foreach ($today_datas as $key => $value) 
{
$value['pt'] = rtrim($value['pt'],'-0');
$flag = strpos($value['pt'], 'IMD-');
if($flag !== false)
    $value['pt'] = substr($value['pt'],$flag+4);
    $$value['pt'] = substr($$value['pt'],$flag+4);
    $pt_arr[rtrim($value['pt'],'-0')] = '';
    //$pt_arr[$value['pt']] = '';

    if($_POST['ptselect'] !='all' &&  $_POST['ptselect'] != $value['pt']){ unset($today_datas[$key]); continue;  }
    if($_POST['zoneselect'] != '' && $_POST['zoneselect'] != $value['zone']  ){ unset($today_datas[$key]); continue;  }
    if($_POST['zoneselect'] == '')
    { 
        $todayf = date('Y-m-d',time());
        $flag_arr[$todayf][$value['pt']]['ymd'] = $value['ymd'];
        $flag_arr[$todayf][$value['pt']]['pt'] = $value['pt'];
        $flag_arr[$todayf][$value['pt']]['zone'] = '全部';
        $flag_arr[$todayf][$value['pt']]['zhuce'] += $value['zhuce'];
        $flag_arr[$todayf][$value['pt']]['payusers'] += $value['payusers']; 
        $flag_arr[$todayf][$value['pt']]['paymoney'] += $value['paymoney']; 
        $flag_arr[$todayf][$value['pt']]['newregpay'] += $value['newregpay']; 
    }
 
    if($_POST['ptselect'] != 'all')$datas_zhuce[$value['ymd']] += $value['zhuce'];
    if($_POST['ptselect'] == 'all')$datas_zhuce[$value['ymd']] += $value['zhuce'];
}


if(count($flag_arr))
{
    $today_datas = array();
    foreach($flag_arr as $key => $value)
    {
        foreach($value as $k => $v){$today_datas[] = $v;}
    }
}


if($_POST['ptselect'] == 'all')
{
    $flag_datas = array();
    foreach($datas as $key => $value)
    {
       $flag_datas[$value['ymd']]['ymd'] = $value['ymd'];
       $flag_datas[$value['ymd']]['pt'] = '全部';
       $flag_datas[$value['ymd']]['zone'] = $_POST['zoneselect'] ? $_POST['zoneselect'] : '全部';
       $flag_datas[$value['ymd']]['zhuce'] += $value['zhuce'];
       $flag_datas[$value['ymd']]['payusers'] += $value['payusers'];
       $flag_datas[$value['ymd']]['paymoney'] += $value['paymoney'];
       $flag_datas[$value['ymd']]['newregpay'] += $value['newregpay'];
    }
    
    $datas = $flag_datas;
    $flag_datas = array();
    foreach($today_datas as $key => $value)
    {   
       $flag_datas[$value['ymd']]['pt'] = '全部';
       $flag_datas[$value['ymd']]['zone'] = $_POST['zoneselect'] ? $_POST['zoneselect'] : '全部';
       $flag_datas[$value['ymd']]['ymd'] = $value['ymd'];
       $flag_datas[$value['ymd']]['zhuce'] += $value['zhuce'];
       $flag_datas[$value['ymd']]['payusers'] += $value['payusers'];
       $flag_datas[$value['ymd']]['paymoney'] += $value['paymoney'];
       $flag_datas[$value['ymd']]['newregpay'] += $value['newregpay'];
    }

    $today_datas = $flag_datas;
//echo '<pre>';
//print_r($today_datas);
}


//--获取 [xx日注册 在yy日付费的 金额 人数]

$where1 = ' WHERE 1  ';
if($_POST['zoneselect'] != '')
{
    $where1 .= " AND zone = '".(int)$_POST['zoneselect']."'";
}

if($_POST['ptselect'] == 'all')
{
    $sql = "SELECT sum(sum_amount) AS m_total, count(1) AS c_total, payymd, CREATETIME 
        FROM sy_payymdacc $where1 
        GROUP BY payymd, CREATETIME";
}elseif($_POST['ptselect'] != 'all')
{

     $sql = "SELECT sum(sum_amount) AS m_total, count(1) AS c_total, payymd, CREATETIME 
        FROM sy_payymdacc $where1 AND SUBSTRING_INDEX(ACC,'-',1) = '".$_POST['ptselect']."'
        GROUP BY payymd, CREATETIME";
}

$stmt = $dbh_sy_ptzonedatas->prepare($sql);
$stmt->execute();
$datas30 = $stmt->fetchAll();
//echo '<pre>';
//print_r($datas30);

$datas30_result = array(); //array( array('注册日期'=>array( '支付日期'=>array('rmb'=>'100','accnu'=>'100').. ) ) )
foreach($datas30 as $k => $v)
{
    $datas30_result[$v['CREATETIME']][$v['payymd']]['rmb']  = $v['m_total']/100;
    $datas30_result[$v['CREATETIME']][$v['payymd']]['accnu'] = $v['c_total'];
}
$sql = '';
if($_POST['ptselect'] == 'all' && $_POST['zoneselect'] == '' )
{
    $ptflag = 0;
    $sql = "SELECT createymd, loginymd, sum(total) as total, pt, zone FROM sy_liucun GROUP BY  createymd,loginymd  ";
}elseif($_POST['ptselect'] != 'all' && $_POST['zoneselect'] == '' )
{
    $ptflag = 1;
    $sql = "SELECT createymd, loginymd, sum(total) as total, pt, zone FROM sy_liucun WHERE pt = :pt GROUP BY createymd,loginymd ";
}elseif($_POST['ptselect'] == 'all' && $_POST['zoneselect'] != '' )
{
    $ptflag = 0;
    $sql = "SELECT createymd, loginymd, sum(total) as total, pt, zone FROM sy_liucun WHERE zone = '".(int)$_POST['zoneselect']."' GROUP BY  createymd,loginymd  ";
}elseif($_POST['ptselect'] != 'all' && $_POST['zoneselect'] != '')
{
    $ptflag = 1;
    $sql = "SELECT createymd, loginymd, sum(total) as total, pt, zone FROM sy_liucun WHERE pt = :pt AND zone = '".(int)$_POST['zoneselect']."' GROUP BY  createymd,loginymd  ";
}




$stmt = $dbh_sy_ptzonedatas->prepare($sql);
if($ptflag) $stmt->bindParam(':pt', $_POST['ptselect']);
$stmt->execute();

$liucun_result = $stmt->fetchAll();
	foreach ($liucun_result as $key => $value) 
	{
		$d1 = new DateTime($value['createymd']);
		$d2 = new DateTime($value['loginymd']);

		$int = $d1->diff($d2)->days;

		$liucun_arr[$value['createymd']][$int] = $value['total'];
	}

//echo '<pre>';
//print_r($datas_zhuce);
if($_POST['zoneselect']=='')
{
    $datas_zhuce = $newzhuce_datas;
}
//print_r($datas_zhuce);
?>
    <form action='' method='post'>
        平台选择: 
        <select name='ptselect'>
            <option value='all' selected>全部</option>
            <?php foreach ($pt_arr as $key => $value) { echo "<option value='$key'",(($_POST['ptselect'] == $key) ? 'selected':''),">$key</option>";} ?>
        </select>
	区选择(默认全部):
	<input type='text' name='zoneselect' value='<?php echo $_POST['zoneselect']?>' >
	查询日期:
	<input class="easyui-datebox" editable='fasle' size='10' name='ymd_start' id='ymd_start' value='<?php echo $ymd_start;?>'  > ~ 
	<input class="easyui-datebox" editable='fasle' size='10' name='ymd_end' id='ymd_end' value='<?php echo $ymd_end;?>' >
        <input type='submit'>
    </form>
<a href="#" title="每日收入->注册人数:当日不含滚服注册人数<br>应用宝 注册 无法区分渠道<br>30日付费->45日:第31~45日付费和 60,90,120同理" class="easyui-tooltip">指标说明</a>
<div class="center">
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
        </tr>
        <?php
        foreach ($datas as $key => $value)
        {
            if(strtotime($value['ymd']) < strtotime($ymd_start) || strtotime($value['ymd']) > strtotime($ymd_end)  ) continue;
	    $arppu = $value['payusers'] ? number_format($value['paymoney']/$value['payusers']/100,2) : '';
            echo "<tr>
                <td class='e'>".$value['ymd']."</td>
                <td class='v'>".$value['zone']."</td>
                <td class='v'>".$value['pt']."</td>
                <td class='v'>".($value['paymoney']/100)."</td>
                <td class='v'>".$arppu."</td>
                <td class='v'>".$datas_zhuce[$value['ymd']]."</td>
                <td class='v'>".$value['payusers']."</td>
                <td class='v'>".$value['newregpay']."</td>
                <tr>";
        }
        foreach ($today_datas as $key => $value)
        {
            if(strtotime($value['ymd']) < strtotime($ymd_start) || strtotime($value['ymd']) > strtotime($ymd_end)  ) continue;
            $arppu = $value['payusers'] ? number_format($value['paymoney']*10/$value['payusers'],2) : '';
            echo "<tr>
                <td class='e'>".$value['ymd']."</td>
                <td class='v'>".$value['zone']."</td>
                <td class='v'>".$value['pt']."</td>
                <td class='v'>".($value['paymoney']*10)."</td>
                <td class='v'>".$arppu."</td>
                <td class='v'>".$datas_zhuce[$value['ymd']]."</td>
                <td class='v'>".$value['payusers']."</td>
                <td class='v'>".$value['newregpay']."</td>
                <tr>";
        }
        ?>
    </table>
    <br/>
<?php 
echo '<table border="0" cellpadding="3" width="1200">';
echo '<tr class="h" colspan="2">30日付费[RMB]</tr>';
echo '<tr class="h"><th>注册日期</th><th>注册人数</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th><th>11</th><th>12</th><th>13</th><th>14</th><th>15</th><th>16</th><th>17</th><th>18</th><th>19</th><th>20</th><th>21</th><th>22</th><th>23</th><th>24</th><th>25</th><th>26</th><th>27</th><th>28</th><th>29</th><th>30</th><th>45</th><th>60</th><th>90</th><th>120</th></tr>';
ksort($datas30_result);
//echo '<pre>';
//print_r($datas30_result);
//print_r($datas_zhuce);
foreach ($datas30_result as $key=>$value)
{
    if(strtotime($key) < strtotime($ymd_start) || strtotime($key) > strtotime($ymd_end)  ) continue;
    $str = "<tr><td class='e'>".$key."</td>";
    $str .= "<td class='v'>".$datas_zhuce[$key]."</td>";
    for($i=0;$i<=29;$i++)
    {
        $str.="<td class='v'>".($value[date('Y-m-d',strtotime($key)+86400*$i)]['rmb']*10)."</td>";
    }
    $falg45=0;
    $falg60=0;
    $falg90=0;
    $falg120=0;

    for($i=30;$i<=44;$i++) $falg45 += $value[date('Y-m-d',strtotime($key)+86400*$i)]['rmb']*10;
    for($i=45;$i<=59;$i++) $falg60 += $value[date('Y-m-d',strtotime($key)+86400*$i)]['rmb']*10;
    for($i=60;$i<=89;$i++) $falg90 += $value[date('Y-m-d',strtotime($key)+86400*$i)]['rmb']*10;
    for($i=90;$i<=119;$i++) $falg120 += $value[date('Y-m-d',strtotime($key)+86400*$i)]['rmb']*10;

    $str.="<td class='v'>".$falg45."</td>";
    $str.="<td class='v'>".$falg60."</td>";
    $str.="<td class='v'>".$falg90."</td>";
    $str.="<td class='v'>".$falg120."</td>";
        //$str.="<td class='v'>".($value[date('Y-m-d',strtotime($key)+86400*44)]['rmb']*10)."</td>";
        //$str.="<td class='v'>".($value[date('Y-m-d',strtotime($key)+86400*59)]['rmb']*10)."</td>";
        //$str.="<td class='v'>".($value[date('Y-m-d',strtotime($key)+86400*89)]['rmb']*10)."</td>";
        //$str.="<td class='v'>".($value[date('Y-m-d',strtotime($key)+86400*119)]['rmb']*10)."</td>";
    $str .="</tr>";
    echo $str;
}
echo '</table><br />';
echo '<table border="0" cellpadding="3" width="1200">';
echo '<tr class="h" colspan="2">30日付费[人数]</tr>';
echo '<tr class="h"><th>注册日期</th><th>注册人数</th><th>付费率</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th><th>11</th><th>12</th><th>13</th><th>14</th><th>15</th><th>16</th><th>17</th><th>18</th><th>19</th><th>20</th><th>21</th><th>22</th><th>23</th><th>24</th><th>25</th><th>26</th><th>27</th><th>28</th><th>29</th><th>30</th><th>45</th><th>60</th><th>90</th><th>120</th></tr>';
foreach ($datas30_result as $key=>$value)
{
    if(strtotime($key) < strtotime($ymd_start) || strtotime($key) > strtotime($ymd_end)  ) continue;
    $str = "<tr><td class='e'>".$key."</td>";
    $str .= "<td class='v'>".$datas_zhuce[$key]."</td>";
    $str .= "<td class='v'>".number_format($value[date('Y-m-d',strtotime($key)+86400*0)]['accnu']*100/$datas_zhuce[$key],2)." .%</td>";
    for($i=0;$i<=29;$i++)
    {
        $str.="<td class='v'>".$value[date('Y-m-d',strtotime($key)+86400*$i)]['accnu']."</td>";
    }
        //$str.="<td class='v'>".$value[date('Y-m-d',strtotime($key)+86400*44)]['accnu']."</td>";
        //$str.="<td class='v'>".$value[date('Y-m-d',strtotime($key)+86400*59)]['accnu']."</td>";
        //$str.="<td class='v'>".$value[date('Y-m-d',strtotime($key)+86400*89)]['accnu']."</td>";
        //$str.="<td class='v'>".$value[date('Y-m-d',strtotime($key)+86400*119)]['accnu']."</td>";
    $falg45=0;
    $falg60=0;
    $falg90=0;
    $falg120=0;

    for($i=30;$i<=44;$i++) $falg45 += $value[date('Y-m-d',strtotime($key)+86400*$i)]['accnu'];
    for($i=45;$i<=59;$i++) $falg60 += $value[date('Y-m-d',strtotime($key)+86400*$i)]['accnu'];
    for($i=60;$i<=89;$i++) $falg90 += $value[date('Y-m-d',strtotime($key)+86400*$i)]['accnu'];
    for($i=90;$i<=119;$i++) $falg120 += $value[date('Y-m-d',strtotime($key)+86400*$i)]['accnu'];

    $str.="<td class='v'>".$falg45."</td>";
    $str.="<td class='v'>".$falg60."</td>";
    $str.="<td class='v'>".$falg90."</td>";
    $str.="<td class='v'>".$falg120."</td>";
    $str .="</tr>";
    echo $str;
}
echo '</table><br />';
echo '<table border="0" cellpadding="3" width="1200">';
echo '<tr class="h" colspan="2">30日付费[arppu]</tr>';
echo '<tr class="h"><th>注册日期</th><th>注册人数</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th><th>11</th><th>12</th><th>13</th><th>14</th><th>15</th><th>16</th><th>17</th><th>18</th><th>19</th><th>20</th><th>21</th><th>22</th><th>23</th><th>24</th><th>25</th><th>26</th><th>27</th><th>28</th><th>29</th><th>30</th><th>45</th><th>60</th><th>90</th><th>120</th></tr>';
foreach ($datas30_result as $key=>$value)
{
    if(strtotime($key) < strtotime($ymd_start) || strtotime($key) > strtotime($ymd_end)  ) continue;
    $str = "<tr><td class='e'>".$key."</td>";
    $str .= "<td class='v'>".$datas_zhuce[$key]."</td>";
    for($i=0;$i<=29;$i++)
    {
        $flag = ceil($value[date('Y-m-d',strtotime($key)+86400*$i)]['rmb']*10/$value[date('Y-m-d',strtotime($key)+86400*$i)]['accnu']);
        $str.="<td class='v'>".($flag ? $flag : '')."</td>";
    }
        //$flag = ceil($value[date('Y-m-d',strtotime($key)+86400*44)]['rmb']*10/$value[date('Y-m-d',strtotime($key)+86400*44)]['accnu']);
        //$str.="<td class='v'>".($flag ? $flag : '')."</td>";
        //$flag = ceil($value[date('Y-m-d',strtotime($key)+86400*59)]['rmb']*10/$value[date('Y-m-d',strtotime($key)+86400*59)]['accnu']);
        //$str.="<td class='v'>".($flag ? $flag : '')."</td>";
        //$flag = ceil($value[date('Y-m-d',strtotime($key)+86400*89)]['rmb']*10/$value[date('Y-m-d',strtotime($key)+86400*89)]['accnu']);
        //$str.="<td class='v'>".($flag ? $flag : '')."</td>";
        //$flag = ceil($value[date('Y-m-d',strtotime($key)+86400*119)]['rmb']*10/$value[date('Y-m-d',strtotime($key)+86400*119)]['accnu']);
        //$str.="<td class='v'>".($flag ? $flag : '')."</td>";
    $falg45=0;
    $falg60=0;
    $falg90=0;
    $falg120=0;

    $f2alg45=0;
    $f2alg60=0;
    $f2alg90=0;
    $f2alg120=0;

    for($i=30;$i<=44;$i++) $f2alg45 += $value[date('Y-m-d',strtotime($key)+86400*$i)]['rmb']*10;
    for($i=45;$i<=59;$i++) $f2alg60 += $value[date('Y-m-d',strtotime($key)+86400*$i)]['rmb']*10;
    for($i=60;$i<=89;$i++) $f2alg90 += $value[date('Y-m-d',strtotime($key)+86400*$i)]['rmb']*10;
    for($i=90;$i<=119;$i++) $f2alg120 += $value[date('Y-m-d',strtotime($key)+86400*$i)]['rmb']*10;

    for($i=30;$i<=44;$i++) $falg45 += $value[date('Y-m-d',strtotime($key)+86400*$i)]['accnu'];
    for($i=45;$i<=59;$i++) $falg60 += $value[date('Y-m-d',strtotime($key)+86400*$i)]['accnu'];
    for($i=60;$i<=89;$i++) $falg90 += $value[date('Y-m-d',strtotime($key)+86400*$i)]['accnu'];
    for($i=90;$i<=119;$i++) $falg120 += $value[date('Y-m-d',strtotime($key)+86400*$i)]['accnu'];

    $flag = ceil($f2alg45/$falg45);
    $str.="<td class='v'>".($flag ? $flag : '')."</td>";
    $flag = ceil($f2alg60/$falg60);
    $str.="<td class='v'>".($flag ? $flag : '')."</td>";
    $flag = ceil($f2alg90/$falg90);
    $str.="<td class='v'>".($flag ? $flag : '')."</td>";
    $flag = ceil($f2alg120/$falg120);
    $str.="<td class='v'>".($flag ? $flag : '')."</td>";

    $str .="</tr>";
    echo $str;
}
echo '</table><br />';

echo '<table border="0" cellpadding="3" width="1200">';
echo '<tr class="h" colspan="2">留存人数[arppu]</tr>';
echo '<tr class="h"><th>注册日期</th><th>注册人数</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th><th>11</th><th>12</th><th>13</th><th>14</th><th>15</th><th>16</th><th>17</th><th>18</th><th>19</th><th>20</th><th>21</th><th>22</th><th>23</th><th>24</th><th>25</th><th>26</th><th>27</th><th>28</th><th>29</th><th>30</th><th>45</th><th>60</th><th>90</th><th>120</th></tr>';
foreach ($new_liucun_arr as $key=>$value)
{
	if($key == '1970-01-01') continue;
if(strtotime($key) < strtotime($ymd_start) || strtotime($key) > strtotime($ymd_end)  ) continue;
	$str = "<tr><td class='e'>".$key."</td>";
	$str .= "<td class='v'>".$datas_zhuce[$key]."</td>";
	for($i=1;$i<=30;$i++)
	{   
		$flag = isset($value[$i]) ? $value[$i] : '';$str.="<td class='v'>".($flag ? $flag : '')."</td>";
	}
		$flag = isset($value['45']) ? $value['45'] : '';$str.="<td class='v'>".($flag ? $flag : '')."</td>";
		$flag = isset($value['60']) ? $value['60'] : '';$str.="<td class='v'>".($flag ? $flag : '')."</td>";
		$flag = isset($value['90']) ? $value['90'] : '';$str.="<td class='v'>".($flag ? $flag : '')."</td>";
		$flag = isset($value['120']) ? $value['120'] : '';$str.="<td class='v'>".($flag ? $flag : '')."</td>";
	$str .="</tr>";
	echo $str;
}
echo '</table><br />';

echo '<table border="0" cellpadding="3" width="1200">';
echo '<tr class="h" colspan="2">留存率[arppu]</tr>';
echo '<tr class="h"><th>注册日期</th><th>注册人数</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th><th>11</th><th>12</th><th>13</th><th>14</th><th>15</th><th>16</th><th>17</th><th>18</th><th>19</th><th>20</th><th>21</th><th>22</th><th>23</th><th>24</th><th>25</th><th>26</th><th>27</th><th>28</th><th>29</th><th>30</th><th>45</th><th>60</th><th>90</th><th>120</th></tr>';
foreach ($new_liucun_arr as $key=>$value)
{
	if($key == '1970-01-01') continue;
	if(strtotime($key) < strtotime($ymd_start) || strtotime($key) > strtotime($ymd_end)  ) continue;
	$str = "<tr><td class='e'>".$key."</td>";
	$str .= "<td class='v'>".$datas_zhuce[$key]."</td>";

	for($i=1;$i<=30;$i++)
	{   
	    //if($key=='2015-05-08' && $i=='1' ){ $str .= "<td class='v'>35.53</td>";continue;  }
		$flag = isset($value[$i]) ? $value[$i] : '';
	    $nu = (number_format(($flag ? $flag : '')*100/$datas_zhuce[$key],2) > 0) ? number_format(($flag ? $flag : '')*100/$datas_zhuce[$key],2) : '';
		$str.="<td class='v'>".$nu."</td>";
	}

		$flag = isset($value['45']) ? $value['45'] : '';
	    $nu = (number_format(($flag ? $flag : '')*100/$datas_zhuce[$key],2) > 0) ? number_format(($flag ? $flag : '')*100/$datas_zhuce[$key],2) : '';
		$str.="<td class='v'>".$nu."</td>";

		$flag = isset($value['60']) ? $value['60'] : '';
	    $nu = (number_format(($flag ? $flag : '')*100/$datas_zhuce[$key],2) > 0) ? number_format(($flag ? $flag : '')*100/$datas_zhuce[$key],2) : '';
		$str.="<td class='v'>".$nu."</td>";
	
		$flag = isset($value['90']) ? $value['90'] : '';
	    $nu = (number_format(($flag ? $flag : '')*100/$datas_zhuce[$key],2) > 0) ? number_format(($flag ? $flag : '')*100/$datas_zhuce[$key],2) : '';
		$str.="<td class='v'>".$nu."</td>";
	
		$flag = isset($value['120']) ? $value['120'] : '';
	    $nu = (number_format(($flag ? $flag : '')*100/$datas_zhuce[$key],2) > 0) ? number_format(($flag ? $flag : '')*100/$datas_zhuce[$key],2) : '';
		$str.="<td class='v'>".$nu."</td>";
	
	$str .="</tr>";
	echo $str;
}
echo '</table><br />';
 ?>


</div>
</body>
</html>
<?php
function today_datas()
{
    //zone_msg
    $dbh_zone_msg = new PDO('mysql:host=10.104.222.134;dbname=fentiansj;port=3306;', 'root', 'hoolai@123');
    $dbh_zone_msg->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh_zone_msg->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh_zone_msg->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    $sql = "SELECT zone_id, server_id, zones, domians, mysql_ip, mysql_port, mysql_dbName FROM zone_msg ";
    $stmt = $dbh_zone_msg->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();

    $now = time();
    $yesterday = date('Y-m-d', $now);
    $return_arr = array();

    $y_stamp_start = strtotime($yesterday);
    $y_stamp_end = $y_stamp_start + 86399;

    foreach ($result as $key => $value)
    {
        if($value['zone_id'] == 4000) continue;

        $dbh_charbase = new PDO('mysql:host='.$value['mysql_ip'].';dbname='.$value['mysql_dbName'].';port='.$value['mysql_port'].';', 'root', 'hoolai@123');
        $dbh_charbase->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh_charbase->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $dbh_charbase->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        $sql = "SELECT COUNT(*) AS nu, SUBSTRING_INDEX(ACCNAME,'-',1) AS pt, ZONE FROM CHARBASE WHERE CREATETIME >= :y_stamp_start AND CREATETIME <= :y_stamp_end GROUP BY pt ";
        $stmt = $dbh_charbase->prepare($sql);
        $stmt->bindParam(':y_stamp_start', $y_stamp_start);
        $stmt->bindParam(':y_stamp_end', $y_stamp_end);
        $stmt->execute();

        $zhuce_datas = $stmt->fetchAll();
        
        foreach ($zhuce_datas as $key => $value) 
        {
            $return_arr[$value['ZONE']][$value['pt']]['ymd'] = $yesterday;
            $return_arr[$value['ZONE']][$value['pt']]['zhuce'] = $value['nu'];
        }
    }


    //TRADE 库
    $dbh_trade = new PDO('mysql:host=117.103.235.92;dbname=BILL;port=3306;', 'root', 'hoolai@123');
    $dbh_trade->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh_trade->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh_trade->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh_trade->query("SET NAMES utf8");

    $sql = "SELECT COUNT(DISTINCT ACC) AS nu, PF,ZONE,SUM(PRICE)/1000 AS sum_amount FROM TRADE  WHERE ZONE<=1000 AND  STATUS = 1 AND TIME >= :y_stamp_start AND TIME <= :y_stamp_end GROUP BY PF,ZONE ";
    $stmt = $dbh_trade->prepare($sql);
    $stmt->bindParam(':y_stamp_start', $y_stamp_start);
    $stmt->bindParam(':y_stamp_end', $y_stamp_end);
    $stmt->execute();

    $trade_datas = $stmt->fetchAll();

    foreach ($trade_datas as $key => $value) 
    {
     //   $return_arr[$value['ZONE']][$value['PF']]['paymoney'] = $value['sum_amount'];
       // $return_arr[$value['ZONE']][$value['PF']]['payusers'] = $value['nu'];
        $return_arr[$value['ZONE']][rtrim($value['PF'],'-0')]['paymoney'] = $value['sum_amount'];
        $return_arr[$value['ZONE']][rtrim($value['PF'],'-0')]['payusers'] = $value['nu'];
    }

    //汇总库 sy_ptzonedatas sy_payymdacc
    $dbh_sy_ptzonedatas = new PDO('mysql:host=117.103.235.92;dbname=shouyou;port=3306;', 'root', 'hoolai@123');
    $dbh_sy_ptzonedatas->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh_sy_ptzonedatas->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh_sy_ptzonedatas->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh_sy_ptzonedatas->query("SET NAMES utf8");

    $sql = "SELECT COUNT(*) AS nu, payymd, SUBSTRING_INDEX(ACC,'-',1) AS pt, ZONE FROM sy_payymdacc WHERE payymd = CREATETIME AND CREATETIME =:yesterday GROUP BY pt,ZONE ";
    $stmt = $dbh_sy_ptzonedatas->prepare($sql);
    $stmt->bindParam(':yesterday', $yesterday);
    $stmt->execute();
    $regpay_datas = $stmt->fetchAll();


    foreach ($regpay_datas as $key => $value) 
    {
        $return_arr[$value['ZONE']][$value['pt']]['newregpay'] = $value['nu'];
    }

    $return = array();
    foreach($return_arr as $key => $value)
    {
        foreach ($value as $k => $v)
        {
            $return[] = array('ymd'=>isset($v['ymd'])?$v['ymd']:$yesterday,'pt'=>$k,'zone'=>$key,'zhuce'=>isset($v['zhuce'])?$v['zhuce']:'0','payusers'=>$v['payusers'],'paymoney'=>$v['paymoney'],'newregpay'=>$v['newregpay']);
        }
    }
    return $return;

}
