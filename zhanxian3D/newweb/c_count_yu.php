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
    $sql= ' WHERE '.$sql." TYPE=1";


    $sql="select EVENT,sum(REDUCEYuanBao) as count1,sum(ADDMount) as count2,sum(NOWMount) as count3 from log_currency $sql group by EVENT";
    $result=mysql_query($sql);
    $json='';
    while ($row=mysql_fetch_assoc($result)){
        $json.= json_encode($row).',';
    }
    $json= substr($json, 0,-1); 
    echo '{"rows":['.$json.']}';
    mysql_close();