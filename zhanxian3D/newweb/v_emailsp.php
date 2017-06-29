<?php
/**
 * Created by PhpStorm.
 * User: chenyunhe
 * Date: 2017/2/13
 * Time: 17:37
 * desc:邮件审批
 */
?>
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
<body>
<body style="padding:0px;margin:0px">
<table id="dg" style="width:100%;"></table>

<div id="toolbar">
    <form id='s_form'>
        申请ID:<input id='ID' name='ID' class="easyui-numberbox" size='10'>
        区:<input id='zone' name='zone' class="easyui-numberbox" size='10'>
        角色名称:<input id='name' name='name' class="easyui-textbox" size='10'>
        <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="datagrid_load('c_emailsp.php?action=list')">查询</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="shenhe()">审核</a>
    </form>
</div>


<script type="text/javascript">
    datagrid_load('c_emailsp.php?action=list');
    function datagrid_load(url)
    {
        $('#dg').datagrid({
            title : '',
            url : url,
            rownumbers : true,    //显示行号
            singleSelect: false,   //只允许选择单条数据
            // fitColumns : true,    //设置为true,自动扩展或收缩列的大小以适应网格宽度和防止水平滚动条
            collapsible : true,   //当True时可显示折叠按钮。默认false。
            pagination : true,    //分页
            method : 'post',
            toolbar: '#toolbar',
            pageSize: 20,         //初始化页面大小
            queryParams: {ID:$('#ID').val(),zone:$('#zone').val(),name:$('#name').val(),radioval:$("input[name='radioval']:checked").val()},
            columns:[[
                {field:'STATUS',title:'审核状态',width:80,halign:'center',align:'center',sortable:true},
                {field:'ID',title:'申请ID',width:50,halign:'center',align:'center',sortable:true},
                {field:'ZONE',title:'合服后区',width:50,halign:'center',align:'center',sortable:true},
                {field:'NAME',title:'角色名',width:50,halign:'center',align:'center',sortable:true},
                {field:'CHARID',title:'CHARID',width:50,halign:'center',align:'center',sortable:true},
                {field:'MAILTITLE',title:'邮件标题',width:100,halign:'center',align:'center',sortable:true},
                {field:'MAILTEXT',title:'内容',width:150,halign:'center',align:'center',sortable:true},
                {field:'ITEMID1',title:'道具1',width:30,halign:'center',align:'center',sortable:true},
                {field:'ITEMNUM1',title:'数量1',width:30,halign:'center',align:'center',sortable:true},
                {field:'BIND1',title:'绑定1',width:30,halign:'center',align:'center',sortable:true},
                {field:'ITEMID2',title:'道具2',width:30,halign:'center',align:'center',sortable:true},
                {field:'ITEMNUM2',title:'数量2',width:30,halign:'center',align:'center',sortable:true},
                {field:'BIND2',title:'绑定2',width:30,halign:'center',align:'center',sortable:true},
                {field:'ITEMID3',title:'道具3',width:30,halign:'center',align:'center',sortable:true},
                {field:'ITEMNUM3',title:'数量3',width:30,halign:'center',align:'center',sortable:true},
                {field:'BIND3',title:'绑定3',width:30,halign:'center',align:'center',sortable:true},
                {field:'SHENQING',title:'申请人',width:50,halign:'center',align:'center',sortable:true},
                {field:'SQTIME',title:'申请日期',width:120,halign:'center',align:'center',sortable:true},
                {field:'SHENHE',title:'审核人',width:50,halign:'center',align:'center',sortable:true},
                {field:'SHTIME',title:'审核日期',width:120,halign:'center',align:'center',sortable:true},
            ]]
        });
    }

    function  shenhe(){
        if(confirm("通过审核？")) {
            var rows = $('#dg').datagrid('getSelections');
            if(rows.length==0){
                alert('no selected');
                return false;
            }
            var idstr='';
            for(var i=0; i<rows.length; i++){
                idstr += rows[i].ID+",";
            }
            $.get("c_emailsp.php?action=shenhe&idstr=" + idstr, function (result) {
                alert(result);
                $('#dg').datagrid('reload');
            });
        }
    }
</script>
</body>
</html>
