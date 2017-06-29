<?php
/**
 * Created by PhpStorm.
 * User: chenyunhe
 * Date: 2017/1/6
 * Time: 15:21
 */
header("Content-Type:text/html;charset=UTF-8");
ini_set('display_errors', '1');
error_reporting (7); // Report everything
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
$numRows = (int)$data->sheets[0]['numRows']-1;
echo "[TOTAL:".$numRows."]\r\n";

if($numRows==0) exit('no data');

$dbh = new PDO('mysql:host='.FT_MYSQL_ZONE_MSG_HOST.';dbname='.FT_MYSQL_ZONE_MSG_DBNAME.';port='.FT_MYSQL_ZONE_MSG_PORT.';charset=utf8',FT_MYSQL_ZONE_MSG_ROOT,FT_MYSQL_ZONE_MSG_PASS);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);      //设置异常模式为抛出异常
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); //为语句设置默认获取方式
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);              //false关闭本地预处理 使用mysql预处理
$dbh->query("SET NAMES UTF8");


$error=array();
for($i=2;$i<=$data->sheets[0]['numRows'];$i++)
{
    $email['zone'] = @$data->sheets[0]['cells'][$i][1];
    $email['name'] = @$data->sheets[0]['cells'][$i][2];
    $email['accname'] = @$data->sheets[0]['cells'][$i][3];

    $email['title'] = @$data->sheets[0]['cells'][$i][4];
    $email['content'] = @$data->sheets[0]['cells'][$i][5];
    $email['proid1'] = @$data->sheets[0]['cells'][$i][6];
    $email['pronum1'] = @$data->sheets[0]['cells'][$i][7];
    $email['proid2'] = @$data->sheets[0]['cells'][$i][8];
    $email['pronum2'] = @$data->sheets[0]['cells'][$i][9];
    $email['proid3'] = @$data->sheets[0]['cells'][$i][10];
    $email['pronum3'] = @$data->sheets[0]['cells'][$i][11];

    $new_zone = "%,".$email['zone'].",%";
    $sql = "SELECT zone_id,mysql_ip, mysql_port, mysql_dbName FROM zone_msg WHERE CONCAT(',',zones,',') LIKE :zone ";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':zone', $new_zone);
    $stmt->execute();
    $return_arr = $stmt->fetch();

    $tmp['zone']= $email['zone'];
    $tmp['name']= $email['name'];
    $tmp['accname']=  $email['accname'];
    if(empty($return_arr)){
        $tmp['mes'] = '区服不存在';
        $error[]=$tmp;
    }else{
        $flag=sendemail($return_arr,$email);
        if($flag==0){
            $tmp['mes'] = '角色不存在';
            $error[]=$tmp;
        }else{
            $cnt++;
        }
    }



}
$endTime = microtime(true);
?>

    发放成功，共发放<?php echo $cnt; ?>条
    执行时间1:<?php echo "sss:".round(($endTime - $time2),4);  ?>,<?php echo round(($endTime - $startTime),4);?>
    失败:<?php print_r($error);
function sendemail($zone_msg_info,$email){
    $chardbh = new PDO('mysql:host='.$zone_msg_info['mysql_ip'].';dbname='.$zone_msg_info['mysql_dbName'].';port='.$zone_msg_info['mysql_port'].';charset=utf8', 'root', 'hoolai@123');
    //$chardbh = new PDO('mysql:host='.$zone_msg_info['mysql_ip'].';dbname='.$zone_msg_info['mysql_dbName'].';port='.$zone_msg_info['mysql_port'].';charset=utf8', 'root', '');

    $chardbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);      //设置异常模式为抛出异常
    $chardbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); //为语句设置默认获取方式
    $chardbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);              //false关闭本地预处理 使用mysql预处理
    $chardbh->query("SET NAMES utf8");

    $sql = "SELECT  CHARID,NAME,ZONE FROM CHARBASE WHERE ACCNAME = :ACCNAME";
    $stmt = $chardbh->prepare($sql);

    $stmt->bindParam(':ACCNAME', $email['accname']);

    $stmt->execute();
    $row = $stmt->fetch();

    if(empty($row)){
        return 0;
    }else{
        $sql = "insert into GIFT (CHARID,ZONE,NAME,ITEMID1,ITEMID2,ITEMID3,ITEMNUM1,ITEMNUM2,ITEMNUM3,BIND1,BIND2,BIND3,MAILFROM,MAILTITLE,MAILTEXT) value(".$row["CHARID"].",'".$row["ZONE"]."','".$row["NAME"]."',".$email['proid1'].",".$email['proid2'].",".$email['proid3'].",".$email['pronum1'].",".$email['pronum2'].",".$email['pronum3'].",'1','1','1','系统','".$email['title']."','".$email['content']."');";
        $stmt = $chardbh->prepare($sql);
        $stmt->execute();

        return 1;
    }

}
