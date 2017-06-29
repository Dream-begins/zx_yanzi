<?php
    date_default_timezone_set("PRC");
    include_once "checklogin.php";
    include_once "newweb/h_header.php";
    $DB_HOST=FT_MYSQL_COMMON_HOST;
    $DB_USER=FT_MYSQL_COMMON_ROOT;
    $DB_PASS=FT_MYSQL_COMMON_PASS;
    $DB_NAME=FT_MYSQL_BILL_DBNAME;
    $con = mysql_connect($DB_HOST,$DB_USER,$DB_PASS);
    ini_set('display_errors', 1);
    error_reporting(0);
?> 
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>探墓王-常用礼包配置</title>
  <link rel="stylesheet" type="text/css" href="res/themes/default/easyui.css">
  <link rel="stylesheet" type="text/css" href="res/themes/icon.css">
  <link rel="stylesheet" type="text/css" href="res/jquery.jqplot.min.css">
  <script type="text/javascript" src="res/jquery.min.js"></script>
  <script type="text/javascript" src="res/jquery.easyui.min.js"></script>
  <script type="text/javascript" src="res/admin.js"></script>
</head>
<body>
<?php
ini_set('default_charset','utf-8');  
mysql_select_db(FT_MYSQL_ADMIN_DBNAME, $con) or die("mysql select db error". mysql_error());
mysql_query('set names utf8');
$datas = array();
$query = "SELECT * FROM `ft_common_use_gift` ";

$result = mysql_query($query) or die("Invalid query: " . mysql_error());
$total = mysql_num_rows($result);

while ($row = mysql_fetch_assoc($result)) 
{
    $datas[] = $row;
}

?>
    <div>
        <table><td>
            <form  action="ht_common_gift_conf_do.php" method="post" id="gift_app" >
                <fieldset style="width:650px">
                <legend>常用礼包配置</legend>
                <table>
                    <input type="hidden" name="apply_account" value="<?php echo $_SESSION['xwusername']?>">
                    <tr>
                        <td>礼包名称：</td>
                        <td>
                            <input type="text" class="easyui-validatebox" value=""  name="g_name" id="g_name"/>
                        </td>
                    </tr>
                    <tr>
                        <td>道具:</td>
                        <td>
                            <input type="text" class="easyui-validatebox" value="0"  name="objs1" id="objs1"/>
                            <input type="text" class="easyui-validatebox" value="0" name="objs2" id="objs2"/>
                            <input type="text" class="easyui-validatebox" value="0"  name="objs3" id="objs3"/>
                        </td>
                    </tr>
                    <tr>
                        <td>数量:</td>
                        <td><input type="text" class="easyui-validatebox" value="0" name="nums1" id="nums1" />
                            <input type="text" class="easyui-validatebox" value="0" name="nums2" id="nums2" />
                            <input type="text" class="easyui-validatebox"  value="0"  name="nums3" id="nums3" />
                        </td>
                    </tr>
                    <tr>
                        <td>是否绑定:</td>
                        <td>
                            <input type="text" class="easyui-validatebox"  value="0"  name="binds1" id="binds1"/>
                            <input type="text" class="easyui-validatebox" value="0" name="binds2" id="binds2"/>
                            <input type="text" class="easyui-validatebox" value="0"  name="binds3" id="binds3"/>
                        </td>
                    </tr>
                    <tr>
                        <td>标识:</td>
                        <td>
                            <select name='state'>
                                <option value='2'>外部用</option>
                                <option value='1'>内部用</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td> 
                            <a class='easyui-linkbutton' onclick="form_submit()">提交</a>
                        </td>
                    </tr>

                </table>
                    </fieldset>
            </form>
        </table>
    </div>
    <fieldset style="">
        <legend>已配置：</legend>
        <table class="easyui-datagrid" title="" style="" data-options="singleSelect:true,collapsible:true,method:'get'">
            <thead>
                <tr>
                    <!-- <th data-options="field:'id',width:80">id</th> -->
                    <th data-options="field:'name',width:80">礼包名称</th>
                    <th data-options="field:'item1',width:180">物品1 ID|数量|是否绑定</th>
                    <th data-options="field:'item2',width:180">物品2 ID|数量|是否绑定</th>
                    <th data-options="field:'item3',width:180">物品3 ID|数量|是否绑定</th>
                    <th data-options="field:'mtime',width:150">创建时间</th>
                    <th data-options="field:'madmin',width:100">创建人后台帐号</th>
                    <th data-options="field:'state',width:120">标识</th>
                    <th data-options="field:'del',width:80">删除操作</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $state2name = array('1'=>'内部用','2'=>'外部用');
                    foreach ($datas as $key => $value) 
                    {
                        echo "<tr>";
                            // echo "<td>".$value['id']."</td>";
                            echo "<td>".$value['name']."</td>";
                            echo "<td>".$value['item1_id']."|".$value['item1_num']."|".$value['item1_bind']."</td>";
                            echo "<td>".$value['item2_id']."|".$value['item2_num']."|".$value['item2_bind']."</td>";
                            echo "<td>".$value['item3_id']."|".$value['item3_num']."|".$value['item3_bind']."</td>";
                            echo "<td>".date("Y-m-d H:i:s" ,$value['mtime'])."</td>";
                            echo "<td>".$value['madmin']."</td>";
                            echo "<td>".$state2name[$value['state']]."</td>";
                            echo "<td><a class='easyui-linkbutton' onclick=\"gift_del('".$value['id']."');\">删除</a></td>";
                        echo "</tr>";
                    }
                 ?>
            </tbody>
        </table>


    </fieldset>


<script type="text/javascript">
    function form_submit()
    {
        if(confirm("注意：确定？取消？"))
        {
            $.ajax(
            {
                type:'POST',
                url:'ht_common_gift_conf_do.php?action=add',
                data:$('#gift_app').serialize(),
                // dataType:'json',
                success:function()
                {
                    // $(".tabs-selected .icon-reload").click();
                    loacation.reload();
                }
            });
            // $(".tabs-selected .icon-reload").click();
            location.reload();
        }

    }

    function gift_del(id)
    {

        if(confirm("注意：确定？取消？"))
        {
            $.ajax(
            {
                type:'POST',
                url:'ht_common_gift_conf_do.php?action=del',
                data:{id:id},
                // data:$('#yuanbao_app').serialize(),
                // dataType:'json',
                success:function()
                {
                    // $(".tabs-selected .icon-reload").click();
                    location.reload();
                }
            });
            // $(".tabs-selected .icon-reload").click();
            location.reload();
        }

    }
</script>
</body>
</html>
