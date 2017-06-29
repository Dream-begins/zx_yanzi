<?php session_start();if(strpos(@$_SESSION['priv'],'newweb/v_mailinfo')==0)exit;?>
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
            合并前区:<input id='zones' name='zones' class="easyui-numberbox" size='10'>
            角色名:<input id='NAME'  name='NAME' class='easyui-textbox'  size='10'>
            发放日期:<input type='text' class="easyui-datebox" id="CREATETIME" name="CREATETIME"/>
            <a id='ht_id_search1'href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="datagrid_load('c_mailinfo.php?action=list')">查询</a>
        </form>
    </div>

    <script type="text/javascript">
       datagrid_load();
        function datagrid_load(url)
        {
            
              $('#dg').datagrid({
                title : 'Email发放查询',
                url   : url,
                rownumbers   : true,    //显示行号
                singleSelect : true,    //只允许选择单条数据
                //fitColumns   : true,    //True 自适应宽度 防止水平滚动条
                collapsible  : true,    //True 显示折叠按钮
                pagination   : true,    //True 显示行号
                method       : 'post',
                toolbar      : '#toolbar',
                pageSize     : 20,      //每页条数
                queryParams: {
                    zones:$('#zones').val(),
                    NAME:$('#NAME').val(),
                    CREATETIME:$("input[name='CREATETIME']").val()
                },
                  frozenColumns:[[
                      {field:'ID',            title:'ID',         width:100, halign:'center', align:'center', sortable:true},
                      {field:'STATE',         title:'CHARID',     width:50, halign:'center', align:'center', sortable:true},
                      {field:'FROMZONE',      title:'发送区号',   width:60, halign:'center', align:'center', sortable:true},
                      {field:'FROMNAME',      title:'发送人',     width:50, halign:'center', align:'center', sortable:true},
                      {field:'TOZONE',        title:'接受区号',   width:100, halign:'center', align:'center', sortable:true},
                      {field:'TONAME',        title:'收件人',     width:120, halign:'center', align:'center', sortable:true},
                      {field:'TYPE',          title:'是否绑定',   width:50, halign:'center', align:'center',formatter:function(val){return (val==0) ? '否' : '是'}},
                  ]],
                columns:[[
                    {field:'CREATETIME',    title:'发送时间',   width:140, halign:'center', align:'center', sortable:true},
                    {field:'DELTIME',       title:'过期时间',   width:140, halign:'center', align:'center', sortable:true},
                    {field:'ACCESSORY',     title:'附件',       width:50, halign:'center', align:'center',formatter:function(val){return (val==0) ? '无' : '有'}},
                    {field:'ITEMGOT',       title:'是否获得',   width:60, halign:'center', align:'center',formatter:function(val){return (val==0) ? '否' : '是'}},
                    {field:'SENDMONEYNUMA', title:'发送元宝',   width:100, halign:'center', align:'center', sortable:true},
                    {field:'SENDMONEYNUMB', title:'发送银币',   width:100, halign:'center', align:'center', sortable:true},
                    {field:'TOID',          title:'TOID',       width:120, halign:'center', align:'center', sortable:true},
                    {field:'FROMID',        title:'FROMID',     width:100, halign:'center', align:'center', sortable:true},
                    {field:'TITLE',         title:'标题',       width:100, halign:'center', align:'center', sortable:true},
                    {field:'TEXT',          title:'邮件内容',   width:400, halign:'center', align:'center', sortable:true},
                ]]
            });
        }

    </script>
    </body>
</html>
