<?php session_start();if(strpos(@$_SESSION['priv'],'newweb/v_acc_income')==0)exit;?>
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
</head>
<body style="padding:0px;margin:0px">
    <table id="dg" style="width=100%" data-options=""></table>

    <div id="toolbar">
        <form id='s_form'>
            明细:<input type='checkbox' name='detailed' id='detailed'>
            区分所在区:<input type='checkbox' name='dis_zone' id='dis_zone'>
            帐号:<input id='acc' name='acc' class="easyui-textbox">
            合并前区:<input id='zones' name='zones' class="easyui-numberbox" size='10'>
            合并后区:<input id='zone_id' name='zone_id' class="easyui-numberbox" size='10'>
            查询日期:<input class="easyui-datebox" editable='fasle' size='10' name='ymd_start' id='ymd_start'> ~ <input class="easyui-datebox" editable='fasle' size='10' name='ymd_end' id='ymd_end'>
            途径:<select name='PF' id='PF'>
                    <option value='all' selected >all</option>
<?php 
define('WITHOUT_AUTH',1);
include "h_header.php";
include "m_trade.php";
$trade = new TradeInfo;
$allpfarr = $trade->get_all_pf();

foreach ($allpfarr as $k => $v)
{
    echo "<option value='".$v['PF']."'>".$v['PF']."</option>";
}
?>
                </select>

        <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="datagrid_load('c_acc_income.php?action=list')">查询</a>
<a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="putcsv('c_acc_income_sy.php?action=putcsv')">导出前500条</a> 
       </form>
    </div>

    <script type="text/javascript">
        datagrid_load('');
        function datagrid_load(url)
        {
            $('#dg').datagrid({
                title : '帐号收入',
                url : url,
                rownumbers : true,    //显示行号
                singleSelect: true,   //只允许选择单条数据
                fitColumns : true,    //设置为true,自动扩展或收缩列的大小以适应网格宽度和防止水平滚动条
                collapsible : true,   //当True时可显示折叠按钮。默认false。
                pagination : true,    //True 就会显示行号的列。 
                method : 'post',
                toolbar: '#toolbar',
                pageSize: 20,         //初始化页面大小
                queryParams: {PF:$('#PF').val(),zones:$('#zones').val(),zone_id:$('#zone_id').val(),detailed:$('#detailed').is(':checked'),dis_zone:$('#dis_zone').is(':checked'),acc:$('#acc').val(),ymd_start:$("input[name*='ymd_start']").val(),ymd_end:$("input[name*='ymd_end']").val()},
                columns:[[
                    {field:'ACC',title:'帐号',width:300,halign:'center',align:'center'},
                    {field:'NAME',title:'角色名',width:300,halign:'center',align:'center'},
                    {field:'zone_id',title:'合并后区',width:300,halign:'center',align:'center'},
                    {field:'zone',title:'合并前区',width:300,halign:'center',align:'center'},
                    {field:'SUM_AMOUNT',title:'RMB(元)',width:150,halign:'center',align:'center',sortable:true,formatter:function(rowData){return parseInt(rowData/100).toFixed(0);}},
                    {field:'SUM_YB',title:'钻石',width:300,halign:'center',align:'center',sortable:true,formatter:function(rowData){return parseInt(rowData/10).toFixed(0);}},
                    {field:'PF',title:'途径',width:300,halign:'center',align:'center'},
                    {field:'OBJID',title:'道具ID',width:300,halign:'center',align:'center'},
                    {field:'YMD',title:'时间',width:300,halign:'center',align:'center',sortable:true},
                    {field:'ltime',title:'最后登录时间',width:300,halign:'center',align:'center'},
                ]]
            });
        }
function putcsv(url)
{
	url += '&PF='+$('#PF').val()+'&zones='+$('#zones').val()+'&zone_id='+$('#zone_id').val()+'&detailed='+$('#detailed').is(':checked')+'&dis_zone='+$('#dis_zone').is(':checked')+'&acc='+$('#acc').val()+'&ymd_start='+$("input[name*='ymd_start']").val()+'&ymd_end='+$("input[name*='ymd_end']").val();
	window.open(url);
}
    </script>
    </body>
</html>
