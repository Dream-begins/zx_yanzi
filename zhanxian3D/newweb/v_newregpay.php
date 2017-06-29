<?php session_start();if(strpos(@$_SESSION['priv'],'newweb/v_newregpay')==0)exit;?>
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
            注册日期:<input class="easyui-datebox" editable='fasle' size='10' name='ymd_start' id='ymd_start'>
            <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="datagrid_load('c_newregpay.php?action=list')">查询</a>
            <a href="#" onclick="putcsv('c_newregpay.php?action=putcsv')" class="easyui-linkbutton" iconCls="">导出</a>
        </form>
    </div>

   <script type="text/javascript">
        datagrid_load();
        function datagrid_load(url)
        {
            $('#dg').datagrid({
                title : '新注册付费',
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
                    ymd_start:$("input[name*='ymd_start']").val(),
                },
                columns:[[
                    {field:'dt',         title:'天数',     width:300, halign:'center', align:'center'},
                    {field:'ammount',    title:'总数',     width:300, halign:'center', align:'center'},
                    {field:'arpu',       title:'arpu',     width:150, halign:'center', align:'center',formatter:function(val,data){ return (data["ammount"]/data["cnt"]).toFixed(2);} },
                    {field:'cnt',        title:'付费人数', width:300, halign:'center', align:'center'},
                    {field:'qqgame',     title:'大厅付费', width:300, halign:'center', align:'center'},
                    {field:'qqgamecnt',  title:'大厅人数', width:300, halign:'center', align:'center'},
                    {field:'qqgamearpu', title:'大厅arpu', width:300, halign:'center', align:'center',formatter:function(val,data){ return (data["qqgame"]/data["qqgamecnt"]).toFixed(2);} },
                ]]
            });
        }

        function putcsv(url)
        {
            var ymd = $("input[name*='ymd_start']").val();
            if(!ymd) return false;

            url = url+'&ymd_start='+ymd;
            window.location.href=url;
        }

    </script>
    </body>
</html>