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
//$sql="select LEVEL,count('select count(*) from db_zxquest where QUESTEVENT=2') as num from db_zxquest group by LEVEL";
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
				for select distinct LEVEL from db_zxquest $sql;
				DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
				open mycursor;
				REPEAT
				fetch mycursor into a;
				if(not done) then
				set b=(select count(id) count from db_zxquest  WHERE $sql1 LEVEL>=a);
                set c=(select b/count(id)*100 percent from db_zxquest $sql);
				insert into amp_table(level,count,percent) select a,b,c;
				end if;
	          	UNTIL done END REPEAT;
				close mycursor;
			end;
		end;");
mysql_query("call myproce1()");
$result=mysql_query("select level,count,concat(percent,'%') percent from amp_table group by level");
$total=mysql_num_rows(mysql_query("select count(*) as num from db_zxquest $sql group by LEVEL"));
$json='';
while($row=mysql_fetch_assoc($result)){
    $json.= json_encode($row).',';
}
     $json= substr($json, 0,-1);
    echo '{"total":'.$total.',"rows":['.$json.']}';
    mysql_close();


