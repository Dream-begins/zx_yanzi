<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <title>头衔统计</title>
    <link rel="stylesheet" type="text/css" href="jquery-easyui-1.4/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="jquery-easyui-1.4/themes/icon.css">
    <script type="text/javascript" src="jquery-easyui-1.4/jquery.min.js"></script>
    <script type="text/javascript" src="jquery-easyui-1.4/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="../res/admin.js"></script>
    <script type="text/javascript" src="jquery-easyui-1.4/locale/easyui-lang-zh_CN.js"></script>

</head>
<body style="padding:0px;margin:0px">
<div id="honor">
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
          $('#honor #dataCount').datagrid('load',{
              zone: $.trim($('input[name="zone"]').val()),
              from:$('input[name="from"]').val(),
              to:$('input[name="to"]').val(),
          });  
        },
    };
     $('#honor #dataCount').datagrid({
        url: 'c_count_honor.php',
        remoteSort: true,
        rownumbers:true,
        fitColumns:true,
        striped:true,
        nowrap:false,
        fitColumns:false,
        columns:[[
                {field:'level',title:'头衔id(名称)',width:100},
                {field:'count',title:'突破总数',width:100},        
        ]],   
       });
});
</script>
  </body>
</html>