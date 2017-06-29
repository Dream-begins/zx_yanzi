<?php 
include_once "checklogin.php";
include_once "newweb/h_header.php";
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <title><?php echo FT_COMMON_TITLE;?></title>
    <link rel="stylesheet" type="text/css" href="res/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="res/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="res/jquery.jqplot.min.css">
    <script type="text/javascript" src="res/jquery.min.js"></script>
    <script type="text/javascript" src="res/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="res/admin.js"></script>
    <style type="text/css">
        body {background-color: #ffffff; color: #000000;}
        body, td, th, h1, h2 {font-family: sans-serif;}
        pre {margin: 0px; font-family: monospace;}
        a:link {color: #000099; text-decoration: none; background-color: #ffffff;}
        a:hover {text-decoration: underline;}
        table {border-collapse: collapse;}
        .center {text-align: center;}
        .center table { margin-left: auto; margin-right: auto; text-align: left;}
        .center th { text-align: center !important; }
        td, th { border: 1px solid #000000; font-size: 75%; vertical-align: baseline;}
        h1 {font-size: 150%;}
        h2 {font-size: 125%;}
        .p {text-align: left;}
        .e {background-color: #ccccff; font-weight: bold; color: #000000;}
        .h {background-color: #9999cc; font-weight: bold; color: #000000;}
        .v {background-color: #cccccc; color: #000000;}
        .vr {background-color: #cccccc; text-align: right; color: #000000;}
        img {float: right; border: 0px;}
        hr {width: 600px; background-color: #cccccc; border: 0px; height: 1px; color: #000000;}
    </style>
</head>
<body>

<form action='' method='post' id='one_key_form'>
    <table >
        <tr>
            <td style='border:0px' >游戏服：</td>
            <td colspan='2' style='border:0px'><input size='6' type='test' name='onek_zone1'></td>
        </tr>
        <tr>
            <td style='border:0px'>开服日期：</td>
            <td style='border:0px'>
                <input type='text' value='' id='kaifu_time' name='kaifu_time' size='10' class="easyui-datebox" data-options="formatter: formatDateText_onekey,parser: parseDate_onekey"/>
            </td>
        </tr>
        <tr>
            <td style='border:0px'> <a href="javascript:void(0)" class='easyui-linkbutton' onclick='one_key_sub()'> 开启</a></td>
        </tr>
    </table>
</form>

<br>

<?php 
header("Content-Type:text/html;charset=UTF-8");
ini_set('default_charset','utf-8');
date_default_timezone_set("PRC");
ini_set('display_errors', 1);
error_reporting(0);
$dbh = new PDO('mysql:host='.FT_MYSQL_COMMON_HOST.';dbname='.FT_MYSQL_SHOUYOU_DBNAME.';port='.FT_MYSQL_COMMON_PORT.';charset=utf8', FT_MYSQL_COMMON_ROOT, FT_MYSQL_COMMON_PASS);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);          
$dbh->query("SET NAMES UTF8");

$sql = "SELECT * FROM sy_onekey_confs";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll();
echo '<h3>当前配置↓</h3>';
echo '<table border="0" cellpadding="3" width="600">';
echo '<tr class="h"><th>序号</th>
<th>活动类型</th>
<th>活动编号</th>
<th>开启</th>
<th>结束</th>
<th>过期</th>
<th>活动图标</th>
<th>标签图标</th>
<th>标签标题</th>
<th>标签排序位置</th>
<th>描述背景图</th>
<th>活动描述</th>
<th>开放平台</th>
</tr>';
foreach ($result as $key => $value)
{
echo "<tr><td class='e'>".($key+1)."</td>
<td class='v'>".$value['TYPE']."</td>
<td class='v'>".$value['ACTIVITYID']."</td>
<td class='v'>".$value['TIMESTART']."</td>
<td class='v'>".$value['TIMEEND']."</td>
<td class='v'>".$value['TIMEEXPIRE']."</td>
<td class='v'>".$value['ICONID']."</td>
<td class='v'>".$value['TABID']."</td>
<td class='v'>".$value['TABINFO']."</td>
<td class='v'>".$value['TABTYPE']."</td>
<td class='v'>".$value['PICID']."</td>
<td class='v'>".$value['PICINFO']."</td>
<td class='v'>".$value['PLATID']."</td>
</tr>";
}
echo '</table><br />';

?>

<script type="text/javascript">
function one_key_sub()
{
    if(confirm("注意：确定？取消？"))
    {
        $.ajax({
            type:'POST',
            url:'excel_onekey_do.php',
            data:$('#one_key_form').serialize(),
            success:function(result)
            {
                alert(result);
            }
        });
    }
}

function formatDateText_onekey(date)
{
    return date.formatDate("yyyy-MM-dd"); 
}

function parseDate_onekey(dateStr)
{
    var regexDT = /(\d{4})-?(\d{2})?-?(\d{2})?\s?(\d{2})?:?(\d{2})?:?(\d{2})?/g;  
    var matchs = regexDT.exec(dateStr);  

    if(matchs==null) return new Date();

    var date = new Array();  

    for (var i = 1; i < matchs.length; i++)
    {
        if (matchs[i]!=undefined)
        {
            date[i] = matchs[i];  
        } else {
            if (i<=3) {  
                date[i] = '01';  
            } else {
                date[i] = '00';  
            }
        }
    }
    
    return new Date(date[1], date[2]-1, date[3], date[4], date[5],date[6]);  
}
</script>
</body>
</html>
