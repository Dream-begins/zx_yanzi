<?php #session_start();if(strpos(@$_SESSION['priv'],'newweb/v_showerr')==0)exit;?>
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
            服务器:<input id='zone'     name='zone'     class="easyui-numberbox" size='10'>
			工作人员UID:<input id='acc'   name='acc'size='10'>
            玩家名称:<input id='usrname'   name='usrname' size='10'>
            <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="datagrid_load()">查询</a>
        </form>
    </div>

    <script type="text/javascript">
        datagrid_load();
        function datagrid_load()
        {
            $('#dg').datagrid({
                title : 'show_ban',
                url   : 'c_showban.php?action=list',
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
					zone:$('#zone').val(),
					acc:$('#acc').val(),
					usrname:$('#usrname').val(),
				},
                columns:[[
                    {field:'username',title:'用户名',   width:150, halign:'center', align:'center', sortable:true},
                    {field:'serverId',title:'服务器',   width:100, halign:'center', align:'center'},
                    {field:'dt',      title:'时间',     width:150, halign:'center', align:'center'},
                    {field:'tp',      title:'操作',     width:100, halign:'center', align:'center'},
                    {field:'opid',    title:'操作id',   width:250, halign:'center', align:'center'},
                    {field:'opip',    title:'操作IP',   width:100, halign:'center', align:'center'},
                    {field:'talk',    title:'对话',     width:300, halign:'center', align:'center'},
                    {field:'keyword', title:'关键词',   width:50, halign:'center', align:'center'},
                    {field:'isban',   title:'是否禁言', width:50, halign:'center', align:'center'},
                ]]
            });
        }
    </script>
    </body>
</html>
