<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="jquery-easyui-1.4/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="jquery-easyui-1.4/themes/icon.css">
    <script type="text/javascript" src="jquery-easyui-1.4/jquery.min.js"></script>
    <script type="text/javascript" src="jquery-easyui-1.4/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="jquery-easyui-1.4/locale/easyui-lang-zh_CN.js"></script>
    <script type="text/javascript" src="../res/plupload/plupload.full.min.js"></script>
</head>
<body>
    <h3>xls->探墓王-国战</h3>
    <div id="container">
        <a id="pickfiles" href="javascript:;" class="easyui-linkbutton l-btn">选择要上传的文件</a> 
        <a id="uploadfiles" href="javascript:;" class="easyui-linkbutton l-btn"> 开始上传</a>
    </div>
    
    <div id="filelist">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
    <br />
    <textarea id="log_htexcel"></textarea>

    <form  action="c_guozhan_excel.php" method="post"   id="giftform">
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
excel 格式：区（如45）   站区id（如1）
excel 从第一行开始执行
excel 后缀名 .xls
</pre>     

<script type="text/javascript">

    var uploader = new plupload.Uploader({
        runtimes : 'html5,flash,silverlight,html4',
        browse_button : 'pickfiles', // you can pass in id...
        container: document.getElementById('container'), // ... or DOM Element itself
        url : 'upload.php',
        flash_swf_url : 'plupload/Moxie.swf',
        silverlight_xap_url : 'plupload/Moxie.xap',
        
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
