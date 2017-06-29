<?php session_start();if(strpos(@$_SESSION['priv'],'newweb/v_paylistadd')==0)exit;?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <title>安卓-支付单子补充功能</title>
    <link rel="stylesheet" type="text/css" href="jquery-easyui-1.4/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="jquery-easyui-1.4/themes/icon.css">
    <script type="text/javascript" src="jquery-easyui-1.4/jquery.min.js"></script>
    <script type="text/javascript" src="jquery-easyui-1.4/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="jquery-easyui-1.4/locale/easyui-lang-zh_CN.js"></script>
</head>
<body>
    <form method="post" action='c_paylistadd.php' novalidate>
        <table>
            <tr><td>充值时间</td><td><input name="TIME" id="TIME" class="easyui-datetimebox" required="true" editable='fasle' size='16' ></td></tr>
            <tr><td>所在区:</td><td><input name="ZONE" id="ZONE" class="easyui-numberbox" required="true"></td></tr>
            <tr><td>帐号:</td><td><input name="ACC" id="ACC" class="easyui-textbox" required="true"></td></tr>
            <tr><td>角色名:</td><td><input name="NAME" id="NAME" class="easyui-textbox" required="true"></td></tr>
            <tr><td>道具ID:</td><td><input name="OBJID" id="OBJID" class="easyui-numberbox" required="true"></td></tr>
            <tr><td>RMB(元):</td><td><input name="PRICE" id="PRICE" class="easyui-numberbox" required="true"></td></tr>
            <tr><td><input type='submit'></td></tr>
        </table>
    </form>
</body>
</html>