<?php session_start();if(strpos(@$_SESSION['priv'],'newweb/v_timeing_gift')==0)exit;?>
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
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="conf_edit()">修改</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyZones()">移除</a>
                    】
                </td>
            </tr>
        </table>
    </div>

    <div id="dlg" class="easyui-dialog" style="width:650px;height:400px;padding:10px 20px" closed="true" buttons="#dlg-buttons">
        <form id="fm" method="post" >
        <table>
            <tr>
                <td>创建角色时间:</td>
                <td>
                    <input id='create_start' name="create_start" class="easyui-datebox" editable='fasle' size='10' >
                    ~
                    <input id='create_end' name="create_end" class="easyui-datebox" editable='fasle' size='10' >
                </td>
            </tr>
            <tr>
                <td>最近登录时间:</td>
                <td>
                    <input id='lastlogin_start' name="lastlogin_start" class="easyui-datebox" editable='fasle' size='10' >
                    ~
                    <input id='lastlogin_end' name="lastlogin_end" class="easyui-datebox" editable='fasle' size='10' >
                </td>
            </tr>
            <tr>
                <td>等级:</td>
                <td>
                    <input id='lv_start' name="lv_start" class="easyui-textbox" size='10' >
                    ~
                    <input id='lv_end' name="lv_end" class="easyui-textbox" size='10' >
                </td>
            </tr>
            <tr>
                <td>道具1:</td>
                <td>
                    id:<input id='item1' name="item1" class="easyui-textbox" size='10' >
                    ~
                    数量:<input id='num1' name="num1" class="easyui-textbox" size='10' >
                    ~
                    是否绑定:<select id='bind1' name='bind1' ><option value='1'>绑定</option><option value='0'>不绑定</option></select>
                </td>
            </tr>
            <tr>
                <td>道具2:</td>
                <td>
                    id:<input id='item2' name="item2" class="easyui-textbox" size='10' >
                    ~
                    数量:<input id='num2' name="num2" class="easyui-textbox" size='10' >
                    ~
                    是否绑定:<select id='bind2' name='bind2' ><option value='1'>绑定</option><option value='0'>不绑定</option></select>
                </td>
            </tr>
            <tr>
                <td>道具3:</td>
                <td>
                    id:<input id='item3' name="item3" class="easyui-textbox" size='10' >
                    ~
                    数量:<input id='num3' name="num3" class="easyui-textbox" size='10' >
                    ~
                    是否绑定:<select id='bind3' name='bind3' ><option value='1'>绑定</option><option value='0'>不绑定</option></select>
                </td>
            </tr>
            <tr>
                <td>邮件标题:</td>
                <td>
                    <input id='mtitle' name='mtitle' class='easyui-textbox' size='15'>
                </td>
            </tr>
            <tr>
                <td>邮件内容:</td>
                <td><input id='m_content' name="m_content" class="easyui-textbox"  data-options="multiline:true" style="height:60px;width:250px"></td>
            </tr>

            <tr>
                <td>执行区:</td>
                <td><input id='zoness' name="zoness" class="easyui-textbox" >格式1,3-10,13</td>
            </tr>
            <tr>
                <td>执行时间:</td>
                <td><input id='dotime' name="dotime" class="easyui-datetimebox" ></td>
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
                title : '定时礼包配置',
                url : 'c_timeing_gift.php?action=list',
                rownumbers : true,    //显示行号
                singleSelect: true,   //只允许选择单条数据
                fitColumns : true,    //设置为true,自动扩展或收缩列的大小以适应网格宽度和防止水平滚动条
                collapsible : true,   //当True时可显示折叠按钮。默认false。
                fitColumns:false,    //横向滚动条
                method : 'post',
                toolbar: '#toolbar',
                columns:[[
                    {title:'状态',field:'state',width:180,halign:'center',align:'center'},
                    {title:'执行时间',field:'dotime',width:180,halign:'center',align:'center'},
                    {title:'区',field:'zoness',width:150,halign:'center',align:'center'},

                    {title:'邮件标题',field:'mtitle',width:180,halign:'center',align:'center'},
                    {title:'邮件内容',field:'m_content',width:200,halign:'center',align:'center'},

                    {title:'创角时间',field:'create',width:130,halign:'center',align:'center'},
                    {title:'最后登录',field:'lastlogin',width:130,halign:'center',align:'center'},
                    {title:'等级',field:'lv',width:50,halign:'center',align:'center'},

                    {title:'id1',field:'item1',width:90,halign:'center',align:'center'},
                    {title:'绑定1',field:'bind1',width:40,halign:'center',align:'center'},
                    {title:'数量1',field:'num1',width:40,halign:'center',align:'center'},
                    {title:'id2',field:'item2',width:90,halign:'center',align:'center'},
                    {title:'绑定2',field:'bind2',width:40,halign:'center',align:'center'},
                    {title:'数量2',field:'num2',width:40,halign:'center',align:'center'},
                    {title:'id3',field:'item3',width:90,halign:'center',align:'center'},
                    {title:'绑定3',field:'bind3',width:40,halign:'center',align:'center'},
                    {title:'数量3',field:'num3',width:40,halign:'center',align:'center'},


                    {title:'log',field:'log',width:180,halign:'center',align:'center'},
                    {title:'创建日期',field:'ctime',width:70,halign:'center',align:'center'},
                    {title:'创建人',field:'cadmin',width:50,halign:'center',align:'center'},
                ]]
            });
        }

        var url ='';

        function newZones(){
            $('#dlg').dialog('open').dialog('setTitle','新加');
            $('#fm').form('clear');
            $(".weeks").prop("checked", true);
            url = "c_timeing_gift.php?action=add";
        }

        function destroyZones(){
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $.messager.confirm('提示','确认删除么?无法恢复',function(r){
                    if (r){
                        $.post('c_timeing_gift.php?action=del',{id:row.id},function(result){
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

        function conf_edit(){
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $('#dlg').dialog('open').dialog('setTitle','修改');
                $('#fm').form('load',row);
                url = "c_timeing_gift.php?action=edit&id="+row.id;
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
