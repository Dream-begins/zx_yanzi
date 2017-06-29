<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="jquery-easyui-1.4/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="jquery-easyui-1.4/themes/icon.css">
    <script type="text/javascript" src="jquery-easyui-1.4/jquery.min.js"></script>
    <script type="text/javascript" src="jquery-easyui-1.4/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="../res/admin.js"></script>
    <script type="text/javascript" src="jquery-easyui-1.4/locale/easyui-lang-zh_CN.js"></script>

</head>
<body style="padding:0px;margin:0px">
<div id="currency">
    <div>
        <table class="tab_search">
            <tr>
                <td>区:</td>
                <td><input type="text" id="zone" name="zone" value="" /></td> 
                <td>从:</td>
                <td><input class="easyui-datebox" name="from" id="start_time" data-options="required:true"></input></td>
                <td>到:</td>
                <td><input class="easyui-datebox" name="to" id="end_time" data-options="required:true"></input></td>
                <td><a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="obj.find()">查询</td>
                <td>注:这里需要点击<font color="red">"查询"</font>才能查询默认日期的数据</td>   
            </tr>
        </table>
    </div>
    <div id="count_total" style="width: 100%; "></div>
    <div id="dataCount" style="width:100%"></div>

</div>
<script type="text/javascript">
function from_time(date) {
            var y = date.getFullYear();
            var m = date.getMonth() + 1;
            var d = date.getDate()-7;

            return y + '-' + (m < 10 ? ('0' + m) : m) + '-'
                    + (d < 10 ? ('0' + d) : d);
        }
        function to_time(date) {
            var y = date.getFullYear();
            var m = date.getMonth() + 1;
            var d = date.getDate();

            return y + '-' + (m < 10 ? ('0' + m) : m) + '-'
                    + (d < 10 ? ('0' + d) : d);
        }
        
    $(function(){
    var curr_time = new Date();
            $("#start_time").datebox({
                value : from_time(curr_time)
            });
            $("#end_time").datebox({
                value : to_time(curr_time)
            });
    obj = {
        find:function(){
          $('#currency #dataCount').datagrid('load',{   
              zone: $.trim($('input[name="zone"]').val()),
              from: $('input[name="from"]').val(),
              to: $('input[name="to"]').val(),
          }); 
         $('#currency #count_total').datagrid('load',{   
              zone: $.trim($('input[name="zone"]').val()),
              from: $('input[name="from"]').val(),
              to: $('input[name="to"]').val(),
          }); 
        },
    },
         $('#currency #count_total').datagrid({
        url: 'count_yin_total.php',
        remoteSort: true,
        rownumbers:true,
        fitColumns:true,
        striped:true,
        nowrap:false,
        fitColumns:false,
        rowStyler: function(index,row){
            return 'font-size:50;color:red;';
        },
        columns:[[
                {field:'sum1',title:'时间段内总消耗银币量',align:'center',width:200,height:50,formatter: 
                function(value,row,index){
                            return '<span style="font-size:20px">'+value+'</span>';//改变表格中内容字体的大小
                }},
                {field:'sum2',title:'时间段内总产出银币量',align:'center',width:200,height:50,formatter: 
                function(value,row,index){
                            return '<span style="font-size:20px">'+value+'</span>';//改变表格中内容字体的大小
                }},
                {field:'sum',title:'时间段内银币总剩余量',align:'center',width:200,height:50,formatter: 
                function(value,row,index){
                            return '<span style="font-size:20px">'+value+'</span>';//改变表格中内容字体的大小
                }},
                {field:'total',title:'时间段内活跃用户数量',align:'center',width:200,height:50,formatter: 
                function(value,row,index){
                            return '<span style="font-size:20px">'+value+'</span>';//改变表格中内容字体的大小
                }},
        ]],
    });
        
        $('#currency #dataCount').datagrid({
        url: 'c_count_yin.php',
        remoteSort: true,
        rownumbers:true,
        fitColumns:true,
        striped:true,
        nowrap:false,
        fitColumns:false,
        columns:[[
                {field:'EVENT',title:'货币事件ID',width:100},
                {field:'count1',title:'消耗银币',width:100},
                {field:'count2',title:'银币增加量',width:100},
                {field:'count3',title:'银币现存量',width:100},
        ]],
    });
 });
     
</script>
  </body>
</html>
