<?php
    include_once "newweb/h_header.php";
?>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo FT_COMMON_TITLE;?></title>
    <script type="text/javascript" src="res/jquery.min.js"></script>
    <script type="text/javascript" src="res/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="res/jquery.jqplot.js"></script>
    <script type="text/javascript" src="res/jqplot.json2.min.js"></script>
    <script type="text/javascript" src="res/jqplot.dateAxisRenderer.js"></script>
    <script type="text/javascript" src="res/jqplot.cursor.min.js"></script>
    <script type="text/javascript" src="res/jqplot.highlighter.min.js"></script>
    <script language="javascript" type="text/javascript" src="res/js/highcharts.js"></script>   
    <script type="text/javascript" src="res/admin.js"></script>
</head>
<body>

<div id="accbox">
    <h1>首次登录+密码小于9位+密码由纯数字组成 <font color='red'>请重置密码:</font></h1>
    <form  action="changePass.php" method="post" id="changeop">
        <table class="tab_search">
            <tr>
                <td>新登录密码：</td>
                <td><input type="password" id="passId" name="passId" value="" size=50 /></td>
            </tr>
            <tr>
                <td>确定新密码：</td>
                <td><input type="password" id="passId2" name="passId2" value="" size=50/></td>
                <td>注意：密码中不能包含#号</td>
            </tr>
            <tr>
                <td><input type="button" value="提交" onclick="changePass()" /></td>
            </tr>
        </table>
    </form>
</div>

<script type="text/javascript">

function changePass()
{
    var newpass=$("#accbox #passId").val();
    var compass=$("#accbox #passId2").val();
 
    if(!newpass)
    {
       window.confirm("密码不能为空！");
        return;
    }
    
    if(compass!=newpass)
    {
        window.confirm("两次密码不同！");
        return;
    }

    if(newpass.search('#')!=-1)
    {
        window.confirm("密码中不能包含#号");
        return;
    }

    if(newpass.length <=8)
    {
        window.confirm('密码应该大于8位');
        return;
    }

    if(newpass==parseInt(newpass))
    {
        window.confirm('密码不能为纯数字');
        return;
    }

    $.post("changePass.php", {passId:newpass},function(result){if(result==1) window.location.href='main.php'});
}

</script>
</body>
</html>
