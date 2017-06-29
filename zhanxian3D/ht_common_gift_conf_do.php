<?php
ini_set('default_charset','utf-8');
include_once "common.php";
include_once "checklogin.php";
include_once "upgrade.php";
include_once "newweb/h_header.php";
ini_set('display_errors', 1);
error_reporting('0');
    $DB_HOST=FT_MYSQL_COMMON_HOST;
    $DB_USER=FT_MYSQL_COMMON_ROOT;
    $DB_PASS=FT_MYSQL_COMMON_PASS;
    $DB_NAME=FT_MYSQL_BILL_DBNAME;
    $con = mysql_connect($DB_HOST,$DB_USER,$DB_PASS);

mysql_select_db(FT_MYSQL_ADMIN_DBNAME, $con) or die("mysql select db error". mysql_error());


$con = mysql_connect($DB_HOST,$DB_USER,$DB_PASS);

if(!$con) die("mysql connect error");

mysql_query("set names utf8");



$action = isset($_GET['action']) ? $_GET['action'] : "";
if($action == "add")
{
    $apply_account = post_param("apply_account", "");
    $g_name = post_param("g_name", "");
    $objs1 = post_param("objs1", "0");
    $objs2 = post_param("objs2", "0");
    $objs3 = post_param("objs3", "0");
    $nums1 = post_param("nums1", "0");
    $nums2 = post_param("nums2", "0");
    $nums3 = post_param("nums3", "0");
    $binds1 = post_param("binds1", "0");
    $binds2 = post_param("binds2", "0");
    $binds3 = post_param("binds3", "0");
    $state = post_param("state", "1");
    
    $now = time();

    //state描述 1:内部 2:外部
    $sql = "INSERT INTO `ft_common_use_gift`(`name`,`item1_id`,`item1_num`,`item1_bind`,`item2_id`,`item2_num`,`item2_bind`,`item3_id`,`item3_num`,`item3_bind`,`mtime`,`madmin`,`state`) VALUES('{$g_name}','{$objs1}','{$nums1}','{$binds1}','{$objs2}','{$nums2}','{$binds2}','{$objs3}','{$nums3}','{$binds3}','{$now}','{$apply_account}','{$state}')";
    mysql_query($sql);
}
if($action = "del")
{
    $id = isset($_POST['id']) ? (int)$_POST['id'] : "";
    if(!$id) exit;
    echo $sql = "DELETE FROM `ft_common_use_gift` WHERE `id` = '{$id}'";
    mysql_query($sql);
}

//////////////////////////functions
function post_param($keyword, $default=null)
{
    $return_data=$default;
    if(isset($_POST[$keyword])){
        $return_data = SS($_POST[$keyword]);
    }
    return $return_data;
}

function SS($name)
{
    $name = trim($name);
    if (get_magic_quotes_gpc()) 
    {
        return $name;
    }else
    {
        return mysql_real_escape_string($name);
    }
}
