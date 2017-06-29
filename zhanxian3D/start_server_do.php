<?php
header("Content-Type:text/html;charset=UTF-8");
ini_set('default_charset','utf-8');
date_default_timezone_set("PRC");
set_time_limit(1800);
ini_set("max_execution_time", "1800"); 
session_start();
ini_set('display_errors', 1);
error_reporting(-1);
include_once "newweb/h_header.php";

$server = isset($_POST['server'] ) ? trim($_POST['server']) : '';    
if(!$server) exit('server empty');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, FT_URL_START_GS."?server=".$server);
curl_setopt($ch, CURLOPT_TIMEOUT,360);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$output = curl_exec($ch);
curl_close($ch);

echo '<pre>';
echo FT_URL_START_GS."?server=".$server;
print_r($output);

echo "执行结束";

