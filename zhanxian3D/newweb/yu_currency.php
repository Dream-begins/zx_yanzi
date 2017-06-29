<?php #session_start();if(strpos(@$_SESSION['priv'],'newweb/log_currency')==0)exit;?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="jquery-easyui-1.4/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="jquery-easyui-1.4/themes/icon.css">
    <script type="text/javascript" src="jquery-easyui-1.4/jquery.min.js"></script>
    <script type="text/javascript" src="jquery-easyui-1.4/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="../res/admin.js"></script>
    <script type="text/javascript" src="jquery-easyui-1.4/locale/easyui-lang-zh_CN.js"></script>

</head>
<body style="padding:0px;margin:0px">
<div id="currency">
    <div>
        <table class="tab_search">
            <tr>
                <td>区:</td>
                <td><input type="text" id="zone" name="zone" value="" /></td>
                 <td>玩家id:</td>
                <td><input type="text" id="name" name="name" value="" /></td>
               <td>从:</td>
                <td><input class="easyui-datebox" name="from" id="start_time" data-options="required:true"></input></td>
                <td>到:</td>
                <td><input class="easyui-datebox" name="to" id="end_time" data-options="required:true"></input></td>
                <td><a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="obj.find()">查询</td>
               <td><input type="button" value="导出" onclick="exportData('currency','c_yu_currency.php')" /></td>
               <td>注:这里需要点击<font color="red">"查询"</font>才能查询默认日期的数据</td>
            </tr>
        </table>
    </div>
    <div id="dataTable" styles="width:100%" ></div>
</div>
<script type="text/javascript">
function from_time(date) {
            var y = date.getFullYear();
            var m = date.getMonth() + 1;
            var d = date.getDate()-7;

            return y + '-' + (m < 10 ? ('0' + m) : m) + '-'
                    + (d < 10 ? ('0' + d) : d);
        }
        function to_time(date) {
            var y = date.getFullYear();
            var m = date.getMonth() + 1;
            var d = date.getDate();

            return y + '-' + (m < 10 ? ('0' + m) : m) + '-'
                    + (d < 10 ? ('0' + d) : d);
        }
$(function(){
    var curr_time = new Date();
            $("#start_time").datebox({
                value : from_time(curr_time)
            });
            $("#end_time").datebox({
                value : to_time(curr_time)
            });
    obj = {
        find:function(){
          $('#currency #dataTable').datagrid('load',{
              zone: $.trim($('input[name="zone"]').val()),
              name: $.trim($('input[name="name"]').val()),
              from: $('input[name="from"]').val(),
              to   :$('input[name="to"]').val(),
          });  
        },
    };
    $('#currency #dataTable').datagrid({
        url: 'c_yu_currency.php',
        remoteSort: true,
        pagination:true,
        rownumbers:true,
        fitColumns:false,
        striped:true,
        nowrap:true,
        pageSize:20,
        columns:[[
               {field:'datetime',title:'日期',width:180},
               {field:'ZONEID',title:'区',width:100},
               /*{field:'SERVERID',title:'服务器',width:100},*/             
               {field:'USERID',title:'玩家id',width:80},
               {field:'ACCNAME',title:'账号名',width:100},
               {field:'NAME',title:'玩家名',width:80},
               {field:'LEVEL',title:'玩家等级',width:100},           
               {field:'CHARGEMOUNT',title:'充值金额',width:100},
               {field:'REDUCEYuanBao',title:'消耗仙玉',width:80},
               {field:'ADDMount',title:'仙玉增加量',width:100},
               {field:'NOWMount',title:'仙玉现存量',width:100},
               {field:'EVENT',title:'货币事件',width:100},          
               {field:'ITEMID',title:'物品id',width:100},
               {field:'ITEMNAME',title:'物品名称',width:100},       
               {field:'PARA1',title:'参数1',width:100},
               {field:'PARA2',title:'参数2',width:100},
               {field:'PARA3',title:'参数3',width:100},
               {field:'PARA4',title:'参数4',width:100},
               {field:'PARA5',title:'参数5',width:100},
               {field:'EXTRA',title:'额外信息',width:100},
           ]],
       });
});
</script>
  </body>
</html>
