<?php
/**
 * Created by PhpStorm.
 * User: chenyunhe
 * Date: 2017/2/13
 * Time: 15:37
 */
include "h_header.php";
$action = isset($_GET['action']) ? $_GET['action'] : NULL;
$dbh = new PDO(PDO_gonggaoInfo_host, PDO_gonggaoInfo_root, PDO_gonggaoInfo_pass);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);      //设置异常模式为抛出异常
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); //为语句设置默认获取方式
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);              //false关闭本地预处理 使用mysql预处理
$dbh->query("SET NAMES utf8");

if($action == "list")
{
    $result = array();
    $where = array();
    $page       = isset($_POST['page'])      ? (int)$_POST['page']                  : 1;
    $rows       = isset($_POST['rows'])      ? (int)$_POST['rows']                  : 20;
    $sort       = isset($_POST['sort'])      ?  htmlspecialchars( $_POST['sort'] )  : 'ID';
    $order      = isset($_POST['order'])     ?  htmlspecialchars( $_POST['order'] ) : 'desc';
    if($page <= 0) $page = 1;

    if(!empty($_POST['radioval']))  $where[] = "STATUS={$_POST['radioval']}";
    if(!empty($_POST['ID'])) $where[] = "ID={$_POST['ID']}";
    if(!empty($_POST['name'])) $where[] = "NAME={$_POST['name']}";
    if(!empty($_POST['zone'])) $where[] = "ZONE={$_POST['zone']}";

    $orderby = ' ORDER BY ' . $sort . ' ' .$order;
    $limit = ' LIMIT ' . ($page-1)*$rows . ',' . $rows;

    $wherestr = (empty($where)) ? '' : 'WHERE '.implode(' AND ',$where);
    $sql = "SELECT * FROM EMAIL_SHENPI  $wherestr $orderby  $limit";
    $stmts = $dbh->prepare($sql);
    $stmts->execute();
    $result = $stmts->fetchAll();

    if(empty($result)){
        exit( json_encode(array()) );
    }
    $array = array(0=>'审核中',1=>'<font color="green">已审核通过</font>',2=>'<font color="silver">审核未通过</font>',3=>'<font color="red">审核失败</font>');
    foreach($result as $k=>$v){
        $v['STATUS'] = $array[$v['STATUS']];
        $v['SQTIME'] = date('Y-m-d H:i:s',$v['SQTIME']);
        $v['SHTIME'] = date('Y-m-d H:i:s',$v['SHTIME']);
        $v['del'] ='<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="del(\''.$v['ID'].'\')" style="width:90px">删除</a>';
        $r['rows'][] = $v;
    }

    $totalsql = "SELECT COUNT(*) AS TOTAL FROM EMAIL_SHENPI {$wherestr}";
    $stmts = $dbh->prepare($totalsql);
    $stmts->execute();
    $row = $stmts->fetch();

    $r['total'] = $row['TOTAL'];
    exit( json_encode($r) );
}elseif($action=='del'){
    if($_GET['id']<=0){
        exit('parameter error');
    }

    $sql = "DELETE FROM EMAIL_SHENPI WHERE ID='{$_GET['id']}'";
    $stmts = $dbh->prepare($sql);
    if($stmts->execute()){
        exit('success');
    }else{
        exit('fail');
    }
}else{
    exit(json_encode(array()));
}


