<?php include_once "checklogin.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="newweb/jquery-easyui-1.4/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="newweb/jquery-easyui-1.4/themes/icon.css">
    <script type="text/javascript" src="newweb/jquery-easyui-1.4/jquery.min.js"></script>
    <script type="text/javascript" src="newweb/jquery-easyui-1.4/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="newweb/jquery-easyui-1.4/locale/easyui-lang-zh_CN.js"></script>

</head>
<body style="padding:0px;margin:0px">

    <table id="dg" style="width:100%;" data-options=""></table>
    
    <div id="toolbar">
        <form id='s_form'>
        appVer:<input id='appVer' name='appVer' class="easyui-textbox" size='10'>
        msg过滤:<input id='msg' name='msg' class="easyui-textbox" placeholder="过滤输入的内容" size='10'>
        查询日期:<input id='ymd_start' type="text" class="easyui-datebox" editable='fasle' size='10' name='ymd_start' >
        显示详情:<input type='checkbox' id='nowrap' name='nowrap'>
        <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="datagrid_load()">查询</a>
<a style='float:right;' href="javascript:void(0)" onclick='putcsv()' class="easyui-linkbutton" iconCls="">导出全部</a>
        </form>
    </div>

    <script type="text/javascript">
        $('#ymd_start').datebox({
            required:false
        });
        datagrid_load();
        function datagrid_load()
        {

            var nowraped = !$("#nowrap").is(":checked");
            $('#dg').datagrid({
                title : 'inside_error_log',
                url : 'show_inside_error_do2.php?action=zone_list',
                rownumbers : true,    //显示行号
                // ctrlSelect: true,     //ctrl+左键 选多行
                singleSelect: true,   //只允许选择单条数据
                fitColumns : true,    //设置为true,自动扩展或收缩列的大小以适应网格宽度和防止水平滚动条
                collapsible : true,   //当True时可显示折叠按钮。默认false。
                pagination : true,    //True 就会显示行号的列。 
                method : 'post',
                toolbar: '#toolbar',
                pageSize: 10,         //初始化页面大小
                queryParams: {ymd_start:$("#ymd_start").datebox('getValue') ,appVer:$('#appVer').val(),msg:$('#msg').val()},
                nowrap: nowraped,
                columns:[[
                    // {field:'checkbox',checkbox:true},
                    {field:'id',title:'id',width:50,halign:'center',align:'center',sortable:true},
                    {field:'msg',title:'msg',width:800,halign:'center',align:'center',sortable:true},
                    {field:'tp',title:'tp',width:50,halign:'center',align:'center',sortable:true},
                    {field:'appVer',title:'appVer',width:50,halign:'center',align:'center',sortable:true},
                    {field:'device',title:'device',width:50,halign:'center',align:'center',sortable:true},
                    {field:'userid',title:'userid',width:50,halign:'center',align:'center',sortable:true},
                    {field:'mtime',title:'mtime',width:150,halign:'center',align:'center',sortable:true},
                    {field:'ip',title:'ip',width:150,halign:'center',align:'center',sortable:true},
                ]]
            });
        }
        function putcsv(){
            var ymd = $('#ymd_start').datebox('getValue');
            var url = "./inside_error_excel.php?putcsv=putcsv&ymd="+ymd;
            window.open(url);
       }
    </script>

    <style type="text/css">
        #fm{
            margin:0;
            padding:10px 30px;
        }
        .ftitle{
            font-size:14px;
            font-weight:bold;
            padding:5px 0;
            margin-bottom:10px;
            border-bottom:1px solid #ccc;
        }
        .fitem{
            margin-bottom:5px;
        }
        .fitem label{
            display:inline-block;
            width:80px;
        }
        .fitem input{
            width:160px;
        }
    </style>
    </body>
</html>
