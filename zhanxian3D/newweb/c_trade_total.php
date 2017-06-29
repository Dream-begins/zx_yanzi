<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'core.php';
include_once "../upgrade.php";
include_once "../common.php";
$sql='';
$zone='';
$from='';
$to ='';
$item='';
$name='';
$event='';
$export=@$_GET['export'];

if($export){
    if(isset($_GET['zone']) && !empty($_GET['zone'])){
    $zone=" ZONEID='{$_GET['zone']}' AND ";
    $sql.=$zone;
}
if(isset($_GET['item']) && !empty($_GET['item'])){
    $item=" ITEMID='{$_GET['item']}' AND ";
    $sql.=$item;
}
if(isset($_GET['name']) && !empty($_GET['name'])){
    $name=" USERID='{$_GET['name']}' AND ";
    $sql.=$name;
}
if(isset($_GET['event']) && !empty($_GET['event'])){
    $event=" EVENT='{$_GET['event']}' AND ";
    $sql.=$event;
}

if (isset($_GET['from']) && !empty($_GET['from'])) {
		$from = " FROM_UNIXTIME(TIME,'%Y-%m-%d %H:%i:%S')>='{$_GET['from']}' AND ";
		$sql .= $from;
	}
    
if (isset($_GET['to']) && !empty($_GET['to'])) {
		 $to = " FROM_UNIXTIME(TIME,'%Y-%m-%d') <='{$_GET['to']}' AND ";
		$sql .= $to;
	}

if(!empty($sql)){
     $sql= ' WHERE '.substr($sql, 0,-4);
}
    $sql    = "SELECT from_unixtime(TIME,'%Y-%m-%d %H:%i:%S') as datetime,ZONEID,SERVERID,USERID,ACCNAME,NAME,LEVEL,EVENT,ITEMID,ITEMNAME,REDUCEYuanBao,LEFTYuanBao,COUNT,LEFTCOUNT,NOWPRICE,SELLERNAME,SELLERID,SELLERIP,SELLERPARAM1,SELLERPARAM2,PARA1,PARA2,PARA3,PARA4,PARA5,EXTRA from log_trade $sql order by datetime desc";
    $result = mysql_query($sql) or die("Invalid query: " . mysql_error());
    $rows=array();
    while ($row = mysql_fetch_row($result)){
         if($row['7']=="1"){
            $row['7']='1(购买)';
        }elseif ($row['7']=="2") {
            $row['7']='2(上架)';
        }elseif($row['7']=="3"){
            $row['7']='3(下架)';
        }
        
        if($row['1']=='2'){
            $row['1']='2(万佳测试服)';
        }elseif($row['1']=='6'){
            $row['1']='6(外网稳定服)';
        }elseif($row['1']=='100'){
            $row['1']='100(内网测试服)';
        }elseif($row['1']=='1'){
            $row['1']='1(丁乐开发服)';
        }elseif($row['1']=='3'){
            $row['1']='3(开发服)';
        }elseif($row['1']=='4'){
            $row['1']='4(周传高开发服)';
        }elseif($row['1']=='7'){
            $row['1']='7(任务测试服)';
        }elseif($row['1']=='8'){
            $row['1']='8(战斗测试服)';
        }elseif($row['1']=='9'){
            $row['1']='9(数据测试服)';
        }elseif($row['1']=='5'){
            $row['1']='5(内网稳定服)';
        }elseif($row['1']=='12'){
            $row['1']='12(万鹏测试服)';
        }
        array_push($rows,$row);
    }
    require_once "../exportExcel.php";
       export(array("时间","区","服务器id","玩家id","账号名","玩家名","玩家等级","交易所事件","物品id","物品名称","消耗元宝","剩余元宝","交易数量","剩余数量","服务器近期均价","卖家名","卖家id","卖家ip","卖家设备","卖家平台","参数1","参数2","参数3","参数4","参数5","额外信息"),$rows,"交易所统计");
}else{
$page=@$_POST['page'];
$pageSize=@$_POST['rows'];
$first=$pageSize*($page-1);
if(isset($_POST['zone']) && !empty($_POST['zone'])){
    $zone=" ZONEID='{$_POST['zone']}' AND ";
    $sql.=$zone;
}
if(isset($_POST['item']) && !empty($_POST['item'])){
    $item=" ITEMID='{$_POST['item']}' AND ";
    $sql.=$item;
}
if(isset($_POST['event']) && !empty($_POST['event'])){
    $event=" EVENT='{$_POST['event']}' AND ";
    $sql.=$event;
}
if(isset($_POST['name']) && !empty($_POST['name'])){
    $name=" USERID='{$_POST['name']}' AND ";
    $sql.=$name;
}

if (isset($_POST['from']) && !empty($_POST['from'])) {
		$from = " FROM_UNIXTIME(TIME,'%Y-%m-%d %H:%i:%S')>='{$_POST['from']}' AND ";
		$sql .= $from;
	}
    
if (isset($_POST['to']) && !empty($_POST['to'])) {
		 $to = " FROM_UNIXTIME(TIME,'%Y-%m-%d') <='{$_POST['to']}' AND ";
		$sql .= $to;
	}

if(!empty($sql)){
     $sql= ' WHERE '.substr($sql, 0,-4);
}
$result=mysql_query("select *,FROM_UNIXTIME(TIME,'%Y-%m-%d %H:%i:%S') as datetime from log_trade $sql LIMIT $first,$pageSize");
$total=mysql_num_rows(mysql_query("select * from log_trade $sql"));
$json='';
while(@$row=mysql_fetch_assoc($result)){
    //var_dump($row['EVENT']);exit;
    if($row['EVENT']=="1"){
        $row['EVENT']='1(购买)';
    }elseif ($row['EVENT']=="2") {
        $row['EVENT']='2(上架)';
    }elseif($row['EVENT']=="3"){
        $row['EVENT']='3(下架)';
    }
    
    if($row['ZONEID']=='2'){
        $row['ZONEID']='2(万佳测试服)';
    }elseif($row['ZONEID']=='6'){
        $row['ZONEID']='6(外网稳定服)';
    }elseif($row['ZONEID']=='100'){
        $row['ZONEID']='100(内网测试服)';
    }elseif($row['ZONEID']=='1'){
        $row['ZONEID']='1(丁乐开发服)';
    }elseif($row['ZONEID']=='3'){
        $row['ZONEID']='3(开发服)';
    }elseif($row['ZONEID']=='4'){
        $row['ZONEID']='4(周传高开发服)';
    }elseif($row['ZONEID']=='7'){
        $row['ZONEID']='7(任务测试服)';
    }elseif($row['ZONEID']=='8'){
        $row['ZONEID']='8(战斗测试服)';
    }elseif($row['ZONEID']=='9'){
        $row['ZONEID']='9(数据测试服)';
    }elseif($row['ZONEID']=='5'){
        $row['ZONEID']='5(内网稳定服)';
    }elseif($row['ZONEID']=='12'){
        $row['ZONEID']='12(万鹏测试服)';
    }
    $json.= json_encode($row).',';
}
$json= substr($json, 0,-1);
echo '{"total":'.$total.',"rows":['.$json.']}';
}
mysql_close(); 
