<?php
define('WITHOUT_AUTH',1);
include "h_header.php";
$action = isset($_GET['action']) ? $_GET['action'] : NULL;
if($action == "list")
{

    $page = (int)$_POST['page'];
    $rows = (int)$_POST['rows'];
    $sort = isset($_POST['sort']) ?  htmlspecialchars( $_POST['sort'] )  : 'SUM_YB';
    $order = isset($_POST['order']) ?  htmlspecialchars( $_POST['order'] )  : 'desc';
    $zones = isset($_POST['zones']) ? (int)$_POST['zones'] : '';
    $zone_id = isset($_POST['zone_id']) ? (int)$_POST['zone_id'] : '';
    $detailed = isset($_POST['detailed'])  && strtolower($_POST['detailed']) == 'true' ? true : false;
    $dis_zone = isset($_POST['dis_zone'])  && strtolower($_POST['dis_zone']) == 'true' ? true : false;
    $acc = isset($_POST['acc']) ? htmlspecialchars( $_POST['acc'] ) : '';
    $ymd_start = isset($_POST['ymd_start']) ? $_POST['ymd_start'] : '';
    $ymd_end = isset($_POST['ymd_end']) ? $_POST['ymd_end'] : '';
    
    $PF = isset($_POST['PF']) ? $_POST['PF'] : '';


    if($page <= 0) {
        $page = 1;
    }
 
	include "m_zone_msg.php";
	include "m_charbase.php";
    $zone_msg = new ZoneMsgInfo;
 
	$domians2zoneid_array = $zone_msg->domians2zoneid_array();
    $domians2zones_array = $zone_msg->domians2zones_array();

    include "m_trade_sy.php";
    $trade = new Trade_syInfo;

    $where = array();

    if($zones)
    {
        $domian = $zone_msg->zones2infos($zones);
        $where['ZONE'] = $domian['domian'];
    }

    if($zone_id)
    {
        $domians = $zone_msg->zone_id2infos($zone_id);
        $where['ZONE'] = trim($domians['domians'], ',');
    }

    if($acc)
    {
        $where['ACC'] = $acc;
    }

    if($ymd_start)
    {
        $where['TIME1'] = strtotime($ymd_start);
    }

    if($ymd_end)
    {
        $where['TIME2'] = strtotime($ymd_end) + 86399;
    }

    $where['PF'] = $PF;
    $return_arr = array();
    
    $orderby = ' ORDER BY  ' . $sort . ' ' .$order;
    $limit = ' LIMIT ' . ($page-1)*$rows . ',' . $rows;

    if( $detailed )
    {
        $return_arr['rows'] = $trade->detailed_list( $where, $orderby, $limit );
        $return_arr['total'] = $trade->detailed_total( $where );

        foreach ($return_arr['rows'] as $key => $value)
		{
			$zone_msg11 = $zone_msg->domians2infos($value['ZONE']);
			$charbase = new CharbaseInfo($zone_msg11);
            $datasss = $charbase->get_acc_list( $value['ACC'], $value['NAME'], '', 'limit 1' );
            $return_arr['rows'][$key]['VIP'] = $datasss['0']['VIP'];
            $return_arr['rows'][$key]['CTIME'] = $datasss['0']['CREATETIME'];
			$return_arr['rows'][$key]['zone_id'] = $domians2zoneid_array[$value['ZONE']];
            $return_arr['rows'][$key]['zone'] = $domians2zones_array[$value['ZONE']];
        }

        exit( json_encode($return_arr) );
    }

    if( !$detailed && !$dis_zone )
    {
        $return_arr['rows'] = $trade->acc_group_list( $where, $orderby, $limit );
        $return_arr['total'] = $trade->acc_group_total( $where );

        foreach ($return_arr['rows'] as $key => $value)
		{
			$zone_msg11 = $zone_msg->domians2infos($value['ZONE']);
			$charbase = new CharbaseInfo($zone_msg11);
			$datasss = $charbase->get_acc_list( $value['ACC'], $value['NAME'], '', 'limit 1' );
			$return_arr['rows'][$key]['VIP'] = $datasss['0']['VIP'];
            $return_arr['rows'][$key]['CTIME'] = $datasss['0']['CREATETIME'];
            $return_arr['rows'][$key]['zone_id'] = $domians2zoneid_array[$value['ZONE']];
            $return_arr['rows'][$key]['zone'] = $domians2zones_array[$value['ZONE']];
        }
        exit( json_encode($return_arr) );     
    }

    if( !$detailed && $dis_zone )
    {
        $return_arr['rows'] = $trade->zone_acc_group_list( $where, $orderby, $limit );
        $return_arr['total'] = $trade->zone_acc_group_total( $where );

        foreach ($return_arr['rows'] as $key => $value)
        {
			$zone_msg11 = $zone_msg->domians2infos($value['ZONE']);
			$charbase = new CharbaseInfo($zone_msg11);
            $datasss = $charbase->get_acc_list( $value['ACC'], $value['NAME'], '', 'limit 1' );
            $return_arr['rows'][$key]['VIP'] = $datasss['0']['VIP'];
            $return_arr['rows'][$key]['CTIME'] = $datasss['0']['CREATETIME'];
            $return_arr['rows'][$key]['zone_id'] = $domians2zoneid_array[$value['ZONE']];
            $return_arr['rows'][$key]['zone'] = $domians2zones_array[$value['ZONE']];
        }

        exit( json_encode($return_arr) );
    }
}
if($action == "putcsv")
{
	$sort = isset($_GET['sort']) ?  htmlspecialchars( $_GET['sort'] )  : 'SUM_YB';
	$order = isset($_GET['order']) ?  htmlspecialchars( $_GET['order'] )  : 'desc';
	$zones = isset($_GET['zones']) ? (int)$_GET['zones'] : '';
	$zone_id = isset($_GET['zone_id']) ? (int)$_GET['zone_id'] : '';
	$detailed = isset($_GET['detailed'])  && strtolower($_GET['detailed']) == 'true' ? true : false;
	$dis_zone = isset($_GET['dis_zone'])  && strtolower($_GET['dis_zone']) == 'true' ? true : false;
	$acc = isset($_GET['acc']) ? htmlspecialchars( $_GET['acc'] ) : '';
	$ymd_start = isset($_GET['ymd_start']) ? $_GET['ymd_start'] : '';
	$ymd_end = isset($_GET['ymd_end']) ? $_GET['ymd_end'] : '';

	$PF = isset($_GET['PF']) ? $_GET['PF'] : '';
	include "m_zone_msg.php";
	include "m_charbase.php";
	$zone_msg = new ZoneMsgInfo;

	$domians2zoneid_array = $zone_msg->domians2zoneid_array();
	$domians2zones_array = $zone_msg->domians2zones_array();

	include "m_trade_sy.php";
	$trade = new Trade_syInfo;

	$where = array();

	if($zones)
	{
		$domian = $zone_msg->zones2infos($zones);
		$where['ZONE'] = $domian['domian'];
	}

	if($zone_id)
	{
		$domians = $zone_msg->zone_id2infos($zone_id);
		$where['ZONE'] = trim($domians['domians'], ',');
	}

	if($acc)
	{
		$where['ACC'] = $acc;
	}

	if($ymd_start)
	{
		$where['TIME1'] = strtotime($ymd_start);
	}

	if($ymd_end)
	{
		$where['TIME2'] = strtotime($ymd_end) + 86399;
	}

	$where['PF'] = $PF;
	$return_arr = array();

	$orderby = ' ORDER BY  ' . $sort . ' ' .$order;

	if( $detailed )
	{
		$return_arr['rows'] = $trade->detailed_list( $where, $orderby, 'LIMIT 0,500' );
		$return_arr['total'] = $trade->detailed_total( $where );

		foreach ($return_arr['rows'] as $key => $value)
		{
			$zone_msg11 = $zone_msg->domians2infos($value['ZONE']);
			$charbase = new CharbaseInfo($zone_msg11);
			$datasss = $charbase->get_acc_list( $value['ACC'], $value['NAME'], '', 'limit 1' );
			$return_arr['rows'][$key]['VIP'] = $datasss['0']['VIP'];
			$return_arr['rows'][$key]['CTIME'] = $datasss['0']['CREATETIME'];
			$return_arr['rows'][$key]['zone_id'] = $domians2zoneid_array[$value['ZONE']];
			$return_arr['rows'][$key]['zone'] = $domians2zones_array[$value['ZONE']];
		}
		$ndatass = array();
		foreach ($return_arr['rows'] as $key => $value) 
		{
			$ndatass[$key]['ACC']   = $value['ACC'];
			$ndatass[$key]['zone_id']   =  $domians2zoneid_array[$value['ZONE']];
			$ndatass[$key]['zone']   = $domians2zones_array[$value['ZONE']];
			$ndatass[$key]['NAME'] = $value['NAME'];
			$ndatass[$key]['VIP'] = $value['VIP'];
			$ndatass[$key]['CTIME'] = $value['CTIME'];
			$ndatass[$key]['SUM_AMOUNT'] = $value['SUM_AMOUNT']/100;
			$ndatass[$key]['SUM_YB'] = $value['SUM_YB']/10;
			$ndatass[$key]['PF'] = $value['PF'];
			$ndatass[$key]['YMD'] = $value['YMD'];
		}
		array_unshift($ndatass, array('帐号','合并后区','合并前区','角色名','VIP','创建时间','rmb','元宝','途径','时间') );
		outputCSV($ndatass,'帐号收入');
		exit;
	}

	if( !$detailed && !$dis_zone )
	{
		$return_arr['rows'] = $trade->acc_group_list( $where, $orderby, 'LIMIT 0,500' );
		$return_arr['total'] = $trade->acc_group_total( $where );

		foreach ($return_arr['rows'] as $key => $value)
		{
			$zone_msg11 = $zone_msg->domians2infos($value['ZONE']);
			$charbase = new CharbaseInfo($zone_msg11);
			$datasss = $charbase->get_acc_list( $value['ACC'], $value['NAME'], '', 'limit 1' );
echo '<pre>';
print_r($datasss);
			$return_arr['rows'][$key]['VIP'] = $datasss['0']['VIP'];
			$return_arr['rows'][$key]['CTIME'] = $datasss['0']['CREATETIME'];
			//$return_arr['rows'][$key]['zone_id'] = $domians2zoneid_array[$value['ZONE']];
			//$return_arr['rows'][$key]['zone'] = $domians2zones_array[$value['ZONE']];
		}
		$ndatass = array();
		foreach ($return_arr['rows'] as $key => $value) 
		{
			$ndatass[$key]['ACC']   = $value['ACC'];
                        $ndatass[$key]['zone_id']   =  $domians2zoneid_array[$value['ZONE']];
                        $ndatass[$key]['zone']   = $domians2zones_array[$value['ZONE']];
			$ndatass[$key]['NAME'] = $value['NAME'];
			$ndatass[$key]['VIP'] = $value['VIP'];
			$ndatass[$key]['CTIME'] = $value['CTIME'];
			$ndatass[$key]['SUM_AMOUNT'] = $value['SUM_AMOUNT']/100;
			$ndatass[$key]['SUM_YB'] = $value['SUM_YB']/10;
			$ndatass[$key]['PF'] = $value['PF'];
			$ndatass[$key]['YMD'] = $value['YMD'];
		}

		array_unshift($ndatass, array('帐号','合并后区','合并前区','角色名','VIP','创建时间','rmb','元宝','途径','时间') );
		outputCSV($ndatass,'帐号收入');
		exit;
	}

	if( !$detailed && $dis_zone )
	{
		$return_arr['rows'] = $trade->zone_acc_group_list( $where, $orderby, 'LIMIT 0,500' );
		$return_arr['total'] = $trade->zone_acc_group_total( $where );

		foreach ($return_arr['rows'] as $key => $value)
		{
			$zone_msg11 = $zone_msg->domians2infos($value['ZONE']);
			$charbase = new CharbaseInfo($zone_msg11);
			$datasss = $charbase->get_acc_list( $value['ACC'], $value['NAME'], '', 'limit 1' );
			$return_arr['rows'][$key]['VIP'] = $datasss['0']['VIP'];
			$return_arr['rows'][$key]['CTIME'] = $datasss['0']['CREATETIME'];
			$return_arr['rows'][$key]['zone_id'] = $domians2zoneid_array[$value['ZONE']];
			$return_arr['rows'][$key]['zone'] = $domians2zones_array[$value['ZONE']];
		}

		$ndatass = array();
		foreach ($return_arr['rows'] as $key => $value) 
		{
			$ndatass[$key]['ACC']   = $value['ACC'];
                        $ndatass[$key]['zone_id']   =  $domians2zoneid_array[$value['ZONE']];
                        $ndatass[$key]['zone']   = $domians2zones_array[$value['ZONE']];
			$ndatass[$key]['NAME'] = $value['NAME'];
			$ndatass[$key]['VIP'] = $value['VIP'];
			$ndatass[$key]['CTIME'] = $value['CTIME'];
			$ndatass[$key]['SUM_AMOUNT'] = $value['SUM_AMOUNT']/100;
			$ndatass[$key]['SUM_YB'] = ($value['SUM_YB']/10);
			$ndatass[$key]['PF'] = $value['PF'];
			$ndatass[$key]['YMD'] = $value['YMD'];
		}
		array_unshift($ndatass, array('帐号','合并后区','合并前区','角色名','VIP','创建时间','rmb','元宝','途径','时间') );
		outputCSV($ndatass,'帐号收入');
		exit;
	}
}


