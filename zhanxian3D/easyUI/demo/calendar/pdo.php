<?php
	//1.设置响应头信息
	header('content-type:text/html;charset=utf-8');
	//2.实例化pdo类
	$pdo=new PDO('mysql:host=localhost;dbname=project','root','root');
	