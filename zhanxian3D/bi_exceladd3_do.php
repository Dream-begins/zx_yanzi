<?php 
ini_set('display_errors', '1');
error_reporting (-1);
set_time_limit(200); 
date_default_timezone_set("PRC");
ini_set('default_charset','utf-8');
include_once "common.php";
include_once "checklogin.php";
$filename = getParam("file2",null);
error_reporting (-1);
$startTime=microtime(true);
//$get_server2name_arr = get_server2name();
$get_server2zone_arr = get_server2zone();

if($filename == null) exit('上传文件先');

$temp = strrpos($filename,"\\");

$filename = substr($filename,$temp+1);
$filename = "excel/".$filename;

$excel2array = excel2array($filename);

$flag_title = array_shift($excel2array);

define('PDO_ZoneMsgInfo_host', 'mysql:host=10.104.222.134;dbname=fentiansj;port=3306;charset=utf8');
define('PDO_ZoneMsgInfo_root', 'root');
define('PDO_ZoneMsgInfo_pass', 'hoolai@123');

define('PDO_TradeInfo_host', 'mysql:host=117.103.235.92;dbname=BILL;port=3306;charset=utf8');
define('PDO_TradeInfo_root', 'root');
define('PDO_TradeInfo_pass', 'hoolai@123');

define('PDO_Trade_syInfo_host', 'mysql:host=117.103.235.92;dbname=BILL;port=3306;charset=utf8');
define('PDO_Trade_syInfo_root', 'root');
define('PDO_Trade_syInfo_pass', 'hoolai@123');

define('PDO_AllUserInfo_host', 'mysql:host=117.103.235.92;dbname=GameData;port=3306;charset=utf8');
define('PDO_AllUserInfo_root', 'root');
define('PDO_AllUserInfo_pass', 'hoolai@123');

define('PDO_gonggaoInfo_host', 'mysql:host=117.103.235.92;dbname=admin;port=3306;charset=utf8');
define('PDO_gonggaoInfo_root', 'root');
define('PDO_gonggaoInfo_pass', 'hoolai@123');

define('PDO_shouyou_host', 'mysql:host=117.103.235.92;dbname=shouyou;port=3306;charset=utf8');
define('PDO_shouyou_root', 'root');
define('PDO_shouyou_pass', 'hoolai@123');

define('PDO_ZONE_ROOT', 'root');
define('PDO_ZONE_PASS', 'hoolai@123');


include "./newweb/m_zone_msg.php";
include './newweb/m_charbase.php';

$zone_msg = new ZoneMsgInfo;

$flag_client_id = '';

foreach ($excel2array as $key => $value)
{
	if((int)$value['1']==0) continue;

	if($flag_client_id != $value['1'])
		$zones_info = $zone_msg->zones2infos( $value['1']);
    if(!isset($zones_info['server_id'])) continue;
	$charbase = new CharbaseInfo($zones_info);
	$a = $charbase->get_acc_list('',$value['2']);

        $excel2array[$key]['0'] = isset($a['0']['ACCNAME'])?$a['0']['ACCNAME']:'';
        $excel2array[$key]['1'] = $value['1'];
        $excel2array[$key]['2'] = $value['2'];
        $excel2array[$key]['3'] = isset($a['0']['LEVEL'])?$a['0']['LEVEL']:'';
        $excel2array[$key]['4'] = isset($a['0']['VIP'])?$a['0']['VIP']:'';
        $excel2array[$key]['5'] = isset($a['0']['ZHENQI'])?$a['0']['ZHENQI']:'';
        $excel2array[$key]['6'] = isset($a['0']['COUNTRY'])?$a['0']['COUNTRY']:'';
        $excel2array[$key]['7'] = isset($a['0']['MONEY1'])?$a['0']['MONEY1']:'';
        $excel2array[$key]['8'] = isset($a['0']['MONEY3'])?$a['0']['MONEY3']:'';
        $excel2array[$key]['9'] = isset($a['0']['CREATETIME'])?$a['0']['CREATETIME']:'';
        $excel2array[$key]['10'] = isset($a['0']['LASTACTIVEDATE'])?$a['0']['LASTACTIVEDATE']:'';
        $excel2array[$key]['11'] = isset($a['0']['CREATEIP'])? (my_long2ip( (float)$a['0']['CREATEIP']).'|'.$a['0']['CREATEIP']):'';
}


function my_long2ip($ip)
{
    $a = ((float)$ip >> 24) & 0xff;
    $b = ((float)$ip >> 16) & 0xff;
    $c = ((float)$ip >> 8) & 0xff;
    $d = ((float)$ip >> 0) & 0xff;

    return $d.'.'.$c.'.'.$b.'.'.$a;
}

ksort($excel2array);

if( count($excel2array) )
{
	require_once "exportExcel.php";
    $flag_title[0] = 'id';
    $flag_title[1] = '区';
    $flag_title[2] = '角色名';
    $flag_title[3] = '等级';
    $flag_title[4] = 'vip';
    $flag_title[5] = '战力';
    $flag_title[6] = '宗派';
    $flag_title[7] = '元宝';
    $flag_title[8] = '返还元宝';
    $flag_title[9] = '创建时间';
    $flag_title[10] = '最后登录时间';
    $flag_title[11] = 'IP';
	@export($flag_title, $excel2array, date('Y-m-d')." 帐号明细导出.xls");
} 

function excel2array( $filename, $sheetname=0)
{
	require_once "reader.php";
	include_once "Classes/PHPExcel.php";

	$PHPReader = new PHPExcel_Reader_Excel2007();

	if(!$PHPReader->canRead( $filename ))
	{
		$PHPReader = new PHPExcel_Reader_Excel5();
		if(!$PHPReader->canRead( $filename ))
		{
			echo 'no Excel';
			return;
		}  
	} 

	$PHPExcel = $PHPReader->load( $filename );
	$currentSheet = $PHPExcel->getSheet($sheetname);
	$allColumn = $currentSheet->getHighestColumn();
	$allColumn = zz2int($allColumn);
	$allRow = $currentSheet->getHighestRow();

	$return_arr = array();
	$fanyi = array();

	for($i=1; $i<=$allRow; $i++)
	{  
		for($j=1;$j<=$allColumn;$j++)
		{  
			$addr = int2zz($j).$i;

			$cell = $currentSheet->getCell($addr)->getValue();

			if($cell instanceof PHPExcel_RichText)
			{
				$cell = $cell->__toString();
			}
			$return_arr[$i][$j] = $cell;
		}
	}
	return $return_arr;
}


function zz2int($i)
{
	return strlen($i) == 2 ? (ord($i['0']) -64) * 26 + (ord($i['1']) -64) : $i = (ord($i['0']) -64) ;
}

function int2zz($i)
{
	return ($i <= 26) ? chr(64+$i) : chr(intval( $i/26 ) + 64) . chr($i % 26 + 64);
}



function get_server2name()
{
	$dbh = new PDO('mysql:host=10.104.222.134;dbname=fentiansj;port=3306;charset=utf8', 'root', 'hoolai@123');
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$dbh->query("SET NAMES utf8");

	$stmt = $dbh->prepare("SELECT zone_num,domain_num,zone_name FROM zone_domain2 ");
	$stmt->execute();
	$result = $stmt->fetchAll();

	$return_arr = array();

	foreach ($result as $key => $value)
	{
		$return_arr[$value['domain_num']] = $value['zone_name'];
	}

	return $return_arr;
}

function get_server2zone()
{
	$dbh = new PDO('mysql:host=10.104.222.134;dbname=fentiansj;port=3306;charset=utf8', 'root', 'hoolai@123');
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$dbh->query("SET NAMES utf8");

	$stmt = $dbh->prepare("SELECT zone_id,server_id FROM zone_msg ");
	$stmt->execute();
	$result = $stmt->fetchAll();

	$return_arr = array();

	foreach ($result as $key => $value)
	{
		$return_arr[$value['server_id']] = $value['zone_id'];
	}

	return $return_arr;
}



