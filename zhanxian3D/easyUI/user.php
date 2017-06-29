<?php
require 'config.php';
$query=mysql_query("select ID,user,pass,passChange from admin") or die('SQL 错误!');
$json='';
while(!!$row=mysql_fetch_array($query,MYSQL_ASSOC)){
    $json.=json_encode($row).',';
}
$json= substr($json, 0,-1);
echo '['.$json.']';

mysql_close();