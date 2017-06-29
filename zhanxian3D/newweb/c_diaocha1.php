<?php
date_default_timezone_set("PRC");
ini_set('display_errors', 1);
error_reporting(0);
session_start();
include "h_define.php";
$action = $_GET['action'];

$dbh = new PDO('mysql:host='.FT_MYSQL_COMMON_HOST.';dbname=admin;port='.FT_MYSQL_COMMON_PORT.';charset=utf8',FT_MYSQL_COMMON_ROOT, FT_MYSQL_COMMON_PASS);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbh->query("SET NAMES UTF8");

include 'arr1.php';
$title_arr = array();

foreach ($diaocha_arrs['forms'] as $key => $value)
{
    foreach ($value['selects'] as $k => $v)
    {
        $flag = 't'.($key);
        $title_arr[$flag][] = $v;
    }
}

if($action == 'list'){

    $sort = (isset($_POST['sort']) && !empty($_POST['sort'])) ? $_POST['sort'] :  'id';
    $order = (isset($_POST['order']) && !empty($_POST['order'])) ? $_POST['order'] : 'desc';
    $page = (isset($_POST['page']) && !empty($_POST['page'])) ? $_POST['page'] : '1';
    $rows = (isset($_POST['rows']) && !empty($_POST['rows'])) ? $_POST['rows'] : '20';

    $sql_order = '';

    if( $sort == 'id' && $order == 'desc' ) $sql_order = ' ORDER BY id desc ';
    if( $sort == 'id' && $order == 'asc' ) $sql_order = ' ORDER BY id asc ';

    $sql_limit = " LIMIT " . intval(($page-1) * $rows) . " , " . (int)$rows;

    $sql = "SELECT * FROM ".'diaocha'.$diaocha_arrs['banben']." " . $sql_order . $sql_limit;

    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    foreach ($result as $key => $value)
    {

        foreach ($diaocha_arrs['forms'] as $key1 => $value1)
        {
            if(@$value1['type'] == 'single')
            {
                $a11 = 't'.$key1;
                $a12 = $value[$a11]-1;
                $result[$key][$a11] = @$title_arr[$a11][$a12];
            }

            if(@$value1['type'] == 'single&input')
            {
                $a11 = 't'.$key1;
                $a12 = $value[$a11]-1;
                $result[$key][$a11] = @$title_arr[$a11][$a12];
            }

            if($value1['type'] == 'check')
            {
                foreach ($value1['selects'] as $k => $v)
                {
                    $a11 = 't'.$key1;
                    $a13 = 't'.$key1.'_'.$k;
                    $b = strpos($a13, '_');
                    $a12 = trim(substr($a13,$b),'_');
                    if($value[$a13] == 'on') $result[$key][$a11] .= @$title_arr[$a11][$a12]."<br>";
                }
            }

            if($value1['type'] == 'check&input')
            {
                foreach ($value1['selects'] as $k => $v)
                {
                    $a11 = 't'.$key1;
                    $a13 = 't'.$key1.'_'.$k;
                    $b = strpos($a13, '_');
                    $a12 = trim(substr($a13,$b),'_');
                    if($value[$a13] == 'on') $result[$key][$a11] .= @$title_arr[$a11][$a12]."<br>";
                }
            }


        }
    }

    $return['rows'] = $result;

    $sql = "SELECT COUNT(*) AS total FROM ".'diaocha'.$diaocha_arrs['banben'];
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch();
    $return['total'] = $result['total'];


    exit(json_encode($return));
}


if($action == "putcsv")
{

    $sql = "SELECT * FROM ".'diaocha'.$diaocha_arrs['banben']." limit 3000 ";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    foreach ($result as $key => $value)
    {

        foreach ($diaocha_arrs['forms'] as $key1 => $value1)
        {
            if(@$value1['type'] == 'single')
            {
                $a11 = 't'.$key1;
                $a12 = $value[$a11]-1;
                $result[$key][$a11] = @$title_arr[$a11][$a12];
            }

            if(@$value1['type'] == 'single&input')
            {
                $a11 = 't'.$key1;
                $a12 = $value[$a11]-1;
                $result[$key][$a11] = @$title_arr[$a11][$a12];
            }

            if($value1['type'] == 'check')
            {
                foreach ($value1['selects'] as $k => $v)
                {
                    $a11 = 't'.$key1;
                    $a13 = 't'.$key1.'_'.$k;
                    $b = strpos($a13, '_');
                    $a12 = trim(substr($a13,$b),'_');
                    if($value[$a13] == 'on') $result[$key][$a11] .= @$title_arr[$a11][$a12]."<br>";
                }
            }

            if($value1['type'] == 'check&input')
            {
                foreach ($value1['selects'] as $k => $v)
                {
                    $a11 = 't'.$key1;
                    $a13 = 't'.$key1.'_'.$k;
                    $b = strpos($a13, '_');
                    $a12 = trim(substr($a13,$b),'_');
                    if($value[$a13] == 'on') $result[$key][$a11] .= @$title_arr[$a11][$a12]."<br>";
                }
            }


        }
    }

    $result2 = array();
    foreach ($result as $key => $value)
    {
        $result2[$key]['zone_id'] = $value['zone_id'];
        $result2[$key]['name'] = $value['name'];
        $result2[$key]['t0'] = $value['t0'];
        $result2[$key]['t1'] = $value['t1'];
        $result2[$key]['t2'] = $value['t2'];
        $result2[$key]['t3'] = $value['t3'];
        $result2[$key]['t4'] = $value['t4'];
        $result2[$key]['t5'] = $value['t5'];
        $result2[$key]['t6'] = $value['t6'];
        $result2[$key]['t7'] = $value['t7'];
        $result2[$key]['t8'] = $value['t8'];
        $result2[$key]['t9'] = $value['t9'];
        $result2[$key]['t10'] = $value['t10'];
        $result2[$key]['t11'] = $value['t11'];
        $result2[$key]['t12'] = $value['t12'];
        $result2[$key]['t13'] = $value['t13'];
        $result2[$key]['t14'] = $value['t14'];
        $result2[$key]['t15'] = $value['t15'];
        $result2[$key]['t16'] = $value['t16'];
    }


    array_unshift($result2, array('区','角色名','题1','题2','题3','题4','题5','题6','题7','题8','题9','题10','题11','题12','题13','题14','题15','题16','题17'));

    outputCSV($result2,'调查问卷');
}



function outputCSV($data,$name)
{
    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename={$name}.csv");
    header("Pragma: no-cache");
    header("Expires: 0");

    $outputBuffer = fopen("php://output", 'w');
    foreach($data as $val)
    {
        foreach ($val as $key => $val2) 
        {
            $val[$key] = iconv('utf-8', 'gbk', $val2);
        }
        fputcsv($outputBuffer, $val);
    }
    fclose($outputBuffer);
}



