<?php
    include_once "checklogin.php";
    include_once "newweb/h_header.php";
    $priv_arr = explode(',', $_SESSION['priv']);
    if( !in_array('banandgonggao', $priv_arr) ) header('Location: index.php');
    date_default_timezone_set("PRC");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo FT_COMMON_TITLE;?></title>
    <link rel="stylesheet" type="text/css" href="res/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="res/themes/icon.css">
    <script type="text/javascript" src="res/jquery.min.js"></script>
    <script type="text/javascript" src="res/jquery.easyui.min.js"></script>
    <style type="text/css">
        div{font-size: 10px}
    </style>
</head>
<body>
<fieldset style='width:1200px'>
    <legend align="center">禁言</legend>
    <form class="easyui-form" id='gm_form' method="post" >
        <table align="left">
            <tr>
                <td>角色名:</td>
                <td><input name='gm_code' id='gm_code'></td>
                <td align='center'>区设置:</td>
                <td><input name='gm_zones' id='gm_zones'></textarea></td>
                <td> <a href="javascript:void(0)" class='easyui-linkbutton' onclick='GMcode_do3()'>封号</a></td>
                <td> <a href="javascript:void(0)" class='easyui-linkbutton' onclick='GMcode_do4()'>解除封号</a></td>
                <td> <a href="javascript:void(0)" class='easyui-linkbutton' onclick='GMcode_do1_1()'>禁言1小时</a></td>
                <td> <a href="javascript:void(0)" class='easyui-linkbutton' onclick='GMcode_do1_24()'>禁言1天</a></td>
                <td> <a href="javascript:void(0)" class='easyui-linkbutton' onclick='GMcode_do1_10()'>禁言10天</a></td>
                <td> <a href="javascript:void(0)" class='easyui-linkbutton' onclick='GMcode_do2()'>解除禁言</a></td>
                <td> <a href="javascript:void(0)" class='easyui-linkbutton' onclick='GMcode_do20()'>踢人</a></td>
                <td> <a href="javascript:void(0)" class='easyui-linkbutton' onclick='GMcode_do21()'>取消密码保护</a></td>
            </tr>
        </table>
    </form>
</fieldset>
<br>
<fieldset style='width:1200px'>
    <legend align="center">发放公告</legend>
    <form class="easyui-form" id='gm_form2' method="post" >
        <table align="left">
            <tr>
                <td>公告内容:</td>
                <td><textarea name='gm_code' id='gm_code2'></textarea></td>
                <td align='center'>区设置:</td>
                <td><input name='gm_zones' id='gm_zones2'></textarea></td>
                <td> <a href="javascript:void(0)" class='easyui-linkbutton' onclick='GMcode_do5()'>发送</a></td>
            </tr>
        </table>
        发放多个区 请填  1到100和102区demo =>  1-100,102
    </form>
</fieldset>
<br>
<fieldset style='width:1200px'>
    <legend align="center">加载礼包、活动</legend>
    <form class="easyui-form" id='gm_form3' method="post" >
        <table align="left">
            <tr>
                <td align='center'>区设置:</td>
                <td><input name='gm_zones' id='gm_zones3'></textarea></td>
                <td> <a href="javascript:void(0)" class='easyui-linkbutton' onclick='GMcode_do50()'>加载活动</a></td>
                <td> <a href="javascript:void(0)" class='easyui-linkbutton' onclick='GMcode_do51()'>加载礼包</a></td>
            </tr>
        </table>
        发放多个区 请填  1到100和102区demo =>  1-100,102  
    </form>
</fieldset>

<table>
    <tr>
        <td>
            <fieldset style='width:450px;'>
                <legend>执行Log</legend>
                <div id='log_text' style="OVERFLOW-Y: auto; OVERFLOW-X:auto; height:400px; width:450px"></div>
            </fieldset>
        </td>
        <td>
            <fieldset style='width:450px;'>
                <legend><input type='checkbox' id='monitor_check' name='monitor_check' onchange='monitor_list()'><font id='titlefont'>监控反馈</font></legend>
                <div id='monitor_text' style="OVERFLOW-Y: auto; OVERFLOW-X:auto; height:400px; width:450px"></div>
            </fieldset>        
        </td>
    </tr>
    <tr>
        <td><a href="javascript:void(0)" onclick='clear_zx_log()'>清空执行Log</a></td>
        <td><a href="javascript:void(0)" onclick='clear_jk_log()'>清空监控Log</a></td>
    </tr>
</table>

<input type='hidden' name='FlagId' id='FlagId' value=''>

<script type="text/javascript">

setInterval(monitor_list, 2000);

function GMcode_do1_1()
{
    if(confirm("注意：确定？取消？"))
    {
        var gm_code = $("#gm_code").val();
        var gm_zones = $("#gm_zones").val();

        if(gm_code.length == 0 || gm_zones.length == 0)
        {
            gm_add_log('<font color=red>参数不能为空</font>');
            return false;
        }

        var do_log = '========================================================<br/>'+'[命令]'+gm_code + ' [区服]' + gm_zones + ' </br>请等待。。。。。。';

        gm_add_log(do_log);

        $.ajax({
            type:'POST',
            url:'dobanagonggao.php?action=jinyan&timesss=1',
            data:$('#gm_form').serialize(),
            success:function(result)
            {
                gm_add_log(result);
                $("#monitor_check").prop("checked",true);
                monitor_list();
            }
        });
    }
}

function GMcode_do1_24()
{
    if(confirm("注意：确定？取消？"))
    {
        var gm_code = $("#gm_code").val();
        var gm_zones = $("#gm_zones").val();

        if(gm_code.length == 0 || gm_zones.length == 0)
        {
            gm_add_log('<font color=red>参数不能为空</font>');
            return false;
        }

        var do_log = '========================================================<br/>'+'[命令]'+gm_code + ' [区服]' + gm_zones + ' </br>请等待。。。。。。';
        
        gm_add_log(do_log);
        
        $.ajax({
            type:'POST',
            url:'dobanagonggao.php?action=jinyan&timesss=24',
            data:$('#gm_form').serialize(),
            success:function(result)
            {
                gm_add_log(result);
                $("#monitor_check").prop("checked",true);
                monitor_list();
            }
        });
    }
}

function GMcode_do1_10()
{
    if(confirm("注意：确定？取消？"))
    {
        var gm_code = $("#gm_code").val();
        var gm_zones = $("#gm_zones").val();
        
        if(gm_code.length == 0 || gm_zones.length == 0)
        {
            gm_add_log('<font color=red>参数不能为空</font>');
            return false;
        }
    
        var do_log = '========================================================<br/>'+'[命令]'+gm_code + ' [区服]' + gm_zones + ' </br>请等待。。。。。。';
        
        gm_add_log(do_log);
        $.ajax({
            type:'POST',
            url:'dobanagonggao.php?action=jinyan&timesss=240',
            data:$('#gm_form').serialize(),
            success:function(result)
            {
                gm_add_log(result);
                $("#monitor_check").prop("checked",true);
                monitor_list();
            }
        });
    }
}

function GMcode_do2()
{
    if(confirm("注意：确定？取消？"))
    {
        var gm_code = $("#gm_code").val();
        var gm_zones = $("#gm_zones").val();
        
        if(gm_code.length == 0 || gm_zones.length == 0)
        {
            gm_add_log('<font color=red>参数不能为空</font>');
            return false;
        }
    
        var do_log = '========================================================<br/>'+'[命令]'+gm_code + ' [区服]' + gm_zones + ' </br>请等待。。。。。。';
    
        gm_add_log(do_log);
    
        $.ajax({
            type:'POST',
            url:'dobanagonggao.php?action=jiechujinyan',
            data:$('#gm_form').serialize(),
            success:function(result)
            {
                gm_add_log(result);
                $("#monitor_check").prop("checked",true);
                monitor_list();
            }
        });
    }
}

function GMcode_do3()
{
    if(confirm("注意：确定？取消？"))
    {
        var gm_code = $("#gm_code").val();
        var gm_zones = $("#gm_zones").val();

        if(gm_code.length == 0 || gm_zones.length == 0)
        {
            gm_add_log('<font color=red>参数不能为空</font>');
            return false;
        }

        var do_log = '========================================================<br/>'+'[命令]'+gm_code + ' [区服]' + gm_zones + ' </br>请等待。。。。。。';

        gm_add_log(do_log);
        
        $.ajax({
            type:'POST',
            url:'dobanagonggao.php?action=fenghao',
            data:$('#gm_form').serialize(),
            success:function(result)
            {
                gm_add_log(result);
                $("#monitor_check").prop("checked",true);
                monitor_list();
            }
        });
    }
}

function GMcode_do4()
{
    if(confirm("注意：确定？取消？"))
    {
        var gm_code = $("#gm_code").val();
        var gm_zones = $("#gm_zones").val();
        
        if(gm_code.length == 0 || gm_zones.length == 0)
        {
            gm_add_log('<font color=red>参数不能为空</font>');
            return false;
        }

        var do_log = '========================================================<br/>'+'[命令]'+gm_code + ' [区服]' + gm_zones + ' </br>请等待。。。。。。';
        
        gm_add_log(do_log);
        
        $.ajax({
            type:'POST',
            url:'dobanagonggao.php?action=jiechufenghao',
            data:$('#gm_form').serialize(),
            success:function(result)
            {
                gm_add_log(result);
                $("#monitor_check").prop("checked",true);
                monitor_list();
            }
        });
    }
}

function GMcode_do5()
{
    if(confirm("注意：确定？取消？"))
    {
        var gm_code = $("#gm_code2").val();
        var gm_zones = $("#gm_zones2").val();
        
        if(gm_code.length == 0 || gm_zones.length == 0)
        {
            gm_add_log('<font color=red>参数不能为空</font>');
            return false;
        }
        
        var do_log = '========================================================<br/>'+'[命令]'+gm_code + ' [区服]' + gm_zones + ' </br>请等待。。。。。。';
        
        gm_add_log(do_log);
        
        $.ajax({
            type:'POST',
            url:'dobanagonggao.php?action=gonggao',
            data:$('#gm_form2').serialize(),
            success:function(result)
            {
                gm_add_log(result);
                $("#monitor_check").prop("checked",true);
                monitor_list();
            }
        });
    }
}

function GMcode_do50()
{
    if(confirm("注意：确定？取消？"))
    {
        var gm_code = 'loadact';
        var gm_zones = $("#gm_zones3").val();
        
        if(gm_code.length == 0 || gm_zones.length == 0)
        {
            gm_add_log('<font color=red>参数不能为空</font>');
            return false;
        }

        var do_log = '========================================================<br/>'+'[命令]'+gm_code + ' [区服]' + gm_zones + ' </br>请等待。。。。。。';

        gm_add_log(do_log);

        $.ajax({
            type:'POST',
            url:'dobanagonggao.php?action=jiazaihuodong',
            data:$('#gm_form3').serialize(),
            success:function(result)
            {
                gm_add_log(result);
                $("#monitor_check").prop("checked",true);
                monitor_list();
            }
        });
    }
}

function GMcode_do51()
{
    if(confirm("注意：确定？取消？"))
    {
        var gm_code = 'loadgift';
        var gm_zones = $("#gm_zones3").val();
        
        if(gm_code.length == 0 || gm_zones.length == 0)
        {
            gm_add_log('<font color=red>参数不能为空</font>');
            return false;
        }

        var do_log = '========================================================<br/>'+'[命令]'+gm_code + ' [区服]' + gm_zones + ' </br>请等待。。。。。。';
        
        gm_add_log(do_log);
        
        $.ajax({
            type:'POST',
            url:'dobanagonggao.php?action=jiazailibao',
            data:$('#gm_form3').serialize(),
            success:function(result)
            {
                gm_add_log(result);
                $("#monitor_check").prop("checked",true);
                monitor_list();
            }
        });
    }
}

function GMcode_do20()
{
    if(confirm("注意：确定？取消？"))
    {
        var gm_code = $("#gm_code").val();
        var gm_zones = $("#gm_zones").val();
        
        if(gm_code.length == 0 || gm_zones.length == 0)
        {
            gm_add_log('<font color=red>参数不能为空</font>');
            return false;
        }

        var do_log = '========================================================<br/>'+'[命令]'+gm_code + ' [区服]' + gm_zones + ' </br>请等待。。。。。。';
        
        gm_add_log(do_log);

        $.ajax({
            type:'POST',
            url:'dobanagonggao.php?action=tiren',
            data:$('#gm_form').serialize(),
            success:function(result)
            {
                gm_add_log(result);
                $("#monitor_check").prop("checked",true);
                monitor_list();
            }
        });
    }
}

function GMcode_do21()
{
    if(confirm("注意：确定？取消？"))
    {
        var gm_code = $("#gm_code").val();
        var gm_zones = $("#gm_zones").val();
    
        if(gm_code.length == 0 || gm_zones.length == 0)
        {
            gm_add_log('<font color=red>参数不能为空</font>');
            return false;
        }
    
        var do_log = '========================================================<br/>'+'[命令]'+gm_code + ' [区服]' + gm_zones + ' </br>请等待。。。。。。';
        
        gm_add_log(do_log);
        
        $.ajax({
            type:'POST',
            url:'dobanagonggao.php?action=quxiaomimabaohu',
            data:$('#gm_form').serialize(),
            success:function(result)
            {
                gm_add_log(result);
                $("#monitor_check").prop("checked",true);
                monitor_list();
            }
        });
    }
}

function gm_add_log(msg)
{
    $("#log_text").html($("#log_text").html()+'</br>'+msg);
    $('#log_text').scrollTop( $('#log_text')[0].scrollHeight );
}

function monitor_add_log(msg)
{
    $("#monitor_text").html($("#monitor_text").html()+'</br>'+msg);
    $('#monitor_text').scrollTop( $('#monitor_text')[0].scrollHeight );
}

function change_checked()
{
    if( $("#monitor_check").is(':checked') == false )
    {
        $("#monitor_check").prop("checked",true);
        $("#titlefont").css("color",'blue');
        monitor_add_log('开始监控 ===================================');
    }else
    {
        $("#monitor_check").prop("checked",false);
        $("#titlefont").css("color",'black');
        monitor_add_log('停止监控 ===================================');
    }
}

function monitor_list()
{
    if( $("#monitor_check").is(':checked') == true )
    {
        $.ajax({
            type:'POST',
            url:'dobanagonggao.php?action=monitor_list&FlagId='+$("#FlagId").val(),
            success:function(result)
            {
                monitor_add_log(result);
            }
        });
    }
}

function clear_zx_log()
{
    $("#log_text").html('');
}

function clear_jk_log()
{
    $("#monitor_text").html('');
}
</script>
</body>
</html>
