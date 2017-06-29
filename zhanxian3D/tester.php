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

<div><h2>测试服白名单管理</h2></div>
<div>
    <div>玩家ID：</div>
    <form action="dotester.php" method="post" id="giftform">
        <div><input type="text" name="tempId" size="50"/></div>
        <input type="submit"/>
    </form>
</dvi>

<script>
$(function()
{
    $('#giftform').form
    ({
        url:'dotester.php',
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

function addlog(msg)
{
    $("#log").val($("#log").val()+"\n"+msg);
}

submiting=false;

var userids=[];

function submitNext()
{
    var userid = userids.shift();
    
    if(userid == undefined)
    {
        addlog("添加完成!");
        return;
    }

    submiting = true;
    
    $("#userid").val(userid);
    
    addlog("开始添加测试员:"+userid);

    $('#giftform').form('submit',{
        success:function(data){
            addlog("添加玩家:"+userid+"成功\n"+data);
            submiting = false;
            submitNext();
        },
        error:function(){
            addlog("添加玩家:"+userid+"失败\n"+data);
            submiting = false;
            submitNext();
        }
    });
}

function getUserList()
{
    userids = [];
    alluser = $("#users").val();
    allusers = alluser.split(",");

    for( var i=0;i<allusers.length;i++)
    {
        userids.push(allusers[i]);
    }
}

var submitCnt = 0;

function submitForm()
{
    submitCnt = 0;
    getUserList();

    if(userids.length == 0)
    {
        alert("没有玩家需要添加");
        return;
    }

    for(var i = 0;i<5;i++)
        submitNext();
}
</script>
</body>
</html>
