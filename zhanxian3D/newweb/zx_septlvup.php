<?php #session_start();if(strpos(@$_SESSION['priv'],'newweb/zx_septlvup')==0)exit;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="jquery-easyui-1.4/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="jquery-easyui-1.4/themes/icon.css">
    <script type="text/javascript" src="jquery-easyui-1.4/jquery.min.js"></script>
    <script type="text/javascript" src="../res/admin.js"></script>
    <script type="text/javascript" src="jquery-easyui-1.4/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="jquery-easyui-1.4/locale/easyui-lang-zh_CN.js"></script>

</head>
<body style="padding:0px;margin:0px">
<div id="septlvup">
    <div>
        <table class="tab_search">
            <tr>
                <td>服务器:</td>
                <td><input type="text" id="zone" name="zone" value="" /></td>
                <td>从:</td>
                <td><input class="easyui-datebox" name="from"></input></td>
                <td>到:</td>
                <td><input class="easyui-datebox" name="to"></input></td>
                <td><a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="obj.find()">查询</td>
                <td><input type="button" value="导出" onclick="exportData('septlvup','../excle_septlvup.php')" /></td>
            </tr>
        </table>
    </div>
    <div id="dataTable" styles="width:100%" ></div>
</div>
<script type="text/javascript">
$(function(){
    obj = {
        find:function(){
          $('#septlvup #dataTable').datagrid('load',{
              zone: $.trim($('input[name="zone"]').val()),
              from: $('input[name="from"]').val(),
              to   :$('input[name="to"]').val(),
          });  
        },
    };
    $('#septlvup #dataTable').datagrid({
        url: 'c_zx_septlvup.php',
        remoteSort: true,
        pagination:true,
        rownumbers:true,
        fitColumns:true,
        striped:true,
        nowrap:false,
        pageSize:20,
        fitColumns:false,
        columns:[[
               {field:'datetime',title:'日期',width:180},   
                {field:'SERVERID',title:'服务器',width:100},
               {field:'ZONEID',title:'区域',width:100},
               {field:'USERID',title:'玩家角色id',width:100},
               {field:'ACCNAME',title:'账号名',width:100},
               {field:'NAME',title:'玩家名',width:100},
               {field:'LEVEL',title:'玩家等级',width:100},
               {field:'PARA1',title:'参数1',width:100},
               {field:'PARA2',title:'参数2',width:100},
               {field:'PARA3',title:'参数3',width:100},
               {field:'PARA4',title:'参数4',width:100},
               {field:'PARA5',title:'参数5',width:100},
               {field:'EXTRA',title:'额外信息',width:100},         
               {field:'SEPTID',title:'仙踪id',width:100},         
               {field:'SEPTNAME',title:'仙踪名称',width:100},                  
               {field:'BUILDTYPE',title:'建筑类型',width:100},         
               {field:'BUILDLEVEL',title:'建筑等级',width:100},         
               {field:'COSTTYPE',title:'消耗货币类型',width:100},         
               {field:'COST',title:'消耗量',width:100},                           
           ]],
       });
});
</script>
  </body>
</html>
