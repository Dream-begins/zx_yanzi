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
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="user_del()">删除帐号</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="user_add()">添加帐号</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="qx_update()">修改权限</a>
                </td>
            </tr>
        </table>
    </div>

    <div id="dlg" class="easyui-dialog" style="width:260px;height:120px;padding:10px 20px" closed="true" buttons="#dlg-buttons">
        <form id="fm" method="post">
            <div class="fitem">
                <label>帐号:</label>
                <input name="user" id='user'></input>
            </div>
        </form>
    </div>

    <div id="dlg-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveZone()" style="width:90px">保存</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">取消</a>
    </div>

    <div id="dlg2" class="easyui-dialog" style="width:600px;height:500px;padding:10px 20px" closed="true" buttons="#dlg2-buttons">
        
        <div class="easyui-panel" style="padding:5px">
            <ul id="tt1"></ul>
        </div>

    </div>

    <div id="dlg2-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveZone2()" style="width:90px">保存</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg2').dialog('close')" style="width:90px">取消</a>
    </div>

<script type="text/javascript">
    datagrid_load();

    function datagrid_load()
    {
        $('#dg').datagrid({
            title : '',
            url : 'c_admin_conf.php?action=user_list',
            rownumbers : true,
            singleSelect: true,
            fitColumns : true,
            collapsible : true,
            pagination : false, 
            method : 'post',
            toolbar: '#toolbar',
            pageSize: 20,
            nowrap: false,
            collapsible: true,
            queryParams: {s_ptname:$('#s_ptname').val()},
            columns:[[
                {field:'user',title:'用户名'},
                {field:'passChange',title:'是否更改密码'},
                {field:'priv_nu',title:'已开权限/全部权限'},
            ]]
        });
    }

    var url ='';

    function user_add()
    {
        $('#dlg').dialog('open').dialog('setTitle','添加帐号');
        url = "c_admin_conf.php?action=user_add";
    }

    function user_del(){
        var row = $('#dg').datagrid('getSelected');
        if (row)
        {
            $.messager.confirm('提示','确认删除么?无法恢复',function(r)
            {
                if (r)
                {
                    $.post('c_admin_conf.php?action=user_del',{id:row.ID},function(result)
                    {
                        if (result.errorMsg)
                        {
                            $.messager.show(
                            {
                                title: 'Error',
                                msg: result.errorMsg
                            });
                            $('#dg').datagrid('reload');
                        } else 
                        {
                            $('#dg').datagrid('reload');
                        }
                    },'json');
                }
            });
        }
    }

    function qx_update()
    {
        var row = $('#dg').datagrid('getSelected');
        if (row)
        {
            $('#dlg2').dialog('open').dialog('setTitle','修改权限');
            $('#fm2').form('load',row);
            url = "c_admin_conf.php?action=qx_update&id="+row.ID;

            $('#tt1').tree({
                url:'c_admin_conf.php?action=qx_list&id='+row.ID,
                method:'get',
                animate:true,
                checkbox:true
            });

        }
    }


    function saveZone()
    {
        $('#fm').form('submit',
        {
            url: url,
            onSubmit: function()
            {
                return $(this).form('validate');
            },
            success: function(result)
            {
                var result = eval('('+result+')');
                if (result.errorMsg)
                {
                    $.messager.show(
                    {
                        title: 'Error',
                        msg: result.errorMsg
                    });
                } else 
                {
                    $('#dlg').dialog('close');
                    $('#dg').datagrid('reload');
                }
            }
        });
    }

    function saveZone2()
    {
        var nodess = getChecked();
        var postdatass = JSON.parse(JSON.stringify(nodess));

        $.ajax(
        {
            type:'POST',
            url:url,
            data:{postdatas:postdatass},
            success: function(result)
            {
                var result = eval('('+result+')');
                if (result.errorMsg)
                {
                    $.messager.show(
                    {
                        title: 'Error',
                        msg: result.errorMsg
                    });
                } else 
                {
                    $('#dlg2').dialog('close');
                    $('#dg').datagrid('reload');
                }
            }
        });
    }

    function getChecked()
    {
        var arr = [];
        var checkeds = $('#tt1').tree('getChecked', 'checked');
        for (var i = 0; i < checkeds.length; i++) {
            arr.push(checkeds[i].id);
        }
        return arr;
    }

</script>
</body>
</html>