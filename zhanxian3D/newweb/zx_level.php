<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <title>玩家等级统计</title>
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
                <td>从:</td>
                <td><input class="easyui-datebox" name="from"></input></td>
                <td>到:</td>
                <td><input class="easyui-datebox" name="to"></input></td>
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
              from:$('input[name="from"]').val(),
              to:$('input[name="to"]').val(),
          });  
        },
    };
     $('#quest #dataCount').datagrid({
        url: 'lv_zx_quest.php',
        remoteSort: true,
        rownumbers:true,
        fitColumns:true,
        striped:true,
        nowrap:false,
        fitColumns:false,
        columns:[[
                {field:'level',title:'玩家等级',width:100},
                {field:'count',title:'用户总数',width:100},
                {field:'percent',title:'等级占比',width:100},           
        ]],   
       });
});
</script>
  </body>
</html>