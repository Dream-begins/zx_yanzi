<?php session_start();if(strpos(@$_SESSION['priv'],'newweb/v_showerr')==0)exit;?>
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

    <script type="text/javascript">
        datagrid_load();
        function datagrid_load()
        {
            $('#dg').datagrid({
                title : 'show_err',
                url   : 'c_showerr.php?action=list',
                rownumbers   : true,    //显示行号
                singleSelect : true,    //只允许选择单条数据
                fitColumns   : true,    //True 自适应宽度 防止水平滚动条
                collapsible  : true,    //True 显示折叠按钮
                pagination   : true,    //True 显示行号
                method       : 'post',
                toolbar      : '#toolbar',
                nowrap       : false,
                pageSize     : 20,      //每页条数
                queryParams: {
                },
                columns:[[
                    {field:'id',            title:'id',             width:300, halign:'center', align:'center', sortable:true},
                    {field:'userid',        title:'userid',         width:300, halign:'center', align:'center', sortable:true},
                    {field:'tp',            title:'tp',             width:150, halign:'center', align:'center'},
                    {field:'ip',            title:'ip',             width:300, halign:'center', align:'center'},
                    {field:'adddate',       title:'adddate',        width:300, halign:'center', align:'center'},
                    {field:'info',          title:'info',           width:300, halign:'center', align:'center'},
                    {field:'serverid',      title:'serverid',       width:300, halign:'center', align:'center'},
                    {field:'browser',       title:'browser',        width:300, halign:'center', align:'center'},
                    {field:'flashversion',  title:'flashversion',   width:300, halign:'center', align:'center'},
                ]]
            });
        }
    </script>
    </body>
</html>