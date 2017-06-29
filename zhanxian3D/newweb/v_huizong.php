<?php session_start();if(strpos(@$_SESSION['priv'],'newweb/v_huizong')==0)exit;?>
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
    <table id="dg" style="width:100%;" data-options=""></table>
        
    <div id="toolbar">
        <form id='s_form'>
            查询日期:<input class="easyui-datebox" editable='fasle' size='10' name='ymd_start' id='ymd_start'> ~ <input class="easyui-datebox" editable='fasle' size='10' name='ymd_end' id='ymd_end'>
            <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="datagrid_load('c_huizong.php?action=list')">查询</a>
            <a href="#" class="easyui-linkbutton" iconCls="" onclick="putcsv('c_huizong.php?action=putcsv')" style='float:right;'>导出</a>
       </form>
    </div>
    当前时间<?php echo date('Y-m-d H:i:s');?>
    <script type="text/javascript">
        datagrid_load()
        function datagrid_load(url)
        {
            $('#dg').datagrid({
                title : '焚天手游-数据汇总',
                url : url,
                rownumbers : false,    //显示行号
                // ctrlSelect: true,     //ctrl+左键 选多行
                singleSelect: true,   //只允许选择单条数据
                fitColumns : true,    //设置为true,自动扩展或收缩列的大小以适应网格宽度和防止水平滚动条
                collapsible : true,   //当True时可显示折叠按钮。默认false。
                pagination : false,    //分页栏位
                method : 'post',
                toolbar: '#toolbar',
                pageSize: 20,         //初始化页面大小
                queryParams: {ymd_start:$("input[name*='ymd_start']").val(),ymd_end:$("input[name*='ymd_end']").val()},
                columns:[[
                    {field:'ymd',title:'日期',width:100,halign:'center',align:'center'},
                    {field:'all_pay_money',title:'收入[总]',width:80,halign:'center',align:'center'},
                    {field:'all_pay_user',title:'付费人数[总]',width:80,halign:'center',align:'center'},
                    //{field:'all_pay_arpu',title:'ARPU[总]',width:80,halign:'center',align:'center'},
                    {field:'all_pay_arppu',title:'ARPPU[总]',width:80,halign:'center',align:'center'},
                    
                    {field:'an_yyb_pay_money',title:'收入[应用宝]',width:80,halign:'center',align:'center'},
                    {field:'an_yyb_pay_user',title:'付费人数[应用宝]',width:80,halign:'center',align:'center'},
                    {field:'an_yyb_pay_arppu',title:'ARPPU[应用宝]',width:80,halign:'center',align:'center'},

                    {field:'an_ly_pay_money',title:'收入[联运]',width:80,halign:'center',align:'center'},
                    {field:'an_ly_pay_user',title:'付费人数[联运]',width:80,halign:'center',align:'center'},
                    {field:'an_ly_pay_arppu',title:'ARPPU[联运]',width:80,halign:'center',align:'center'},

                    {field:'ios_zb_pay_money',title:'收入[IOS]',width:80,halign:'center',align:'center'},
                    {field:'ios_zb_pay_user',title:'付费人数[IOS]',width:80,halign:'center',align:'center'},
                    {field:'ios_zb_pay_arppu',title:'ARPPU[IOS]',width:80,halign:'center',align:'center'},

                    {field:'all_zhuce',title:'注册[总]',width:80,halign:'center',align:'center'},
                    {field:'an_yyb_zhuce',title:'注册[应用宝]',width:80,halign:'center',align:'center'},
                    {field:'an_ly_zhuce',title:'注册[联运]',width:80,halign:'center',align:'center'},
                    {field:'ios_zb_zhuce',title:'注册[IOS]',width:80,halign:'center',align:'center'},

                ]]
            });
        }

	function putcsv(url)
	{
    	    window.open(url+'&ymd_start='+$("input[name*='ymd_start']").val()+'&ymd_end='+$("input[name*='ymd_end']").val());
	}
    </script>
</body>
</html>
