<?php 
include_once "checklogin.php";
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title></title>
<link rel="stylesheet" type="text/css" href="res/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="res/themes/icon.css">
<link rel="stylesheet" type="text/css" href="res/jquery.jqplot.min.css">
<script type="text/javascript" src="res/jquery.min.js"></script>
<script type="text/javascript" src="res/jquery.easyui.min.js"></script>
<script type="text/javascript" src="res/admin.js"></script>
</head>
<body >

<form action='' method='post' id='one_key_form'>
<table >
	<tr>
		<td>游戏服：</td>
		<td colspan='2'><input size='6' type='test' name='onek_zone1'></td>
	</tr>
	<tr>
		<td>开服日期：</td>
		<td>
			<input type='text' value='' id='kaifu_time' name='kaifu_time' size='10' class="easyui-datebox" data-options="formatter: formatDateText_onekey,parser: parseDate_onekey"/>
		</td>
	</tr>
	<tr>
		<td> <a href="javascript:void(0)" class='easyui-linkbutton' onclick='one_key_sub()'> 开启</a></td>
	</tr>
</table>
</form>
<br>

<script type="text/javascript">

	function one_key_sub()
	{
		if(confirm("注意：确定？取消？"))
	    {
	    	$.ajax(
	    	{
	        	type:'POST',
	        	url:'onekey_gameactivity_do.php',
	        	data:$('#one_key_form').serialize(),
	        	//data:{id:id,sendemail:sendemail},
	        	success:function(result)
	        	{
	        		alert(result);
	        	}
	    	});
	    }
	}

	function formatDateText_onekey(date) {  
		return date.formatDate("yyyy-MM-dd"); 
	}  
	function parseDate_onekey(dateStr) {  

		var regexDT = /(\d{4})-?(\d{2})?-?(\d{2})?\s?(\d{2})?:?(\d{2})?:?(\d{2})?/g;  
		var matchs = regexDT.exec(dateStr);  
		if(matchs==null)
			return new Date();
		var date = new Array();  
		for (var i = 1; i < matchs.length; i++) {  
			if (matchs[i]!=undefined) {  
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

