<?php
include "h_header.php";
include "m_zone_msg.php";
$zone_msg = new ZoneMsgInfo;
include "m_mail.php";

$action = isset($_GET['action']) ? $_GET['action'] : NULL;

if($action == "list")
{
    $page       = isset($_POST['page'])      ? (int)$_POST['page']    : 1;
    $rows       = isset($_POST['rows'])      ? (int)$_POST['rows']    : 20;
    $zones      = isset($_POST['zones'])     ? (int)$_POST['zones']   : '';
    $NAME       = isset($_POST['NAME'])      ? $_POST['NAME']         : '';
    $CREATETIME       = isset($_POST['CREATETIME'])      ? $_POST['CREATETIME']         : '';
    //排序
    $sort = isset($_POST['sort']) ?  htmlspecialchars( $_POST['sort'] )  : 'CREATETIME';
    $order = isset($_POST['order']) ?  htmlspecialchars( $_POST['order'] )  : 'desc';
    $orderby = ' ORDER BY ' . $sort . '*1  ' .$order;
    if($page <= 0) $page = 1;
    $limit = ' LIMIT ' . ($page-1)*$rows . ',' . $rows;
    
    if(!empty($CREATETIME)){
        $start = strtotime($CREATETIME);
        $end =  $start+86400;
        $limit = "AND CREATETIME>={$start} AND CREATETIME<={$end}".$orderby.$limit;
        //$limit = " AND CREATETIME>='$start' AND CREATETIME<='$end' LIMIT " . ($page-1)*$rows . ',' . $rows;
    }else{
        $limit = $orderby.$limit;
    }
    
    $result = array();
    $zones_info = $zone_msg->zones2infos( $zones );

    if( !$zones_info or !isset($zones_info['mysql_ip']) ) exit(json_encode( array() ));

    $MAIL = new MAILInfo($zones_info);


 
   
    if($NAME !== '')
    {
        $result['rows'] = $MAIL->get_list_name($NAME ,$limit);
        $result['total'] = $MAIL->get_total_name($NAME,$start);//开始时间做参数
    }else
    {
        $result['rows'] = $MAIL->get_list($limit);
        
       $result['total'] = $MAIL->get_total($start);
    }

    exit(json_encode($result));
}
