<?php 
include_once "checklogin.php";
include_once "./newweb/h_menu.php";
include_once "newweb/h_header.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo FT_COMMON_TITLE;?></title>

    <link rel="stylesheet" type="text/css" href="res/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="res/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="res/jquery.jqplot.min.css">

    <script type="text/javascript" src="res/jquery.min.js"></script>
    <script type="text/javascript" src="res/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="res/jquery.jqplot.min.js"></script>
    <script type="text/javascript" src="res/jqplot.json2.min.js"></script>
    <script type="text/javascript" src="res/jqplot.dateAxisRenderer.js"></script>
    <script type="text/javascript" src="res/jqplot.cursor.min.js"></script>
    <script type="text/javascript" src="res/jqplot.highlighter.min.js"></script>
    <script language="javascript" type="text/javascript" src="res/js/highcharts.js"></script> 
    <script type="text/javascript" src="res/datagrid-groupview.js"></script>
    <script type="text/javascript" src="res/admin.js"></script>
    <script type="text/javascript" src="res/plupload/plupload.full.min.js"></script>
    <style type="text/css">
      .tree-title {
          font-size: 13px;
      }
    </style>
</head>
<body class="easyui-layout" style="text-align:left">

<div region="north" style="width:100%"><a style="right:0px" href="logout.php" >注销</a></div> 

<div data-options="region:'west',split:true" title=" " style="width:180px;">
    <?php
    

    $priv_arr=  explode(',', trim(@$_SESSION['priv'],',') );

    $menu_show_arr = array();

    foreach ($menu_arrays as $key => $value) 
    {
        @$menu_show_arr[$key] .= '<ul class="easyui-tree">';
        $menu_show_arr[$key] .= '<li>';
        $menu_show_arr[$key] .= "<span>{$key}</span>";
        $menu_show_arr[$key] .= '<ul>';
        
        $flag = 0;
        foreach ($value as $k => $v) 
        {
            if( in_array( $v['index'] ,$priv_arr ) )
            {
                $flag=1;
                $menu_show_arr[$key] .= "<li><a onclick=\"".$v['open']."('".$v['name']."','".$v['index'].".".$v['Suffix']."')\" >".$v['name']."</a></li>";
            }
        }

        $menu_show_arr[$key] .= '</ul>';
        $menu_show_arr[$key] .= '</li>';
        $menu_show_arr[$key] .= '</ul>';
        
        if(!$flag)
        {
            unset($menu_show_arr[$key]);
        }
    }

    foreach ($menu_show_arr as $key => $value) 
    {
        echo $value;
    }
    ?>
</div>

<div region="center" border="false">
    <div id="tt" class="easyui-tabs" fit="true" border="false" plain="true"></div>
</div>

<script type="text/javascript">
    function open2(title,url){
        window.open(url);
    }
    function open3(title,url)
    {
        if ($('#tt').tabs('exists',title))
        {
            $('#tt').tabs('select', title);
        }else 
        {
            var content = '<iframe scrolling="auto" frameborder="0" src="'+url+'" style="width:100%;height:100%;padding:0px;margin:0px"></iframe>';
            $('#tt').tabs('add',{
                title:title,
                content:content,
                closable:true,
                tools:[
                {
                    iconCls:'icon-reload',
                    handler:function()
                    {
                        $('#tt').tabs('select', title);
                        var tab = $('#tt').tabs('getSelected');
                        $('#tt').tabs('update',
                        {
                            tab:tab,
                            options: 
                            {
                                content:content,
                            }
                        });
                    }
                }]
            });
        }
    }
</script>
</body>
</html>
