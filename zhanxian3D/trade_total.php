<div id="trade">
    <div>
        <table class="tab_search">
            <tr>
                <td>区:</td>
                <td><input type="text" id="zone" name="zone" value="" /></td>
                <td> 道具id:</td>
                <td><input type="text" id="item" name="item" value="" /></td>
                <td>玩家id:</td>
                <td><input type="text" id="name" name="name" value="" /></td>
                <td>交易事件id:</td>
                <td><input type="text" id="name" name="event" value="" /></td>
                <td>从:</td>
                <td><input class="easyui-datebox" name="from"></input></td>
                <td>到:</td>
                <td><input class="easyui-datebox" name="to"></input></td>
                <td><a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="obj.search()">查询</td>
                <td><input type="button" value="导出" onclick="exportData('trade','newweb/c_trade_total.php')" /></td>
            </tr>
        </table>
    </div>
    <div id="dataTable" styles="width:100%" ></div>
</div>
<script type="text/javascript" src="newweb/jquery-easyui-1.4/locale/easyui-lang-zh_CN.js"></script>
<script type="text/javascript">
$(function(){
    obj = {
        search:function(){
          $('#trade #dataTable').datagrid('load',{
              zone: $.trim($('input[name="zone"]').val()),
              item:$.trim($('input[name="item"]').val()),
              name:$.trim($('input[name="name"]').val()),
              event:$.trim($('input[name="event"]').val()),
              from: $('input[name="from"]').val(),
              to   :$('input[name="to"]').val(),
          });  
        },
    };
    $('#trade #dataTable').datagrid({
        url: 'newweb/c_trade_total.php',
        remoteSort: true,
        pagination:true,
        rownumbers:true,
        fitColumns:true,
        striped:true,
        nowrap:false,
        fitColumns:false,
        pageSize:20,
        columns:[[
               {field:'datetime',title:'日期',width:180},                 
               {field:'ZONEID',title:'区',width:100},
              /*{field:'SERVERID',title:'服务器',width:100}, */
               {field:'USERID',title:'玩家id',width:80},
               {field:'ACCNAME',title:'账号名',width:100},
               {field:'NAME',title:'玩家名',width:80},
               {field:'LEVEL',title:'玩家等级',width:100},
               {field:'EVENT',title:'交易事件',width:100}, 
               {field:'ITEMID',title:'道具id',width:100},
               {field:'ITEMNAME',title:'道具名称',width:100},
               {field:'REDUCEYuanBao',title:'消耗元宝',width:100},
               {field:'LEFTYuanBao',title:'剩余元宝',width:80},
               {field:'COUNT',title:'交易数量',width:80},
               {field:'LEFTCOUNT',title:'交易剩余数量',width:80},
               {field:'NOWPRICE',title:'服务器近期均价',width:80},
               {field:'SELLERNAME',title:'卖家名',width:80},
               {field:'SELLERID',title:'卖家id',width:80},
               {field:'SELLERIP',title:'卖家ip',width:80},
               {field:'SELLERPARAM1',title:'卖家设备',width:80},
               {field:'SELLERPARAM2',title:'卖家平台',width:80},
               {field:'PARA1',title:'参数1',width:80},
               {field:'PARA2',title:'参数2',width:80},
               {field:'PARA3',title:'参数3',width:80},
               {field:'PARA4',title:'参数4',width:80},
               {field:'PARA5',title:'参数5',width:80},
               {field:'EXTRA',title:'额外信息',width:80},
           ]],
       });
});
</script>
