<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>仙玉统计</title>
    <!-- 引入 echarts.js -->
    <!-- 这里是加载刚下好的echarts.min.js，注意路径 -->
    <script src="echarts.min.js"></script>
</head>
<body>
    <div id="pieChart" style="width: 1000px;height:580px;"></div>
    <script type="text/javascript">
        // 基于准备好的dom，初始化echarts实例
        var pie = echarts.init(document.getElementById('pieChart'));

        var option={
           
        legend: {
            orient: 'vertical',
            left: 'right',
            bottom:0,
            data: ['直接访问','邮件营销','联盟广告','视频广告','搜索引擎']
        },
        tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        series : [
            {
                name: '仙玉图形统计',
                type: 'pie',
                radius: '55%',
                center:["50%","60%"],
                data:[
                    {value:400, name:'搜索引擎'},
                    {value:335, name:'直接访问'},
                    {value:310, name:'邮件营销'},
                    {value:274, name:'联盟广告'},
                    {value:235, name:'视频广告'}
                ],

        itemStyle: {
         emphasis: {
             shadowBlur: 10,
             shadowOffsetX: 0,
             shadowColor: 'rgba(0, 0, 0, 0.5)'
         }
     },
        }
    ]
};
        // 使用刚指定的配置项和数据显示图表。
        pie.setOption(option);

    </script>
</body>
</html>