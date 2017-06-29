<?php 
include_once "checklogin.php";
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>探墓王后台管理</title>
	<link rel="stylesheet" type="text/css" href="res/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="res/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="res/jquery.jqplot.min.css">
	<script type="text/javascript" src="res/jquery.min.js"></script>
	<script type="text/javascript" src="res/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="res/admin.js"></script>
</head>
<body >

<div><h2>补偿发放</h2></div>

<div>
	<form  action="doemail.php" method="post" id="giftform">
		<table>
			<tr>
				<td>服务器:</td>
				<td><input required="true" style="width:150px" id="zone" name="zone"></td>
			</tr>
			<tr>
				<td>用户名</td>
				<td><input required="true" style="width:150px" id="username" name="username" onchange="loadInfo_email0917()"></td>
			</tr>
			<tr>
				<td colnum="2" id="usernameinfo_email0917"></td>
			</tr>
			<tr>
				<td>道具:</td>
				<td>
					<input type="text" class="easyui-validatebox" value="0" required="true" name="objs1" id="email_objs1"/>,
					<input type="text" value="0"  class="easyui-validatebox" required="true" name="objs2" id="email_objs2"/>,
					<input type="text" class="easyui-validatebox" value="0"  required="true" name="objs3" id="email_objs3"/>
				</td>
			</tr>
			<tr>
				<td>数量:</td>
				<td>
					<input type="text" class="easyui-validatebox" required="true" name="nums1" id="email_nums1" value="0" />,
					<input type="text" class="easyui-validatebox" required="true" name="nums2" id="email_nums2" value="0" />,
					<input type="text" class="easyui-validatebox" required="true" name="nums3" id="email_nums3" value="0" />
				</td>
			</tr>
			<tr>
				<td>是否绑定:</td>
				<td>
					<input type="text" value="1" class="easyui-validatebox" required="true" name="binds1" id="email_binds1"/>,
					<input type="text" value="1" class="easyui-validatebox" required="true" name="binds2" id="email_binds2"/>,
					<input type="text" value="1" class="easyui-validatebox" required="true" name="binds3" id="email_binds3"/>
				</td>
			</tr>
			<tr>
				<td>邮件标题:</td>
				<td><input type="text" class="easyui-validatebox" required="true" name="subject" id="subject"/></td>
			</tr>
			<tr>
				<td>邮件内容:</td>
				<td>
					<textarea name="content" class="easyui-validatebox" required="true" id="content"></textarea>
					<span id="inputContent2"></span>
				</td>
			</tr>
			<tr>
				<td cols="2" align="center">
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="submitForm_email0917()">提交</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="clearGift_email0917()">清除</a>
				</td>
			</tr>
		</table>
	</form>
<div>

<textarea id="log_email0917"></textarea>

<br />
常用礼包:
<table width="960px">
	<tr>
		<td wdith="50%">
			<fieldset>
				<legend>外部用</legend>
				<?php 
					$datas = array();
    include_once "newweb/h_header.php";
    $DB_HOST=FT_MYSQL_COMMON_HOST;
    $DB_USER=FT_MYSQL_COMMON_ROOT;
    $DB_PASS=FT_MYSQL_COMMON_PASS;
    $DB_NAME=FT_MYSQL_BILL_DBNAME;
    $con = mysql_connect($DB_HOST,$DB_USER,$DB_PASS);
					mysql_select_db("admin", $con) or die("mysql select db error". mysql_error()); 
mysql_query('set names utf8');
					$sql = "SELECT * FROM `ft_common_use_gift` where state ='2'";
					$result = mysql_query($sql) or die("Invalid query: " . mysql_error());
					while ($row = mysql_fetch_assoc($result)) 
					{
						$datas[] = $row;
					}
					foreach ($datas as $key => $value) 
					{
						echo '<a id="gift_'.$key.'" href="javascript:void(0)" class="easyui-linkbutton" onclick="gift_'.$key.'()">'.$value['name'].'</a>';
					}

					echo "<script>";
					foreach ($datas as $key => $value) 
					{
						echo 'function gift_'.$key.'(){$("#email_objs1").val("'.$value['item1_id'].'");$("#email_nums1").val("'.$value['item1_num'].'");$("#email_binds1").val("'.$value['item1_bind'].'");'.'$("#email_objs2").val("'.$value['item2_id'].'");$("#email_nums2").val("'.$value['item2_num'].'");$("#email_binds2").val("'.$value['item2_bind'].'");'. '$("#email_objs3").val("'.$value['item3_id'].'");$("#email_nums3").val("'.$value['item3_num'].'");$("#email_binds3").val("'.$value['item3_bind'].'");}';
					}
					echo "</script>";
				?>
			</fieldset>
		</td>
		<td widht='50%'>
			<fieldset>
				<legend>内部用</legend>
				<?php 
					$result = null;
					$datas = array();
					$row = array();
					$sql = '';
					$sql = "SELECT * FROM `ft_common_use_gift` where state ='1'";
					$result = mysql_query($sql) or die("Invalid query: " . mysql_error());
					while ($row = mysql_fetch_assoc($result)) 
					{
						$datas[] = $row;
					}
					foreach ($datas as $key => $value) 
					{
						echo '<a id="gift2_'.$key.'" href="javascript:void(0)" class="easyui-linkbutton" onclick="gift2_'.$key.'()">'.$value['name'].'</a>';
					}

					echo "<script>";
					foreach ($datas as $key => $value) 
					{
						echo 'function gift2_'.$key.'(){$("#email_objs1").val("'.$value['item1_id'].'");$("#email_nums1").val("'.$value['item1_num'].'");$("#email_binds1").val("'.$value['item1_bind'].'");'.'$("#email_objs2").val("'.$value['item2_id'].'");$("#email_nums2").val("'.$value['item2_num'].'");$("#email_binds2").val("'.$value['item2_bind'].'");'. '$("#email_objs3").val("'.$value['item3_id'].'");$("#email_nums3").val("'.$value['item3_num'].'");$("#email_binds3").val("'.$value['item3_bind'].'");}';
					}			
					echo "</script>";
				?>
			</fieldset>
		</td>
	</tr>
</table>

<script type="text/javascript">
Date.prototype.formatDate = function (format)
{
	var o = {  
		"M+": this.getMonth() + 1,
		"d+": this.getDate(),
		"h+": this.getHours(),
		"m+": this.getMinutes(),
		"s+": this.getSeconds(),
		"q+": Math.floor((this.getMonth() + 3) / 3),
		"S": this.getMilliseconds()
	}
	
	if (/(y+)/.test(format)) format = format.replace(RegExp.$1,(this.getFullYear() + "").substr(4 - RegExp.$1.length));  
	
	for (var k in o) 
		if (new RegExp("(" + k + ")").test(format))
			format = format.replace(RegExp.$1,RegExp.$1.length == 1 ? o[k] : ("00" + o[k]).substr(("" + o[k]).length));  
		return format;
}

$(function()
{
	$.fn.datebox.defaults.formatter = function(date)
	{
		var y = date.getFullYear();
		var m = date.getMonth()+1;
		var d = date.getDate();
		return y+"-"+m+'-'+d +" "+ date.getHours()+":"+date.getMinutes()+":00";
	}

	function formatDateText(date) 
	{
		return date.formatDate("yyyy-MM-dd hh:mm:ss"); 
	}  
	
	function parseDate(dateStr) 
	{
		var regexDT = /(\d{4})-?(\d{2})?-?(\d{2})?\s?(\d{2})?:?(\d{2})?:?(\d{2})?/g;  
		var matchs = regexDT.exec(dateStr);  
		if(matchs==null)
			return new Date();
		var date = new Array();  
		for (var i = 1; i < matchs.length; i++) 
		{
			if (matchs[i]!=undefined) 
			{
				date[i] = matchs[i];  
			} else 
			{  
				if (i<=3) 
				{
					date[i] = '01';  
				} else 
				{  
					date[i] = '00';  
				}
			} 
		}  
		return new Date(date[1], date[2]-1, date[3], date[4], date[5],date[6]);  
	}

	$("#createtime,#lastactive,#createtime2,#lastactive2").datetimebox({
		showSeconds:false,  
		formatter: formatDateText,
		parser: parseDate
	});

	$('#giftform').form({
		url:'dogift.php',
		onSubmit:function()
		{
			return $(this).form('validate');
		},
		success:function(data)
		{
			alert(data);
		},
		error:function()
		{
			alert("ss");
		}
	});
});

function addlog_email_0917(msg)
{
	$("#log_email0917").val($("#log_email0917").val()+"\n"+msg);
}

submiting=false;
var mergeserver=[2,3,5,6,7,9,10,12,13,15];
var submitCnt = 0;

function submitForm_email0917()
{
  var isbind_arr = {1:"绑定",2:"非绑定"};

  var notice_str = $("#zone").val()+"服-"+$("#username").val()+"\r";

	if(confirm(notice_str))
  {
		submitCnt = 0;
		addlog_email_0917("开始发放 服务器:"+$("#zone").val()+",用户名:"+$("#username").val() );
		$('#giftform').form('submit',{
			success:function(data)
			{
				addlog_email_0917("发放 成功\n"+data);
			},
			error:function()
			{
				addlog_email_0917("发放 失败\n"+data);
			}
		});
  }
}

function clearNextGift_email0917()
{
	var zone = servers.shift();
	if(zone == undefined)
	{
		addlog_email_0917("清除完成!");
		return;
	}
	addlog_email_0917("开始清除:"+zone); 
	$.ajax("cleargift.php?subject="+$("#subject").val()+"&zone="+zone,{
		success:function(data)
		{
			addlog_email_0917("清除成功:"+data);
			clearNextGift_email0917();
		},
		error:function()
		{
			addlog_email_0917("清除失败:"+zone); 
			clearNextGift_email0917();
		}
	});
}

function clearGift_email0917()
{
	getServerList();
	if(servers.length == 0)
	{
		alert("没有服务器需要发放");
		return;
	}
	for(var i = 0;i<5;i++)			
		clearNextGift_email0917();
}

function loadInfo_email0917()
{
	$("#usernameinfo_email0917").html("正在获取用户信息...");
	$.getJSON("queryacc.php?username="+$("#username").val()+"&zone="+$("#zone").val(),function(result)
	{
		if(result.ACCNAME == undefined)
			$("#usernameinfo_email0917").html("没有此用户");
		else
			$("#usernameinfo_email0917").html("openid:"+result.ACCNAME+"<br/>level:"+result.LEVEL+"<br/>VIP:"+result.VIP);
	});
}
</script>

<script type="text/javascript">
immediately_email0719();

function immediately_email0719()
{ 
	var element = document.getElementById("content"); 
	if("\v"=="v") 
		element.onpropertychange = webChange; 
	else
		element.addEventListener("input",webChange,false);
	
	function webChange()
	{ 
		if(element.value)
		{
			var re=/\\r/gi;  
			document.getElementById("inputContent2").innerHTML = element.value.replace(re,"<br/>").length;
		}
	}
}
</script>
</body>
</html>
