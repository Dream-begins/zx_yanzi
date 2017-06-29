<?php #session_start();if(strpos(@$_SESSION['priv'],'newweb/zx_dup')==0)exit;
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
    <script type="text/javascript" src="../res/admin.js"></script>
    <script type="text/javascript" src="jquery-easyui-1.4/locale/easyui-lang-zh_CN.js"></script>

</head>
<body style="padding:0px;margin:0px">
<div id="dup">
    <div>
        <table class="tab_search">
            <tr>
                <td>区:</td>
                <td><input type="text" id="zone" name="zone" value="" /></td>
                <td>从:</td>
                <td><input class="easyui-datebox" name="from"></input></td>
                <td>到:</td>
                <td><input class="easyui-datebox" name="to"></input></td>
                <td><a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="obj.find()">查询</td>
                <td><input type="button" value="导出" onclick="exportData('dup','c_zx_dup.php')" /></td>
            </tr>
        </table>
    </div>
    <div id="dataTable" styles="width:100%" ></div>
</div>
<script type="text/javascript">
$(function(){
    obj = {
        find:function(){
          $('#dup #dataTable').datagrid('load',{
              zone: $.trim($('input[name="zone"]').val()),
              from: $('input[name="from"]').val(),
              to   :$('input[name="to"]').val(),
          });  
        },
    };
    $('#dup #dataTable').datagrid({
        url: 'c_zx_dup.php',
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
               {field:'ZONEID',title:'区',width:100},
               /*{field:'SERVERID',title:'服务器',width:100}, */             
               {field:'DUPEVENT',title:'副本事件',width:100},
               {field:'DUPID',title:'副本id',width:100},
               {field:'DUPNAME',title:'副本名称',width:100},
               {field:'USER1ID',title:'玩家1ID',width:100},
               {field:'USER2ID',title:'玩家2ID',width:100},
               {field:'USER3ID',title:'玩家3ID',width:100},         
               {field:'USER4ID',title:'玩家4ID',width:100},         
           ]],
       });
});
</script>
  </body>
</html>
