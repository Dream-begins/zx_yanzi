<?php
session_start();
include "h_header.php";
$action = isset($_GET['action']) ? $_GET['action'] : '';

$dbh = new PDO(PDO_gonggaoInfo_host,PDO_gonggaoInfo_root,PDO_gonggaoInfo_pass);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbh->query("SET NAMES UTF8");

if('add' == $action)
{
    $zoness = isset($_POST['zoness']) ? $_POST['zoness'] : '';
    $create_start = isset($_POST['create_start']) ? strtotime($_POST['create_start']) : '';
    $create_end = isset($_POST['create_end']) ? strtotime($_POST['create_end'])+86399 : '';
    $lastlogin_start = isset($_POST['lastlogin_start']) ? strtotime($_POST['lastlogin_start']) : '';
    $lastlogin_end = isset($_POST['lastlogin_end']) ? strtotime($_POST['lastlogin_end'])+86399 : '';
    $lv_start = isset($_POST['lv_start']) ? $_POST['lv_start'] : '';
    $lv_end = isset($_POST['lv_end']) ? $_POST['lv_end'] : '';
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

    $ctime = time();
    $cadmin = isset($_SESSION['xwusername']) ? $_SESSION['xwusername'] : '';

    $sql = "INSERT INTO `h_timing_gift`(ctime,cadmin,zoness,create_start,create_end,lastlogin_start,lastlogin_end,lv_start,lv_end,item1,bind1,num1,item2,bind2,num2,item3,bind3,num3,mtitle,m_content,dotime,state,log) 
            VALUES(:ctime,:cadmin,:zoness,:create_start,:create_end,:lastlogin_start,:lastlogin_end,:lv_start,:lv_end,:item1,:bind1,:num1,:item2,:bind2,:num2,:item3,:bind3,:num3,:mtitle,:m_content,:dotime,0,'') 
            ";
    $stmt = $dbh->prepare($sql);

    $stmt->bindParam(':ctime', $ctime);
    $stmt->bindParam(':cadmin', $cadmin);
    $stmt->bindParam(':zoness', $zoness);
    $stmt->bindParam(':create_start', $create_start);
    $stmt->bindParam(':create_end', $create_end);
    $stmt->bindParam(':lastlogin_start', $lastlogin_start);
    $stmt->bindParam(':lastlogin_end', $lastlogin_end);
    $stmt->bindParam(':lv_start', $lv_start);
    $stmt->bindParam(':lv_end', $lv_end);
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

    $stmt->execute();
    echo $stmt->rowCount();
    $dbh = null;
}

if('list' == $action)
{
    $sql = "SELECT id,ctime,cadmin,zoness,create_start,create_end,
                   lastlogin_start,lastlogin_end,lv_start,lv_end,item1,
                   bind1,num1,item2,bind2,num2,item3,bind3,num3,mtitle,
                   m_content,dotime,state,log 
            FROM `h_timing_gift` ";

    $return_arr = array();
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();

    $datas = array();

    foreach ($result as $key => $value) 
    {
        $datas[$key]['id'] = $value['id'];
        $datas[$key]['ctime'] = date('Y-m-d',$value['ctime']);
        $datas[$key]['cadmin'] = $value['cadmin'];
        $datas[$key]['zoness'] = $value['zoness'];
        $datas[$key]['create'] =  date('Y-m-d',$value['create_start']).'~'.date('Y-m-d',$value['create_end']);
        $datas[$key]['lastlogin'] = date('Y-m-d',$value['lastlogin_start']).'~'.date('Y-m-d',$value['lastlogin_end']);
        $datas[$key]['lv'] = $value['lv_start'].'~'.$value['lv_end'];
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
        $datas[$key]['state'] = $value['state'] ? 'ok' : 'wait';
        $datas[$key]['log'] = $value['log'];
        $datas[$key]['lv_start'] = $value['lv_start'];
        $datas[$key]['lv_end'] = $value['lv_end'];
        $datas[$key]['create_start'] = date('Y-m-d',$value['create_start']);
        $datas[$key]['create_end'] = date('Y-m-d',$value['create_end']);
        $datas[$key]['lastlogin_start'] = date('Y-m-d',$value['lastlogin_start']);
        $datas[$key]['lastlogin_end'] = date('Y-m-d',$value['lastlogin_end']);
    }

    $return_arr['rows'] = $datas;
    echo json_encode($return_arr);
}

if('del' == $action)
{
    $id = isset($_POST['id']) ? (int)$_POST['id'] : NULL;
    if($id == 0) exit;
    $stmt = $dbh->prepare("DELETE FROM h_timing_gift WHERE id = :id LIMIT 1 ");
    $stmt->bindParam(":id", $id);

    echo $stmt->execute();
}
if('edit' == $action)
{
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    $zoness = isset($_POST['zoness']) ? $_POST['zoness'] : '';
    $create_start = isset($_POST['create_start']) ? strtotime($_POST['create_start']) : '';
    $create_end = isset($_POST['create_end']) ? strtotime($_POST['create_end'])+86399 : '';
    $lastlogin_start = isset($_POST['lastlogin_start']) ? strtotime($_POST['lastlogin_start']) : '';
    $lastlogin_end = isset($_POST['lastlogin_end']) ? strtotime($_POST['lastlogin_end'])+86399 : '';
    $lv_start = isset($_POST['lv_start']) ? $_POST['lv_start'] : '';
    $lv_end = isset($_POST['lv_end']) ? $_POST['lv_end'] : '';
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

    $ctime = time();
    $cadmin = isset($_SESSION['xwusername']) ? $_SESSION['xwusername'] : '';

    $sql = "UPDATE h_timing_gift SET ctime=:ctime, cadmin=:cadmin, zoness=:zoness, create_start=:create_start, create_end=:create_end, lastlogin_start=:lastlogin_start, lastlogin_end=:lastlogin_end, lv_start=:lv_start, lv_end=:lv_end, item1=:item1, bind1=:bind1, num1=:num1, item2=:item2, bind2=:bind2, num2=:num2, item3=:item3, bind3=:bind3, num3=:num3, mtitle=:mtitle, m_content=:m_content, dotime=:dotime  WHERE id=:id ";

    $stmt = $dbh->prepare($sql);

    $stmt->bindParam(':ctime', $ctime);
    $stmt->bindParam(':cadmin', $cadmin);
    $stmt->bindParam(':zoness', $zoness);
    $stmt->bindParam(':create_start', $create_start);
    $stmt->bindParam(':create_end', $create_end);
    $stmt->bindParam(':lastlogin_start', $lastlogin_start);
    $stmt->bindParam(':lastlogin_end', $lastlogin_end);
    $stmt->bindParam(':lv_start', $lv_start);
    $stmt->bindParam(':lv_end', $lv_end);
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

    $stmt->execute();
    echo $stmt->rowCount();
    $dbh = null;
}

