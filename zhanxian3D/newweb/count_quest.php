<?php #session_start();if(strpos(@$_SESSION['priv'],'newweb/count_quest')==0)exit;?>
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
                <td><a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="obj.find()">查询</td>               
            </tr>
        </table>
    </div>
    <div id="dataCount" style="width:100%"></div>
</div>
<script type="text/javascript">
$(function(){
    obj = {
        find:function(){
          $('#quest #dataCount').datagrid('load',{
              zone: $.trim($('input[name="zone"]').val()),
          }); 
        },
    },
        $('#quest #dataCount').datagrid({
        url: 'c_count_quest.php',
        remoteSort: true,
        rownumbers:true,
        fitColumns:true,
        striped:true,
        nowrap:false,
        fitColumns:false,
        columns:[[
                {field:'QUESTID',title:'任务ID',width:100},
                {field:'QUESTNAME',title:'任务名称',width:100},
                {field:'a_times',title:'任务次数',width:100},
                {field:'f_times',title:'完成次数',width:100},
                {field:'percent',title:'任务完成率',width:100},
        ]],
    });
 });
     
</script>
  </body>
</html>
