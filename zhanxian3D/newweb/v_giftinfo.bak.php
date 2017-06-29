<?php session_start();if(strpos(@$_SESSION['priv'],'newweb/v_giftinfo')==0)exit;?>
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
            合并前区:<input id='zones'     name='zones'     class="easyui-numberbox" size='10'>
            角色名:<input id='NAME' name='NAME' class='easyui-textbox' size='10'>
            <a id='ht_id_search1'href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="datagrid_load('c_giftinfo.php?action=list')">查询</a>
        </form>
    </div>

<div>
描述：删除操作 (1、只允许删除是否已发放为否的物品 2、将根据 用户名+未发放+发送原因删除)3、<font color='red'>在打命令前执行删除操作才有效</font>
4、<font color='red'>删除将根据：'发送原因'+'区'+'未被收取'+'角色名'</font>
</div>

    <script type="text/javascript">
        datagrid_load();
        function datagrid_load(url)
        {
            $('#dg').datagrid({
                title : 'Gift发放查询',
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
                    zones:$('#zones').val(),
                    NAME:$('#NAME').val()
                },
                columns:[[
                    {field:'del',       title:'删除操作',   width:300, halign:'center', align:'center'},
                    {field:'NAME',      title:'玩家名',     width:300, halign:'center', align:'center'},
                    {field:'CHARID',    title:'CHARID',     width:150, halign:'center', align:'center'},
                    {field:'ITEMGOT',   title:'是否已收取', width:300, halign:'center', align:'center',formatter:function(val){return (val==0) ? "<font color='red'>否</font>" : "<font color='blue'>是</font>"}},
                    {field:'ITEMID1',   title:'道具1',      width:300, halign:'center', align:'center'},
                    {field:'ITEMNUM1',  title:'数量1',      width:300, halign:'center', align:'center'},
                    {field:'BIND1',     title:'是否绑定',   width:300, halign:'center', align:'center',formatter:function(val){return (val==0) ? '否' : '是'}},
                    {field:'ITEMID2',   title:'道具2',      width:300, halign:'center', align:'center'},
                    {field:'ITEMNUM2',  title:'数量2',      width:300, halign:'center', align:'center'},
                    {field:'BIND2',     title:'是否绑定',   width:300, halign:'center', align:'center',formatter:function(val){return (val==0) ? '否' : '是'}},
                    {field:'ITEMID3',   title:'道具3',      width:300, halign:'center', align:'center'},
                    {field:'ITEMNUM3',  title:'数量3',      width:300, halign:'center', align:'center'},
                    {field:'BIND3',     title:'是否绑定',   width:300, halign:'center', align:'center',formatter:function(val){return (val==0) ? '否' : '是'}},
                    {field:'MAILFROM',  title:'发送方',     width:300, halign:'center', align:'center'},
                    {field:'MAILTITLE', title:'发放原因',   width:300, halign:'center', align:'center'},
                ]]
            });
        }

        function hxiangou_iddel(f_NAME,f_ITEMGOT,f_MAILTITLE,zone)
        {
            if(confirm("注意：确定？取消？"))
            {
                $.ajax(
                {
                    type:'POST',
                    url:'c_giftinfo.php?action=del&zone='+zone,
                    data:{f_NAME:f_NAME,f_ITEMGOT:f_ITEMGOT,f_MAILTITLE:f_MAILTITLE},
                    success:function()
                    {
                        $("#ht_id_search1").click();
                    }
                });
                $("#ht_id_search1").click();
            }
        }


    </script>
    </body>
</html>
