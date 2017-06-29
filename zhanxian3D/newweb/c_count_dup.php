<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'core.php';
include_once "../upgrade.php";
include_once "../common.php";
//$page=$_POST['page'];
//$pageSize=$_POST['rows'];
//$first=$pageSize*($page-1);
$sql1="select DUPID,DUPNAME,count(*) as a_times from  db_zxdup group by DUPID";
$result1=mysql_query($sql1);
$result2=mysql_query("select DUPID,DUPNAME,count(*) as f_times from db_zxdup WHERE  DUPEVENT=2 group by DUPID");
$total=mysql_num_rows(mysql_query("select count(*) as num from db_zxdup  group by DUPID"));
$json='';
while ($row1=mysql_fetch_assoc($result1)){
    $rows1[]=$row1;
}
while ($row2=mysql_fetch_assoc($result2)){
    $rows2[]=$row2;
}
foreach($rows2 as $vv){
    foreach($rows1 as $v){
        if($v['DUPID']==$vv['DUPID']){
            $v['f_times']=$vv['f_times'];
            $v['percent']= round($v['f_times']/$v['a_times']*100,2).'%'; 
            $json.= json_encode($v).',';                      
        }
    }
}

    $json= substr($json, 0,-1); 
    echo '{"total":'.$total.',"rows":['.$json.']}';
    mysql_close();