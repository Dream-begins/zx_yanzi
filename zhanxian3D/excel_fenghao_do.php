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
$numRows = ($data->sheets[0]['numRows']>0) ? $data->sheets[0]['numRows']-1 : 0 ;
echo "[TOTAL:".$numRows."]\r\n";

if($numRows==0) exit('无数据');

$dbh = new PDO('mysql:host='.FT_MYSQL_ZONE_MSG_HOST.';dbname='.FT_MYSQL_ZONE_MSG_DBNAME.';port='.FT_MYSQL_ZONE_MSG_PORT.';charset=utf8',FT_MYSQL_ZONE_MSG_ROOT,FT_MYSQL_ZONE_MSG_PASS);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);      //设置异常模式为抛出异常
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); //为语句设置默认获取方式
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);              //false关闭本地预处理 使用mysql预处理
$dbh->query("SET NAMES UTF8");

$arr= array();
for($i=2;$i<=$data->sheets[0]['numRows'];$i++)
{
    $zone = @$data->sheets[0]['cells'][$i][1];
    $accname = @$data->sheets[0]['cells'][$i][2];
    $new_zone = "%,".$zone.",%";
    $sql = "SELECT zone_id,mysql_ip, mysql_port, mysql_dbName FROM zone_msg WHERE CONCAT(',',zones,',') LIKE :zone ";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':zone', $new_zone);
    $stmt->execute();
    $return_arr = $stmt->fetch();

    if(empty($return_arr)){
        exit('区服'.$zone.'不存在');
    }

    $charbase=charbase($return_arr,$accname);
    if(!$charbase){
        exit('区服：'.$zone.'|accname:'.$accname.'不存在');
    }else{
        $temp['gm_code'] = $charbase['NAME'];
        $temp['zone']=$charbase['ZONE'];
        $arr[]=$temp;
    }
}
$endTime = microtime(true);
$dbh=null;

if(empty($arr)){
    exit('No data');
}

$admin_name = isset($_SESSION['xwusername']) ? $_SESSION['xwusername'] : '';
$FromIP   = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : '';

$FlagId = time();
$_SESSION['FlagId'] = $FlagId;

$sql = 'INSERT INTO `GM_COMMAND`(`SN`,`FromIP`,`ServerID`,`Command`,`Status`,`OptTimeStamp`,`FlagId`,`CmdLength`,`ADMIN`) VALUES';
$values = '';
foreach ($arr as $key => $value)
{
    $Command  = ltrim($value['gm_code'],'/');
    $CmdLength = strlen($Command);
    $OptTimeStamp = time();
    $SN = md5(uniqid(rand(), true));
    $ServerID = $value['zone'];
    $Command  = "ban name={$Command} zone={$ServerID}";
    $values .= "('{$SN}','{$FromIP}','{$ServerID}','{$Command}','1','{$OptTimeStamp}','{$FlagId}','{$CmdLength}','{$admin_name}'),";
}

$values = trim($values,',');
if($values != '')
{
    $exdbh = new PDO('mysql:host='.FT_MYSQL_COMMON_HOST.';dbname='.FT_MYSQL_EXTGAMESERVER_DBNAME.';port='.FT_MYSQL_COMMON_PORT.';charset=utf8',FT_MYSQL_COMMON_ROOT,FT_MYSQL_COMMON_PASS);
    $exdbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);      //设置异常模式为抛出异常
    $exdbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); //为语句设置默认获取方式
    $exdbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);              //false关闭本地预处理 使用mysql预处理
    $exdbh->query("SET NAMES UTF8");
    $sql .= $values;
    $extmts=$exdbh->prepare($sql);


    if( $extmts->execute())
    {
        $total = count($arr);
        echo '命令入库成功:'.$total.'FlagID:'.$FlagId;
    }else{
        echo '命令入库失败';
    }
}else{
    echo '无数据';
}


//if($action == 'monitor_list')
//{
//    $result_arr = array();
//    $FlagId = '';
//
//    if($FlagId)
//    {
//        $sql = "SELECT * FROM `GM_COMMAND` WHERE `FlagId` = '{$FlagId}'";
//        $result = mysql_query($sql);
//        while ($row = mysql_fetch_assoc($result))
//        {
//            $result_arr[] = $row;
//        }
//    }else{
//        return ;
//    }
//
//    $num_total = count($result_arr);
//    $num_wait = 0;
//    $num_running = 0;
//    $num_done = 0;
//
//    foreach ($result_arr as $key => $value)
//    {
//        switch ($value['Status'])
//        {
//            case '1':
//                $num_wait++;
//                break;
//            case '2':
//                $num_done++;
//                break;
//            case '3':
//                $num_done++;
//                break;
//        }
//    }
//
//    echo "[FlagId:{$FlagId}]<br>";
//    echo "总计:{$num_total},等待执行:{$num_wait},<font color='green'>执行完成:{$num_done}</font><br>";
//
//    $result_message_arr = array();
//    $message_arr_new = array();
//
//    $sql = "select count(*) as num from `GM_RESULT_RECORD` WHERE `FlagId` = '{$FlagId}'";
//    $result = mysql_query($sql);
//    $row = mysql_fetch_assoc($result);
//    $result_codes_num = $row['num'];
//
//}


function charbase($zone_msg_info,$ACCNAME){
    if(!$zone_msg_info) return NULL;
    if(!isset($zone_msg_info['mysql_ip'])) return NULL;

    $chardbh = new PDO('mysql:host='.$zone_msg_info['mysql_ip'].';dbname='.$zone_msg_info['mysql_dbName'].';port='.$zone_msg_info['mysql_port'].';charset=utf8', 'root', 'hoolai@123');

    $chardbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);      //设置异常模式为抛出异常
    $chardbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); //为语句设置默认获取方式
    $chardbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);              //false关闭本地预处理 使用mysql预处理
    $chardbh->query("SET NAMES utf8");

    $sql = "SELECT  NAME,ZONE FROM CHARBASE WHERE ACCNAME = :ACCNAME";
    $stmt = $chardbh->prepare($sql);

    $stmt->bindParam(':ACCNAME', $ACCNAME);

    $stmt->execute();
    $result = $stmt->fetch();

    return $result;
}


