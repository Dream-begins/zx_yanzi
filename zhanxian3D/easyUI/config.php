<?php
    header('content-type:text/html;charset=utf-8');
    define('DB_HOST', 'localhost');
    define('DB_USER', '127.0.0.1');
    define('DB_PWD', 'root');
    define('DB_NAME', 'admin');   
    $conn=mysql_connect(DB_HOST,DB_USER,DB_PWD) or die('数据库连接失败:'.mysql_error());
    mysql_select_db(DB_NAME) or die('数据库错误:'.mysql_error());
    mysql_query('set names utf8') or die('字符集错误:'.mysql_error());
        