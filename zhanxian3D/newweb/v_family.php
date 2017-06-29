<?php session_start();if(strpos(@$_SESSION['priv'],'newweb/v_giftinfo')==0)exit;?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
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
        <form id='s_form'>
            合并前区:<input id='zones'     name='zones'     class="easyui-numberbox" size='10'>
            家族名:<input id='SEPTNAME' name='SEPTNAME' class='easyui-textbox' size='10'>
            <a id='ht_id_search1'href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="datagrid_load('c_family.php?action=list')">查询</a>
        </form>
    </div>
<!--    <a href="#" onclick="dg_load('c_family.php?septid=2')">查看</a>-->
    <br>
    <div id="jiazu" class="easyui-panel" closed='true' title="家族管理">
        <table>

            <tr>
                <td>家族名称</td><td><input class="easyui-textbox" id="familyname" name="familyname"                              value=""/></td>
            </tr>
            <tr>
                <td>家族等级</td><td><input class="easyui-textbox" id="familylv" name="familylv" value=""/></td>
            </tr>
<!--            <tr>-->
<!--                <td>家族经验</td><td><input class="easyui-textbox" id="familyexp" name="familyexp" value=""/></td>-->
<!--            </tr>-->
            <tr>
                <td><a href="#" class="easyui-linkbutton" data-options="iconCls:'icon-reload'" onclick="updatesept()">更新家族信息</a>
                </td>
                <td><a href="#" class="easyui-linkbutton" data-options="iconCls:'icon-cancel'" style="">解散家族</a>
                </td>
            </tr>


        </table><br>
    </div>
    <table id="member" style="width:100%;" data-options=""></table>
    <div id="tool" class="easyui-panel" closed='true'>

        <form id='form'>  </form>
            <table>
            <tr>
                <td align='left'>
                   【 <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="member_edit()">修改信息</a>&nbsp;&nbsp;
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="member_del()">踢出家族</a>
                    】 需选中后
                </td>
            </tr>
            </table>
    </div>

<script type="text/javascript">
    //家族成员信息修改
    function member_edit(){
        var row = $('#member').datagrid('getSelected');
        if (row){
            $('#dlg').dialog('open').dialog('setTitle','修改');
            $('#fm').form('load',row);
            url = "c_family.php?action=edit&id="+row.SEPTMBRID;
        }
    }
    //踢出家族成员
    function member_del(){
        var row = $('#member').datagrid('getSelected');
        if (row){
            $.messager.confirm('提示','确认踢出家族?无法恢复',function(r){
                if (r){
                    $.post('c_family.php?action=del',{id:row.SEPTMBRID},function(result){
                        if (result.errorMsg){
                            $.messager.show({
                                title: 'Error',
                                msg: result.errorMsg
                            });
                            $('#member').datagrid('reload');
                        } else {
                            $('#member').datagrid('reload');
                        }
                    },'json');
                }
            });
        }
    }
</script>
    <div id="dlg" class="easyui-dialog" style="width:650px;height:400px;padding:10px 20px" closed="true" buttons="#dlg-buttons">
        <form id="fm" method="post" >
            <table>
                <tr>
                    <td>角色名称:</td>
                    <td>
                        <input id='SEPTMBRNAME' name="SEPTMBRNAME" class="easyui-textbox"  size='20' >

                    </td>
                </tr>

                <tr>
                    <td>贡献:</td>
                    <td>
                        <input id='CONTRIBUTION' name="CONTRIBUTION" class="easyui-textbox"  size='20' >

                    </td>
                </tr>

                <tr>
                    <td>职位:</td>
                    <td>
                        <select id='SEPTPOSITION' name='SEPTPOSITION' >
                            <option value='0'>族众</option>
                            <option value='1'>精英</option>
                            <option value='2'>长老</option>
                            <option value='3'>不知道</option>
                            <option value='4'>副族长</option>
                            <option value='5'>族长</option>
                        </select>
                    </td>
                </tr>


            </table>
        </form>
    </div>

    <div id="dlg-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveZone()" style="width:90px">保存</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">取消</a>
    </div>
    
    <!-- 家族列表-->
    <script type="text/javascript">
        datagrid_load();
        function datagrid_load(url)
        {
            $('#dg').datagrid({
                title : '家族列表',
                url   : url,
                rownumbers   : true,    //显示行号
                singleSelect : true,    //只允许选择单条数据
                //fitColumns   : true,    //True 自适应宽度 防止水平滚动条
                collapsible  : true,    //True 显示折叠按钮
                pagination   : true,    //True 显示行号
                method       : 'post',
                toolbar      : '#toolbar',
                pageSize     : 10,      //每页条数
                queryParams: {
                    zones:$('#zones').val(),
                    SEPTNAME:$('#SEPTNAME').val()
                },
                columns:[[

                    {field:'SEPTNAME',      title:'家族名称',     width:200, halign:'center', align:'center'},
                    {field:'SEPTLEVEL',    title:'家族等级',     width:100, halign:'center', align:'center'},
                    {field:'SEPTMBRNAME',    title:'族长',     width:200, halign:'center', align:'center'},
                    {field:'CREATETIME',    title:'创建时间',     width:200, halign:'center', align:'center'},
                    {field:'num',    title:'现有成员数',     width:200, halign:'center', align:'center'},
                    {field:'detail',    title:'操作',     width:200, halign:'center', align:'center'},

                ]]
            });
        }

        function hxiangou_iddel(f_NAME,f_ITEMGOT,f_MAILTITLE,zone)
        {
            if(confirm("注意：确定？取消？"))
            {
                $.ajax(
                {
                    type:'POST',
                    url:'c_family.php?action=del&zone='+zone,
                    data:{f_NAME:f_NAME,f_ITEMGOT:f_ITEMGOT,f_MAILTITLE:f_MAILTITLE},
                    success:function()
                    {
                        $("#ht_id_search1").click();
                    }
                });
                $("#ht_id_search1").click();
            }
        }


    </script>
    <!-- 家族成员列表-->
    <script type="text/javascript">
        function dg_load(url)
        {

           // 显示家族信息处理
            var rowss = $('#dg').datagrid('getSelected');
            $('#familyname').textbox('setValue',rowss.SEPTNAME);
            $('#familylv').textbox('setValue',rowss.SEPTLEVEL);
            //$('#familyname').textbox('setValue',row.name);

            $('#jiazu').panel('open');
            $('#member2').panel('open');


            $('#member').datagrid({
            title : '成员列表',
            url   : url,
            rownumbers   : true,    //显示行号
            singleSelect : true,    //只允许选择单条数据
            //fitColumns   : true,    //True 自适应宽度 防止水平滚动条
            //collapsible  : true,    //True 显示折叠按钮
            pagination   : true,    //True 显示行号
            method       : 'post',
            toolbar      : '#tool',
            pageSize     : 10,      //每页条数
            queryParams: {
            //zones:$('#zones').val(),
            //NAME:$('#NAME').val()
            },
            columns:[[

            {field:'SEPTMBRNAME',      title:'角色名称',     width:200, halign:'center', align:'center'},
            //{field:'zhandou',    title:'战斗力',     width:100, halign:'center', align:'center'},
            {field:'SEPTPOSITION',    title:'职位',     width:200, halign:'center', align:'center'},
            {field:'CONTRIBUTION',    title:'贡献',     width:200, halign:'center', align:'center'},
            //{field:'lv',    title:'等级',     width:200, halign:'center', align:'center'},

            ]]
            });

        }

        function updatesept(){
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $.messager.confirm('提示','是否确定更新?无法恢复',function(r){
                    if (r){
                        $.post('c_family.php?action=updatesept',{id:row.id},function(result){
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
    </script>
    </body>
</html>
