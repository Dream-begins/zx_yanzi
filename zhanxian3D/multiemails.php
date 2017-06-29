<?php
include_once "checklogin.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>探墓风云</title>
    <link rel="stylesheet" type="text/css" href="res/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="res/themes/icon.css">
    <script type="text/javascript" src="res/jquery.min.js"></script>
    <script type="text/javascript" src="res/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="res/admin.js"></script>
    <script type="text/javascript" src="res/plupload/plupload.full.min.js"></script>
</head>
<body>
<h3>批量发送补偿</h3>
<div id="container">
    <a id="pickfiles" href="javascript:;" class="easyui-linkbutton l-btn">选择要上传的文件</a>
    <a id="uploadfiles" href="javascript:;" class="easyui-linkbutton l-btn"> 开始上传</a>
</div>

<div id="filelist">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
<br />
<textarea id="log_htexcel"></textarea>

<form  action="domultiemails.php" method="post"   id="giftform">
    <table>
        <input type="hidden" style="width:1px" name="file2" id="file2">
        <tr>
            <td cols="2" align="center">
                <a href="javascript:void(0)" class="easyui-linkbutton" onclick="submitForm_htexcel()">提交</a>
            </td>
        </tr>
    </table>
</form>

<pre>
    说明：
        1、excel 需要转换为 xls后缀 ,不要有中文
        2、excel 字段 A开始->【 服务器ID  角色名   邮件标题 邮件内容 道具id1 道具数量1 道具id2 道具数量2 道具id3 道具数量3】
        3、每条记录支持3个道具
        4、所有道具皆为绑定
        5、程序将从excel第二行开始执行
        <a href="./excel/mulemails_demo.xls">下载模板</a>
</pre>

<script type="text/javascript">
    // Custom example logic

    var uploader = new plupload.Uploader({
        runtimes : 'html5,flash,silverlight,html4',
        browse_button : 'pickfiles', // you can pass in id...
        container: document.getElementById('container'), // ... or DOM Element itself
        url : 'upload2.php',
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
        addlog_htexcel("开始发放！");
        $('#giftform').form('submit',
            {
                success:function(data)
                {
                    addlog_htexcel("发放成功\n"+data);
                },
                error:function()
                {
                    addlog_htexcel("发放失败\n"+data);
                }
            });
    }
</script>
</body>
</html>
