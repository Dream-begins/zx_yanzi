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
$sql1='';
if(isset($_POST['zone']) && !empty($_POST['zone'])){
        $zone=" ZONEID='{$_POST['zone']}' AND ";
        $sql.=$zone;
    }
if (isset($_POST['from']) && !empty($_POST['from'])) {
		$from = " FROM_UNIXTIME(TIME,'%Y-%m-%d') >='{$_POST['from']}' AND ";
		$sql .= $from;
}
if (isset($_POST['to']) && !empty($_POST['to'])) {
		$to = " FROM_UNIXTIME(TIME,'%Y-%m-%d') <='{$_POST['to']}' AND ";
		$sql .= $to;
}
 if(!empty($sql)){
         $sql1=$sql;
         $sql= ' WHERE '.substr($sql, 0,-4);         
    }
mysql_query("DROP PROCEDURE IF EXISTS myproce1");
mysql_query("create procedure myproce1()
        begin
            drop table if exists amp_table;
            create temporary table amp_table(level int not null,count int not null,percent varchar(16)); 
            begin
            	DECLARE done INT DEFAULT 0;
	            declare a int;
	            declare b int;
                declare c varchar(5);
				declare mycursor cursor 
				for select distinct HONORID from db_zxhonor $sql;
				DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
				open mycursor;
				REPEAT
				fetch mycursor into a;
				if(not done) then
				set b=(select count(id) count from db_zxhonor  WHERE $sql1 HONORID>=a);
                set c=(select b/count(id)*100 percent from db_zxhonor $sql);
				insert into amp_table(level,count,percent) select a,b,c;
				end if;
	          	UNTIL done END REPEAT;
				close mycursor;
			end;
		end;");
mysql_query("call myproce1()");
$result=mysql_query("select level,count,concat(percent,'%') percent from amp_table group by level");
$total=mysql_num_rows(mysql_query("select count(*) as num from db_zxhonor $sql group by HONORID"));
$json='';
while($row=mysql_fetch_assoc($result)){
    if($row['level']==1){
       $row['level']='1(炼气)';
    }elseif($row['level']==2){
        $row['level']='2(灵动)';
    }elseif($row['level']==3){
        $row['level']='3(开光)';
    }elseif($row['level']==4){
        $row['level']='4(融合)';
    }elseif($row['level']==5){
        $row['level']='5(筑基)';
    }elseif($row['level']==6){
        $row['level']='6(金丹)';
    }elseif($row['level']==7){
        $row['level']='7(元婴)';
    }elseif($row['level']==8){
        $row['level']='8(分神)';
    }elseif($row['level']==9){
        $row['level']='9(化神)';
    }elseif($row['level']==10){
        $row['level']='10(炼虚)';
    }elseif($row['level']==11){
        $row['level']='11(大乘)';
    }elseif($row['level']==12){
        $row['level']='12(斗转)';
    }elseif($row['level']==13){
        $row['level']='13(渡劫)';
    }elseif($row['level']==14){
        $row['level']='14(登峰)';
    }elseif($row['level']==15){
        $row['level']='15(散仙)';
    }elseif($row['level']==16){
        $row['level']='16(地仙)';
    }elseif($row['level']==17){
        $row['level']='17(金仙)';
    }elseif($row['level']==18){
        $row['level']='18(神通)';
    }
    $json.= json_encode($row).',';
}
     $json= substr($json, 0,-1);
    echo '{"total":'.$total.',"rows":['.$json.']}';
    mysql_close();


