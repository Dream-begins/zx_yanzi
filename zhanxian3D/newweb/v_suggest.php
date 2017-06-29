<?php
    session_start();
    if(strpos(@$_SESSION['priv'],'newweb/v_suggest')==0) exit;
    include "h_define.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo FT_COMMON_TITLE;?></title>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
    <link rel="stylesheet" type="text/css" href="./jquery-easyui-1.4/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="./jquery-easyui-1.4/themes/icon.css">
    <script type="text/javascript" src="./jquery-easyui-1.4/jquery.min.js"></script>
    <script type="text/javascript" src="./jquery-easyui-1.4/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="./jquery-easyui-1.4/locale/easyui-lang-zh_CN.js"></script>
</head>
<body>

<div id="searchSort">
    <table class="tab_search">
        <tr>
            <td>服务器:</td>
            <td><input type="text" id="zone" name="zone" value="" /></td>
            <td>玩家OPENID:</td>
            <td><input type="text" id="acc" name="acc" value="" size='50'></td>
            <td><input type="button" value="搜索" onclick="searchData()" /></td>
           <td><input type="button" value="导出" onclick="location.href='c_suggest.php?action=export'" /></td>
        </tr>
    </table>

    <div id="dataTable" styles="width:100%" ></div>
    
    <form action="c_suggest.php?action=reply" method="post" id="replyop">
        <table>
            <tr>
                服务器:<input type="text" id="usrzone" name="usrzone" value="" />
                玩家OPENID:<input type="text" id="usracc" name="usracc" value="" size='50'>
            </tr>
            <tr>
                <td>邮件标题:</td>
                <td><input type="text" class="easyui-validatebox"  required="true" name="subject" id="subject" value="回复您的建议" size='30' /></td>
            </tr>
            <tr>
                <td>邮件内容</td>
                <td><input type="text" id="mailInfo" name="mailInfo" size='100' required="true" class="easyui-validatebox" value="感谢您对探墓风云的一路支持！" /></td>
            </tr>
            <tr>
                <td><input type="button" value="回复邮件" onclick="mailUsr()"/></td>
            </tr>
        </table>
    </form>
</div>

<script type="text/javascript">

    function searchData()
    { 
        $.parser.parse($(".tab_search"));
        var param = {};
        $.each($("#searchSort"+" .tab_search :input").serializeArray(),function(i,o){
            if($.trim(o.value) != "")
                param[o.name] = o.value;
        });

        _period = param.period;

        $('#searchSort'+" #dataTable").datagrid("clearSelections");
        $('#searchSort'+" #dataTable").datagrid("load",param);
    }

    function mailUsr()
    {
        var row = $('#searchSort #dataTable').datagrid('getSelected');

        if (row)
        {
            if(row.isReply==1)
            {
                window.confirm("本条已回复！");
                return;
            }
            
            var i=window.confirm("确认发送?\n");

            if(i!=0)
            {
                $.post("c_suggest.php?action=reply",{server:row.serverId,openid:row.openid,ID:row.ID,title:$("#replyop #subject").val(),content:$("#replyop #mailInfo").val() },function(result){alert(result)});
            }
        }
        else
        {
            alert("清先选中某条建议");
        }
    }

    $('#searchSort #dataTable').datagrid(
    {
        url:'c_suggest.php?action=list',
        remoteSort: true,
        pagination:true,
        rownumbers:true,
        singleSelect:true,
        onSelect:function onDataGridSelect(rowIndex, rowData)
        {
            $("#searchSort #usrzone").val(rowData["serverId"]);
            $("#searchSort #usracc").val(rowData["openid"]);
        },
        columns:[[
            {field:'ID',title:'ID',width:'50',sortable:true},
            {field:'openid',title:'玩家OPENID',sortable:true,order:"desc"},
            {field:'ip',title:'玩家IP',sortable:true,order:"desc"},
            {field:'serverId',title:'服务器ID',sortable:true},
            {field:'addDate',title:'添加时间',sortable:true,order:"desc"},
            {field:'isReply',title:'是否已回复',sortable:true,formatter:function(val){
                if(val==0)
                    return "否";
                return "是";
            }},
            {field:'content',title:'建议内容',width:'1000',sortable:true},
            {field:'hftime',title:'回复时间',sortable:true},
            {field:'htfontent',title:'回复内容',width:'1000',sortable:true},
        ]]
    });
</script>
</body>
</html>
