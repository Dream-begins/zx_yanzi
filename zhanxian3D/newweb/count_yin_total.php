<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'core.php';
include_once "../upgrade.php";
include_once "../common.php";
$sql='';
$from='';
$to='';
$zone='';
if(isset($_POST['zone']) && !empty($_POST['zone'])){
    $zone=" ZONEID='{$_POST['zone']}' AND ";
    $sql.=$zone;
}
if (isset($_POST['from']) && !empty($_POST['from'])) {
		$from = " FROM_UNIXTIME(TIME,'%Y-%m-%d') >='{$_POST['from']}' AND ";
		$sql .= $from;
}
if (isset($_POST['to']) && !empty($_POST['to'])) {
		$to = " FROM_UNIXTIME(TIME,'%Y-%m-%d') <='{$_POST['to']}' AND ";
		$sql .= $to;
}
    $sql= ' WHERE '.$sql." TYPE=3";
     $sql2="select max(TIME) time1,USERID from log_currency $sql group by USERID";
     $result2=mysql_query($sql2);
     $sum=0;
    while ($row1=mysql_fetch_assoc($result2)){
        $s=$sql." and  TIME=".$row1['time1']." and USERID=".$row1['USERID'];
        $sql3="select NOWMount,FROM_UNIXTIME(TIME,'%Y-%m-%d %H:%i:%S') t,USERID from log_currency $s";
        $result3=mysql_query($sql3);
        while($row2=mysql_fetch_assoc($result3)){
//            echo '<pre>';
//            print_r($row2);
            $sum+=$row2['NOWMount'];
        }
    }
    $total=mysql_num_rows(mysql_query("select * from log_currency $sql group by USERID"));
    $sql="select sum(REDUCEYuanBao) as sum1,sum(ADDMount) as sum2 from log_currency $sql";
   
    $result=mysql_query($sql);
    while($row=mysql_fetch_assoc($result)){
        $rows[]=$row;
    }
    $num1=intval($rows[0]['sum1']);
    $num2=intval($rows[0]['sum2']);
    $data=array("sum1"=>$num1,"sum2"=>$num2,"sum"=>$sum,"total"=>$total);
    $json= json_encode($data);
    echo '['.$json.']';

    
    
 