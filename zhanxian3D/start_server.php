<?php
include "checklogin.php";
include_once "newweb/h_header.php";
?>
<!DOCTYPE html>
<html lang="en">
<head> 
    <title><?php echo FT_COMMON_TITLE; ?></title> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
    <meta http-equiv="Content-Language" content="zh-CN" /> 
    <script language="JavaScript" type="text/javascript">
        function checkform(){
            var name = document.getElementsByName("server")[0].value;
            if (name == ""){
                alert("内容不可为空");
                return false;
            }
            else if (!/^z\d*$/.test(name)){
                alert("格式错误");
                return false;
            }
        }
    </script>
</head>
<body>
<center><h1><b><?php echo FT_COMMON_TITLE;?>-开服</b></h1></center>
<HR style="FILTER: alpha(opacity=100,finishopacity=0,style=3)" width="80%" color=#987cb9 SIZE=3>
<center>
<form action="./start_server_do.php" method="post" onsubmit="return checkform()">
<input type="text" name="server"/><input type="submit" value="确定"/>
<h3><font color=red>请输入要开通的服（例如：z100）--需等待执行2Min左右</font></h3>
</form>
</center>
</body>
</html>
