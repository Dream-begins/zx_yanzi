<?php
if( PHP_SAPI !== 'cli' ) exit;
header("Content-Type:text/html;charset=UTF-8");
ini_set('default_charset','utf-8');
date_default_timezone_set("PRC");
session_start();
set_time_limit(0);
ini_set("memory_limit", "500M");
ini_set('display_errors', 1);
error_reporting(0);

//h_auto_gift 取数据
    $dbh = new PDO('mysql:host=117.103.235.92;dbname=admin;port=3306;charset=utf8', 'root', 'hoolai@123');
//    $dbh = new PDO('mysql:host=127.0.0.1;dbname=admin;port=3306;charset=utf8', 'root', '');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->query("SET NAMES UTF8");


    //$time = time();
    $time = strtotime(date("Y-m-d",time()));
    //$time = strtotime(date("2017-2-14",time()));

    $sql = "SELECT id,ACCNAME,NAME,ctime,cadmin,zoneid,grant_start,grant_end,item1,bind1,num1,item2,bind2,num2,item3,bind3,num3,mtitle,m_content,rate,dotime,state,last_time FROM `h_auto_gift` WHERE state = 0 and grant_start<=$time;";

    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $gift_result = $stmt->fetchAll();
 

    foreach ($gift_result as $key => $value) 
    {       $rate = $value['rate']*86400;//频率转换为时间戳
            $next_time = $value['last_time']+$rate; //下次执行时间

            //上次执行时间为0，立刻执行
            if( $value['last_time'] == 0){
                $dbh->exec("UPDATE `h_auto_gift` SET last_time={$time} WHERE id = {$value['id']} ;");
                continue;
            }
            //下次执行时间小于最后发送时间
            if($next_time <= $value['grant_end']){

                if($time == $next_time and $value['grant_end'] != $next_time){

                    $dbh->exec("UPDATE `h_auto_gift` SET last_time={$time} WHERE id = {$value['id']} ;");
                }elseif($time == $next_time and $time == $value['grant_end']){

                    $dbh->exec("UPDATE `h_auto_gift` SET state = 1, last_time={$time} WHERE id = {$value['id']} ;");
                }else{

                    unset($gift_result[$key]);
                }

            }
            //下次执行时间大于最后发送时间，设置过期，不执行
            else{

                $dbh->exec("UPDATE `h_auto_gift` SET state = 1 WHERE id = {$value['id']} ;");
                unset($gift_result[$key]);
            }

    }
//print_r($gift_result);exit;
    $dbh = NULL;

	if(count($gift_result) == 0) exit;

	$dbh3 = new PDO('mysql:host=117.103.235.92;dbname=ExtGameServer;port=3306;charset=utf8', 'root', 'hoolai@123');
//	$dbh3 = new PDO('mysql:host=127.0.0.1;dbname=ExtGameServer;port=3306;charset=utf8', 'root', '');
	$dbh3->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh3->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$dbh3->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$dbh3->query("SET NAMES UTF8");


//zone_msg取数据->执行逻辑
    $zone_msg = new ZoneMsgInfo;
	$domians2zoneid_array = $zone_msg->domians2zoneid_array();

    $zones2server_id_array = $zone_msg->zones2server_id(); //前端区 => 后端服
    $zones_arr = array();
    $flag_arr = array();

    foreach ($gift_result as $key => $value) 
    {
        $zones_arr = str_format_arr_1( $value['zoneid'] );
        $server_id_arr = array();

        if(!is_array($zones_arr)) continue;
        foreach ($zones_arr as $k => $v)
        {
            $server_id = isset($zones2server_id_array[$v]) ? $zones2server_id_array[$v] : null;
            if( !in_array($server_id, $server_id_arr) ) $server_id_arr[] = $server_id;
        }

		foreach ($server_id_arr as $k => $v) 
		{
			if($v != '')
			{
				$zone_msg_info = $zone_msg->server_id2infos($v);
				$charbase_info = new CharbaseInfo($zone_msg_info);
				$users_datas = $charbase_info->get_acc_list($value['NAME'], $value['ACCNAME']);
				if(count($users_datas) > 0) $charbase_info->togifts($users_datas, $value);

				$SN = md5(uniqid(rand(), true));
				$OptTimeStamp = time();
				$FlagId = time();
			 	$sql1 = "INSERT INTO `GM_COMMAND`(`SN`,`FromIP`,`ServerID`,`Command`,`Status`,`OptTimeStamp`,`FlagId`,`CmdLength`,`ADMIN`) VALUES('$SN', '127.0.0.1', '".$domians2zoneid_array[$v]."', 'loadgift', 1, '$OptTimeStamp', '$FlagId', '8', 'local_bin') ";
				$dbh3->query($sql1);

			}
		}
    }


/**
* @param string like '1,3,4-10,14'
* @return array like array(1,3,4,5,6,7,8,9,10,12)
*/
function str_format_arr_1($in_str)
{
    $in_str = trim($in_str);
    $str_flag1 = explode(',',$in_str);  
    $return_arr = array();

    foreach ($str_flag1 as $key => $value) 
    {
        if(strpos($value,'-') !== false)
        {
            $value_flag1 = explode('-',$value);
            if(count($value_flag1) != 2 || strlen($value_flag1[0]) != strlen(intval($value_flag1[0])) || strlen($value_flag1[1]) != strlen(intval($value_flag1[1])))
            {
                return false;
            }else{
                for($i = $value_flag1[0];$i<=$value_flag1[1]; $i++)
                {
                    $return_arr[] = $i;
                }
            }
        }else
        {
            $return_arr[] =trim($value);
        }
    }

    foreach ($return_arr as $key => $value) 
    {
        if( strlen($value) != strlen(intval($value)) )
        {
            return false;
        }
    }

    $value_counts = array_count_values($return_arr);

    foreach ($value_counts as $key => $value) 
    {
        if($value > 1)
        {
            return false;
        }
    }

    return $return_arr;
}

/**
 * zone_msg 模型类 从zone_msg获取各种数据
 */
class ZoneMsgInfo
{
    public $dbh = NULL;
    
    function __construct()
    {
        $this->dbh = new PDO('mysql:host=10.104.222.134;dbname=fentiansj;port=3306;charset=utf8', 'root', 'hoolai@123');
//        $this->dbh = new PDO('mysql:host=127.0.0.1;dbname=fentiansj;port=3306;charset=utf8', 'root', '');
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->dbh->query("SET NAMES utf8");
    }

    function __destruct()
    {
        $this->dbh = NULL;
    }

    /**
     * @param int $server_d 合并后 服
     * @return array array('zone_id'=>'xxx', 'server_id'=>'xxx', 'domians'=>'xxx', 'mysql_ip'=>'xxx', 'mysql_port'=>'', mysql_dbName=>'xxx' );
     */
    public function server_id2infos($server_id)
    {
        $sql = "SELECT zone_id, server_id, zones, domians, mysql_ip, mysql_port, mysql_dbName FROM zone_msg WHERE server_id = :server_id ";
        
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':server_id', $server_id);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    /**
     * @desc 获取 合并前区 => 合并后服 映射数组
     * @return array('合并前区1'=>'合并后服1', '合并前区2'=>'合并后服2' ...);
     */
    public function zones2server_id()
    {
        $stmt = $this->dbh->prepare('SELECT server_id,zone_id, zones, domians FROM zone_msg');
        $stmt->execute();
        $result = $stmt->fetchAll();

        $zones2server_id_array = array();
        $zones_arr = array();
        $flag_arr = array();
        foreach ($result as $key => $value) 
        {
            $zones_arr = explode(',', trim( $value['zones'], ',' ) );

            foreach ($zones_arr as $k => $v) 
            {
                $zones2server_id_array[$v] = isset($value['server_id']) ? $value['server_id'] : '';
            }
        }

        return $zones2server_id_array;
    }
public function domians2zoneid_array()
{
	$stmt = $this->dbh->prepare('SELECT zone_id, zones, domians FROM zone_msg');
	$stmt->execute();
	$result = $stmt->fetchAll();

	$domians2zoneid_array = array();
	$domians_arr = array();
	$flag_arr = array();
	foreach ($result as $key => $value) 
	{
		$domians_arr = explode(',', trim( $value['domians'], ',' ) );

		foreach ($domians_arr as $k => $v) 
		{
			$domians2zoneid_array[$v] = $value['zone_id'];
		}
	}

	return $domians2zoneid_array;
}
}

/**
 * CHARBASE 模型类 从CHARBASE获取各种数据
 */

class CharbaseInfo
{
    public $dbh = NULL;
    
    function __construct( $zone_msg_info )
    {
        if(!$zone_msg_info) return NULL;

        $this->dbh = new PDO('mysql:host='.$zone_msg_info['mysql_ip'].';dbname='.$zone_msg_info['mysql_dbName'].';port='.$zone_msg_info['mysql_port'].';charset=utf8', 'root', 'hoolai@123');//本地调试
//        $this->dbh = new PDO('mysql:host='.$zone_msg_info['mysql_ip'].';dbname='.$zone_msg_info['mysql_dbName'].';port='.$zone_msg_info['mysql_port'].';charset=utf8', 'root', '');//本地调试

        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);      //设置异常模式为抛出异常
        $this->dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); //为语句设置默认获取方式
        $this->dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);              //false关闭本地预处理 使用mysql预处理
        $this->dbh->query("SET NAMES utf8");
    }

    function __destruct()
    {
        $this->dbh = NULL;
    }

    /**
     * @desc 根据条件查询帐号信息
     */
    public function get_acc_list($NAME, $ACCNAME)
    {
        $sql = "SELECT CHARID,ZONE,NAME 
                FROM CHARBASE ";

        $WHERE = " WHERE 1 ";
        $WHERE .= ' AND NAME = :NAME ';
        $WHERE .= ' AND ACCNAME = :ACCNAME ';

//        if($CREATETIME1 > 90000) $WHERE .= ' AND CREATETIME >= :CREATETIME1 ';
//        if($CREATETIME2 > 90000) $WHERE .= ' AND CREATETIME <= :CREATETIME2 ';
//        if($LASTACTIVEDATE1 > 90000) $WHERE .= ' AND UNIX_TIMESTAMP(LASTACTIVEDATE) >= :LASTACTIVEDATE1 ';
//        if($LASTACTIVEDATE2 > 90000) $WHERE .= ' AND UNIX_TIMESTAMP(LASTACTIVEDATE) <= :LASTACTIVEDATE2 ';
//        if($LEVEL1 > 0) $WHERE .= ' AND LEVEL >= :LEVEL1 ';
//        if($LEVEL2 > 0) $WHERE .= ' AND LEVEL <= :LEVEL2 ';


        $sql .= $WHERE;

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':NAME',$NAME);
        $stmt->bindParam(':ACCNAME',$ACCNAME);
//        if($CREATETIME1 > 90000) $stmt->bindParam(':CREATETIME1',$CREATETIME1);
//        if($CREATETIME2 > 90000) $stmt->bindParam(':CREATETIME2',$CREATETIME2);
//        if($LASTACTIVEDATE1 > 90000) $stmt->bindParam(':LASTACTIVEDATE1',$LASTACTIVEDATE1);
//        if($LASTACTIVEDATE2 > 90000) $stmt->bindParam(':LASTACTIVEDATE2',$LASTACTIVEDATE2);
//        if($LEVEL1 > 0) $stmt->bindParam(':LEVEL1',$LEVEL1);
//        if($LEVEL2 > 0) $stmt->bindParam(':LEVEL2',$LEVEL2);

        $stmt->execute();
        $result = $stmt->fetchAll();

        return $result;
    }

    public function togifts($users_datas, $contents)
    {
        $sql = "INSERT INTO GIFT(CHARID,ZONE,NAME,ITEMID1,ITEMID2,ITEMID3,ITEMNUM1,ITEMNUM2,ITEMNUM3,BIND1,BIND2,BIND3,MAILFROM,MAILTITLE,MAILTEXT) VALUES ";

        $cnt = 0;
        $sqlval = '';

        foreach ($users_datas as $key => $value) 
        {
/*
            $sqlval .= "('".$value['CHARID']."','".$value['ZONE']."','".$value['NAME']."','".$contents['item1'].
                    "','".$contents['item2']."','".$contents['item3']."','".$contents['num1']."','".$contents['num2'].
                    "','".$contents['num3']."','".$contents['bind1']."','".$contents['bind2']."','".$contents['bind3'].
                    "','系统','".$contents['mtitle']."','".$contents['m_content']."'),";
*/

            $sqlval .= "('".mysql_escape_string($value['CHARID'])."','".mysql_escape_string($value['ZONE'])."','".mysql_escape_string($value['NAME'])."','".mysql_escape_string($contents['item1']).
                    "','".mysql_escape_string($contents['item2'])."','".mysql_escape_string($contents['item3'])."','".mysql_escape_string($contents['num1'])."','".mysql_escape_string($contents['num2']).
                    "','".mysql_escape_string($contents['num3'])."','".mysql_escape_string($contents['bind1'])."','".mysql_escape_string($contents['bind2'])."','".mysql_escape_string($contents['bind3']).
                    "','系统','".mysql_escape_string($contents['mtitle'])."','".mysql_escape_string($contents['m_content'])."'),";


            $cnt += strlen($sqlval);

            if($cnt >= 5000)
            {
                $dosql =trim($sql.$sqlval,',');
                $stmt = $this->dbh->prepare($dosql);
                $stmt->execute();
                $cnt = 0;
                $sqlval = '';
            }
        }

        if($cnt>0)
        {
            $dosql= trim($sql.$sqlval,',');
            $stmt = $this->dbh->prepare($dosql);
            $stmt->execute();
        }
        error_log( date('Y-m-d H:i:s').'-'.count($users_datas)." 人-".$users_datas[0]['ZONE']." 服\r\n", 3, "/tmp/timeing_gift_".date('Y-m-d').".log");    
    }
}
