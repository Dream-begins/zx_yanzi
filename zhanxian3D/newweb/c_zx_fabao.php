<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'core.php';
$sql='';
$zone='';
$from='';
$to ='';
$export=@$_GET['export'];
if($export){
   if(isset($_GET['zone']) && !empty($_GET['zone'])){
        $zone=" ZONEID='{$_GET['zone']}' AND ";
        $sql.=$zone;
    }    
    if (isset($_GET['from']) && !empty($_GET['from'])) {
            $from = " FROM_UNIXTIME(TIME,'%Y-%m-%d %H:%i:%S')>='{$_GET['from']}' AND ";
            $sql .= $from;
        }

    if (isset($_GET['to']) && !empty($_GET['to'])) {
            $to = "FROM_UNIXTIME(TIME,'%Y-%m-%d')<='{$_GET['to']}' AND ";
            $sql .= $to;
        }

    if(!empty($sql)){
         $sql= ' WHERE '.substr($sql, 0,-4);
    } 
     $sql    = "SELECT from_unixtime(TIME,'%Y-%m-%d %H:%i:%S') as datetime,ZONEID,USERID,ACCNAME,NAME,LEVEL,FABAOEVENT,FABAOID,FABAONAME,COST,ITEMID,ITEMCOUNT,ITEMNAME,PARA1,PARA2,PARA3,PARA4,PARA5,EXTRA from db_zxfabao $sql order by datetime desc";
    $result = mysql_query($sql) or die("Invalid query: " . mysql_error());
    $rows=array();
    while ($row = mysql_fetch_row($result)){ 
        if($row['6']=='1'){
            $row['6']='装备法宝';
        }elseif($row['6']=='2'){
            $row['6']='卸下法宝';
        }elseif($row['6']=='3'){
            $row['6']='升级法宝';
        }
        if($row['1']=='2'){
            $row['1']='2(万佳测试服)';
        }elseif($row['1']=='6'){
            $row['1']='6(外网稳定服)';
        }elseif($row['1']=='100'){
            $row['1']='100(内网测试服)';
        }elseif($row['1']=='1'){
            $row['1']='1(丁乐开发服)';
        }elseif($row['1']=='3'){
            $row['1']='3(开发服)';
        }elseif($row['1']=='4'){
            $row['1']='4(周传高开发服)';
        }elseif($row['1']=='7'){
            $row['1']='7(任务测试服)';
        }elseif($row['1']=='8'){
            $row['1']='8(战斗测试服)';
        }elseif($row['1']=='9'){
            $row['1']='9(数据测试服)';
        }elseif($row['1']=='5'){
            $row['1']='5(内网稳定服)';
        }elseif($row['1']=='12'){
            $row['1']='12(万鹏测试服)';
        }
        array_push($rows,$row);
    }
    require_once "../exportExcel.php";
       export(array("日期","区","玩家id","账号名","玩家名","玩家等级","法宝事件","法宝id","法宝名称","消耗银币","消耗道具id","消耗道具数量","消耗道具名称","参数1","参数2","参数3","参数4","参数5","额外信息"),$rows,"法宝相关");
}else{
        $page=$_POST['page'];
        $pageSize=$_POST['rows'];
        $first=$pageSize*($page-1);
    if(isset($_POST['zone']) && !empty($_POST['zone'])){
        $zone=" ZONEID='{$_POST['zone']}' AND ";
        $sql.=$zone;
    }

    if (isset($_POST['from']) && !empty($_POST['from'])) {
            $from = " FROM_UNIXTIME(TIME,'%Y-%m-%d %H:%i:%S')>='{$_POST['from']}' AND ";
            $sql .= $from;
        }

    if (isset($_POST['to']) && !empty($_POST['to'])) {
            $to = "FROM_UNIXTIME(TIME,'%Y-%m-%d')<='{$_POST['to']}' AND ";
            $sql .= $to;
        }

    if(!empty($sql)){
         $sql= ' WHERE '.substr($sql, 0,-4);
    }

    $result=mysql_query("select *,from_unixtime(TIME,'%Y-%m-%d %H:%i:%S') as datetime from db_zxfabao $sql LIMIT $first,$pageSize");
        $total=mysql_num_rows(mysql_query("select * from db_zxfabao $sql"));
    $json='';
    while($row=mysql_fetch_assoc($result)){
        if($row['FABAOEVENT']=='1'){
            $row['FABAOEVENT']='装备法宝';
        }elseif($row['FABAOEVENT']=='2'){
            $row['FABAOEVENT']='卸下法宝';
    }elseif($row['FABAOEVENT']=='3'){
            $row['FABAOEVENT']='升级法宝';
        }
        if($row['ZONEID']=='2'){
            $row['ZONEID']='2(万佳测试服)';
        }elseif($row['ZONEID']=='6'){
            $row['ZONEID']='6(外网稳定服)';
        }elseif($row['ZONEID']=='100'){
            $row['ZONEID']='100(内网测试服)';
        }elseif($row['ZONEID']=='1'){
            $row['ZONEID']='1(丁乐开发服)';
        }elseif($row['ZONEID']=='3'){
            $row['ZONEID']='3(开发服)';
        }elseif($row['ZONEID']=='4'){
            $row['ZONEID']='4(周传高开发服)';
        }elseif($row['ZONEID']=='7'){
            $row['ZONEID']='7(任务测试服)';
        }elseif($row['ZONEID']=='8'){
            $row['ZONEID']='8(战斗测试服)';
        }elseif($row['ZONEID']=='9'){
            $row['ZONEID']='9(数据测试服)';
        }elseif($row['ZONEID']=='5'){
            $row['ZONEID']='5(内网稳定服)';
        }elseif($row['ZONEID']=='12'){
            $row['ZONEID']='12(万鹏测试服)';
        }
        $json.= json_encode($row).',';
    }
    $json= substr($json, 0,-1);
    echo '{"total":'.$total.',"rows":['.$json.']}';
}
mysql_close(); 
