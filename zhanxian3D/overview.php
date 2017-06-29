<div id="server">
    <div>
        <table class="tab_search">
            <tr>
                <td>从:</td>
                <td><input class="easyui-datebox" name="from"></input></td>
                <td>到:</td>
                <td><input class="easyui-datebox" name="to"></input></td>
                <td>服务器:</td>
                <td><input type="text" id="zone" name="zone" value="" /></td>
                <td><input type="button" value="搜索" onclick="reloadData('server')" /></td>
                <td><input type="button" value="导出" onclick="exportData('server','server.php')" /></td>
            </tr>
        </table>
    </div>
    <div id="dataTable" styles="width:100%" ></div>
</div>

<script type="text/javascript">
$(function(){
    $('#server #dataTable').datagrid({
        url:'server.php',
        remoteSort: true,
        pagination:true,
        rownumbers:true,
        
        columns:[[
               {field:'dt',title:'日期',width:300,formatter:function(val){
                   return val.substr(0,10);
               }},
                {field:'serverid',title:'日活跃用户数量',width:100},
               {field:'serverid',title:'新注册',width:100},
               {field:'ammount',title:'创角数',width:80,formatter:function(val,data){return(data['ammount']*1).toFixed(2)}},
               {field:'arpu',title:'活跃人数',width:100,formatter:function(val,data){
                   return (data["ammount"]*1/data["cnt"]).toFixed(2);
               }},
               {field:'cnt',title:'收入',width:80},
               {field:'qqgame',title:'累计付费人数',width:180},

           ]]
       });

    $.parser.parse($("#server .tab_search"));
});
</script>
