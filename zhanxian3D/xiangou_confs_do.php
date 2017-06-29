<?php 
ini_set('default_charset','utf-8'); 
include_once "common.php";
include_once "checklogin.php";
include_once "upgrade.php";

include_once "newweb/h_header.php";

$DB_HOST=FT_MYSQL_COMMON_HOST; 
$DB_USER=FT_MYSQL_COMMON_ROOT;
$DB_PASS=FT_MYSQL_COMMON_PASS;
$DB_NAME=FT_MYSQL_BILL_DBNAME;

$con = mysql_connect($DB_HOST,$DB_USER,$DB_PASS);

if(!$con) die("mysql connect error");

mysql_query("set names 'utf8'");

mysql_select_db(FT_MYSQL_ADMIN_DBNAME, $con) or die("mysql select db error". mysql_error());
$action = $_GET['action'];

if($action == 'add')
{
    $b_class = post_param('b_class');
    $time_id = post_param('time_id');
    $shop_page = post_param('shop_page');
    $shop_position = post_param('shop_position');
    $item_id = post_param('item_id');
    $item_name = post_param('item_name');
    $bind = post_param('bind');
    $money_type = post_param('money_type');
    $open_type = post_param('open_type');
    $old_money = post_param('old_money');
    $now_money = post_param('now_money');
    $onep_limit = post_param('onep_limit');
    $all_limit = post_param('all_limit');
    $icon_type = post_param('icon_type');
    $lv_limit = post_param('lv_limit');
    $icon_img = post_param('icon_img');
    $icon_title = post_param('icon_title');
    $weight = post_param('weight');
    $ds_time = post_param('ds_time');
    $de_time = post_param('de_time');
    $now = time();

    $MONEYID=(int)post_param("MONEYID");
    $AWARDS=post_param("AWARDS");
    $STAGEPRICES=post_param("STAGEPRICES");
    $PRESHOW=post_param('PRESHOW');
    $DESC=post_param("DESC");

    $admin = isset($_SESSION['xwusername']) ? $_SESSION['xwusername'] : '';

    $sql = "INSERT INTO `common_shoplimit_list`(`b_class`,`time_id`,`item_id`,`item_name`,`bind`,`money_type`,`open_type`,`old_money`,`now_money`,`onep_limit`,`all_limit`,`ds_time`,`de_time`,`icon_type`,`lv_limit`,`icon_img`,`icon_title`,`weight`,`mtime`,`madmin`,`shop_page`,`shop_position`,MONEYID,AWARDS,STAGEPRICES,PRESHOW,`DESC`) VALUES('{$b_class}','{$time_id}','{$item_id}','{$item_name}','{$bind}','{$money_type}','{$open_type}','{$old_money}','{$now_money}','{$onep_limit}','{$all_limit}','{$ds_time}','{$de_time}','{$icon_type}','{$lv_limit}','{$icon_img}','{$icon_title}','{$weight}','{$now}','{$admin}','{$shop_page}','{$shop_position}','{$MONEYID}','{$AWARDS}','{$STAGEPRICES}','{$PRESHOW}','{$DESC}') ";

    $result = mysql_query($sql);
}

if($action == 'list')
{
    $sql = "SELECT * FROM `common_shoplimit_list`";
    $result = mysql_query($sql) or die("Invalid query: " . mysql_error());
    $total = mysql_num_rows($result);
    $datas = array();
    while ($row = mysql_fetch_assoc($result)) 
    {
        $datas[] = $row;
    }
    foreach ($datas as $key => $value) 
    {
        $datas[$key]['mtime'] = date('Y-m-d', $value['mtime']);
        $datas[$key]['del'] = "<a href='javascript:void(0)' onclick='hxiangou_delwid(\"".$value['id']."\")'>删除</a>";
    }



    $array_data = array("total"=>$total,"rows"=>$datas);
    echo json_encode($array_data);
}
$delid = isset($_GET['delid']) ? (int)$_GET['delid'] : 0;

if($action == 'del' && $delid)
{
    $sql = "DELETE FROM `common_shoplimit_list` WHERE `id` = '{$delid}'";
    mysql_query($sql);
}

if($action == 'update')
{
    $b_class = post_param('b_class');
    $time_id = post_param('time_id');
    $shop_page = post_param('shop_page');
    $shop_position = post_param('shop_position');
    $item_id = post_param('item_id');
    $item_name = post_param('item_name');
    $bind = post_param('bind');
    $money_type = post_param('money_type');
    $open_type = post_param('open_type');
    $old_money = post_param('old_money');
    $now_money = post_param('now_money');
    $onep_limit = post_param('onep_limit');
    $all_limit = post_param('all_limit');
    $icon_type = post_param('icon_type');
    $lv_limit = post_param('lv_limit');
    $icon_img = post_param('icon_img');
    $icon_title = post_param('icon_title');
    $weight = post_param('weight');
    $ds_time = post_param('ds_time');
    $de_time = post_param('de_time');
    $now = time();
    $admin = isset($_SESSION['xwusername']) ? $_SESSION['xwusername'] : '';
    $id = post_param('id');
    $MONEYID=(int)post_param("MONEYID");
    $AWARDS=post_param("AWARDS");
    $STAGEPRICES=post_param("STAGEPRICES");
    $PRESHOW=post_param('PRESHOW');
    $DESC=post_param("DESC");

    if(!$id) return;


    $sql = "UPDATE `common_shoplimit_list` SET `b_class` = '{$b_class}',`time_id` = '{$time_id}',`item_id` = '{$item_id}',`item_name` = '{$item_name}',`bind` = '{$bind}',`money_type` = '{$money_type}',`open_type` = '{$open_type}',`old_money` = '{$old_money}',`now_money` = '{$now_money}',`onep_limit` = '{$onep_limit}',`all_limit` = '{$all_limit}',`ds_time` = '{$ds_time}',`de_time` = '{$de_time}',`icon_type` = '{$icon_type}',`lv_limit` = '{$lv_limit}',`icon_img` = '{$icon_img}',`icon_title` = '{$icon_title}',`weight` = '{$weight}',`mtime` = '{$mtime}',`madmin` = '{$madmin}',`shop_page` = '{$shop_page}',`shop_position` = '{$shop_position}',`MONEYID`='{$MONEYID}',`AWARDS`='{$AWARDS}',`STAGEPRICES`='{$STAGEPRICES}',`PRESHOW`='{$PRESHOW}',`DESC`='{$DESC}' WHERE id = '{$id}'";

    $result = mysql_query($sql);
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
