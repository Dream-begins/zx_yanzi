<?php include_once "checklogin.php";?>
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

<div id="shoplimit">
	<div>
		<table>
			<tr>
				<td>游戏服:</td>
				<td><input type="text" class="easyui-validatebox" required="true" name="serverid" id="serverid" /></td>
				<td><input type="button" value="物品查询" onclick="queryShopItem('cur')"/></td>
				<td><input type="button" value="上一服" onclick="queryShopItem('pre')"/></td>
				<td><input type="button" value="下一服" onclick="queryShopItem('next')"/></td>
			</tr>	
		</table>
	</div>				
	
	<div>物品列表:</div>		
	
	<div id="dataTable" styles="width:80%" >
	</div>
</div>
<br />

<div>
	<form action="doshoplimit.php" method="post" id="shoplimitform">
		<fieldset>
			<legend>增加物品</legend>
			<table margin:5px 20px 5px 5px;>
				<tr>
					<td>游戏服:</td>
					<td><input required="true" style="width:150px" id="sp_zone1" name="sp_zone1"></td>
					<td>----</td>
					<td><input required="true" style="width:150px" id="sp_zone2" name="sp_zone2"><font color
='red'>第一行必填</font></td>
				</tr>
	                         <tr>
					<td>游戏服:</td>
					<td><input required="true" style="width:150px" id="sp_zone3" name="sp_zone3"></td>
					<td>----</td>
					<td><input required="true" style="width:150px" id="sp_zone4" name="sp_zone4"></td>
				</tr>
				<tr>
					<td>游戏服:</td>
					<td><input required="true" style="width:150px" id="sp_zone5" name="sp_zone5"></td>
					<td>----</td>
					<td><input required="true" style="width:150px" id="sp_zone6" name="sp_zone6"></td>
				</tr>
				<td>游戏服:</td>
				<td><input required="true" style="width:150px" id="sp_zone7" name="sp_zone7"></td>
				<td>----</td>
				<td><input required="true" style="width:150px" id="sp_zone8" name="sp_zone8"></td>
				</tr>
				<tr>
					<td>游戏服:</td>
					<td><input required="true" style="width:150px" id="sp_zone9" name="sp_zone9"></td>
					<td>----</td>
					<td><input required="true" style="width:150px" id="sp_zone10" name="sp_zone10"></td>
				</tr>
				<tr>
					<td>ID:</td>
					<td><input type="text" class="easyui-validatebox" name="dbid" id="dbid" readonly="readonly"/></td>
					<td>日期ID:</td>
					<td><input type="text" class="easyui-validatebox" name="indexid" id="indexid"/></td>
					<td><input type="hidden" style="width:1px" name="isadd" id="isadd"></td>
				</tr>
				<tr>
					<td>店铺分页:</td>
					<td><input type="text" class="easyui-validatebox" required="true" name="page" id="page" /></td>
					<td>店铺位置:</td>
					<td><input required="true" style="width:150px" name="supermarketpos" id="supermarketpos"></td>
				</tr>	
				<tr>
					<td>物品ID:</td>
					<td><input required="true" style="width:150px" id="objid" name="objid"></td>
					<td>物品名称:</td>
					<td><input type="text" class="easyui-validatebox" style="width:150px" id="objname" name="objname"></td>
					<td>是否绑定:</td>
					<td><input required="true" type="text" style="width:150px" name="isbind" id="isbind"></td>
				</tr>
				<tr>
					<td>货币类型:</td>
					<td><input required="true" style="width:150px" name="moneytype" id="moneytype"></td>
					<td>开放类型:</td>
					<td><input required="true" style="width:150px" name="opentype" id="opentype"></td>
				</tr>
				<tr>
					<td>原价:</td>
					<td><input required="true" style="width:150px" name="originalprice" id="originalprice"></td>
					<td>现价:</td><td><input required="true" style="width:150px" name="discontprice" id="discontprice"></td>
				</tr>		
				<tr>
					<td>单人限购数量:</td>
					<td><input required="true" style="width:150px" name="singlecanbuynum" id="singlecanbuynum"></td>
					<td>总限购数量:</td>
					<td><input required="true" style="width:150px" name="totalbuylimitnum" id="totalbuylimitnum"></td>
				</tr>
				<tr>
					<td>开放时间:</td>
					<td><input class="easyui-timespinner" data-options="min:'00:00:00',showSeconds:true" required="true" style="width:150px" name="openttime" id="openttime"></td>
					<td>结束时间:</td>
					<td><input class="easyui-timespinner" data-options="min:'23:59:59',showSeconds:true" required="true" style="width:150px" name="closetime" id="closetime"></td>
				</tr>
				<tr>
					<td>限购开始日期:</td>
					<td><input class="easyui-datebox" data-options="formatter:dateboxformatter,parser:databoxparser" required="true" style="width:150px" name="limitedpurchasestarttime" id="limitedpurchasestarttime"></td>
					<td>限购结束日期:</td>
					<td><input class="easyui-datebox" data-options="formatter:dateboxformatter,parser:databoxparser" required="true" style="width:150px" name="limitedpurchaseendtime" id="limitedpurchaseendtime"></td>
				</tr>	
				<tr>
					<td>标签类型:</td>
					<td><input required="true" style="width:150px" name="tagtype" id="tagtype"></td>
					<td>等级限制:</td>
					<td><input required="true" style="width:150px" name="needviplevel" id="needviplevel"></td>
				</tr>
				<tr>
					<td>标签ICON:</td>
					<td><input required="true" style="width:150px" name="tabIcon" id="tabIcon"></td>
					<td>标签标题:</td>
					<td><input required="true" style="width:150px" name="tabTitle" id="tabTitle"></td>
					<td>权重:</td>
					<td><input required="true" style="width:150px" name="weight" id="weight"></td>
				</tr>
				<tr>
					<td cols="2" align="center">
						<a href="javascript:void(0)" class="easyui-linkbutton" onclick="submitForm_09174('add')">新增物品</a> 
						<a href="javascript:void(0)" class="easyui-linkbutton" onclick="submitForm_09174('update')">更新物品</a> 
						<a href="javascript:void(0)" class="easyui-linkbutton" onclick="submitForm_09174('delete')">删除物品</a>
                                                <a href="javascript:void(0)" class="easyui-linkbutton" onclick="submitForm_09174('clear')">删除全服物品</a>
                                                 <a href="javascript:void(0)" class="easyui-linkbutton" onclick="submitForm_09174('clearsomeone')">删除指定物品</a>
					</td>
				</tr>
			</table>
		</fieldset>
	</form>
</div>

<!-- <a href="javascript:void(0)" class="easyui-linkbutton" onclick="clear_all_input1()">清空表格</a>  -->
<br>
<br>
<div>
<?php
include_once "newweb/h_header.php";

$DB_HOST=FT_MYSQL_COMMON_HOST; 
$DB_USER=FT_MYSQL_COMMON_ROOT;
$DB_PASS=FT_MYSQL_COMMON_PASS;
$DB_NAME=FT_MYSQL_BILL_DBNAME;

$con = mysql_connect($DB_HOST,$DB_USER,$DB_PASS);

if(!$con) die("mysql connect error");

mysql_query("set names 'utf8'");
	mysql_select_db("admin", $con) or die("mysql select db error". mysql_error()); 
	$sql = "SELECT * FROM `common_shoplimit_list`";
	$result = mysql_query($sql) or die("Invalid query: " . mysql_error());
	$datas = array();
	while ($row = mysql_fetch_assoc($result)) 
	{
		$datas[] = $row;
	}

	$list_arr = array();
	foreach ($datas as $key => $value) 
	{
		$list_arr[$value['b_class']][] = $datas[$key];
	}

	$flag_div = 1;
	foreach ($list_arr as $key => $value) 
	{
		echo "<span id='flag_id_{$flag_div}' onclick='flag_zk({$flag_div})' class='datagrid-row-expander datagrid-row-expand' style='display:inline-block;width:16px;height:16px;cursor:pointer'>&nbsp;</span>";
		echo $key.':';
		echo "<div id='flag_{$flag_div}' style='display:none'>";
		foreach ($value as $k => $v) 
		{
			echo '<a id="cshoplimit_'.$v['id'].'" href="javascript:void(0)" class="easyui-linkbutton" onclick="cshoplimit_'.$v['id'].'()">'.$v['item_name'].'</a>';
		}
		echo "</div>";
		echo "<br/>";
		$flag_div ++;
	}
	
	echo "<script>";
	foreach ($datas as $key => $value) 
	{
		echo 'function cshoplimit_'.$value['id'].'(){clear_all_input1();$("#indexid").val("'.$value['time_id'].'");$("#page").val("'.$value['shop_page'].'");$("#objid").val("'.$value['item_id'].'");$("#objname").val("'.$value['item_name'].'");$("#supermarketpos").val("'.$value['shop_position'].'");$("#moneytype").val("'.$value['money_type'].'");$("#originalprice").val("'.$value['old_money'].'");$("#discontprice").val("'.$value['now_money'].'");$("#isbind").val("'.$value['bind'].'");$("#singlecanbuynum").val("'.$value['onep_limit'].'");$("#totalbuylimitnum").val("'.$value['all_limit'].'");$("#opentype").val("'.$value['open_type'].'");$("#openttime").val("'.$value['ds_time'].'");$("#closetime").val("'.$value['de_time'].'");$("#tagtype").val("'.$value['icon_type'].'");$("#needviplevel").val("'.$value['lv_limit'].'");$("#tabIcon").val("'.$value['icon_img'].'");$("#tabTitle").val("'.$value['icon_title'].'");$("#weight").val("'.$value['weight'].'");}';
	}
	echo "</script>";
?>
</div>

<script type="text/javascript">
function clear_all_input1()
{
var ht_z1 = $("#sp_zone1").val();
	var ht_z2 = $("#sp_zone2").val();
	var ht_t1 = $("#limitedpurchasestarttime").datebox("getValue");
	var ht_t2 = $("#limitedpurchaseendtime").datebox("getValue");

	var ht_z3 = $("#sp_zone3").val();
	var ht_z4 = $("#sp_zone4").val();
	var ht_t3 = $("#limitedpurchasestarttime").datebox("getValue");
	var ht_t4 = $("#limitedpurchaseendtime").datebox("getValue");

	var ht_z5 = $("#sp_zone5").val();
	var ht_z6 = $("#sp_zone6").val();
	var ht_t5 = $("#limitedpurchasestarttime").datebox("getValue");
	var ht_t6 = $("#limitedpurchaseendtime").datebox("getValue");

	var ht_z7 = $("#sp_zone7").val();
	var ht_z8 = $("#sp_zone8").val();
	var ht_t7 = $("#limitedpurchasestarttime").datebox("getValue");
	var ht_t8 = $("#limitedpurchaseendtime").datebox("getValue");

	var ht_z9 = $("#sp_zone9").val();
	var ht_z10 = $("#sp_zone10").val();
	var ht_t9 = $("#limitedpurchasestarttime").datebox("getValue");
	var ht_t10 = $("#limitedpurchaseendtime").datebox("getValue");
	$(':input','#shoplimitform')
		.not(':button, :submit, :reset, :hidden')
		.val('')  
		.removeAttr('checked')  
		.removeAttr('selected');

	$("#sp_zone1").val(ht_z1);
	$("#sp_zone2").val(ht_z2);
	$("#shoplimitform #limitedpurchasestarttime").datebox("setValue",ht_t1);
	$("#shoplimitform #limitedpurchaseendtime").datebox("setValue",ht_t2);

	$("#sp_zone3").val(ht_z3);
	$("#sp_zone4").val(ht_z4);
	$("#shoplimitform #limitedpurchasestarttime").datebox("setValue",ht_t3);
	$("#shoplimitform #limitedpurchaseendtime").datebox("setValue",ht_t4);

	$("#sp_zone5").val(ht_z5);
	$("#sp_zone6").val(ht_z6);
	$("#shoplimitform #limitedpurchasestarttime").datebox("setValue",ht_t5);
	$("#shoplimitform #limitedpurchaseendtime").datebox("setValue",ht_t6);

	$("#sp_zone7").val(ht_z7);
	$("#sp_zone8").val(ht_z8);
	$("#shoplimitform #limitedpurchasestarttime").datebox("setValue",ht_t7);
	$("#shoplimitform #limitedpurchaseendtime").datebox("setValue",ht_t8);

	$("#sp_zone9").val(ht_z9);
	$("#sp_zone10").val(ht_z10);
	$("#shoplimitform #limitedpurchasestarttime").datebox("setValue",ht_t9);
	$("#shoplimitform #limitedpurchaseendtime").datebox("setValue",ht_t10);

}
function queryShopItem(whichzone)
{
	var qzone=$("#shoplimit #serverid").val();
	if (whichzone == 'next')
	{
		qzone=Number(qzone)+1;
		$("#shoplimit #serverid").val(qzone);
	}
	else if(whichzone == 'pre' && Number(qzone) > 1)
	{
		qzone=Number(qzone)-1;
		$("#shoplimit #serverid").val(qzone);
  }

	$('#shoplimit #dataTable').datagrid({
		url:'queryshoplimit.php',
		queryParams:{zone:qzone},
		remoteSort: false,
		pagination:true,
		rownumbers:true,
		singleSelect:true,
		toolbar:"#shopItem",
		onSelect:function onDataGridSelect(rowIndex, rowData)
		{
			$("#shoplimitform #sp_zone1").val($("#shoplimit #serverid").val());
			$("#shoplimitform #sp_zone2").val($("#shoplimit #serverid").val());
			$("#shoplimitform #dbid").val(rowData["ID"]);
			$("#shoplimitform #indexid").val(rowData["INDEXID"]);
			$("#shoplimitform #page").val(rowData["PAGE"]);
			$("#shoplimitform #objid").val(rowData["OBJID"]);
			$("#shoplimitform #objname").val(rowData["OBJNAME"]);
			$("#shoplimitform #supermarketpos").val(rowData["SUPERMARKETPOS"]);
			$("#shoplimitform #moneytype").val(rowData["MONEYTYPE"]);
			$("#shoplimitform #originalprice").val(rowData["ORIGINALPRICE"]);
			$("#shoplimitform #discontprice").val(rowData["DISCONTPRICE"]);
			$("#shoplimitform #isbind").val(rowData["ISBIND"]);
			$("#shoplimitform #singlecanbuynum").val(rowData["SINGLECANBUYNUM"]);
			$("#shoplimitform #totalbuylimitnum").val(rowData["TOTALBUYLIMITNUM"]);
			$("#shoplimitform #opentype").val(rowData["OPENTYPE"]);
			$("#shoplimitform #openttime").timespinner('setValue',rowData["OPENTTIME"]);
			$("#shoplimitform #closetime").timespinner('setValue',rowData["CLOSETIME"]);
			$("#shoplimitform #limitedpurchasestarttime").datebox("setValue",rowData["LIMITEDPURCHASESTARTTIME"]);
			$("#shoplimitform #limitedpurchaseendtime").datebox("setValue",rowData["LIMITEDPURCHASEENDTIME"]);
			$("#shoplimitform #tagtype").val(rowData["TAGTYPE"]);
			$("#shoplimitform #needviplevel").val(rowData["NEEDVIPLEVEL"]);	
			$("#shoplimitform #tabIcon").val(rowData["ICON"]); 
			$("#shoplimitform #tabTitle").val(rowData["TITLE"]); 
			$("#shoplimitform #weight").val(rowData["WEIGHT"]); 
		},
		columns:[[
			{field:'ID',title:'ID',width:30,sortable:true},
			{field:'INDEXID',title:'日期ID',width:60,sortable:true},
			{field:'PAGE',title:'店铺分页',width:60,sortable:true},
			{field:'OBJID',title:'物品ID',width:80,sortable:true},
			{field:'OBJNAME',title:'物品名称',width:120,sortable:true},
			{field:'SUPERMARKETPOS',title:'店铺位置',width:60,sortable:true}, 
			{field:'MONEYTYPE',title:'货币类型',width:60,sortable:true},		    
			{field:'ORIGINALPRICE',title:'原价',width:60,sortable:true},
			{field:'DISCONTPRICE',title:'现价',width:60,sortable:true},
			{field:'ISBIND',title:'是否绑定',width:60,sortable:true},
			{field:'SINGLECANBUYNUM',title:'单人限购数量',width:60,sortable:true},
			{field:'TOTALBUYLIMITNUM',title:'总限购数量',width:60,sortable:true},
			{field:'OPENTYPE',title:'开放类型',width:60,sortable:true},
			{field:'OPENTTIME',title:'开放时间',width:100,sortable:true},
			{field:'CLOSETIME',title:'结束时间',width:100,sortable:true},
			{field:'LIMITEDPURCHASESTARTTIME',title:'限购开始日期',width:100,sortable:true},
			{field:'LIMITEDPURCHASEENDTIME',title:'限购结束日期',width:100,sortable:true},
			{field:'TAGTYPE',title:'标签类型',width:60,sortable:true},
			{field:'NEEDVIPLEVEL',title:'等级限制',width:60,sortable:true},
			{field:'ICON',title:'标签图片',width:60,sortable:true},
			{field:'TITLE',title:'标签标题',width:60,sortable:true},
			{field:'WEIGHT',title:'权重',width:60,sortable:true},
		]]
	});
	$.parser.parse($(".tab_search"));
}
</script>	

<script type="text/javascript">
function dateboxformatter(date)
{
	var y = date.getFullYear();
	var m = date.getMonth()+1;
	var d = date.getDate();
	return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
}

function databoxparser(s)
{
	if (!s) return new Date();
	var ss = (s.split('-'));
	var y = parseInt(ss[0],10);
	var m = parseInt(ss[1],10);
	var d = parseInt(ss[2],10);
	if (!isNaN(y) && !isNaN(m) && !isNaN(d))
	{
		return new Date(y,m-1,d);
	} else 
	{
		return new Date();
	}
}

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

	$('#shoplimitform').form({
		url:'doshoplimit.php',
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

	$('#shoplimitform #openttime').timespinner('setValue', '00:00:00');
	$('#shoplimitform #closetime').timespinner('setValue', '23:59:59');
	var curr_time = new Date();
	var strDate = curr_time.getFullYear()+"-";
	strDate += curr_time.getMonth()+1+"-";
	strDate += curr_time.getDate();
	$("#shoplimitform #limitedpurchasestarttime").datebox("setValue", strDate); 
	$("#shoplimitform #limitedpurchaseendtime").datebox("setValue", strDate);
});

function submitForm_09174(act)
{
	submiting=false;
	if(act == 'add')
	{
		$('#shoplimitform #isadd').val(1);
	}
	else if(act =='update')
	{
		$('#shoplimitform #isadd').val(2);
	}
	else if(act == 'delete')
	{
		$('#shoplimitform #isadd').val(3);
	}
	else if(act == 'clear')
	{
                var i=window.confirm("确认删除\n"+$("#shoplimitform #sp_zone1").val()+"服到"+ $("#shoplimitform #sp_zone2").val()+"服，物品编号"+$("#shoplimitform #objid").val()+"。物品名称："+$("#shoplimitform #objname").val());
		if(i==0)
                   return;
		$('#shoplimitform #isadd').val(4);
	}
        else if(act == 'clearsomeone')
	{
		$('#shoplimitform #isadd').val(5);
	}
	else
	{
		alert("未知操作");
		return;
	}
	
	if(submiting)
		return;
	submiting = true;
	$('#shoplimitform').form('submit',{
		success:function(data)
		{
			alert(data);
			queryShopItem('cur');
			submiting = false;
		},
		error:function()
		{
			alert("ss");
			submiting = false;
		}
	});
}

function flag_zk(flag)
{
	if($("#flag_id_"+flag).attr("class") == "datagrid-row-expander datagrid-row-expand")
	{
		$("#flag_id_"+flag).removeClass().addClass("datagrid-row-expander datagrid-row-collapse");
		$("#flag_"+flag).css("display","block");
	}else{
		$("#flag_id_"+flag).removeClass().addClass("datagrid-row-expander datagrid-row-expand");
		$("#flag_"+flag).css("display","none");
	}
}

</script>
</body>
</html>
