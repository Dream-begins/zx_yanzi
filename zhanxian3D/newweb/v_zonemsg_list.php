<?php session_start();if(strpos(@$_SESSION['priv'],'newweb/v_zone_msg')==0)exit;?>
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
            合并前服:<input id='zone'   name='zone'   class="easyui-numberbox" size='10'>
            合并后服:<input id='zone_id' name='zone_id' class='easyui-numberbox' size='10'>
            <select id="qudao" name="qudao">
                <option value="0">请选择</option>
                <option value="1-1000">应用宝</option>
                <option value="1601-1800">硬核</option>
                <option value="1801-1900">360专服</option>
                <option value="1001-1600">联运</option>
                <option value="2001-3000">IOS</option>
            </select>
            <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="datagrid_load('c_zonemsg_list.php?action=list')">查询</a>
        </form>
    </div>

    <script type="text/javascript">
        datagrid_load();
        function datagrid_load(url)
        {
            $('#dg').datagrid({
                title : '',
                url   : url,
                rownumbers   : true,    //显示行号
                singleSelect : true,    //只允许选择单条数据
                fitColumns   : true,    //True 自适应宽度 防止水平滚动条
                collapsible  : true,    //True 显示折叠按钮
                pagination   : true,    //True 显示行号
                method       : 'post',
                toolbar      : '#toolbar',
                pageSize     : 20,      //每页条数
                queryParams: {
                    zone:$('#zone').val(),
                    zone_id:$('#zone_id').val(),
                    qudao:$('#qudao').val(),
                },
                columns:[[
                    {field:'zone_id',     title:'合并后服', width:300, halign:'center', align:'center', sortable:true},
                    {field:'domians',     title:'合并前服', width:300, halign:'center', align:'center'},
                    {field:'zone_name', title:'区名', width:300, halign:'center', align:'center'},
                    {field:'mysql_ip',    title:'IP',       width:300, halign:'center', align:'center'},
                    {field:'mysql_port',  title:'PORT',     width:300, halign:'center', align:'center'},
                    {field:'mysql_dbName',title:'DBNAME',   width:300, halign:'center', align:'center'},
                ]]
            });
        }
    </script>
    </body>
</html>
