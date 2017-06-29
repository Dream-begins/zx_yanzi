<?php
include "h_header.php";
include "m_zone_msg.php";
ini_set('display_errors',1);
$action = isset($_GET['action']) ? $_GET['action'] : NULL;

$zone_msg = new ZoneMsgInfo;

$zones      = isset($_POST['zones'])     ? (int)$_POST['zones']       :  $_GET['zones'];

$zone_msg_info = $zone_msg->zones2infos( $zones ); //合并前区
$dbh = new PDO('mysql:host='.$zone_msg_info['mysql_ip'].';dbname='.$zone_msg_info['mysql_dbName'].';port='.$zone_msg_info['mysql_port'].';charset=utf8', PDO_ZONE_ROOT, PDO_ZONE_PASS);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);      //设置异常模式为抛出异常
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); //为语句设置默认获取方式
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);              //false关闭本地预处理 使用mysql预处理
$dbh->query("SET NAMES utf8");

//家族信息列表
if($action == "list")
{
    $page       = isset($_POST['page'])      ? (int)$_POST['page']                  : 1;
    $rows       = isset($_POST['rows'])      ? (int)$_POST['rows']                  : 10;
    $zones      = isset($_POST['zones'])     ? (int)$_POST['zones']                 : '';
    $SEPTNAME       = isset($_POST['SEPTNAME'])      ? $_POST['SEPTNAME']                  : '';
    //echo $SEPTNAME;exit;
    if($page <= 0) $page = 1;
    $where = "where 1=1";
    if(!empty($SEPTNAME)) {$where  .= " and SEPTNAME='".$SEPTNAME."'";}
    $limit = ' LIMIT ' . ($page-1)*$rows . ',' . $rows;

    //$result = array();

    $sql = "SELECT * FROM SEPT ".$where.$limit;
    //echo $sql;
    $stmts = $dbh->prepare($sql);
    $stmts->execute();
    $result = $stmts->fetchAll();

    $sql1 = "SELECT count(*) as total FROM SEPT ".$where;
    $stmts1 = $dbh->prepare($sql1);
    $stmts1->execute();
    $total = $stmts1->fetch();

    foreach ($result as $k=>$v){
        $result[$k]['detail'] = "<a id='ht_id_search1'href=\"#\" class=\"easyui-linkbutton\"  onclick=\"dg_load('c_family.php?action=member&zones=$zones&septid=$v[SEPTID]')\">查看</a>";
        //成员数
        $sql2= "SELECT count(*) as num FROM SEPTMEMBER where SEPTID=$v[SEPTID]";
        $stmts = $dbh->prepare($sql2);
        $stmts->execute();
        $num = $stmts->fetch();
        $result[$k]['num'] = $num['num'];
        //族长
            $sql3 = "SELECT SEPTMBRNAME  FROM SEPTMEMBER where SEPTID=$v[SEPTID] and SEPTPOSITION=5";
        $stmts = $dbh->prepare($sql3);
        $stmts->execute();
        $SEPTMBRNAME = $stmts->fetch();
        $result[$k]['SEPTMBRNAME'] = $SEPTMBRNAME['SEPTMBRNAME'];
        //时间格式化
        $result[$k]['CREATETIME'] = date("Y-m-d H:i:s",$v['CREATETIME']);

    }
    $results['rows'] = $result;
    $results['total'] = $total['total'];
    exit(json_encode($results));
}

//单个家族成员列表
if($action == "member")
{

    $page       = isset($_POST['page'])      ? (int)$_POST['page']                  : 1;
    $rows       = isset($_POST['rows'])      ? (int)$_POST['rows']                  : 10;
    $zones      = isset($_POST['zones'])     ? (int)$_POST['zones']                 : '';
    $NAME       = isset($_POST['NAME'])      ? $_POST['NAME']                  : '';

    if($page <= 0) $page = 1;

    $limit = ' LIMIT ' . ($page-1)*$rows . ',' . $rows;

    $id= $_GET['septid'];
    $sql = "SELECT * FROM SEPTMEMBER where SEPTID=$id".$limit;
    $stmts = $dbh->prepare($sql);
    $stmts->execute();
    $result = $stmts->fetchAll();
    //总数
    $sql1 = "SELECT count(*) as total FROM SEPTMEMBER where SEPTID=$id";
    $stmts = $dbh->prepare($sql1);
    $stmts->execute();
    $total = $stmts->fetch();

//    foreach ($result as $k =>$v ){
//        $result[$k]['set'] = "<a id='ht_id_search2'href=\"#\" class=\"easyui-linkbutton\"  onclick=\"member_set('c_family.php?action=member_set&zones=$zones&mid=$v[SEPTMBRID]&septid=$v[SEPTID]')\">修改</a>";
//        $result[$k]['clear'] = "<a id='ht_id_search3'href=\"#\" class=\"easyui-linkbutton\"  onclick=\"member_clear('c_family.php?action=member_clear&zones=$zones&mid=$v[SEPTMBRID]&septid=$v[SEPTID]')\">踢出</a>";
//    }

    $results['rows']= $result;
    $results['total']= $total['total'];
    exit(json_encode($results));
}

//家族信息更新
//if($action == "")

//家族成员信息更新
//修改
if($action == "member_set"){

}
//踢出家族
if($action == "member_clear"){

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