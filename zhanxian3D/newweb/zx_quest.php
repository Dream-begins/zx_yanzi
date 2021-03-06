<?php #session_start();if(strpos(@$_SESSION['priv'],'newweb/zx_quest')==0)exit;?>
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
<div id="quest">
    <div>
        <table class="tab_search">
            <tr>
                <td>区:</td>
                <td><input type="text" id="zone" name="zone" value="" /></td>
                 <td>玩家id:</td>
                <td><input type="text" id="name" name="name" value="" /></td>
                <td>任务id:</td>
                <td><input type="text" id="name" name="quest" value="" /></td>
                <td>从:</td>
                <td><input class="easyui-datebox" name="from"></input></td>
                <td>到:</td>
                <td><input class="easyui-datebox" name="to"></input></td>
                <td><a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="obj.find()">查询</td>
                <td><input type="button" value="导出" onclick="exportData('quest','c_zx_quest.php')" /></td>
            </tr>
        </table>
    </div>
    <div id="dataTable" style="width:100%" ></div>
</div>
<script type="text/javascript">
$(function(){
    obj = {
        find:function(){
          $('#quest #dataTable').datagrid('load',{
              zone: $.trim($('input[name="zone"]').val()),
              name: $.trim($('input[name="name"]').val()),
              quest: $.trim($('input[name="quest"]').val()),
              from: $('input[name="from"]').val(),
              to   :$('input[name="to"]').val(),
          });  
          $('#quest #dataCount').datagrid('load',{
              zone: $.trim($('input[name="zone"]').val()),
              name: $.trim($('input[name="name"]').val()),
              from: $('input[name="from"]').val(),
              to   :$('input[name="to"]').val(),
          });  
        },
    };
    $('#quest #dataTable').datagrid({
        url: 'c_zx_quest.php',
        remoteSort: true,
        pagination:true,
        rownumbers:true,
        fitColumns:true,
        striped:true,
        nowrap:false,
        fitColumns:false,
        pageSize:20,
        columns:[[
               {field:'datetime',title:'日期',width:200},  
               {field:'ZONEID',title:'区',width:100},
               /*{field:'SERVERID',title:'服务器',width:100},  */            
               {field:'USERID',title:'玩家id',width:80},
               {field:'ACCNAME',title:'账号名',width:100},
               {field:'NAME',title:'玩家名',width:80},
               {field:'LEVEL',title:'玩家等级',width:100},              
               {field:'QUESTEVENT',title:'任务事件',width:100},
               {field:'QUESTID',title:'任务id',width:100},
               {field:'QUESTNAME',title:'任务名称',width:100}, 
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
