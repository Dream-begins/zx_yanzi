<?php
/**
 * Created by PhpStorm.
 * User: chenyunhe
 * Date: 2016/9/22
 * Time: 17:28
 * 上传限购活动配置表
 */

date_default_timezone_set("PRC");
ini_set('display_errors', 1);
error_reporting(0);

ini_set("memory_limit", "100M");

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="newweb/jquery-easyui-1.4/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="newweb/jquery-easyui-1.4/themes/icon.css">
    <script type="text/javascript" src="newweb/jquery-easyui-1.4/jquery.min.js"></script>
    <script type="text/javascript" src="newweb/jquery-easyui-1.4/jquery.easyui.min.js"></script>

</head>
<body >
<h3>上传配置文件</h3>
<div>
<form action="uploadshoplimitconf.php" method="post" enctype="multipart/form-data">
    <input type="file" name="file" value="选择文件" />
    <input type="submit" name="submit" value="上传" />
</form>
</div>

<br/>
<h3>当前表中数据</h3>
<table id="dg" style="width:100%;" data-options=""></table>
<script type="text/javascript">
    datagrid_load('uploadshoplimitconf.php?action=list');
    function datagrid_load(url)
    {
        $('#dg').datagrid({
            title : '',
            url   : url,
            singleSelect : true,
            fitColumns   : true,
            // collapsible  : true, //折叠按钮
            pagination   : true, //翻页栏
            pagePosition : 'bottom',
            method       : 'post',
            //toolbar      : '#toolbar',
            pageSize     : 20,
            pageList     : [5,10,20,30,40,50],
            queryParams: {
            },
            columns:[[
                {field:'ID', title:'编号', halign:'center', align:'center'},
                {field:'PAGE',  title:'PAGE' },
                {field:'OBJID',  title:'OBJID' },
                {field:'OBJNAME',  title:'OBJNAME' },
                {field:'SUPERMARKETPOS',  title:'SUPERMARKETPOS' },
                {field:'MONEYTYPE',  title:'MONEYTYPE' },
                {field:'ORIGINALPRICE',  title:'ORIGINALPRICE' },
                {field:'DISCONTPRICE',  title:'DISCONTPRICE' },
                {field:'ISBIND',  title:'ISBIND' },
                {field:'SINGLECANBUYNUM',  title:'SINGLECANBUYNUM' },
                {field:'TOTALBUYLIMITNUM',  title:'TOTALBUYLIMITNUM' },
                {field:'OPENTYPE',  title:'OPENTYPE' },

                {field:'OPENTTIME',  title:'OPENTTIME' },
                {field:'CLOSETIME',  title:'CLOSETIME' },
                {field:'LIMITEDPURCHASESTARTTIME',  title:'LIMITEDPURCHASESTARTTIME' },
                {field:'LIMITEDPURCHASEENDTIME',  title:'LIMITEDPURCHASEENDTIME' },
                {field:'TAGTYPE',  title:'TAGTYPE' },
                {field:'NEEDVIPLEVEL',  title:'NEEDVIPLEVEL' },
                {field:'INDEXID',  title:'INDEXID' },
                {field:'ICON',  title:'ICON' },
                {field:'TITLE',  title:'TITLE' },
                {field:'WEIGHT',  title:'WEIGHT' },
                {field:'MONEYID',title:'基金ID',width:60,sortable:true},
                {field:'AWARDS',title:'礼包信息',width:100,sortable:true},
                {field:'STAGEPRICES',title:'阶段价格',width:100,sortable:true},
                {field:'PRESHOW',title:'是否显示预售',width:100,sortable:true},
                {field:'DESC',title:'道具描述',width:100,sortable:true},

            ]],
        });
    }
</script>
<br/>
<form action="uploadshoplimitconf.php?action=sub" method="post">
    服务器：<input type="text" name="Zone" />&nbsp;设置时间:<input class="easyui-datebox" id="from" name="starttime"><br/>
    <input type="submit" name="sub" value="应用"/>
</form>

<h3>服务器格式：</h3>
<h4>连续服：使用"-"分割，例如1-10</h4>
<h4>非连续服：使用英文","分割，例如1,3,5</h4>

</body>
</html>
