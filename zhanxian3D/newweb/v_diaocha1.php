<?php
date_default_timezone_set("PRC");
ini_set('display_errors', 1);
error_reporting(0);
session_start();
include "h_define.php";
ini_set("memory_limit", "100M");


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <meta http-equiv="Pragma" contect="no-cache">
    <title><?php echo FT_TITLE; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo FT_RES;?>themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo FT_RES;?>themes/icon.css">
    <script type="text/javascript" src="<?php echo FT_RES;?>jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo FT_RES;?>jquery.easyui.min.js"></script>
    <script type="text/javascript" src="<?php echo FT_RES;?>locale/easyui-lang-zh_CN.js"></script>
    <script type="text/javascript" src="http://s2.app100715380.qqopenapp.com/ftadmin/res/js/highcharts.js"></script>
    <script type="text/javascript" src="<?php echo HIGHCHARTS;?>modules/exporting.js"></script>
</head>
<body>


<?php 
include 'arr1.php';

$dbh = new PDO('mysql:host='.FT_MYSQL_COMMON_HOST.';dbname=admin;port='.FT_MYSQL_COMMON_PORT.';charset=utf8',FT_MYSQL_COMMON_ROOT, FT_MYSQL_COMMON_PASS);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbh->query("SET NAMES UTF8");


$title_arr = array();//问题题目数组
foreach ($diaocha_arrs['forms'] as $key => $value)
{
    $k = 't'.($key+1);
    if(in_array($k,array('t17'))) continue;
    $title_arr[$k]= $value['title'];

}


$t1 = array(
    '1'=>'朋友安利',
    '2'=>'游戏媒体报道',
    '3'=>'参加过内网测试',
    '4'=>'各种应用商店/AppStore',
    '5'=>'其他'
);

$t2 = array(
    '1'=>'做试炼任务',
    '2'=>'刷探墓副本',
    '3'=>'准点抢世界BOSS',
    '4'=>'参与决战地宫',
    '5'=>'世界/家族闲聊',
    '6'=>'去其他国家边境约架、偷BOSS',
    '7'=>'参与国战'
);

$t3 = array(
    '1'=>'聊天、交朋友和好朋友一起玩',
    '2'=>'通过PK战胜对手',
    '3'=>'争做区服最强者、受人崇拜',
    '4'=>'强迫症，每天必须把所有任务做完',
    '5'=>'寻找游戏策略，如何搭配宠物达到低战碾压高战',
    '6'=>'喜欢培养宠物，让所有宠物都毕业',
    '7'=>'闲着无聊，进去打发时间',
);

$t4 = array(
    '1'=>'1-2小时',
    '2'=>'3-6小时',
    '3'=>'7-10小时',
    '4'=>'12小时以上',

);

$t5 = array(
    '1'=>'动作类（ACT）',
    '2'=>'模拟游戏（SLG）',
    '3'=>'角色扮演（RPG）',
    '4'=>'休闲类',
    '5'=>'我几乎不玩手游',
);

$t6 = array(
    '1'=>'早上',
    '2'=>'上午',
    '3'=>'中午',
    '4'=>'下午',
    '5'=>'晚上',
    '6'=>'通宵',
);

$t7 = array(
    '1'=>'从不看',
    '2'=>'偶尔看',
    '3'=>'天天看',

);

$t8 = array(
    '1'=>'玄幻',
    '2'=>'仙侠',
    '3'=>'都市',
    '4'=>'历史',
    '5'=>'言情',
);

$t9 = array(
    '1'=>'腾讯视频',
    '2'=>'爱奇艺',
    '3'=>'乐视TV',
    '4'=>'优酷',
    '5'=>'芒果TV',
    '6'=>'A站',
    '7'=>'B站',
);

$t10 = array(
    '1'=>'睡觉',
    '2'=>'逛街',
    '3'=>'约朋友出去浪',
    '4'=>'没朋友，宅在家里玩游戏',
    '5'=>'出去旅游',
);


$t11 = array(
    '1'=>'没钱买什么买，不买！',
    '2'=>'没钱借钱买，任性！',
    '3'=>'信用卡！',
    '4'=>'蚂蚁花呗',
);


$t12 = array(
    '1'=>'男',
    '2'=>'女',
    '3'=>'其他',
);

$t13 = array(
    '1'=>'12岁以下',
    '2'=>'12-18岁',
    '3'=>'19-25岁',
    '4'=>'25-29岁',
    '5'=>'30岁以上',
);

$t14 = array(
    '1'=>'学生',
    '2'=>'公司职员',
    '3'=>'自由职业/个体',
    '4'=>'企业管理人员',
    '5'=>'事业单位人员',
    '6'=>'其他',
);

$t15= array(
    '1'=> '3000以内',
    '2'=> '3000-5000',
    '3'=> '5000-8000',
    '4'=> '8000-15000',
    '5'=> '15000-30000',
    '6'=> '30000以上',
);

$t16 = array(
    '1'=>'媳妇，老婆最大！',
    '2'=>'老公，老公最有主见',
    '3'=>'男朋友，额……男朋友该换了',
    '4'=>'女朋友，就是这么宠爱',
    '5'=>'单身狗飘过，自己掌管',
    '6'=>'父母大人',

);


$baneben = $diaocha_arrs['banben'];
$sql = "SELECT t0,t1_0,t1_1,t1_2,t1_3,t1_4,t1_5,t1_6,t2_0,t2_1,t2_2,t2_3,t2_4,t2_5,t2_6,t3,t4,t5_0,t5_1,t5_2,t5_3,t5_4,t5_5,t6,t7,t8,t9,t10,t11,t12,t13,t14,t15 FROM diaocha".$baneben." where openid != '' ";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll();

$all_result = array();
//答案累加数组 all_result
foreach ($result as $key => $value) {
    $all_result['t1'][$t1[$value['t0']]]++;
    for($i=0;$i<6;$i++){
        $item = $i+1;
        if($value["t1_$i"]=='on'){
            $all_result['t2'][$t2[$item]]++;
        }
        if($value["t2_$i"]=='on'){
            $all_result['t3'][$t3[$item]]++;
        }

        if($value["t5_$i"]=='on'){
            $all_result['t6'][$t6[$item]]++;
        }
    }
    $all_result['t4'][$t4[$value['t3']]]++;
    $all_result['t5'][$t5[$value['t4']]]++;
    $all_result['t7'][$t7[$value['t6']]]++;
    $all_result['t8'][$t8[$value['t7']]]++;
    $all_result['t9'][$t9[$value['t8']]]++;
    $all_result['t10'][$t10[$value['t9']]]++;
    $all_result['t11'][$t11[$value['t10']]]++;
    $all_result['t12'][$t12[$value['t11']]]++;
    $all_result['t13'][$t13[$value['t12']]]++;
    $all_result['t14'][$t14[$value['t13']]]++;
    $all_result['t15'][$t15[$value['t14']]]++;
    $all_result['t16'][$t16[$value['t15']]]++;
}
?>

<table id="dg" style="width:100%;" data-options=""></table>

<div id="toolbar">
    <form id='s_form'>
        <a style='float:right;' href="c_diaocha1.php?action=putcsv" class="easyui-linkbutton" iconCls="">导出全部</a>
    </form>
</div>


<table>
    <tr>
        <td><div id="containert1" style="min-width: 500px; height: 300px; max-width: 700px;"></div></td>
        <td><div id="containert2" style="min-width: 500px; height: 300px; max-width: 700px;"></div></td>
    </tr>
    <tr>
        <td><div id="containert3" style="min-width: 500px; height: 300px; max-width: 700px;"></div></td>
        <td><div id="containert4" style="min-width: 500px; height: 300px; max-width: 700px;"></div></td>
    </tr>
    <tr>
        <td><div id="containert5" style="min-width: 500px; height: 300px; max-width: 700px;"></div></td>
        <td><div id="containert6" style="min-width: 500px; height: 300px; max-width: 700px;"></div></td>
    </tr>
    <tr>
        <td><div id="containert7" style="min-width: 500px; height: 300px; max-width: 700px;"></div></td>
        <td><div id="containert8" style="min-width: 500px; height: 300px; max-width: 700px;"></div></td>
    </tr>
    <tr>
        <td><div id="containert9" style="min-width: 500px; height: 300px; max-width: 700px;"></div></td>
        <td><div id="containert10" style="min-width: 500px; height: 300px; max-width: 700px;"></div></td>
    </tr>

    <tr>
        <td><div id="containert11" style="min-width: 500px; height: 300px; max-width: 700px;"></div></td>
        <td><div id="containert12" style="min-width: 500px; height: 300px; max-width: 700px;"></div></td>
    </tr>

    <tr>
        <td><div id="containert13" style="min-width: 500px; height: 300px; max-width: 700px;"></div></td>
        <td><div id="containert14" style="min-width: 500px; height: 300px; max-width: 700px;"></div></td>
    </tr>

    <tr>
        <td><div id="containert15" style="min-width: 500px; height: 300px; max-width: 700px;"></div></td>
        <td><div id="containert16" style="min-width: 500px; height: 300px; max-width: 700px;"></div></td>
    </tr>
    <tr>
        <td><div id="containert17" style="min-width: 500px; height: 300px; max-width: 700px;"></div></td>
        <td></td>
    </tr>


</table>


<script type="text/javascript">
    datagrid_load('c_diaocha1.php?action=list');
    function datagrid_load(url)
    {

        $('#dg').datagrid({
            title : '调查问卷',
            url   : url,
            // rownumbers   : true,
            singleSelect : true,
            fitColumns   : true,
            // collapsible  : true, //折叠按钮
            pagination   : true, //翻页栏
            //pagePosition : 'top',
            method       : 'post',
            toolbar      : '#toolbar',
            pageSize     : 5,
            pageList     : [5,10,20,30,40,50],
            queryParams: {
                gameselect:$('#gameselect').val(),
            },
            columns:[[
            {field:'id', title:'id', halign:'center', align:'center',sortable:true},
            {field:'zone_id', title:'区', halign:'center', align:'center'},
            {field:'name',  title:'角色名' },
            {field:'t0',  title:'题1' ,width:100,align:'left'},
            {field:'t1',  title:'题2' ,width:100,align:'left'},
            {field:'t2',  title:'题3' ,width:100,align:'left'},
            {field:'t3',  title:'题4' ,width:100,align:'left'},
            {field:'t4',  title:'题5' ,width:100,align:'left'},
            {field:'t5',  title:'题6' ,width:100,align:'left'},
            {field:'t6',  title:'题7' ,width:100,align:'left'},
            {field:'t7',  title:'题8' ,width:100,align:'left'},
            {field:'t8',  title:'题9' ,width:100,align:'left'},
            {field:'t9',  title:'题10' ,width:100,align:'left'},
            {field:'t10',  title:'题11' ,width:100,align:'left'},
            {field:'t11',  title:'题12' ,width:100,align:'left'},
            {field:'t12',  title:'题13',width:100,align:'left' },
            {field:'t13',  title:'题14' ,width:100,align:'left'},
            {field:'t14',  title:'题15' ,width:100,align:'left'},
            {field:'t15',  title:'题16' ,width:100,align:'left'},
            {field:'t16',  title:'题17' ,width:100,align:'left'},
            ]],
        });
    }


    <?php
    foreach ($title_arr as $k => $v)
    {
        if(in_array($k,array('t19','t18'))) continue;
        echo <<<EOT
    $(function () {
        $('#container{$k}').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: 1,
                plotShadow: false
            },
            title: {
                text: '{$v}',
                style:{
                     fontSize: '10px'
                }
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            credits: {
                text: '',
            },
            exporting: {
                enabled: false,
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black',
                             
                        }
                    }
                }       
            }, 
            series: [{
                type: 'pie',
                name: '占比',
                data: [
EOT;

        foreach ($all_result[$k] as $key => $value)
        {
            echo "['".$key."', ".$value."],";
        }
        echo "
                ]
            }]
        });
});
";
    }

    ?>
</script>

</body>
</html>
