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
            公司名称:<input class="easyui-testbox" size='10' name='COMPLANY' id='COMPLANY'>
            IP地址:<input class="easyui-testbox" size='10' name='IP' id='IP'>
            <a href="#" class="easyui-linkbutton" iconCls="icon-add" onclick="doadd()">添加</a>
       </form>
    </div>
    <script type="text/javascript">
        datagrid_load('c_ipbailist.php?action=list')
        function doadd()
        {
            if( confirm("确定添加吗") )
            {
                $.ajax({
                    type:'POST',
                    url:'c_ipbailist.php?action=add',
                    data: {COMPLANY:$("input[name*='COMPLANY']").val(),IP:$("input[name*='IP']").val()} ,
                    success:function(result)
                    {
                        alert(result);
                        datagrid_load('c_ipbailist.php?action=list')
                    }
                });  
            }
            return false;
        }        

        function datagrid_load(url)
        {
            $('#dg').datagrid({
                title : 'IP白名单',
                url : url,
                rownumbers : true,    //显示行号
                // ctrlSelect: true,     //ctrl+左键 选多行
                singleSelect: true,   //只允许选择单条数据
                fitColumns : true,    //设置为true,自动扩展或收缩列的大小以适应网格宽度和防止水平滚动条
                collapsible : true,   //当True时可显示折叠按钮。默认false。
                pagination : false,    //True 就会显示行号的列。 
                method : 'post',
                toolbar: '#toolbar',
                pageSize: 20,         //初始化页面大小
                queryParams: {ymd_start:$("input[name*='ymd_start']").val(),ymd_end:$("input[name*='ymd_end']").val()},
                columns:[[
                    {field:'IP',title:'ip',width:100,halign:'center',align:'center'},
                    {field:'COMPLANY',title:'公司名称',width:80,halign:'center',align:'center'},
                    {field:'TIMES',title:'添加日期',width:80,halign:'center',align:'center'},
                ]]
            });
        }
    </script>
    </body>
</html>
