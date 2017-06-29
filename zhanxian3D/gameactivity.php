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
</head>
<body >

<div id="gameact">
    <div>
        <table class="tab_search">
            <tr>
                <td>游戏服:</td>
                <td><input type="text" class="easyui-validatebox"  required="true" name="serverid" id="serverid" /></td>
                <td><input type="button" value="活动查询" onclick="queryGameAct('cur')"/></td>
                <td><input type="button" value="上一服" onclick="queryGameAct('pre')"/></td>
                <td><input type="button" value="下一服" onclick="queryGameAct('next')"/></td>
                <td><input type="button" value="过期活动查询" onclick="queryGameAct('over')"/></td>
                <td><input type="checkbox" name='pingbi' id='pingbi'/>屏蔽</td>
            </tr>
        </table>
    </div>
    
    <div id="gameactopt">
        <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="updateGameAct()">立即过期(某个活动)</a> 
        <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="expireAllGameAct()">一键活动过期(所有)</a> 
    </div>

    <div>活动列表:</div>

    <div id="dataTable" styles="width:80%" ></div>
</div>

<br />

<table>
    <td>
        <form  action="dogameactivity.php" method="post"   id="gameactform">
            <fieldset style="width:650px">
                <legend>增加活动</legend>
                <table>
                    <tr><td>ID:</td><td><input type="text" class="easyui-validatebox" name="actdbid" id="actdbid" readonly style="border-style:none" /></td></tr>
                    <tr><td>状态:</td><td><input type="text" class="easyui-validatebox" name="actstatus" id="actstatus" readonly style="border-style:none" /></td></tr>	
    <tr><td>游戏服:</td><td><input required="true" style="width:150px" id="zone1" name="zone1">---<input required="true" style="width:150px" id="zone2" name="zone2"><font color='red'>第一行必填</font></td></tr>
    <tr><td>游戏服:</td><td><input required="true" style="width:150px" id="zone3" name="zone3">---<input required="true" style="width:150px" id="zone4" name="zone4"></td></tr>
    <tr><td>游戏服:</td><td><input required="true" style="width:150px" id="zone5" name="zone5">---<input required="true" style="width:150px" id="zone6" name="zone6"></td></tr>
    <tr><td>游戏服:</td><td><input required="true" style="width:150px" id="zone7" name="zone7">---<input required="true" style="width:150px" id="zone8" name="zone8"></td></tr>
    <tr><td>游戏服:</td><td><input required="true" style="width:150px" id="zone9" name="zone9">---<input required="true" style="width:150px" id="zone10" name="zone10"></td></tr>

  <tr><td>活动类型:</td><td><select name="acttype" id="acttype">
                        <?php 
                            foreach ($FT_GAMEACTIVITY_ID_TO_NAME_ARR as $key => $value)
                            {
                                echo "<option value ='".$key."'>".$value."</option>";
                            }
                        ?>
                    </select></td></tr>
                    <tr><td>活动编号:</td><td><input type="text" class="easyui-validatebox"  required="true" name="actid" id="actid" /></td></tr>
                    <tr><td>开启时间:</td><td><input	required="true" style="width:150px" id="starttime" name="starttime"></td></tr>
                    <tr><td>领奖时间:</td><td><input   required="true" style="width:150px" name="endtime" id="endtime"></td></tr>
                    <tr><td>过期时间:</td><td><input   required="true" style="width:150px" name="expiretime" id="expiretime"></td></tr>
                    <tr><td>活动图标:</td><td><input   type="text"  required="true" style="width:150px" maxlength=85 name="iconid" id="iconid"></td></tr>	
                    <tr><td>标签图标:</td><td><input   required="true" style="width:150px" name="tabid" id="tabid"></td></tr>
                    <tr><td>标签标题:</td><td><input  type="text"  required="true" style="width:150px" maxlength=85 name="tabinfo" id="tabinfo"></td></tr>
                    <tr><td>标签排序位置:</td><td><input   required="true" style="width:150px" name="tabtype" id="tabtype"></td></tr>
                    <tr><td>描述背景图:</td><td><input   required="true" style="width:150px" name="picid" id="picid"></td></tr>
                    <tr><td>开放平台:</td><td><input   required="true" style="width:150px" name="platid" id="platid"></td></tr>
                    <tr><td>活动描述:</td><td><input   type="text"  required="true" style="width:500px" maxlength=1000 name="picinfo" id="picinfo"></td></tr>
                    <tr>
                        <td>
                            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="submitForm('add')">新增活动</a></td><td>
                            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="submitForm('update')">更新活动</a>
                        </td>
                    </tr>
                    <td><input type="hidden" style="width:1px" name="isadd" id="isadd"></td>
                </table>
            </fieldset>
        </form>
    </td>
    <td>活动描述：<span id="inputContent"></span></td>
</table>

<script type="text/javascript">

immediately();

function immediately()
{ 
    var element = document.getElementById("picinfo"); 
    if("\v"=="v") 
        element.onpropertychange = webChange; 
    else
        element.addEventListener("input",webChange,false);

    function webChange()
    { 
        if(element.value)
        {
            var re=/\\r/gi;
            document.getElementById("inputContent").innerHTML = element.value.replace(re,"<br/>");
        }
    }
}

var ActType = [
    <?php 
        foreach ($FT_GAMEACTIVITY_ID_TO_NAME_ARR as $key => $value)
        {
            echo "{ id: ".$key.", text: '".$value."' },";
        }
    ?>
];

var StatusType = [{ id: 0, text: '等待开启' }, { id: 1, text: '进行中'}, { id: 2, text: '领奖中'}, { id: 3, text: '已过期'}];

function queryGameAct(whichzone)
{
    var qzone=$("#gameact #serverid").val();
    var pingbic = document.getElementById('pingbi');
    var pingbi = pingbic.checked;
    if (whichzone == 'next')
    {
        qzone=Number(qzone)+1;
        $("#gameact #serverid").val(qzone);
    }else if(whichzone == 'pre' && Number(qzone) > 1)
    {
        qzone=Number(qzone)-1;
        $("#gameact #serverid").val(qzone);
    }

    $.parser.parse($(".tab_search"));

    $('#gameact #dataTable').datagrid({
        url:'querygameact.php',
        queryParams:{zone:qzone,opName:whichzone,pingbi:pingbi},
        remoteSort: false,
        pagination:true,
        rownumbers:true,
        singleSelect:true,
        toolbar:"#gameactopt",
        onSelect:function onDataGridSelect(rowIndex, rowData)
        {
            $("#gameactform #zone").val($("#gameact #serverid").val());
            $("#gameactform #acttype").val(rowData["TYPE"]);
            $("#gameactform #actdbid").val(rowData["ID"]);
            $("#gameactform #actid").val(rowData["ACTIVITYID"]);
            $("#gameactform #starttime").datebox("setValue",new Date().formatDate(rowData["ts"]));
            $("#gameactform #endtime").datebox("setValue",new Date().formatDate(rowData["te"]));
            $("#gameactform #expiretime").datebox("setValue",new Date().formatDate(rowData["tp"]));
            $("#gameactform #iconid").val(rowData["ICONID"]);
            $("#gameactform #tabid").val(rowData["TABID"]);
            $("#gameactform #tabinfo").val(rowData["TABINFO"]);
            $("#gameactform #tabtype").val(rowData["TABTYPE"]);
            $("#gameactform #picid").val(rowData["PICID"]);
            $("#gameactform #picinfo").val(rowData["PICINFO"]);
            $("#gameactform #platid").val(rowData["PLATID"]);

            var StatusType=['等待开启','进行中','结束领奖中','过期'];
            $("#gameactform #actstatus").val(StatusType[rowData["STATUS"]]);
        },
        columns:[[
            {field:'ID',title:'ID',width:100,sortable:true},
            {field:'TYPE',title:'类型',width:100,sortable:true,
                formatter:function(val,rec)
                {
                    for (var i = 0, l = ActType.length; i < l; i++) 
                    {
                        var g = ActType[i];
                        if (g.id == val) 
                            return g.text;
                    }
                    return val;
                }
            },
            {field:'ACTIVITYID',title:'活动编号',width:100,sortable:true},
            {field:'ts',title:'开启时间',width:200,sortable:true},
            {field:'te',title:'领奖时间',width:200,sortable:true},
            {field:'tp',title:'过期时间',width:200,sortable:true},
            {field:'STATUS',title:'状态',width:100,sortable:true,
                formatter:function(val,rec)
                {
                    for (var i = 0, l = StatusType.length; i < l; i++) 
                    {
                        var g = StatusType[i];
                        if (g.id == val) 
                            return g.text;
                    }
                    return val;
                }
            },
            {field:'ICONID',title:'活动图标',width:100,sortable:true},
            {field:'TABID',title:'标签图标',width:100,sortable:true},
            {field:'TABINFO',title:'标签标题',width:100,sortable:true},
            {field:'TABTYPE',title:'标签排序位置',width:100,sortable:true},
            {field:'PICID',title:'描述背景图',width:100,sortable:true},
            {field:'PICINFO',title:'活动描述',width:100,sortable:true},
            {field:'PLATID',title:'开放平台',width:100,sortable:true},
        ]]
    });
}
</script>

<script>
Date.prototype.formatDate = function (format)
{  
    var o = {
        "M+": this.getMonth() + 1,
        "d+": this.getDate(),
        "h+": this.getHours(),
        "m+": this.getMinutes(),
        "s+": this.getSeconds(),
        "q+": Math.floor((this.getMonth() + 3) / 3),
        "S": this.getMilliseconds()
    }

    if (/(y+)/.test(format)) format = format.replace(RegExp.$1,(this.getFullYear() + "").substr(4 - RegExp.$1.length));

    for (var k in o) 
        if (new RegExp("(" + k + ")").test(format))
            format = format.replace(RegExp.$1,RegExp.$1.length == 1 ? o[k] : ("00" + o[k]).substr(("" + o[k]).length));

    return format;
}

$(function()
{
    $.fn.datebox.defaults.formatter = function(date)
    {
        var y = date.getFullYear();
        var m = date.getMonth()+1;
        var d = date.getDate();
        return y+"-"+m+'-'+d +" "+ date.getHours()+":"+date.getMinutes()+":00";
    }

    function formatDateText1(date)
    {
        return date.formatDate("yyyy-MM-dd 00:00:00"); 
    }

    function formatDateText2(date)
    {
        return date.formatDate("yyyy-MM-dd 23:59:59"); 
    }

    function parseDate(dateStr)
    {
        var regexDT = /(\d{4})-?(\d{2})?-?(\d{2})?\s?(\d{2})?:?(\d{2})?:?(\d{2})?/g;  
        var matchs = regexDT.exec(dateStr);  
        
        if(matchs==null)
            return new Date();

        var date = new Array();  
        
        for (var i = 1; i < matchs.length; i++)
        {  
            if (matchs[i]!=undefined) {
                date[i] = matchs[i];
            } else {
                if (i<=3) {
                    date[i] = '01';
                } else {
                    date[i] = '00';
                }
            }
        }

        return new Date(date[1], date[2]-1, date[3], date[4], date[5],date[6]);  
    }  

    $("#endtime,#expiretime").datebox({showSeconds:true, formatter: formatDateText2, parser: parseDate });
    $("#starttime").datebox({showSeconds:true, formatter: formatDateText1, parser: parseDate });

    $('#gameactform').form({
        url:'dogameactivity.php',
        onSubmit:function(){
            return $(this).form('validate');
        },
        success:function(data){
            alert(data);
        },
        error:function(){
            alert("ss");
        }
    });

});

submiting=false;

function submitForm(act){
    if(!$("#gameactform #zone1").val())
    {
        alert("清输入开始游戏服...");
        return;
    }
    if(!$("#gameactform #zone2").val())
    {
        alert("清输入结束游戏服...");
        return;
    }

    $('#gameactform #planid').val(0);
    $('#gameactform #planinfo').val("");

    if(act == 'add')
        $('#gameactform #isadd').val(1);
    else if(act =='update')
    {
        var i=window.confirm("确认更新活动?\n"+$("#gameactform #zone1").val()+"服到"+ $("#gameactform #zone2").val()+"服，活动"+$("#gameactform #actid").val()+"。数据库ID："+$("#gameactform #actdbid").val());
        if(i==0)
            return;
        $('#gameactform #isadd').val(2);
    }
    if(submiting)
        return;
    
    submiting = true;
    
    $('#gameactform').form('submit',{
        success:function(data){alert(data);submiting = false;},
        error:function(){alert("ss");submiting = false;}
    });
}

function updateGameAct()
{  		 	
    if(!$("#serverid").val())
    {
        alert("清输入游戏服...");
        return;
    }

    var row = $('#gameact #dataTable').datagrid('getSelected');
    if (row)
    {
        var i=window.confirm("确认删除活动?\n"+$("#serverid").val()+" 服，ID："+row.ID);
        if(i!=0)
        {
            $.post("updateGameActivity.php", { zone:$("#serverid").val(), record:row.ID }, function(data){alert(data);})
        }
    }
    else
    {
        alert("清先选中活动列表中的活动");
    }
}

function expireAllGameAct(){
    if(!$("#serverid").val())
    {
        alert("清输入游戏服...");
        return;
    }

    var i=window.confirm("确认删除活动?\n"+$("#serverid").val()+"服");
    if(i!=0)
    {
        $.post("updateGameActivity.php", { zone:$("#serverid").val(), allact:1}, function(data){alert(data);})
    }
}

function onDataGridSelect(rowIndex, rowData)
{
    $("#gameactform #zone").val($("#gameact #serverid").val());
    $("#gameactform #acttype").val(rowData["TYPE"]);
    $("#gameactform #actdbid").val(rowData["ID"]);
    $("#gameactform #actid").val(rowData["ACTIVITYID"]);
    $("#gameactform #starttime").datebox("setValue",new Date().formatDate(rowData["ts"]));
    $("#gameactform #endtime").datebox("setValue",new Date().formatDate(rowData["te"]));
    $("#gameactform #expiretime").datebox("setValue",new Date().formatDate(rowData["tp"]));
    $("#gameactform #iconid").val(rowData["ICONID"]);
    $("#gameactform #tabid").val(rowData["TABID"]);
    $("#gameactform #tabinfo").val(rowData["TABINFO"]);
    $("#gameactform #tabtype").val(rowData["TABTYPE"]);
    $("#gameactform #picid").val(rowData["PICID"]);
    $("#gameactform #picinfo").val(rowData["PICINFO"]);
    $("#gameactform #platid").val(rowData["PLATID"]);
    var StatusType=['等待开启','进行中','结束领奖中','过期'];
    $("#gameactform #actstatus").val(StatusType[rowData["STATUS"]]);
}
</script>
</body>
</html>

