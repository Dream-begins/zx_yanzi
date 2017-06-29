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
    <table id="dg" style="width:100%;"></table>

    <div id="toolbar">
        <form id='s_form'>
            合并前区:<input id='zones' name='zones' class="easyui-numberbox" size='10'>
            合并后区:<input id='zone_id' name='zone_id' class="easyui-numberbox" size='10'>
            玩家ID:<input id='ACCNAME' name='ACCNAME' class="easyui-textbox" size='33'>
            玩家名称:<input id='NAME' name='NAME' class='easyui-textbox' size='15'>
            <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="datagrid_load()">查询</a>
        </form>
    </div>

    <script type="text/javascript">
        datagrid_load();
        function datagrid_load()
        {
            $('#dg').datagrid({
                title : '帐号明细',
                url : 'c_acc_detail2.php?action=list',
                rownumbers : true,    //显示行号
                singleSelect: true,   //只允许选择单条数据
                // fitColumns : true,    //设置为true,自动扩展或收缩列的大小以适应网格宽度和防止水平滚动条
                collapsible : true,   //当True时可显示折叠按钮。默认false。
                pagination : true,    //分页
                method : 'post',
                toolbar: '#toolbar',
                pageSize: 20,         //初始化页面大小
                queryParams: {zones:$('#zones').val(),zone_id:$('#zone_id').val(),ACCNAME:$('#ACCNAME').val(),NAME:$('#NAME').val()},
                frozenColumns:[[
                    {field:'ACCNAME',title:'玩家ID',width:200,halign:'center',align:'center'},
                    {field:'CHARID',title:'CHARID',width:50,halign:'center',align:'center',sortable:true},
                    {field:'NAME',title:'玩家名称',width:100,halign:'center',align:'center'},
                ]],
                columns:[[
                    {field:'zone',title:'合并前区',width:100,halign:'center',align:'center'},
                    {field:'zone_id',title:'合并后区',width:100,halign:'center',align:'center'},
                    //{field:'LEVEL',title:'等级',width:35,halign:'center',align:'center',sortable:true},
                    {field:'VIP',title:'VIP',width:35,halign:'center',align:'center',sortable:true,},
                    //{field:'SHENQI',title:'战力',width:50,halign:'center',align:'center',sortable:true},
                    //{field:'COUNTRY',title:'宗派',width:35,halign:'center',align:'center',sortable:true},
                    //{field:'MONEY5',title:'元宝5',width:50,halign:'center',align:'center',sortable:true},
                    //{field:'MONEY1',title:'元宝',width:50,halign:'center',align:'center',sortable:true},
                    //{field:'MONEY3',title:'返还元宝',width:50,halign:'center',align:'center',sortable:true},
                    
                    {field:'CREATETIME',title:'创建时间',width:130,halign:'center',align:'center',sortable:true},
                    {field:'LASTACTIVEDATE',title:'最后登录时间',width:130,halign:'center',align:'center',sortable:true},
                    {field:'FORBIDTALK',title:'禁言时间',width:130,halign:'center',align:'center',sortable:true,formatter:function(val,row){if(val=='1970-01-01 08:00:00'){return ''}else{return val}}},
                    {field:'MAPID',title:'地图',width:100,halign:'center',align:'center',sortable:true},
                    {field:'LINEID',title:'分线',width:100,halign:'center',align:'center',sortable:true},
                    {field:'heimingdan',title:'黑名单',width:70,halign:'center',align:'center',sortable:true,formatter:function(val,row){if(val=='1'){return '<font color="red">黑名单</font>'}}},
                    {field:'BITMASK',title:'BITMASK',width:70,halign:'center',align:'center',sortable:true,formatter:function(val,row){if(val==3){return "<font color='red'>封号</font>"}else{return val}}},
                    //{field:'ACCPRIV',title:'TYPE',width:30,halign:'center',align:'center',sortable:true},
                    //{field:'HUANGZAN',title:'黄钻',width:30,halign:'center',align:'center',sortable:true},

                    //{field:'HP',title:'HP',width:50,halign:'center',align:'center',sortable:true},
                    //{field:'MONEY2',title:'绑定元宝',width:55,halign:'center',align:'center',sortable:true},
                    //{field:'LINQI',title:'灵气',width:55,halign:'center',align:'center',sortable:true},
                    //{field:'MONEY4',title:'银币',width:55,halign:'center',align:'center',sortable:true},
                    //{field:'MONEY5',title:'仙石',width:55,halign:'center',align:'center',sortable:true},
                    //{field:'MONEY6',title:'家族贡献',width:55,halign:'center',align:'center',sortable:true},
                    //{field:'MONEY9',title:'荣誉',width:55,halign:'center',align:'center',sortable:true},
                    //{field:'MONEY10',title:'声望',width:55,halign:'center',align:'center',sortable:true},
                    //{field:'MONEY13',title:'元魂',width:55,halign:'center',align:'center',sortable:true},
                ]]
            });
        }
    </script>
</body>
</html>
