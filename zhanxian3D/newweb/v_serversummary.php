<?php session_start();if(strpos(@$_SESSION['priv'],'newweb/v_serversummary')==0)exit;?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="jquery-easyui-1.4/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="jquery-easyui-1.4/themes/icon.css">
    <script type="text/javascript" src="jquery-easyui-1.4/jquery.min.js"></script>
    <script type="text/javascript" src="jquery-easyui-1.4/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="jquery-easyui-1.4/locale/easyui-lang-zh_CN.js"></script>
    <style type="text/css">
        td, th, h1, h2 {font-family: sans-serif;}
        a:link {color: #000099; text-decoration: none; background-color: #ffffff;}
        a:hover {text-decoration: underline;}
        table {border-collapse: collapse;}
        .center {text-align: center;}
        .center table { margin-left: auto; margin-right: auto; text-align: left;}
        .center th { text-align: center !important; }
        h1 {font-size: 150%;}
        h2 {font-size: 125%;}
        .p {text-align: left;}
        .e {border: 1px solid #000000; font-size: 75%; vertical-align: baseline;background-color: #ccccff; font-weight: bold; color: #000000;}
        .h {background-color: #9999cc; font-weight: bold; color: #000000;}
        .v {border: 1px solid #000000; font-size: 75%; vertical-align: baseline;background-color: #cccccc; color: #000000;}
        .vr {background-color: #cccccc; text-align: right; color: #000000;}
        img {float: right; border: 0px;}
        hr {width: 600px; background-color: #cccccc; border: 0px; height: 1px; color: #000000;}
    </style>
</head>
<body>

<div id="serversummary">
    <form id="summaryfrm">
        <table class="tab_search">
            <tr>
                <td>服务器:</td>
                <td><input type="text" id="zone" name="zone" value="" /></td>
                <td>查看日期:</td>
                <td><input class="easyui-datebox" id="from" name="from">--<input class="easyui-datebox" id="to" name="to"></td>
                <td><input type="button" value="搜索" onclick="loadSummaryData()" /></td>
            </tr>
        </table>
    </form>

    <div id="dataTable" styles="width:100%" ></div>
</div>

<script type="text/javascript">
    function loadSummaryData()
    {
        $("#serversummary #dataTable").html("正在加载中...");
        $("#serversummary #dataTable").load("c_serversummary.php",$("#summaryfrm").serialize());       
    }
 
</script>
</body>
</html>