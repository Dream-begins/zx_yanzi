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
</head>
<body>
<h3>xls->帐号明细</h3>
<div id="container">
<a id="pickfiles" href="javascript:;" class="easyui-linkbutton l-btn">选择要上传的文件</a> 
<a id="uploadfiles" href="javascript:;" class="easyui-linkbutton l-btn"> 开始上传</a>
</div>

<div id="filelist">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
<br />
<form  action="bi_exceladd3_do.php" method="post"   id="giftform">
<table>
<tr>
<input type="hidden" style="width:1px" name="file2" id="file2">
<td cols="2" align="center">
<a href="javascript:void(0)" class="easyui-linkbutton" onclick="submitForm_htexcel()">提交</a> 
</td>
</tr>
</table>
</form>    

<pre>
excel 格式 : 区 角色名
(从第二行开始)
</pre>     

<script type="text/javascript">
var uploader = new plupload.Uploader({
	runtimes : 'html5,flash,silverlight,html4',
		browse_button : 'pickfiles',
		container: document.getElementById('container'),
url : 'newweb/upload.php',
flash_swf_url : 'res/plupload/Moxie.swf',
silverlight_xap_url : 'res/plupload/Moxie.xap',

filters : {
	max_file_size : '20mb',
		mime_types: [
		{title : "excel files", extensions : "xls"},
		{title : "excel files", extensions : "xlsx"},
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
	addlog_htexcel("开始！");
	$('#giftform').form('submit',
{
	success:function(data)
{
	addlog_htexcel("成功\n"+data);
},
	error:function()
{
	addlog_htexcel("失败\n"+data);
}
});
}
</script>
</body>
</html>