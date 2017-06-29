<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="res/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="res/themes/icon.css">
    <script type="text/javascript" src="res/jquery.min.js"></script>
    <script type="text/javascript" src="res/jquery.easyui.min.js"></script>
    <!-- // <script type="text/javascript" src="./resources/jquery-easyui-1.4/locale/easyui-lang-zh_CN.js"></script> -->
    <script language="javascript" type="text/javascript" src="res/js/highcharts.js"></script>
    <script type="text/javascript">
        function get_highcharts_datas(datas)
        {
            datas = eval("("+datas+")");
            $("#all_online_container").highcharts(
            {
                chart: {
                    type: 'spline',
                    renderTo: 'all_online_container'  
                },  
                title: {
                    text: '全游戏在线曲线图',
                    x: -20
                },
                credits: {
                    enabled: false,
                },
                xAxis: {type:'datetime'},
                yAxis: {
                    title: {
                        text: '在线人数'
                    },
                    plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }]
                },
                tooltip: {
                    valueSuffix: ''
                },
                legend: {
                    align: 'center',
                    verticalAlign: 'bottom',
                },
                plotOptions: {
                    column: {
                        stacking: 'normal'
                    }
                },
                series: [{
                    marker:{radius:0},
                    name: '查看日期',
                    data: datas['ck_datas'],
                    pointInterval: 600 * 1000
                }, {
                    marker:{radius:0},
                    name: '对比日期',
                    data: datas['db_datas'],
                    pointInterval: 600 * 1000
                }]
            });

        }

        function all_online_submitForm(){
            $('#all_online_submit_form').form('submit',{
                onSubmit:function(){
                    return $(this).form('enableValidation').form('validate');
                },
                success:function(){
                    $.ajax(
                    {
                        type:'POST',
                        url:'ht_all_online_do.php?action=online_nums',
                        data:$('#all_online_submit_form').serialize(),
                        success:function(datas)
                        {
                            get_highcharts_datas(datas);
                        }
                    });
                },
                error:function(){
                    alert("error");
                }
            });
        }
    </script>

</head>
<body>
    <form id="all_online_submit_form" class="easyui-form" method="post" data-options="novalidate:true">
        <table>
            <td>查看日期：</td>
            <td><input name='start_ymd_1' class="easyui-datebox" type='text' size='12' data-options="required:true" editable="false" /></td>
            <td>对比日期：</td>
            <td><input name='start_ymd_2' class="easyui-datebox" type='text' size='12' data-options="required:false" editable="false"/></td>
            <td><a href="javascript:void(0)" class="easyui-linkbutton" onclick="all_online_submitForm()" >查询</a></td>
        </table>
    </form>
    <div id="all_online_container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
</body>
</html>
