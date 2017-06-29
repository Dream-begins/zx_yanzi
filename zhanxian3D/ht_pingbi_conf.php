<?php include_once "checklogin.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title></title>
      <link rel="stylesheet" type="text/css" href="res/themes/default/easyui.css">
      <link rel="stylesheet" type="text/css" href="res/themes/icon.css">
      <script type="text/javascript" src="res/jquery.min.js"></script>
      <script type="text/javascript" src="res/jquery.easyui.min.js"></script>
      <script type="text/javascript" src="res/admin.js"></script>
      <script type="text/javascript" src="res/plupload/plupload.full.min.js"></script>
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

    <h3>xls->活动配置显示屏蔽设置</h3>
    <div id="container">
        <a id="pickfiles" href="javascript:;" class="easyui-linkbutton l-btn">选择要上传的文件</a> 
        <a id="uploadfiles" href="javascript:;" class="easyui-linkbutton l-btn"> 开始上传</a>
    </div>
    
    <div id="filelist">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
    <br />
    <textarea id="log_htexcel"></textarea>

    <form  action="ht_pingbi_conf_do.php" method="post"   id="giftform">
        <table>
            <input type="hidden" style="width:1px" name="file2" id="file2">
            <tr>
                <td cols="2" align="center">
                    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="submitForm_htexcel()">提交</a> 
                </td>
            </tr>
        </table>
    </form>    

<?php 
header("Content-Type:text/html;charset=UTF-8");
ini_set('default_charset','utf-8');
date_default_timezone_set("PRC");
ini_set('display_errors', 1);
error_reporting(0);
include_once "newweb/h_header.php";

$dbh = new PDO('mysql:host='.FT_MYSQL_COMMON_HOST.';dbname='.FT_MYSQL_ADMIN_DBNAME.';port='.FT_MYSQL_COMMON_PORT.';charset=utf8', FT_MYSQL_COMMON_ROOT, FT_MYSQL_COMMON_PASS);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);          
$dbh->query("SET NAMES UTF8");

$sql = "SELECT * FROM ft_gapingbi_conf";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll();
echo '<h3>当前配置↓(刷新页面查看更新)</h3>';
echo '<table border="0" cellpadding="3" width="600">';
echo '<tr class="h"><th>序号</th>
<th>类型</th>
<th>活动编号</th>

</tr>';
foreach ($result as $key => $value)
{
    echo "<tr><td class='e'>".($key+1)."</td>
    <td class='v'>".$value['type']."</td>
    <td class='v'>".$value['bianhao']."</td>
    </tr>";
}
echo '</table><br />';

?>
<script type="text/javascript">
    // Custom example logic

    var uploader = new plupload.Uploader({
        runtimes : 'html5,flash,silverlight,html4',
        browse_button : 'pickfiles', // you can pass in id...
        container: document.getElementById('container'), // ... or DOM Element itself
        url : 'uploadexcel.php',
        flash_swf_url : 'res/plupload/Moxie.swf',
        silverlight_xap_url : 'res/plupload/Moxie.xap',
        
        filters : {
            max_file_size : '10mb',
            mime_types: [
                {title : "excel files", extensions : "xls"},
            ]
        },

        init: {
            PostInit: function() {
                document.getElementById('filelist').innerHTML = '';

                document.getElementById('uploadfiles').onclick = function() {
                    uploader.start();
                    return false;
                };
            },

            FilesAdded: function(up, files) {
                plupload.each(files, function(file) {
                    document.getElementById('filelist').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
                });
            },

            UploadProgress: function(up, file) {
                document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
                if(file.percent=100)
                {
                    document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML += "上传成功";
                    document.getElementById('file2').value = "/"+file.name;
                }
            },

            Error: function(up, err) {
                document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
            }
        }
    });

    uploader.init();

    function addlog_htexcel(msg)
    {
        $("#log_htexcel").val($("#log_htexcel").val()+"\n"+msg);
    }

    function submitForm_htexcel()
    {
        addlog_htexcel("开始执行！");
        $('#giftform').form('submit',
        {
            success:function(data)
            {
                addlog_htexcel("执行成功\n"+data);
            },
            error:function()
            {
                addlog_htexcel("执行失败\n"+data);
            }
        });
    }
</script>
</body>
</html>
