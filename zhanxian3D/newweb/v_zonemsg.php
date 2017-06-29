<?php
/**
 * Created by PhpStorm.
 * User: chenyunhe
 * Date: 2017/1/16
 * Time: 13:25
 */
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
    <script type="text/javascript" src="jquery-easyui-1.4/locale/easyui-lang-zh_CN.js"></script>

</head>
<body style="padding:0px;margin:0px">

<table id="dg" style="width:100%;" data-options=""></table>

<div id="toolbar">
    <table style='width:100%'>
        <tr>
            <td align='left'>
                【<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newZones()">新加</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editZones()">修改</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyZones()">移除</a>
                】
            </td>
        </tr>
    </table>
</div>

<div id="dlg" class="easyui-dialog" style="width:450px;height:360px;padding:10px 20px" closed="true" buttons="#dlg-buttons">
    <form id="fm" method="post" >
        <table>
            <tr>
                <td>服务器id:</td>
                <td><input id='zone_id' name="zone_id" class="easyui-textbox" required="true"  size='20' ></td>
            </tr>
            <tr>
                <td>合服:</td>
                <td><input id="zones" name="zones" class="easyui-textbox" required="true"  size='20'></td>
            </tr>
            <tr>
                <td>mysql_ip:</td>
                <td><input id='mysql_ip' name="mysql_ip" class="easyui-textbox" required="true"  size='20' ></td>
            </tr>
            <tr>
                <td>mysql_port:</td>
                <td><input id='mysql_port' name="mysql_port" class="easyui-textbox" required="true" ' size='20' ></td>
            </tr>
            <tr>
                <td>mysql_dbname:</td>
                <td><input id='mysql_dbname' name="mysql_dbName" class="easyui-textbox" required="true"  size='20' ></td>
            </tr>
        </table>
    </form>
</div>

<div id="dlg-buttons">
    <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveZone()" style="width:90px">保存</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">取消</a>
</div>

<script type="text/javascript">
    datagrid_load();
    function datagrid_load()
    {
        $('#dg').datagrid({
            title : '',
            url : 'c_zonemsg.php?action=list',
            rownumbers : true,    //显示行号
            singleSelect: true,   //只允许选择单条数据
            fitColumns : true,    //设置为true,自动扩展或收缩列的大小以适应网格宽度和防止水平滚动条
            collapsible : true,   //当True时可显示折叠按钮。默认false。
            method : 'post',
            toolbar: '#toolbar',
            columns:[[
                {title:'id',field:'id',width:180,halign:'center',align:'center'},
                {title:'服务器id',field:'zone_id',width:180,halign:'center',align:'center'},
                {title:'合服',field:'zones',width:180,halign:'center',align:'center'},
                {title:'mysql_ip',field:'mysql_ip',width:135,halign:'center',align:'center'},
                {title:'mysql_port',field:'mysql_port',width:300,halign:'center',align:'center'},
                {title:'mysql_dbname',field:'mysql_dbName',width:150,halign:'center',align:'center'},
            ]]
        });
    }

    var url ='';

    function newZones(){
        $('#dlg').dialog('open').dialog('setTitle','新加');
        $('#fm').form('clear');
        url = "c_zonemsg.php?action=add";
    }

    function editZones(){
        var row = $('#dg').datagrid('getSelected');
        if (row){
            $('#dlg').dialog('open').dialog('setTitle','修改');
            $('#fm').form('load',row);
            url = "c_zonemsg.php?action=edit&id="+row.id;
        }
    }

    function destroyZones(){
        var row = $('#dg').datagrid('getSelected');
        if (row){
            $.messager.confirm('提示','确认删除么?无法恢复',function(r){
                if (r){
                    $.post('c_zonemsg.php?action=del',{id:row.id},function(result){
                        if (result.errorMsg){
                            $.messager.show({
                                title: 'Error',
                                msg: result.errorMsg
                            });
                            $('#dg').datagrid('reload');
                        } else {
                            $('#dg').datagrid('reload');
                        }
                    },'json');
                }
            });
        }
    }

    function saveZone(){
        $('#fm').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if (result.errorMsg){
                    $.messager.show({
                        title: 'Error',
                        msg: result.errorMsg
                    });
                } else {
                    $('#dlg').dialog('close');
                    $('#dg').datagrid('reload');
                }
            }
        });
    }
</script>
</body>
</html>
