<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'core.php';
include_once "../upgrade.php";
include_once "../common.php";
$sql1='';
$sql2='';
//$page=$_POST['page'];
//$pageSize=$_POST['rows'];
//$first=$pageSize*($page-1);
if(isset($_POST['zone']) && !empty($_POST['zone'])){
    $zone=" ZONEID='{$_POST['zone']}' AND ";
    $sql1.=$zone;
}
if (!empty($sql1)){
    $sql2=$sql1;
    $sql1= ' WHERE '.substr($sql1, 0,-4);
}
$sql="select QUESTID,QUESTNAME,count(*) as a_times from  db_zxquest $sql1 group by QUESTID";
$result1=mysql_query($sql);
$result2=mysql_query("select QUESTID,QUESTNAME,count(*) as f_times from db_zxquest where $sql2 QUESTEVENT=2 group by QUESTID");
$total=mysql_num_rows(mysql_query("select count(*) as num from db_zxquest where $sql2 QUESTEVENT=2 group by QUESTID"));
$json='';
while ($row1=mysql_fetch_assoc($result1)){
    $rows1[]=$row1;
}
while ($row2=mysql_fetch_assoc($result2)){
    $rows2[]=$row2;
}
foreach($rows2 as $vv){
    foreach($rows1 as $v){
        if($v['QUESTID']==$vv['QUESTID']){
            $v['f_times']=$vv['f_times'];
            $v['percent']= round($v['f_times']/$v['a_times']*100,2).'%'; 
            $json.= json_encode($v).',';                      
        }
    }
}

    $json= substr($json, 0,-1); 
    echo '{"total":'.$total.',"rows":['.$json.']}';
    mysql_close();