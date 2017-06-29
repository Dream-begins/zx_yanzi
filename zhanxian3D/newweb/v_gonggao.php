<?php #session_start();if(strpos(@$_SESSION['priv'],'newweb/v_gonggao')==0)exit;?>
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
                <td>开启日期:</td>
                <td><input id='ymd_start' name="ymd_start" class="easyui-datebox" required="true" editable='fasle' size='10' ></td>
            </tr>
            <tr>
                <td>结束日期:</td>
                <td><input name="ymd_end" class="easyui-datebox" required="true" editable='fasle' size='10'></td>
            </tr>
            <tr>
                <td>每日执行开始时间:</td>
                <td><input id='time_start' name="time_start" class="easyui-timespinner" required="true" editabled='false' size='6' value='00:00'></td>
            </tr>
            <tr>
                <td>每日执行结束时间:</td>
                <td><input id='time_end' name="time_end" class="easyui-timespinner" required="true" size='6' value='23:59'></td>
            </tr>
            <tr>
                <td>内容:</td>
                <td><input id='contents' name="contents" class="easyui-textbox" required="true"  data-options="multiline:true" style="height:60px;width:250px"></td>
            </tr>
            <tr>
                <td>执行区:</td>
                <td><input name="zones" class="easyui-textbox" required="true">格式1,3-10,13</td>
            </tr>
            <tr>
                <td>
                    频率(分钟):
                </td>
                <td>
                    <input name="times" class="easyui-numberbox" required="true">
                </td>
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
                title : '定时公告配置',
                url : 'c_gonggao.php?action=list',
                rownumbers : true,    //显示行号
                singleSelect: true,   //只允许选择单条数据
                fitColumns : true,    //设置为true,自动扩展或收缩列的大小以适应网格宽度和防止水平滚动条
                collapsible : true,   //当True时可显示折叠按钮。默认false。
                method : 'post',
                toolbar: '#toolbar',
                columns:[[
                    {title:'开始日期',field:'ymd_start',width:180,halign:'center',align:'center'},
                    {title:'结束日期',field:'ymd_end',width:180,halign:'center',align:'center'},
                    {title:'每日执行开始时间',field:'time_start',width:135,halign:'center',align:'center'},
                    {title:'每日执行结束时间',field:'time_end',width:135,halign:'center',align:'center'},
                    {title:'内容',field:'contents',width:300,halign:'center',align:'center'},
                    {title:'执行区',field:'zones',width:150,halign:'center',align:'center'},
                    {title:'频率(分钟)',field:'times',width:150,halign:'center',align:'center'},
                ]]
            });
        }

        var url ='';

        function newZones(){
            $('#dlg').dialog('open').dialog('setTitle','新加');
            $('#fm').form('clear');
            $(".weeks").prop("checked", true);
            url = "c_gonggao.php?action=add";
        }

        function editZones(){
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $('#dlg').dialog('open').dialog('setTitle','修改');
                $('#fm').form('load',row);
                url = "c_gonggao.php?action=edit&id="+row.id;
            }
        }

        function destroyZones(){
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $.messager.confirm('提示','确认删除么?无法恢复',function(r){
                    if (r){
                        $.post('c_gonggao.php?action=del',{id:row.id},function(result){
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
