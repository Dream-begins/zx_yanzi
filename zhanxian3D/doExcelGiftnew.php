<?php 
ini_set('display_errors', '1');
error_reporting (E_ALL); // Report everything
set_time_limit(0); 
date_default_timezone_set("PRC");
ini_set('default_charset','utf-8');
include_once "common.php";
include_once "checklogin.php";
include_once "upgrade.php";
require_once "reader.php";
//php中读取excel表格
$filename = getParam("file2",null);
$startTime=microtime(true);
if($filename == null)
{
	echo "请上传文件";
	return;
}
$temp = strrpos($filename,"\\");

$filename = substr($filename,$temp+1);
$filename = "excel/".$filename;
echo $filename;

$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('utf-8');
$data->read("$filename");//读取上传到当前目录下的名叫$filename的文件

$time2 = microtime(true);
$cnt = 0;
echo "[TOTAL:".$data->sheets[0]['numRows']."]\r\n";

for($i=2;$i<=$data->sheets[0]['numRows'];$i++)
{
	$datasss['n_openid'] = @$data->sheets[0]['cells'][$i][3];
	$datasss['n_zone'] = @$data->sheets[0]['cells'][$i][1];
	$datasss['n_name'] = @$data->sheets[0]['cells'][$i][2];
	$datasss['n_title'] = @$data->sheets[0]['cells'][$i][4];
	$datasss['n_content'] = @$data->sheets[0]['cells'][$i][5];
	$datasss['n_item1id'] = @$data->sheets[0]['cells'][$i][6];
	$datasss['n_item1num'] = @$data->sheets[0]['cells'][$i][7];
	$datasss['n_item2id'] = @$data->sheets[0]['cells'][$i][8];
	$datasss['n_item2num'] = @$data->sheets[0]['cells'][$i][9];
	$datasss['n_item3id'] = @$data->sheets[0]['cells'][$i][10];
	$datasss['n_item3num'] = @$data->sheets[0]['cells'][$i][11];
	$datasss['n_item4id'] = @$data->sheets[0]['cells'][$i][12];
	$datasss['n_item4num'] = @$data->sheets[0]['cells'][$i][13];
	$datasss['n_item5id'] = @$data->sheets[0]['cells'][$i][14];
	$datasss['n_item5num'] = @$data->sheets[0]['cells'][$i][15];
	$datasss['n_item6id'] = @$data->sheets[0]['cells'][$i][16];
	$datasss['n_item6num'] = @$data->sheets[0]['cells'][$i][17];
	$datasss['n_item7id'] = @$data->sheets[0]['cells'][$i][18];
	$datasss['n_item7num'] = @$data->sheets[0]['cells'][$i][19];
	$datasss['n_item8id'] = @$data->sheets[0]['cells'][$i][20];
	$datasss['n_item8num'] = @$data->sheets[0]['cells'][$i][21];
	$datasss['n_item9id'] = @$data->sheets[0]['cells'][$i][22];
	$datasss['n_item9num'] = @$data->sheets[0]['cells'][$i][23];
	$datasss['n_item10id'] = @$data->sheets[0]['cells'][$i][24];
	$datasss['n_item10num'] = @$data->sheets[0]['cells'][$i][25];
	$datasss['n_item11id'] = @$data->sheets[0]['cells'][$i][26];
	$datasss['n_item11num'] = @$data->sheets[0]['cells'][$i][27];
	$datasss['n_item12id'] = @$data->sheets[0]['cells'][$i][28];
	$datasss['n_item12num'] = @$data->sheets[0]['cells'][$i][29];
	$datasss['n_item13id'] = @$data->sheets[0]['cells'][$i][30];
	$datasss['n_item13num'] = @$data->sheets[0]['cells'][$i][31];
	$datasss['n_item14id'] = @$data->sheets[0]['cells'][$i][32];
	$datasss['n_item14num'] = @$data->sheets[0]['cells'][$i][33];
	$datasss['n_item15id'] = @$data->sheets[0]['cells'][$i][34];
	$datasss['n_item15num'] = @$data->sheets[0]['cells'][$i][35];
	$datasss['n_item16id'] = @$data->sheets[0]['cells'][$i][36];
	$datasss['n_item16num'] = @$data->sheets[0]['cells'][$i][37];
	$flag = dosqloption2($datasss);
	if($flag) $cnt++;
}
$endTime = microtime(true);
?>

发放<?php echo getParam("zone");?>成功，共发放<?php echo $cnt; ?>条 
执行时间1:<?php echo "sss:".round(($endTime - $time2),4);  ?>,<?php echo round(($endTime - $startTime),4); 

function dosqloption2($datasss)
{
	$DB_HOST="1.4.37.28:";
	$DB_USER="root";
	$DB_PASS="hoolai@123";
	$DB_NAME="S2_DATA";
	$zone = $datasss['n_zone'];
	if($zone) echo "[zone:".$zone."]";
	$con2=null;
	if($zone != "")
	{
		$cfg = getIndexCfg2($zone);
                // $cfg = getZoneCfg2($zone);
		if($cfg)
		{
			$_zonenum= $zone;
			echo $_dburl = $cfg["mysql_ip"].":".$cfg["mysql_port"];
			$DB_GAME=$cfg["mysql_dbName"];
			$con2 = mysql_connect($_dburl,$DB_USER,$DB_PASS);
			echo "domians:".$cfg['domians'];
			echo "zones:".$cfg['zones'];	
			echo "dbName:".$DB_GAME;
			$ndomains_arr = explode(',',$cfg['domians']);
			$nzones_arr =  explode(',',$cfg['zones']);

			$domains2zones = array_combine($ndomains_arr,$nzones_arr);

			$select_zones = $domains2zones[$datasss['n_zone']];

			if( !$con2)
			{
				die("mysql connect error");
			}
			mysql_query("set names 'utf8'");
		}
		else{
			echo "unknown zone:".$zone;
			return;
		}
	}
	if($con2 == null)
	{
            if(!$zone) return;
		
	    echo "[zone:".$zone."][id:".$datasss['n_openid']."]发送失败，原因：找不到服务器。";
            return ;
	}

	mysql_select_db($DB_GAME, $con2) or die("mysql select db error". mysql_error());
	mysql_query("SET NAMES utf8",$con2);
	// $sql = "select CHARID,ZONE,NAME from CHARBASE where ACCNAME = '".$datasss['n_openid']."'";
	$sql = "select CHARID,ZONE,NAME from CHARBASE where ACCNAME = '".trim($datasss['n_openid'])."' AND NAME = '".trim(mysql_real_escape_string($datasss['n_name']))."' ";
	$result = mysql_query($sql,$con2) or die("Invalid query: " . mysql_error());
	$total = mysql_num_rows($result);
	if($total!=1)
	{
		if($zone){
		echo "total:".$total;
		echo "sql:".$sql;
		echo "[zone:".$zone."]发送失败，原因：数据异常。";}
	}
	$row = mysql_fetch_assoc($result);
	$name = $row['NAME'];
	echo "[sql:".$row["ZONE"]."]";
	$tempzone = $row["ZONE"];
	$charid = $row["CHARID"];
	echo "[CHARID:".$charid."]";
	echo "[tempzone:".$tempzone."]";

	$items_arr = array();

	$items_arr = array_merge($items_arr,chaigift99($datasss['n_item1id'],$datasss['n_item1num']));
	$items_arr = array_merge($items_arr,chaigift99($datasss['n_item2id'],$datasss['n_item2num']));
	$items_arr = array_merge($items_arr,chaigift99($datasss['n_item3id'],$datasss['n_item3num']));
	$items_arr = array_merge($items_arr,chaigift99($datasss['n_item4id'],$datasss['n_item4num']));
	$items_arr = array_merge($items_arr,chaigift99($datasss['n_item5id'],$datasss['n_item5num']));
	$items_arr = array_merge($items_arr,chaigift99($datasss['n_item6id'],$datasss['n_item6num']));
	$items_arr = array_merge($items_arr,chaigift99($datasss['n_item7id'],$datasss['n_item7num']));
	$items_arr = array_merge($items_arr,chaigift99($datasss['n_item8id'],$datasss['n_item8num']));
	$items_arr = array_merge($items_arr,chaigift99($datasss['n_item9id'],$datasss['n_item9num']));
	$items_arr = array_merge($items_arr,chaigift99($datasss['n_item10id'],$datasss['n_item10num']));
	$items_arr = array_merge($items_arr,chaigift99($datasss['n_item11id'],$datasss['n_item11num']));
	$items_arr = array_merge($items_arr,chaigift99($datasss['n_item12id'],$datasss['n_item12num']));
	$items_arr = array_merge($items_arr,chaigift99($datasss['n_item13id'],$datasss['n_item13num']));
	$items_arr = array_merge($items_arr,chaigift99($datasss['n_item14id'],$datasss['n_item14num']));
	$items_arr = array_merge($items_arr,chaigift99($datasss['n_item15id'],$datasss['n_item15num']));

	$sql = "insert into GIFT (CHARID,ZONE,NAME,ITEMID1,ITEMID2,ITEMID3,ITEMNUM1,ITEMNUM2,ITEMNUM3,BIND1,BIND2,BIND3,MAILFROM,MAILTITLE,MAILTEXT) values";
	$insert_str = '';
	$iiiCHARID=$charid;
	$iiiname = mysql_escape_string($name);
	$iiititle= $datasss['n_title'];
	$iiicontent = $datasss['n_content'];

	foreach ($items_arr as $key => $value) 
	{
		if($key%3==0)
		{
			if($value['id'] != '')
			{
				$iiitem1id = $value['id'];
				$iiitem2id = isset($items_arr[$key+1]['id']) ? $items_arr[$key+1]['id'] : 0;
				$iiitem3id = isset($items_arr[$key+2]['id']) ? $items_arr[$key+2]['id'] : 0;
				$iiitem1num = $value['num'];
				$iiitem2num = isset($items_arr[$key+1]['num']) ? $items_arr[$key+1]['num'] : 0;
				$iiitem3num = isset($items_arr[$key+2]['num']) ? $items_arr[$key+2]['num'] : 0;
				if($iiitem1id || $iiitem2id || $iiitem3id)
				{
					$insert_str .= "('{$iiiCHARID}','{$tempzone}','{$iiiname}','{$iiitem1id}','{$iiitem2id}','{$iiitem3id}','{$iiitem1num}','{$iiitem2num}','{$iiitem3num}',1,1,1,'系统','{$iiititle}','{$iiicontent}'),";
				}
			}

		}
	}
	$insert_str = rtrim($insert_str,',');
	$iiisql = $sql.$insert_str;
        $a = '';
	if($insert_str != '')
	{
		echo $iiisql;
		$a = mysql_query($iiisql,$con2) or die("Invalid query: " . mysql_error());
	}

	if($a){
		return 1;
	}else{
		return 0;
	}
}
function chaigift99($itemid,$itemnum)
{
    $new_arr = array();
    $flag = floor($itemnum / 99) ; 
    for ($i=0; $i <$flag ; $i++) 
    { 
        $new_arr[] = array('id'=>$itemid,'num'=>'99');
    }
    $last_num = ($itemnum % 99);
    $new_arr[] = array('id'=>$itemid,'num'=>$last_num);

    return $new_arr;
}
