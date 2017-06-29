<?php #session_start();if(strpos(@$_SESSION['priv'],'newweb/v_suggest')==0)exit;?>
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
                title : '玩家建议反馈',
                url   : 'c_suggest_sy.php?action=list',
                rownumbers   : true,    //显示行号
                singleSelect : true,    //只允许选择单条数据
                fitColumns   : false,    //True 自适应宽度 防止水平滚动条
                collapsible  : true,    //True 显示折叠按钮
                pagination   : true,    //True 显示行号
                method       : 'post',
                toolbar      : '#toolbar',
				pageSize     : 20,      //每页条数
                nowrap       : false,    //单元格多行
                queryParams: {
                },
                columns:[[
                    {field:'openid',    title:'玩家OPENID',width:300, halign:'center', align:'center', sortable:true},
                    {field:'ip',        title:'玩家IP',    width:150, halign:'center', align:'center'},
                    {field:'serverId',  title:'区ID',      width:100, halign:'center', align:'center'},
                    {field:'addDate',   title:'添加时间',  width:300, halign:'center', align:'center'},
                    {field:'content',   title:'建议内容',  width:300, halign:'center', align:'center'},
                ]]
            });
        }
    </script>
    </body>
</html>
