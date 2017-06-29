<?php
    include_once "checklogin.php";
    include_once "newweb/h_header.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo FT_COMMON_TITLE;?></title>
    <link rel="stylesheet" type="text/css" href="res/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="res/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="res/jquery.jqplot.min.css">
    <script type="text/javascript" src="res/jquery.min.js"></script>
    <script type="text/javascript" src="res/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="res/admin.js"></script>
    <script type="text/javascript" src="res/datagrid-groupview.js"></script>
</head>
<body >
<form action="xiangou_confs_do.php?action=add" method="post" id="common_shoplimit">
<fieldset>
    <legend>常用限购配置</legend>
    <table margin:5px 20px 5px 5px;>
        <tr>
            <td>大类名称:</td>
            <td><input type='text' name='b_class' id='b_class'></td>
            <input type='hidden' name='id' id='id'>
            <td colspan='2'>(便于常用配置的分类记忆使用,游戏中不涉及，例：五折礼包 -> 大类下n个礼包)</td>
        </tr>
        <tr>
            <td>日期ID：</td>
            <td><input type='text' name='time_id' id='time_id'></td>
        </tr>
        <tr>
            <td>店铺分页：</td>
            <td><input type='text' name='shop_page' value='3' id='shop_page'></td>
        </tr>
        <tr>
            <td>店铺位置：</td>
            <td><input type='text' name='shop_position' value='1' id='shop_position'></td>
        </tr>
        <tr>
            <td>开放时间:</td>
            <td><input class="easyui-timespinner" value='00:00:00' data-options="min:'00:00:00',showSeconds:true" style="width:150px" name="ds_time" id="ds_time"></td>
            <td>结束时间:</td>
            <td><input class="easyui-timespinner" value='23:59:59' data-options="min:'23:59:59',showSeconds:true" style="width:150px" name="de_time" id="de_time"></td>        
        </tr>
        <tr>
            <td>物品ID：</td>
            <td><input type='text' name='item_id' id='item_id'></td>
            <td>物品名称：</td>
            <td><input type='text' name='item_name' id='item_name'></td>
            <td>是否绑定</td>
            <td><input type='text' name='bind' value='1' id='bind'></td>
        </tr>
        <tr>
            <td>货币类型：</td>
            <td><input type='text' name='money_type' id='money_type'></td>
            <td>开放类型：</td>
            <td><input type='text' name='open_type' value='127' id='open_type'></td>
        </tr>
        <tr>
            <td>原价：</td>
            <td><input type='text' name='old_money' id='old_money'></td>
            <td>现价：</td>
            <td><input type='text' name='now_money' id='now_money'></td>
        </tr>
        <tr>
            <td>单人限购数量：</td>
            <td><input type='text' name='onep_limit' id='onep_limit'></td>
            <td>总限购数量：</td>
            <td><input type='text' name='all_limit' id='all_limit'></td>
        </tr>
        <tr>
            <td>标签类型：</td>
            <td><input type='text' name='icon_type' value='4' id='icon_type'></td>
            <td>等级限制：</td>
            <td><input type='text' name='lv_limit' value='0' id='lv_limit'></td>
        </tr>
        <tr>
            <td>标签ICON：</td>
            <td><input type='text' name='icon_img' value='20004' id='icon_img'></td>
            <td>标签标题：</td>
            <td><input type='text' name='icon_title' id='icon_title'></td>
            <td>道具品质：</td>
            <td><input type='text' name='weight' id='weight'></td>
        </tr>

        <tr>
            <td>基金道具ID：</td>
            <td><input type='text' name='MONEYID'  id='MONEYID'></td>
            <td>礼包信息：</td>
            <td><input type='text' name='AWARDS' id='AWARDS'></td>
            <td>阶段价格：</td>
            <td><input type='text' name='STAGEPRICES' id='STAGEPRICES'></td>
        </tr>

        <tr>
            <td>道具描述：</td>
            <td><input type='text' name='DESC'  id='DESC'></td>
            <td>是否显示预售：</td>
            <td><input type='text'  name='PRESHOW' value="0" id='PRESHOW'></td>
            <td></td>
            <td></td>
        </tr>

        <tr>
            <td align="center"><a href="javascript:void(0)" class="easyui-linkbutton" onclick='hxiangou_formadd()'>添加到常用</a></td>
            <td colspan="8" align="center"><a href="javascript:void(0)" class="easyui-linkbutton" onclick='hxiangou_formupdate()'>更新操作</a></td>
        </tr>
    </table>
</fieldset>
</form>
<table class="easyui-datagrid" title="后台目录管理" style=""
        data-options="
            singleSelect:true,
            collapsible:true,
            rownumbers:true,
            fitColumns:false,
            url:'xiangou_confs_do.php?action=list',
            method:'get',
            view:groupview,
            groupField:'b_class',
            groupFormatter:function(value,rows){
                return value + '(' + rows.length +')';
            },onLoadSuccess:function(){
                $('datagrid-row-expander datagrid-row-collapse').removeClass().addClass('datagrid-row-expander datagrid-row-expand'); 
                $('.datagrid-btable').css('display','none');
            },
            onSelect:function onDataGridSelect(rowIndex, rowData){
$('#common_shoplimit #b_class').val(rowData['b_class']);
$('#common_shoplimit #time_id').val(rowData['time_id']);
$('#common_shoplimit #shop_page').val(rowData['shop_page']);
$('#common_shoplimit #shop_position').val(rowData['shop_position']);
$('#common_shoplimit #item_id').val(rowData['item_id']);
$('#common_shoplimit #item_name').val(rowData['item_name']);
$('#common_shoplimit #bind').val(rowData['bind']);
$('#common_shoplimit #money_type').val(rowData['money_type']);
$('#common_shoplimit #open_type').val(rowData['open_type']);
$('#common_shoplimit #old_money').val(rowData['old_money']);
$('#common_shoplimit #now_money').val(rowData['now_money']);
$('#common_shoplimit #onep_limit').val(rowData['onep_limit']);
$('#common_shoplimit #all_limit').val(rowData['all_limit']);
$('#common_shoplimit #icon_type').val(rowData['icon_type']);
$('#common_shoplimit #lv_limit').val(rowData['lv_limit']);
$('#common_shoplimit #icon_img').val(rowData['icon_img']);
$('#common_shoplimit #icon_title').val(rowData['icon_title']);
$('#common_shoplimit #weight').val(rowData['weight']);
$('#common_shoplimit #id').val(rowData['id']);
$('#common_shoplimit #MONEYID').val(rowData['MONEYID']);
$('#common_shoplimit #AWARDS').val(rowData['AWARDS']);
$('#common_shoplimit #STAGEPRICES').val(rowData['STAGEPRICES']);
$('#common_shoplimit #DESC').val(rowData['DESC']);
$('#common_shoplimit #PRESHOW').val(rowData['PRESHOW']);
},
        " nowrap="false">
    <thead>
        <tr>
            <th data-options="field:'time_id',width:60">日期ID</th>
            <th data-options="field:'shop_page',width:60">店铺分页</th>
            <th data-options="field:'item_id',width:70">物品ID</th>
            <th data-options="field:'item_name',width:90">物品名称</th>
            <th data-options="field:'shop_position',width:60">店铺位置</th>
            <th data-options="field:'money_type',width:60">货币类型</th>
            <th data-options="field:'old_money',width:40">原价</th>
            <th data-options="field:'now_money',width:40">现价</th>
            <th data-options="field:'bind',width:60">是否绑定</th>
            <th data-options="field:'onep_limit',width:80">单人限购数量</th>
            <th data-options="field:'all_limit',width:70">总限购数量</th>
            <th data-options="field:'open_type',width:60">开放类型</th>
            <th data-options="field:'ds_time',width:60">开放时间</th>
            <th data-options="field:'de_time',width:60">结束时间</th>
            <th data-options="field:'icon_type',width:60">标签类型</th>
            <th data-options="field:'lv_limit',width:60">等级限制</th>
            <th data-options="field:'icon_img',width:60">标签图片</th>
            <th data-options="field:'icon_title',width:60">标签标题</th>
            <th data-options="field:'weight',width:40">道具品质</th>
            <th data-options="field:'MONEYID',width:40">基金道具ID</th>
            <th data-options="field:'AWARDS',width:40">礼包信息</th>
            <th data-options="field:'STAGEPRICES',width:40">阶段价格</th>
            <th data-options="field:'DESC',width:40">道具描述</th>
            <th data-options="field:'PRESHOW',width:40">是否显示预售</th>
            <th data-options="field:'mtime',width:70">创建时间</th>
            <th data-options="field:'madmin',width:60">操作人</th>
            <th data-options="field:'del',width:60">删除操作</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">
    function hxiangou_formadd()
    {
        if(confirm("注意：确定？取消？"))
        {
            $.ajax(
            {
                type:'POST',
                url:'xiangou_confs_do.php?action=add',
                data:$('#common_shoplimit').serialize(),
                success:function()
                {
                }
            });
            $('#tt').datagrid({
                url:'xiangou_confs_do.php?action=list',
            });
        }
    }

    function hxiangou_formupdate()
    {
        if(confirm("注意：确定？取消？"))
        {
            $.ajax(
            {
                type:'POST',
                url:'xiangou_confs_do.php?action=update',
                data:$('#common_shoplimit').serialize(),
                success:function()
                {
                }
            });
            $('#tt').datagrid({
                url:'xiangou_confs_do.php?action=list',
            });
        }
    }

    function hxiangou_delwid(id)
    {
        if(confirm("注意：确定？取消？"))
        {
            $.ajax(
            {
                type:'POST',
                url:'xiangou_confs_do.php?action=del&delid='+id,
                success:function()
                {
                }
            });
            $('#tt').datagrid({
                url:'xiangou_confs_do.php?action=list',
            });
        }
    }
</script>
</body>
</html>
