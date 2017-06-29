<?php
include "h_header.php";
include "m_zone_msg.php";
$zone_msg = new ZoneMsgInfo;
include "m_gift.php";

$action = isset($_GET['action']) ? $_GET['action'] : NULL;

if($action == "list")
{
    $page       = isset($_POST['page'])      ? (int)$_POST['page']                  : 1;
    $rows       = isset($_POST['rows'])      ? (int)$_POST['rows']                  : 20;
    $zones      = isset($_POST['zones'])     ? (int)$_POST['zones']                 : '';
    $NAME       = isset($_POST['NAME'])      ? $_POST['NAME']                  : '';

    if($page <= 0) $page = 1;

    $limit = ' LIMIT ' . ($page-1)*$rows . ',' . $rows;

    $result = array();

    $zones_info = $zone_msg->zones2infos( $zones );

    if( !$zones_info or !isset($zones_info['mysql_ip']) ) exit(json_encode( array() ));

    $GIFT = new GIFTInfo($zones_info);

    if($NAME !== '')
    {
        $result['rows'] = $GIFT->get_list_name($NAME ,$limit);
        $result['total'] = $GIFT->get_total_name($NAME);        
    }else
    {
        $result['rows'] = $GIFT->get_list($limit);
        $result['total'] = $GIFT->get_total();
    }

    foreach ($result['rows'] as $key => $value) 
    {
        $f_NAME = $value['NAME'];
        $f_ITEMGOT = $value['ITEMGOT'];
        $f_MAILTITLE = $value['MAILTITLE'];

        if(!$f_ITEMGOT && $f_NAME && $zones)
        {
            $result['rows'][$key]['del'] = "<a href='javascript:void(0)' class='easyui-linkbutton' onclick=\"hxiangou_iddel('$f_NAME','$f_ITEMGOT','$f_MAILTITLE','$zones')\">删除</a>";
        }
    }

    exit(json_encode($result));
}

if($action == 'del')
{
    $f_NAME       = isset($_POST['f_NAME'])      ? $_POST['f_NAME']         : '';
    $f_ITEMGOT    = isset($_POST['f_ITEMGOT'])   ? (int)$_POST['f_ITEMGOT'] : 1;
    $f_MAILTITLE  = isset($_POST['f_MAILTITLE']) ? $_POST['f_MAILTITLE']    : '';
    $zones        = isset($_GET['zone'])         ? (int)$_GET['zone']       : '';

    if($f_NAME && !$f_ITEMGOT)
    {
        $zones_info = $zone_msg->zones2infos( $zones );

        if( !$zones_info or !isset($zones_info['mysql_ip']) ) exit(json_encode( array() ));

        $GIFT = new GIFTInfo($zones_info);
        $GIFT->gift_del($f_NAME, $f_MAILTITLE);
    }
}