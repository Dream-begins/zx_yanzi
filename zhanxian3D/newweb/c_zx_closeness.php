<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'core.php';
$page=$_POST['page'];
$pageSize=$_POST['rows'];
$first=$pageSize*($page-1);
$sql='';
$server='';
$from='';
$to ='';
if(isset($_POST['zone']) && !empty($_POST['zone'])){
    $server=" SERVERID='{$_POST['zone']}' AND ";
    $sql.=$server;
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

$result=mysql_query("select *,from_unixtime(TIME,'%Y-%m-%d %H:%i:%S') as datetime from db_zxcloseness $sql LIMIT $first,$pageSize");
    $total=mysql_num_rows(mysql_query("select * from db_zxcloseness $sql"));
$json='';
while($row=mysql_fetch_assoc($result)){
    $json.= json_encode($row).',';
}
$json= substr($json, 0,-1);
echo '{"total":'.$total.',"rows":['.$json.']}';
mysql_close(); 
