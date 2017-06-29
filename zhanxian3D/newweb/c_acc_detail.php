<?php
include "h_header.php";
include "m_zone_msg.php";
include 'm_charbase.php';
include 'm_alluser.php';
error_reporting(0);
$action = isset($_GET['action']) ? $_GET['action'] : NULL;

ini_set('memory_limit', '500M');

if($action == "list")
{
    $page = (int)$_POST['page'];
    $rows = (int)$_POST['rows'];
    $sort = isset($_POST['sort']) ?  htmlspecialchars( $_POST['sort'] )  : 'LEVEL';
    $order = isset($_POST['order']) ?  htmlspecialchars( $_POST['order'] )  : 'desc';
    $zones = isset($_POST['zones']) ? (int)$_POST['zones'] : '';
    $zone_id = isset($_POST['zone_id']) ? (int)$_POST['zone_id'] : '';
    
    $ACCNAME = isset($_POST['ACCNAME']) ? trim($_POST['ACCNAME']) : '';
    $NAME = isset($_POST['NAME']) ? trim($_POST['NAME']) : '';

 
    if($page <= 0) $page = 1;
    $zone_msg = new ZoneMsgInfo;

    $domians2zoneid_array = $zone_msg->domians2zoneid_array();
    $domians2zones_array = $zone_msg->domians2zones_array();

    if( $zones || $zone_id )
    {
        if($zones)
        {
            $zones_info = $zone_msg->zones2infos( $zones );
        }elseif(!$zones && $zone_id)
        {
            $zones_info = $zone_msg->zone_id2infos( $zone_id );
        }

        if( !$zones_info ) exit(json_encode( array() ));

        $charbase = new CharbaseInfo($zones_info);

        $orderby = ' ORDER BY ' . $sort . '*1  ' .$order;
        $limit = ' LIMIT ' . ($page-1)*$rows . ',' . $rows;

        $result = $charbase->get_acc_list( $ACCNAME, $NAME, $orderby, $limit );
	$now = time();
        foreach ($result as $key => $value) 
        {
            if($now - strtotime($result[$key]['LASTACTIVEDATE']) >= 86400*7) $result[$key]['LASTACTIVEDATE'] = "<font color='red'>".$result[$key]['LASTACTIVEDATE']."</font>";
            $result[$key]['zone'] = $domians2zones_array[$value['ZONE']];
            $result[$key]['zone_id'] = $domians2zoneid_array[$value['ZONE']];
             $result[$key]['CREATEIP'] = my_long2ip((float)$value['CREATEIP']).'|'.$value['CREATEIP'];
        }

        $array1['rows'] = $result;
        $array1['total'] = ($NAME || $ACCNAME) ? 1 : $charbase->get_total_acc();
        echo json_encode($array1);
    }
    elseif( !$zones && !$zone_id && $ACCNAME )
    {
        $alluser = new AlluserInfo();
        $ACCNAME;
	$domains = $alluser->get_openid_zones( $ACCNAME );

        $domains_arr = explode(',', $domains);

        $result = array();
        $flag_arr = array();
        $flag = '';
        foreach ($domains_arr as $key => $value) 
        {
            $zones_info = $zone_msg->domians2infos( $value );
            if( !$zones_info ) continue;
            if( $flag == $zones_info['zone_id'] ) continue;
            $flag = $zones_info['zone_id'];
            $charbase = new CharbaseInfo($zones_info);
            $flag_arr = $charbase->get_acc_list($ACCNAME);
            $result = array_merge($flag_arr,$result); 
       }
 
        $now = time();
        foreach ($result as $key => $value) 
        {
            if($now - strtotime($result[$key]['LASTACTIVEDATE']) >= 86400*7) $result[$key]['LASTACTIVEDATE'] = "<font color='red'>".$result[$key]['LASTACTIVEDATE']."</font>";
            $result[$key]['zone'] = $domians2zones_array[$value['ZONE']];
            $result[$key]['zone_id'] = $domians2zoneid_array[$value['ZONE']];
	     $result[$key]['CREATEIP'] = my_long2ip((float)$value['CREATEIP']).'|'.$value['CREATEIP'];
        }
        $array1['rows'] = $result;
        $array1['total'] = count($result);
        echo json_encode($array1);

    }
    else{
        exit(json_encode(array()));
    }
}
function my_long2ip($ip)
{
    $a = ((float)$ip >> 24) & 0xff;
    $b = ((float)$ip >> 16) & 0xff;
    $c = ((float)$ip >> 8) & 0xff;
    $d = ((float)$ip >> 0) & 0xff;

    return $d.'.'.$c.'.'.$b.'.'.$a;
}
if($action == "putcsv")
{
    $zones = isset($_GET['zones']) ? (int)$_GET['zones'] : '';
    $zone_id = isset($_GET['zone_id']) ? (int)$_GET['zone_id'] : '';
    
    $zone_msg = new ZoneMsgInfo;

    $domians2zoneid_array = $zone_msg->domians2zoneid_array();
    $domians2zones_array = $zone_msg->domians2zones_array();

    if( $zones || $zone_id )
    {
        if($zones)
        {
            $zones_info = $zone_msg->zones2infos( $zones );
        }elseif(!$zones && $zone_id)
        {
            $zones_info = $zone_msg->zone_id2infos( $zone_id );
        }

        if( !$zones_info ) exit(json_encode( array() ));

        $charbase = new CharbaseInfo($zones_info);

        $orderby = ' ORDER BY ' . $sort . '*1  ' .$order;
        $limit = ' LIMIT ' . ($page-1)*$rows . ',' . $rows;

        $result = $charbase->get_acc_list( $ACCNAME, $NAME, 'order by LEVEL DESC ', 'LIMIT 20000' );
        $now = time();
        foreach ($result as $key => $value) 
        {
            if($now - strtotime($result[$key]['LASTACTIVEDATE']) >= 86400*7) $result[$key]['LASTACTIVEDATE'] = "<font color='red'>".$result[$key]['LASTACTIVEDATE']."</font>";
            $result[$key]['zone'] = $domians2zones_array[$value['ZONE']];
            $result[$key]['zone_id'] = $domians2zoneid_array[$value['ZONE']];
             $result[$key]['CREATEIP'] = my_long2ip((float)$value['CREATEIP']).'|'.$value['CREATEIP'];
        }
$new_result = array();
foreach ($result as $key => $value)
{
    $new_result[$key]['ACCNAME'] = $value['ACCNAME'];
    $new_result[$key]['NAME'] = $value['NAME'];
    $new_result[$key]['zone'] = $value['zone'];
    $new_result[$key]['zone_id'] = $value['zone_id'];
    $new_result[$key]['LEVEL'] = $value['LEVEL'];
    $new_result[$key]['VIP'] = $value['VIP'];
    $new_result[$key]['ZHENQI'] = $value['ZHENQI'];
    $new_result[$key]['COUNTRY'] = $value['COUNTRY'];
    $new_result[$key]['MONEY1'] = $value['MONEY1'];
    $new_result[$key]['MONEY3'] = $value['MONEY3'];
    $new_result[$key]['CREATETIME'] = $value['CREATETIME'];
    $new_result[$key]['LASTACTIVEDATE'] = $value['LASTACTIVEDATE'];
    $new_result[$key]['CREATEIP'] = $value['CREATEIP'];
    $new_result[$key]['FORBIDTALK'] = $value['FORBIDTALK'];
    $new_result[$key]['CHARID'] = $value['CHARID'];
    $new_result[$key]['MAPID'] = $value['MAPID'];
    $new_result[$key]['LINEID'] = $value['LINEID'];
    $new_result[$key]['BITMASK'] = $value['BITMASK'];
    $new_result[$key]['ACCPRIV'] = $value['ACCPRIV'];
    $new_result[$key]['HUANGZAN'] = $value['HUANGZAN'];
    $new_result[$key]['HP'] = $value['HP'];
    $new_result[$key]['MONEY2'] = $value['MONEY2'];
    $new_result[$key]['LINQI'] = $value['LINQI'];
    $new_result[$key]['MONEY4'] = $value['MONEY4'];
    $new_result[$key]['MONEY5'] = $value['MONEY5'];
    $new_result[$key]['MONEY6'] = $value['MONEY6'];
    $new_result[$key]['MONEY9'] = $value['MONEY9'];
    $new_result[$key]['MONEY10'] = $value['MONEY10'];
    $new_result[$key]['MONEY13'] = $value['MONEY13'];
}

        array_unshift($new_result, array('玩家ID','玩家名称','合并前区','合并后区','等级','VIP','战力','宗派','元宝','返还元宝','创建时间','最后登陆时间','IP','禁言时间','CHARID','地图','分线','BITMASK','TYPE','黄钻','HP','绑定元宝','灵气','银币','仙石','家族贡献','荣誉','声望','元魂') );
        outputCSV($new_result,'帐号明细');
    }
}

