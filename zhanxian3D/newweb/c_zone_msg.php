<?php
include "h_header.php";

$action = isset($_GET['action']) ? $_GET['action'] : NULL;

if($action == "list")
{
    $page       = isset($_POST['page'])      ? (int)$_POST['page']                  : 1;
    $rows       = isset($_POST['rows'])      ? (int)$_POST['rows']                  : 20;
    $sort       = isset($_POST['sort'])      ?  htmlspecialchars( $_POST['sort'] )  : 'zone_id';
    $order      = isset($_POST['order'])     ?  htmlspecialchars( $_POST['order'] ) : 'desc';
    $zones      = isset($_POST['zones'])     ? (int)$_POST['zones']                 : '';
    $zones2     = isset($_POST['zones2'])    ? (int)$_POST['zones2']                : '';
    $zone_id    = isset($_POST['zone_id'])   ? (int)$_POST['zone_id']               : '';
    $domians    = isset($_POST['domians'])   ? (int)$_POST['domians']               : '';
    $server_id  = isset($_POST['server_id']) ? (int)$_POST['server_id']             : '';

    if($page <= 0) $page = 1;

    $result = array();

    include "m_zone_msg.php";
    $zone_msg = new ZoneMsgInfo;

    if($zones && !$zones2  ) 
    {
        $result['total'] = '1';
        $result['rows'] = array( $zone_msg->zones2infos( $zones ) );
        exit( json_encode($result) );
    }

    if($zones2)
    {
        $flag_arr = array();
        $flag1 = $zones ? $zones :0;
        $result = array();
        for($i=$flag1;$i<=$zones2;$i++)
        {
             $flag_arr = $zone_msg->zones2infos( $i );
             if( isset($flag_arr['zone_id']) ) $result['rows'][$flag_arr['zone_id']] = $flag_arr;
        }
        $result['total'] = 1;
        sort($result['rows']);
        exit( json_encode($result) );
    }

    if($zone_id)
    {
        $result['total'] = '1';
        $result['rows'] = array( $zone_msg->zone_id2infos( $zone_id ) );
        exit( json_encode($result) );
    }

    if($domians)
    {
        $result['total'] = '1';
        $result['rows'] = array( $zone_msg->domians2infos( $domians ) );
        exit( json_encode($result) );
    }

    if($server_id)
    {
        $result['total'] = '1';
        $result['rows'] = array( $zone_msg->server_id2infos( $server_id ) );
        exit( json_encode($result) );
    }

    if( !$zones && !$zone_id && !$domians && !$server_id )
    {
        $orderby = ' ORDER BY ' . $sort . '*1  ' .$order;
        $limit = ' LIMIT ' . ($page-1)*$rows . ',' . $rows;

        $result['total'] = $zone_msg->zone_msg_total();
        $result['rows'] = $zone_msg->zone_msg_list( $orderby, $limit );
        exit( json_encode($result) );
    }
}

if($action == "putcsv")
{
    include "m_zone_msg.php";
    $zone_msg = new ZoneMsgInfo;
    $result = $zone_msg->zone_msg_list('','');
    array_unshift($result, array('合并后区','合并后服','合并前区','合并前服','ip','端口','库名') );
    outputCSV($result,'zone_msg');
}
