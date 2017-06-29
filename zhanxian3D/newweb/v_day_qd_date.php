<?php session_start();if(strpos(@$_SESSION['priv'],'newweb/v_day_qd_date')==0)exit;?>
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
            日期:<input class="easyui-datebox" editable='fasle' size='10' name='ymd_start' id='ymd_start'>
            <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="datagrid_load('c_day_qd_date.php?action=list')">查询</a>
        </form>
    </div>

    <script type="text/javascript">
        datagrid_load();
        function datagrid_load(url)
        {
            $('#dg').datagrid({
                url   : url,
                rownumbers   : true,    //显示行号
                singleSelect : true,    //只允许选择单条数据
                fitColumns   : true,    //True 自适应宽度 防止水平滚动条
                collapsible  : true,    //True 显示折叠按钮
                //pagination   : true,    //True 显示行号
                method       : 'post',
                toolbar      : '#toolbar',
                //pageSize     : 20,      //每页条数
                queryParams: {
                    ymd:$("input[name*='ymd_start']").val(),
                },
                columns:[[
{field:'qd', title:'渠道', width:300, halign:'center', align:'center'},
{field:'reg', title:'新注册', width:300, halign:'center', align:'center'},
{field:'income', title:'收入', width:300, halign:'center', align:'center'},
{field:'payuser', title:'付费人数', width:300, halign:'center', align:'center'},
{field:'paylv', title:'付费率', width:300, halign:'center', align:'center'},
{field:'arpu', title:'arpu', width:300, halign:'center', align:'center'},
{field:'arppu', title:'arppu', width:300, halign:'center', align:'center'},
{field:'reg_pay_user', title:'新注册付费人数', width:300, halign:'center', align:'center'},
{field:'reg_pay_money', title:'新注册收入', width:300, halign:'center', align:'center'},

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
