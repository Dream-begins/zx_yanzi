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
                    <!--
                    【<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newZones()">新加</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editZones()">修改</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyZones()">移除</a>
					】
                    -->
                </td>
            </tr>
        </table>
    </div>

    <div id="dlg" class="easyui-dialog" style="width:450px;height:360px;padding:10px 20px" closed="true" buttons="#dlg-buttons">
        <form id="fm" method="post" >
        <table>
            <tr id='ZONE'>
                <td>合并后区:</td>
                <td><input name="ZONE" class="easyui-textbox"></td>
            </tr>
            <tr>
                <td>合并后服:</td>
                <td><input name="SERVERID" class="easyui-textbox"></td>
            </tr>
            <tr id='RACEGROUP'>
                <td>战区:</td>
                <td><input name="RACEGROUP" class="easyui-textbox"></td>
            </tr>
            <tr>
                <td>战区名称:</td>
                <td><input name="GROUPNAME" class="easyui-textbox"></td>
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
                title : '探墓王-国战战区配置',
                url : 'c_guozhan.php?action=list',
                rownumbers : true,
                singleSelect: true,  
                fitColumns : true,   
                collapsible : true,  
                method : 'post',
                toolbar: '#toolbar',
                columns:[[
                    {title:'区|ZONE',field:'ZONE',halign:'center',align:'center'},
                    {title:'战区|GROUP',field:'GROUP',halign:'center',align:'center'}
                ]]
            });
        }

        var url ='';

        function newZones(){
            $("#ZONE").show();
            $("#RACEGROUP").show();
            $('#dlg').dialog('open').dialog('setTitle','新加');
            $('#fm').form('clear');
            $(".weeks").prop("checked", true);
            url = "c_guozhan.php?action=add";
        }

        function editZones(){
            var row = $('#dg').datagrid('getSelected');
            $("#ZONE").hide();
            $("#RACEGROUP").hide();
            if (row){
                $('#dlg').dialog('open').dialog('setTitle','修改');
                $('#fm').form('load',row);
                url = "c_guozhan.php?action=edit";
            }
        }

        function destroyZones(){
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $.messager.confirm('提示','确认删除么?无法恢复',function(r){
                    if (r){
                        $.post('c_guozhan.php?action=del&ZONE='+row.ZONE+'&RACEGROUP='+row.RACEGROUP,function(result){
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
