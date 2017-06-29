<?php 
include_once "checklogin.php";
include_once "newweb/h_header.php";
$priv_arr = explode(',', $_SESSION['priv']);
if( !in_array('ht_gmcode', $priv_arr) )
{
  header('Location: index.php');
}
date_default_timezone_set("PRC");?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title><?php echo FT_COMMON_TITLE; ?></title>
  <link rel="stylesheet" type="text/css" href="res/themes/default/easyui.css">
  <link rel="stylesheet" type="text/css" href="res/themes/icon.css">
  <script type="text/javascript" src="res/jquery.min.js"></script>
  <script type="text/javascript" src="res/jquery.easyui.min.js"></script>
  <style type="text/css">
    div{font-size: 10px}
  </style>
</head>
<body>
  <fieldset style='width:1000px'>
    <legend align="center">GM指令</legend>
    <form class="easyui-form" id='gm_form' method="post" >
      <table align="left">
        <tr>
          <td>gm:</td>
          <td>
            <textarea cols="60" rows="5" name='gm_code' id='gm_code'></textarea>
          </td>
          <td align='center'>区设置:</td>
          <td>
            <textarea cols="30" rows="3" name='gm_zones' id='gm_zones'></textarea>
          </td>
          <td> <a href="javascript:void(0)" class='easyui-linkbutton' onclick='GMcode_do()'>GM发送</a></td>
        </tr>
      </table>
    </form>
  </fieldset>
  <br>
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
          <!--legend><input type='checkbox' id='monitor_check' name='monitor_check' onchange='monitor_list()' disabled style="display: none"><font id='titlefont'>监控反馈</font></legend-->
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
    function GMcode_do()
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
        var do_log = '========================================================<br/>'
          +'[命令]'+gm_code + ' [区服]' + gm_zones + ' </br>请等待。。。。。。';
        gm_add_log(do_log);
        $.ajax({
            type:'POST',
            url:'ht_gmcode_do.php?action=dogmcode',
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
            url:'ht_gmcode_do.php?action=monitor_list&FlagId='+$("#FlagId").val(),
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
