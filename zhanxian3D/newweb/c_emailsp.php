<?php
/**
 * Created by PhpStorm.
 * User: chenyunhe
 * Date: 2017/2/13
 * Time: 18:06
 * desc:审核邮件
 */
ini_set('display_errors', 0);
error_reporting(0);
include "h_header.php";
include_once "m_zone_msg.php";
include_once 'm_gift.php';
include_once 'gm_code.php';

$action = isset($_GET['action']) ? $_GET['action'] : NULL;
$dbh = new PDO(PDO_gonggaoInfo_host, PDO_gonggaoInfo_root, PDO_gonggaoInfo_pass);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);      //设置异常模式为抛出异常
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); //为语句设置默认获取方式
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);              //false关闭本地预处理 使用mysql预处理
$dbh->query("SET NAMES utf8");

if($action == "list")
{
    $result = array();
    $where = array('STATUS=0');
    $page       = isset($_POST['page'])      ? (int)$_POST['page']                  : 1;
    $rows       = isset($_POST['rows'])      ? (int)$_POST['rows']                  : 20;
    $sort       = isset($_POST['sort'])      ?  htmlspecialchars( $_POST['sort'] )  : 'ID';
    $order      = isset($_POST['order'])     ?  htmlspecialchars( $_POST['order'] ) : 'desc';
    if($page <= 0) $page = 1;

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

    foreach($result as $k=>$v){
        $v['STATUS'] = '审核中';
        $v['SQTIME'] = date('Y-m-d H:i:s',$v['SQTIME']);
        $v['SHTIME'] = date('Y-m-d H:i:s',$v['SHTIME']);
        $r['rows'][] = $v;
    }

    $totalsql = "SELECT COUNT(*) AS TOTAL FROM EMAIL_SHENPI {$wherestr}";
    $stmts = $dbh->prepare($totalsql);
    $stmts->execute();
    $row = $stmts->fetch();

    $r['total'] = $row['TOTAL'];
    exit( json_encode($r) );
}elseif($action=='shenhe'){
    if($_GET['idstr']<=0){
        exit('parameter error');
    }

    $idstr = substr($_GET['idstr'],0,-1);

    $sql = "SELECT * FROM EMAIL_SHENPI WHERE ID IN({$idstr})";
    $stmts = $dbh->prepare($sql);
    $stmts->execute();
    $result = $stmts->fetchAll();
    if(empty($result)){
        exit('无申请邮件');
    }

    $zonearr = array();
    $zonemsg = new ZoneMsgInfo();
    foreach($result as $k=>$v){
        $zoneinfo = $zonemsg->zone_id2infos($v['ZONE']);
        if(empty($zoneinfo)) continue;
        $zonearr[$v['ZONE']] = 1;
        $giftclass = new GIFTInfo($zoneinfo);
        $sql = "insert into GIFT (CHARID,ZONE,NAME,ITEMID1,ITEMID2,ITEMID3,ITEMNUM1,ITEMNUM2,ITEMNUM3,BIND1,BIND2,BIND3,MAILFROM,MAILTITLE,MAILTEXT) value(".$v["CHARID"].",'".$v["ZONE"]."','".$v["NAME"]."',".$v['ITEMID1'].",".$v['ITEMID2'].",".$v['ITEMID3'].",".$v['ITEMNUM1'].",".$v['ITEMNUM2'].",".$v['ITEMNUM3'].",".$v['BIND1'].",".$v['BIND2'].",".$v['BIND3'].",'系统','".$v['MAILTITLE']."','".$v['MAILTEXT']."');";
        $st = $giftclass->dbh->prepare($sql);
        if(!$st->execute()){
            exit('申请ID='.$v['ID'].'审核失败');
        }
    }

    $shenhe=$_SESSION['xwusername'];
    $shtime = time();
    $sql = "UPDATE EMAIL_SHENPI SET STATUS = 1,SHENHE='{$shenhe}',SHTIME='{$shtime}' WHERE ID IN({$idstr})";
    $stmts = $dbh->prepare($sql);
    $flag = $stmts->execute();
    if($flag){
        //执行GM命令
        if(!empty($zonearr)){
            $all_zong_id2zones_new=getall_zong_id2zones_new();
            $erstr = '';
            $sustr = '';
            foreach($zonearr as $sendz=>$vzone){
                if(doGmcode($sendz,$all_zong_id2zones_new)!=1){
                    $erstr .= $sendz.',';
                }else{
                    $sustr .= $sendz.',';
                }
            }
        }
        exit('success:'.$sustr.'----fail:'.$erstr);
    }else{
        exit('fail');
    }
}else{
    exit(json_encode(array()));
}


