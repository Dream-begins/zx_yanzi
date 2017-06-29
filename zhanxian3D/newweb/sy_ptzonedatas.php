<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <title></title>
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
error_reporting(0);

//汇总库 sy_ptzonedatas sy_payymdacc
$dbh_sy_ptzonedatas = new PDO('mysql:host=117.103.235.92;dbname=shouyou;port=3306;', 'root', 'hoolai@123');
$dbh_sy_ptzonedatas->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh_sy_ptzonedatas->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$dbh_sy_ptzonedatas->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbh_sy_ptzonedatas->query("SET NAMES utf8");

$sql = "SELECT ymd, pt, zone, zhuce, payusers, paymoney, newregpay FROM sy_ptzonedatas ";
$stmt = $dbh_sy_ptzonedatas->prepare($sql);
$stmt->execute();
$datas = $stmt->fetchAll();

$today_datas = today_datas();

$pt_arr = array();
$datas_zhuce = array();


foreach ($datas as $key => $value) 
{
    $pt_arr[$value['pt']] = '';
    if($value['pt']==$_POST['ptselect']) $datas_zhuce[$value['ymd']] = $value['zhuce'];
}

foreach ($today_datas as $key => $value) 
{
    $pt_arr[$value['pt']] = '';
    if($value['pt']==$_POST['ptselect']) $datas_zhuce[$value['ymd']] = $value['zhuce'];
}
//--获取 [xx日注册 在yy日付费的 金额 人数]
$sql = "SELECT sum(sum_amount) AS m_total, count(1) AS c_total, payymd, CREATETIME 
        FROM sy_payymdacc WHERE SUBSTRING_INDEX(ACC,'-',1) = '".$_POST['ptselect']."'
        GROUP BY payymd, CREATETIME";

$stmt = $dbh_sy_ptzonedatas->prepare($sql);
$stmt->execute();
$datas30 = $stmt->fetchAll();

$datas30_result = array(); //array( array('注册日期'=>array( '支付日期'=>array('rmb'=>'100','accnu'=>'100').. ) ) )
foreach($datas30 as $k => $v)
{
    $datas30_result[$v['CREATETIME']][$v['payymd']]['rmb']  = $v['m_total']/100;
    $datas30_result[$v['CREATETIME']][$v['payymd']]['accnu'] = $v['c_total'];
}

$sql = "SELECT createymd, loginymd, total, pt, zone FROM sy_liucun WHERE pt = :pt ";
$stmt = $dbh_sy_ptzonedatas->prepare($sql);
$stmt->bindParam(':pt', $_POST['ptselect']);
$stmt->execute();
$liucun_result = $stmt->fetchAll();
	foreach ($liucun_result as $key => $value) 
	{
		$d1 = new DateTime($value['createymd']);
		$d2 = new DateTime($value['loginymd']);

		$int = $d1->diff($d2)->days;

		$liucun_arr[$value['createymd']][$int] = $value['total'];
	}

?>
    <form action='' method='post'>
        平台选择: 
        <select name='ptselect'>
            <?php foreach ($pt_arr as $key => $value) { echo "<option value='$key'",(($_POST['ptselect'] == $key) ? 'selected':''),">$key</option>";} ?>
        </select>
        <input type='submit'>
    </form>
<div class="center">
    <table border="0" cellpadding="3" width="600">
        <tr class="h" colspan="3">每日收入[区分平台]</tr>
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
	    if($_POST['ptselect'] != $value['pt']) continue;
	    $arppu = $value['payusers'] ? number_format($value['paymoney']/$value['payusers'],2) : '';
            echo "<tr>
                <td class='e'>".$value['ymd']."</td>
                <td class='v'>".$value['zone']."</td>
                <td class='v'>".$value['pt']."</td>
                <td class='v'>".$value['paymoney']."</td>
                <td class='v'>".$arppu."</td>
                <td class='v'>".$value['zhuce']."</td>
                <td class='v'>".$value['payusers']."</td>
                <td class='v'>".$value['newregpay']."</td>
                <tr>";
        }
        foreach ($today_datas as $key => $value)
        {
	    if($_POST['ptselect'] != $value['pt']) continue;
            $arppu = $value['payusers'] ? number_format($value['paymoney']/$value['payusers'],2) : '';
            echo "<tr>
                <td class='e'>".$value['ymd']."</td>
                <td class='v'>".$value['zone']."</td>
                <td class='v'>".$value['pt']."</td>
                <td class='v'>".$value['paymoney']."</td>
                <td class='v'>".$arppu."</td>
                <td class='v'>".$value['zhuce']."</td>
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
foreach ($datas30_result as $key=>$value)
{
    $str = "<tr><td class='e'>".$key."</td>";
    $str .= "<td class='v'>".$datas_zhuce[$key]."</td>";
    for($i=0;$i<=29;$i++)
    {
        $str.="<td class='v'>".$value[date('Y-m-d',strtotime($key)+86400*$i)]['rmb']."</td>";
    }
        $str.="<td class='v'>".$value[date('Y-m-d',strtotime($key)+86400*44)]['rmb']."</td>";
        $str.="<td class='v'>".$value[date('Y-m-d',strtotime($key)+86400*59)]['rmb']."</td>";
        $str.="<td class='v'>".$value[date('Y-m-d',strtotime($key)+86400*89)]['rmb']."</td>";
        $str.="<td class='v'>".$value[date('Y-m-d',strtotime($key)+86400*119)]['rmb']."</td>";
    $str .="</tr>";
    echo $str;
}
echo '</table><br />';
echo '<table border="0" cellpadding="3" width="1200">';
echo '<tr class="h" colspan="2">30日付费[人数]</tr>';
echo '<tr class="h"><th>注册日期</th><th>注册人数</th><th>付费率</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th><th>11</th><th>12</th><th>13</th><th>14</th><th>15</th><th>16</th><th>17</th><th>18</th><th>19</th><th>20</th><th>21</th><th>22</th><th>23</th><th>24</th><th>25</th><th>26</th><th>27</th><th>28</th><th>29</th><th>30</th><th>45</th><th>60</th><th>90</th><th>120</th></tr>';
foreach ($datas30_result as $key=>$value)
{
    $str = "<tr><td class='e'>".$key."</td>";
    $str .= "<td class='v'>".$datas_zhuce[$key]."</td>";
    $str .= "<td class='v'>".number_format($value[date('Y-m-d',strtotime($key)+86400*0)]['accnu']*100/$datas3[$key],2)." .%</td>";
    for($i=0;$i<=29;$i++)
    {
        $str.="<td class='v'>".$value[date('Y-m-d',strtotime($key)+86400*$i)]['accnu']."</td>";
    }
        $str.="<td class='v'>".$value[date('Y-m-d',strtotime($key)+86400*44)]['accnu']."</td>";
        $str.="<td class='v'>".$value[date('Y-m-d',strtotime($key)+86400*59)]['accnu']."</td>";
        $str.="<td class='v'>".$value[date('Y-m-d',strtotime($key)+86400*89)]['accnu']."</td>";
        $str.="<td class='v'>".$value[date('Y-m-d',strtotime($key)+86400*119)]['accnu']."</td>";
    $str .="</tr>";
    echo $str;
}
echo '</table><br />';
echo '<table border="0" cellpadding="3" width="1200">';
echo '<tr class="h" colspan="2">30日付费[arppu]</tr>';
echo '<tr class="h"><th>注册日期</th><th>注册人数</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th><th>11</th><th>12</th><th>13</th><th>14</th><th>15</th><th>16</th><th>17</th><th>18</th><th>19</th><th>20</th><th>21</th><th>22</th><th>23</th><th>24</th><th>25</th><th>26</th><th>27</th><th>28</th><th>29</th><th>30</th><th>45</th><th>60</th><th>90</th><th>120</th></tr>';
foreach ($datas30_result as $key=>$value)
{
    $str = "<tr><td class='e'>".$key."</td>";
    $str .= "<td class='v'>".$datas_zhuce[$key]."</td>";
    for($i=0;$i<=29;$i++)
    {
        $flag = ceil($value[date('Y-m-d',strtotime($key)+86400*$i)]['rmb']/$value[date('Y-m-d',strtotime($key)+86400*$i)]['accnu']);
        $str.="<td class='v'>".($flag ? $flag : '')."</td>";
    }
        $flag = ceil($value[date('Y-m-d',strtotime($key)+86400*44)]['rmb']/$value[date('Y-m-d',strtotime($key)+86400*44)]['accnu']);
        $str.="<td class='v'>".($flag ? $flag : '')."</td>";
        $flag = ceil($value[date('Y-m-d',strtotime($key)+86400*59)]['rmb']/$value[date('Y-m-d',strtotime($key)+86400*59)]['accnu']);
        $str.="<td class='v'>".($flag ? $flag : '')."</td>";
        $flag = ceil($value[date('Y-m-d',strtotime($key)+86400*89)]['rmb']/$value[date('Y-m-d',strtotime($key)+86400*89)]['accnu']);
        $str.="<td class='v'>".($flag ? $flag : '')."</td>";
        $flag = ceil($value[date('Y-m-d',strtotime($key)+86400*119)]['rmb']/$value[date('Y-m-d',strtotime($key)+86400*119)]['accnu']);
        $str.="<td class='v'>".($flag ? $flag : '')."</td>";
    $str .="</tr>";
    echo $str;
}
echo '</table><br />';

echo '<table border="0" cellpadding="3" width="1200">';
echo '<tr class="h" colspan="2">留存人数[arppu]</tr>';
echo '<tr class="h"><th>注册日期</th><th>注册人数</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th><th>11</th><th>12</th><th>13</th><th>14</th><th>15</th><th>16</th><th>17</th><th>18</th><th>19</th><th>20</th><th>21</th><th>22</th><th>23</th><th>24</th><th>25</th><th>26</th><th>27</th><th>28</th><th>29</th><th>30</th><th>45</th><th>60</th><th>90</th><th>120</th></tr>';
foreach ($liucun_arr as $key=>$value)
{
	if($key == '1970-01-01') continue;
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
foreach ($liucun_arr as $key=>$value)
{
	if($key == '1970-01-01') continue;
	$str = "<tr><td class='e'>".$key."</td>";
	$str .= "<td class='v'>".$datas_zhuce[$key]."</td>";

	for($i=1;$i<=30;$i++)
	{   
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

    $sql = "SELECT COUNT(DISTINCT ACC) AS nu, PF,ZONE,SUM(AMOUNT)/100 AS sum_amount FROM TRADE WHERE STATUS = 1 AND TIME >= :y_stamp_start AND TIME <= :y_stamp_end GROUP BY PF,ZONE ";
    $stmt = $dbh_trade->prepare($sql);
    $stmt->bindParam(':y_stamp_start', $y_stamp_start);
    $stmt->bindParam(':y_stamp_end', $y_stamp_end);
    $stmt->execute();

    $trade_datas = $stmt->fetchAll();

    foreach ($trade_datas as $key => $value) 
    {
        $return_arr[$value['ZONE']][$value['PF']]['paymoney'] = $value['sum_amount'];
        $return_arr[$value['ZONE']][$value['PF']]['payusers'] = $value['nu'];
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
            $return[] = array('ymd'=>$v['ymd'],'pt'=>$k,'zone'=>$key,'zhuce'=>$v['zhuce'],'payusers'=>$v['payusers'],'paymoney'=>$v['paymoney'],'newregpay'=>$v['newregpay']);
        }
    }

    return $return;

}
