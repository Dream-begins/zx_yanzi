<?php
include "h_header.php";
error_reporting(1);
$action = isset($_GET['action']) ? $_GET['action'] : '';

$dbh = new PDO(PDO_gonggaoInfo_host,PDO_gonggaoInfo_root,PDO_gonggaoInfo_pass);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbh->query("SET NAMES UTF8");

if('add' == $action)
{   //echo 1;exit;
    $zoneid = isset($_POST['zoneid']) ? $_POST['zoneid'] : '';
    $ACCNAME= isset($_POST['ACCNAME']) ? $_POST['ACCNAME'] : '';
    $NAME = isset($_POST['NAME']) ? $_POST['NAME'] : '';
    $grant_start = isset($_POST['grant_start']) ? strtotime($_POST['grant_start']) : '';
    $grant_end = isset($_POST['grant_end']) ? strtotime($_POST['grant_end']): '';
    //$lastlogin_end = isset($_POST['lastlogin_end']) ? strtotime($_POST['lastlogin_end'])+86399 : '';

    $item1 = isset($_POST['item1']) ? $_POST['item1'] : '';
    $bind1 = isset($_POST['bind1']) && $_POST['bind1'] ? $_POST['bind1'] : true;
    $num1 = isset($_POST['num1']) ? $_POST['num1'] : '';
    $item2 = isset($_POST['item2']) ? $_POST['item2'] : '';
    $bind2 = isset($_POST['bind2']) && $_POST['bind2'] ? $_POST['bind2'] : true;
    $num2 = isset($_POST['num2']) ? $_POST['num2'] : '';
    $item3 = isset($_POST['item3']) ? $_POST['item3'] : '';
    $bind3 = isset($_POST['bind3']) && $_POST['bind3'] ? $_POST['bind3'] : true;
    $num3 = isset($_POST['num3']) ? $_POST['num3'] : '';
    $mtitle = isset($_POST['mtitle']) ? $_POST['mtitle'] : '';
    $dotime = isset($_POST['dotime']) ? strtotime($_POST['dotime']) : '';
    $m_content = isset($_POST['m_content']) ? $_POST['m_content'] : '';
    $rate = isset($_POST['rate']) ? $_POST['rate'] : '';

    $ctime = time();
    $cadmin = isset($_SESSION['xwusername']) ? $_SESSION['xwusername'] : '';

    $sql = "INSERT INTO `h_auto_gift`(ctime,cadmin,zoneid,ACCNAME,NAME,grant_start,grant_end,item1,bind1,num1,item2,bind2,num2,item3,bind3,num3,mtitle,m_content,dotime,state,log,rate) 
            VALUES(:ctime,:cadmin,:zoneid,:ACCNAME,:NAME,:grant_start,:grant_end,:item1,:bind1,:num1,:item2,:bind2,:num2,:item3,:bind3,:num3,:mtitle,:m_content,:dotime,0,'',:rate) 
            ";
    $stmt = $dbh->prepare($sql);

    $stmt->bindParam(':ctime', $ctime);
    $stmt->bindParam(':cadmin', $cadmin);
    $stmt->bindParam(':zoneid', $zoneid);
    $stmt->bindParam(':ACCNAME', $ACCNAME);
    $stmt->bindParam(':NAME', $NAME);
    $stmt->bindParam(':grant_start', $grant_start);
    $stmt->bindParam(':grant_end', $grant_end);
    $stmt->bindParam(':item1', $item1);
    $stmt->bindParam(':bind1', $bind1);
    $stmt->bindParam(':num1', $num1);
    $stmt->bindParam(':item2', $item2);
    $stmt->bindParam(':bind2', $bind2);
    $stmt->bindParam(':num2', $num2);
    $stmt->bindParam(':item3', $item3);
    $stmt->bindParam(':bind3', $bind3);
    $stmt->bindParam(':num3', $num3);
    $stmt->bindParam(':mtitle', $mtitle);
    $stmt->bindParam(':dotime', $dotime);
    $stmt->bindParam(':m_content', $m_content);
    $stmt->bindParam(':rate', $rate);

    $stmt->execute();
    echo $stmt->rowCount();
    $dbh = null;
}

if('list' == $action)
{
    $zoneid = $_POST['zoneid'] ? $_POST['zoneid'] :"";
    $ACCNAME = $_POST['ACCNAME'] ? $_POST['ACCNAME'] :"";
    $NAME = $_POST['NAME'] ? $_POST['NAME'] :"";
    //echo $zoneid;exit;
    $where = "where 1=1 ";
    if($_POST['zoneid']){$where = $where." and zoneid={$_POST['zoneid']}";}
    if($_POST['ACCNAME']){$where = $where." and ACCNAME='{$_POST['ACCNAME']}'";}
    if($_POST['NAME']){$where = $where." and NAME='{$_POST['NAME']}'";}

    $sql = "SELECT id,ACCNAME,NAME,ctime,cadmin,zoneid,grant_start,grant_end,item1,bind1,num1,item2,bind2,num2,item3,bind3,num3,mtitle,
            m_content,rate,dotime,state,log,last_time FROM `h_auto_gift`".$where;

    $return_arr = array();
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();

    $datas = array();

    foreach ($result as $key => $value) 
    {
        $datas[$key]['id'] = $value['id'];
        $datas[$key]['ACCNAME'] = $value['ACCNAME'];
        $datas[$key]['NAME'] = $value['NAME'];
        $datas[$key]['ctime'] = date('Y-m-d',$value['ctime']);
        $datas[$key]['cadmin'] = $value['cadmin'];
        $datas[$key]['zoneid'] = $value['zoneid'];
        $datas[$key]['item1'] = $value['item1'];
        $datas[$key]['bind1'] = $value['bind1'] ? '绑定' : '非绑定';
        $datas[$key]['num1'] = $value['num1'];
        $datas[$key]['item2'] = $value['item2'];
        $datas[$key]['bind2'] = $value['bind2'] ? '绑定' : '非绑定';
        $datas[$key]['num2'] = $value['num2'];
        $datas[$key]['item3'] = $value['item3'];
        $datas[$key]['bind3'] = $value['bind3'] ? '绑定' : '非绑定';
        $datas[$key]['num3'] = $value['num3'];
        $datas[$key]['mtitle'] = $value['mtitle'];
        $datas[$key]['m_content'] = $value['m_content'];
        $datas[$key]['dotime'] = date('Y-m-d H:i:s',$value['dotime']);
        $datas[$key]['state'] = $value['state'] ? '已过期' : '进行中';
        $datas[$key]['rate'] = $value['rate'];
        $datas[$key]['grant_start'] = date('Y-m-d',$value['grant_start']);
        $datas[$key]['grant_end'] = date('Y-m-d',$value['grant_end']);
        $datas[$key]['last_time'] = date('Y-m-d',$value['last_time']);
        //$datas[$key]['lastlogin_start'] = date('Y-m-d',$value['lastlogin_start']);
        //$datas[$key]['lastlogin_end'] = date('Y-m-d',$value['lastlogin_end']);
    }

    $return_arr['rows'] = $datas;
    echo json_encode($return_arr);
}

if('del' == $action)
{
    $id = isset($_POST['id']) ? (int)$_POST['id'] : NULL;
    if($id == 0) exit;
    $stmt = $dbh->prepare("DELETE FROM h_auto_gift WHERE id = :id LIMIT 1 ");
    $stmt->bindParam(":id", $id);

    echo $stmt->execute();
}
if('overdue' == $action)
{
    $id = isset($_POST['id']) ? (int)$_POST['id'] : NULL;
    if($id == 0) exit;
    $stmt = $dbh->prepare("update h_auto_gift set state=1 WHERE id = :id LIMIT 1 ");
    $stmt->bindParam(":id", $id);

    echo $stmt->execute();
}
if('edit' == $action)
{
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    $zoneid = isset($_POST['zoneid']) ? $_POST['zoneid'] : '';
    $ACCNAME= isset($_POST['ACCNAME']) ? $_POST['ACCNAME'] : '';
    $NAME = isset($_POST['NAME']) ? $_POST['NAME'] : '';
    $grant_start = isset($_POST['grant_start']) ? strtotime($_POST['grant_start']) : '';
    $grant_end = isset($_POST['grant_end']) ? strtotime($_POST['grant_end']): '';
    $item1 = isset($_POST['item1']) ? $_POST['item1'] : '';
    $bind1 = isset($_POST['bind1']) && $_POST['bind1'] ? $_POST['bind1'] : true;
    $num1 = isset($_POST['num1']) ? $_POST['num1'] : '';
    $item2 = isset($_POST['item2']) ? $_POST['item2'] : '';
    $bind2 = isset($_POST['bind2']) && $_POST['bind2'] ? $_POST['bind2'] : true;
    $num2 = isset($_POST['num2']) ? $_POST['num2'] : '';
    $item3 = isset($_POST['item3']) ? $_POST['item3'] : '';
    $bind3 = isset($_POST['bind3']) && $_POST['bind3'] ? $_POST['bind3'] : true;
    $num3 = isset($_POST['num3']) ? $_POST['num3'] : '';
    $mtitle = isset($_POST['mtitle']) ? $_POST['mtitle'] : '';
    $dotime = isset($_POST['dotime']) ? strtotime($_POST['dotime']) : '';
    $m_content = isset($_POST['m_content']) ? $_POST['m_content'] : '';
    $rate = isset($_POST['rate']) ? $_POST['rate'] : '';

    $ctime = time();
    $cadmin = isset($_SESSION['xwusername']) ? $_SESSION['xwusername'] : '';

    $sql = "UPDATE h_auto_gift SET ctime=:ctime, cadmin=:cadmin, zoneid=:zoneid,ACCNAME=:ACCNAME,NAME=:NAME, grant_start=:grant_start, grant_end=:grant_end, item1=:item1, bind1=:bind1, num1=:num1, item2=:item2, bind2=:bind2, num2=:num2, item3=:item3, bind3=:bind3, num3=:num3, mtitle=:mtitle, m_content=:m_content, dotime=:dotime,rate=:rate  WHERE id=:id ";

    $stmt = $dbh->prepare($sql);

    $stmt->bindParam(':ctime', $ctime);
    $stmt->bindParam(':cadmin', $cadmin);
    $stmt->bindParam(':zoneid', $zoneid);
    $stmt->bindParam(':ACCNAME', $ACCNAME);
    $stmt->bindParam(':NAME', $NAME);
    $stmt->bindParam(':grant_start', $grant_start);
    $stmt->bindParam(':grant_end', $grant_end);
    $stmt->bindParam(':item1', $item1);
    $stmt->bindParam(':bind1', $bind1);
    $stmt->bindParam(':num1', $num1);
    $stmt->bindParam(':item2', $item2);
    $stmt->bindParam(':bind2', $bind2);
    $stmt->bindParam(':num2', $num2);
    $stmt->bindParam(':item3', $item3);
    $stmt->bindParam(':bind3', $bind3);
    $stmt->bindParam(':num3', $num3);
    $stmt->bindParam(':mtitle', $mtitle);
    $stmt->bindParam(':dotime', $dotime);
    $stmt->bindParam(':m_content', $m_content);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':rate', $rate);

    $stmt->execute();
    echo $stmt->rowCount();
    $dbh = null;
}

