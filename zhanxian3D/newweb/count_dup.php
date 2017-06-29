<?php #session_start();if(strpos(@$_SESSION['priv'],'newweb/count_dup')==0)exit;?>
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
    <div id="dataCount" style="width:100%"></div>
</div>
<script type="text/javascript">
$(function(){
    obj = {
        find:function(){
          $('#dup #dataCount').datagrid('load',{
              zone: $.trim($('input[name="zone"]').val()),
          }); 
        },
    },
        $('#dup #dataCount').datagrid({
        url: 'c_count_dup.php',
        remoteSort: true,
        rownumbers:true,
        fitColumns:true,
        striped:true,
        nowrap:false,
        fitColumns:false,
        columns:[[
                {field:'DUPID',title:'副本ID',width:100},
                {field:'DUPNAME',title:'副本名称',width:100},
                {field:'a_times',title:'副本开启次数',width:100},
                {field:'f_times',title:'副本通关次数',width:100},
                {field:'percent',title:'副本完成率',width:100},
        ]],
    });
 });
     
</script>
  </body>
</html>
