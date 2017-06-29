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
        <table>
            <tr>
                <td>开始服:</td>
                <td><input type="text" class="easyui-validatebox"  required="true" name="startid" id="startid" /></td>
                <td>结束服:</td>
                <td><input type="text" class="easyui-validatebox"  required="true" name="endid" id="endid" /></td>
                <td>活动类型:</td>
                <td>
                    <select name="acttype1" id="acttype1">
                    <?php 
                    foreach ($FT_GAMEACTIVITY_ID_TO_NAME_ARR as $key => $value)
                    {
                        echo "<option value ='".$key."'>".$value."</option>";
                    }
                    ?>
                    </select>
                </td>
                <td>活动编号:</td>
                <td><input type="text" class="easyui-validatebox"  required="true" name="actid1" id="actid1" /></td>
                <td>活动状态:</td>
                <td>
                    <select name="actstate1" id="actstate1">
                        <option value ="0">等待开启</option>
                        <option value ="1">进行中</option>
                        <option value="2">领奖中</option>
                        <option value="3">已过期</option>
                    </select>
                </td>
                <td>
                    开启时间:<input required="true" style="width:150px" id="stime" name="stime">
                </td>
                <td><input type="button" value="活动查询" onclick="queryGameAct()"/></td>
            </tr>
        </table>
    </div>
    
    <div id="gameactopt">
        <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="updateGameAct()">立即过期</a>
    </div>

    <div>活动列表:</div>

    <div id="dataTable" styles="width:80%" ></div>
</div>

<br />

<div>
    <table>
        <td>
            <form action="dogameactivity.php" method="post"   id="gameactform">
                <fieldset style="width:650px">
                    <legend>活动详情</legend>
                    <table>
                        <tr>
                            <td>服务器ID:</td>
                            <td><input type="text" class="easyui-validatebox" name="actserverid" id="actserverid" readonly style="border-style:none" /></td>
                        </tr>
                        <tr>
                            <td>状态:</td>
                            <td><input type="text" class="easyui-validatebox" name="actstatus" id="actstatus" readonly style="border-style:none" /></td>
                        </tr>
                        <tr>
                            <td>活动类型:</td>
                            <td>
                                <select name="acttype" id="acttype">
                                <?php
                                foreach ($FT_GAMEACTIVITY_ID_TO_NAME_ARR as $key => $value)
                                {
                                    echo "<option value ='".$key."'>".$value."</option>";
                                }
                                ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>活动编号:</td>
                            <td><input type="text" class="easyui-validatebox"  required="true" name="actid" id="actid" /></td>
                        </tr>
                        <tr>
                            <td>开启时间:</td>
                            <td><input required="true" style="width:150px" id="starttime" name="starttime"></td>
                        </tr>
                        <tr>
                            <td>领奖时间:</td>
                            <td><input required="true" style="width:150px" name="endtime" id="endtime"></td>
                        </tr>
                        <tr>
                            <td>过期时间:</td>
                            <td><input required="true" style="width:150px" name="expiretime" id="expiretime"></td>
                        </tr>
                        <tr>
                            <td>活动图标:</td>
                            <td><input type="text" required="true" style="width:150px" maxlength=85 name="iconid" id="iconid"></td>
                        </tr>
                        <tr>
                            <td>标签图标:</td>
                            <td><input required="true" style="width:150px" name="tabid" id="tabid"></td>
                        </tr>
                        <tr>
                            <td>标签标题:</td>
                            <td><input type="text" required="true" style="width:150px" maxlength=85 name="tabinfo" id="tabinfo"></td>
                        </tr>
                        <tr>
                            <td>标签排序位置:</td>
                            <td><input required="true" style="width:150px" name="tabtype" id="tabtype"></td>
                        </tr>
                        <tr>
                            <td>描述背景图:</td>
                            <td><input required="true" style="width:150px" name="picid" id="picid"></td>
                        </tr>
                        <tr>
                            <td>开放平台:</td>
                            <td><input required="true" style="width:150px" name="platid" id="platid"></td>
                        </tr>
                        <tr>
                            <td>活动描述:</td>
                            <td><input type="text" required="true" style="width:500px" maxlength=1000 name="picinfo" id="picinfo"></td>
                        </tr>
                        <tr>
                            <td>
                                <a href="javascript:void(0)" class="easyui-linkbutton" onclick="submitDelAll()">全部过期</a>
                            </td>
                            <td>
                                <a href="javascript:void(0)" class="easyui-linkbutton" onclick="submitUpdateAll()">全部更新</a>
                                <!-- a href="javascript:void(0)" class="easyui-linkbutton" onclick="submitForm('update')">更新活动</a--> 
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </form>
        </td>
    </table>
</div>

<script type="text/javascript">
var ActType = [
    <?php 
    foreach ($FT_GAMEACTIVITY_ID_TO_NAME_ARR as $key => $value)
    {
        echo "{ id: ".$key.", text: '".$value."' },";
    }
    ?>
];

var StatusType = [{ id: 0, text: '等待开启' }, { id: 1, text: '进行中'}, { id: 2, text: '领奖中'}, { id: 3, text: '已过期'}];

function queryGameAct()
{
    var startzone=$("#gameact #startid").val();
    var endzone=$("#gameact #endid").val();
    var stime=$("#stime").datebox("getValue");
    var minzone;
    var maxzone;

    if(startzone>endzone)
    {
        minzone=endzone;
        maxzone=startzone;
    }else{
        minzone=startzone;
        maxzone=endzone;
    }
    
    var acttypevalue=$("#gameact #acttype1").val();
    var actidvalue=$("#gameact #actid1").val();
    var actstateV=$("#gameact #actstate1").val();

    $('#gameact #dataTable').datagrid({
        url:'queryAllGameAct.php',
        queryParams:{zone1:minzone,zone2:maxzone,acttype2:acttypevalue,actid2:actidvalue,actstate2:actstateV,stime:stime},
        remoteSort: false,
        pagination:true,
        rownumbers:true,
        singleSelect:true,
        toolbar:"#gameactopt",

        onSelect:function onDataGridSelect(rowIndex, rowData)
        {
            $("#gameactform #zone").val(rowData["SID"]);
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
            {field:'SID',title:'服务器ID',width:100,sortable:true},
            {field:'TYPE',title:'类型',width:100,sortable:true,
                formatter:function(val,rec)
                {
                    for (var i = 0, l = ActType.length; i < l; i++)
                    {
                        var g = ActType[i];
                        if (g.id == val) return g.text;
                    }
                    return val;
                }
            },
            {field:'ACTIVITYID',title:'活动编号',width:100,sortable:true},
            {field:'ts',title:'开启时间',width:200,sortable:true},
            {field:'te',title:'领奖时间',width:200,sortable:true}, 
            {field:'tp',title:'过期时间',width:200,sortable:true},
            {field:'ICONID',title:'活动图标',width:100,sortable:true},
            {field:'TABID',title:'标签图标',width:100,sortable:true},
            {field:'TABINFO',title:'标签标题',width:100,sortable:true},
            {field:'TABTYPE',title:'标签排序位置',width:100,sortable:true},
            {field:'PICID',title:'描述背景图',width:100,sortable:true},
            {field:'PICINFO',title:'活动描述',width:100,sortable:true},
            {field:'PLATID',title:'开放平台',width:100,sortable:true},
            {field:'STATUS',title:'状态',width:100,sortable:true,
                formatter:function(val,rec)
                {
                    for (var i = 0, l = StatusType.length; i < l; i++) 
                    {
                        var g = StatusType[i];
                        if (g.id == val) return g.text;
                    }
                    
                    return val;
                }
            },
        ]]
    });
    
    $.parser.parse($(".tab_search"));
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
    
    if (/(y+)/.test(format)) format = format.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));  

    for (var k in o) 
        if (new RegExp("(" + k + ")").test(format)) format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? o[k] : ("00" + o[k]).substr(("" + o[k]).length));

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

    function formatDateText(date) 
    {
        return date.formatDate("yyyy-MM-dd hh:mm:ss");
    }

    function formatDateText2(date)
    {
        return date.formatDate("yyyy-MM-dd 00:00:00");
    }

    function parseDate(dateStr)
    {
        var regexDT = /(\d{4})-?(\d{2})?-?(\d{2})?\s?(\d{2})?:?(\d{2})?:?(\d{2})?/g;
        var matchs = regexDT.exec(dateStr);

        if(matchs==null) return new Date();
        var date = new Array();
        
        for (var i = 1; i < matchs.length; i++)
        {
            if (matchs[i]!=undefined)
                date[i] = matchs[i];
            else
            {
                if (i<=3)
                    date[i] = '01';
                else
                    date[i] = '00';  
            }
        }
        
        return new Date(date[1], date[2]-1, date[3], date[4], date[5],date[6]);
    }

    $("#starttime,#endtime,#expiretime").datetimebox(
    {
        showSeconds:true,
        formatter: formatDateText,
        parser: parseDate
    });

    $("#stime").datetimebox(
    {
        showSeconds:true,
        formatter: formatDateText2,
        parser: parseDate
    });

    $('#gameactform').form({
        url:'dogameactivity.php',
        onSubmit:function()
        {
            return $(this).form('validate');
        },
        success:function(data)
        {
            alert(data);
        },
        error:function()
        {
            alert("ss");
        }
    });
});

function updateGameAct()
{               
    var row = $('#gameact #dataTable').datagrid('getSelected');

    if (row)
    {
        var i=window.confirm("确认删除活动?\n"+row.SID+" 服，类型为："+row.TYPE+"活动编号为:"+row.ACTIVITYID+"的活动？");
        
        if(i!=0)
        {
            $.post("updateGameActivity.php", { zone:row.SID, record:row.ID }, function(data){alert(data);})
        }
    }
    else
    {
        alert("清先选中活动列表中的活动");
    }
}

function submitDelAll()
{
    var startzone=$("#gameact #startid").val();
    var endzone=$("#gameact #endid").val();
    var stime=$("#stime").datebox("getValue");
    var minzone;
    var maxzone;

    if(startzone>endzone)
    {
        minzone=endzone;
        maxzone=startzone;
    }else
    {
        minzone=startzone;
        maxzone=endzone;
    }

    var acttypevalue=$("#gameact #acttype1").val();
    var actidvalue=$("#gameact #actid1").val();
    var actstate=$("#gameact #actstate1").val();

    if(!minzone||!maxzone||!acttypevalue||!actidvalue)
    {
        alert("四个参数一个都不能少");
        return;
    }

    var i=window.confirm("确认删除"+minzone+" 服到"+maxzone+"服，类型为："+acttypevalue+"活动编号为:"+actidvalue+ "开始时间为" + stime +"的活动？");

    if(i!=0)
    {
        $.post("opAllGameActivity.php?action=del",{zone1:minzone,zone2:maxzone,acttype2:acttypevalue,actid2:actidvalue,actstate2:actstate,stime:stime},function(data){alert(data);})
    }
}

function submitUpdateAll()
{
    var startzone=$("#gameact #startid").val();
    var endzone=$("#gameact #endid").val();
    var stime=$("#stime").datebox("getValue");
    var minzone;
    var maxzone;
    
    if(startzone>endzone)
    {
        minzone=endzone;
        maxzone=startzone;
    }else{
        minzone=startzone;
        maxzone=endzone;
    }

    var acttypevalue=$("#gameact #acttype1").val();
    var actidvalue=$("#gameact #actid1").val();
    var actstate=$("#gameact #actstate1").val();

    if(!minzone||!maxzone||!acttypevalue||!actidvalue)
    {
        alert("四个参数一个都不能少");
        return;
    }
    
    if(confirm("确认更新"+minzone+" 服到"+maxzone+"服，类型为："+acttypevalue+"活动编号为:"+actidvalue+"开始时间为"+stime+"的活动？"))
    {
        $.ajax({
            type:'POST',
            url:'opAllGameActivity.php?action=update&'+"zone1="+minzone+"&zone2="+maxzone+"&acttype2="+acttypevalue+"&actid2="+actidvalue+"&actstate2="+actstate+"&stime="+stime,
            data:$('#gameactform').serialize(),
            success:function(data)
            {
                alert(data);
            }
        });
    }
}
</script>
</body>
</html>
