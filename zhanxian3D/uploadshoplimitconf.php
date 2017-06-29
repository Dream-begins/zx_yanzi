<?php
/**
 * Created by PhpStorm.
 * User: chenyunhe
 * Date: 2016/9/23
 * Time: 10:23
 * 上传限购活动配置表
 */
require_once "newweb/h_define.php";
require_once "reader.php";
require_once "db2new.php";
//error_reporting(0);
$Action = (isset($_GET['action'])) ? $_GET['action'] : '';
if(!empty($Action)){
    //CURD操作
    if($Action=='list'){
        $dbh = dbh();
        echo json_encode(getShopLimitdData($dbh));
    }elseif($Action=='sub'){
         if(empty($_POST['starttime'])){
            echo "<script>alert('请设置时间');history.go(-1);</script>";
        }
        $starttime = strtotime($_POST['starttime']);
        //应用到指定的游戏服中
        UpServer($_POST['Zone'],$starttime);

    }
}else{//处理上传的操作
    $error=$_FILES['file']["error"];//上传后系统返回的值
    if($error==0){
        $name=$_FILES['file']["name"];//上传文件的文件名
        $type=$_FILES['file']["type"];//上传文件的类型
        $size=$_FILES['file']["size"];//上传文件的大小
        $tmp_name=$_FILES['file']["tmp_name"];//上传文件的临时存放路径

        if($type!="application/vnd.ms-excel"){
            echo "<script>alert('请上传excel文件');history.go(-1);</script>";
        }
        //限制文件的大小
        if($size>5*1024 && $size==0){
            echo "<script>alert('上传文件在5M以内且不为空文件');history.go(-1);</script>";
        }
        move_uploaded_file($tmp_name,'excel/'.$name);
        $dir = "excel/ShopLimitConf.xls";
        if(!file_exists($dir)){
            echo "<script>alert('上传文件不存在');history.go(-1);</script>";
        }else{
            $dbh = dbh();
            $r = InitShopLimitConf($dir,$dbh);
            switch($r){
                case 1:echo "<script>alert('数据表初始化错误');history.go(-1);</script>";break;
                case 2:echo "<script>alert('数据更新有误');history.go(-1);</script>";break;
                case 3:echo "<script>alert('无数据');history.go(-1);</script>";break;
                case 0:
                    echo "<script>alert('导入成功');history.go(-1);</script>";
                    break;
            }
        }
    }else{
        echo "<script>alert('上传失败');history.go(-1);</script>";
    }

}


function InitShopLimitConf($filename,$dbh){
    $data = new Spreadsheet_Excel_Reader();
    $data->setOutputEncoding('utf-8');
    $data->read("$filename");

    if(!$dbh->query('TRUNCATE TABLE shoplimitconf')) return 1;

    $num = 0;
    $Str = '';
    $keys = $data->sheets[0]['cells'][2];
    $InsertStr = "INSERT INTO shoplimitconf (`ID`,`".implode("`,`",$keys)."`)VALUES";
    for($i=3;$i<=$data->sheets[0]['numRows'];$i++)//从第三行开始是配置数据
    {
        if(empty($data->sheets[0]['cells'][$i][1])){
           continue;
        }
        $combarr = array_combine($keys,$data->sheets[0]['cells'][$i]);
        $Str .= "('NULL','".implode("','",array_values($combarr))."'),";
        $num++;
    }
    if($num>0){
        $InsertSQL = $InsertStr.substr($Str,0,-1);
        $stmt = $dbh->prepare($InsertSQL);
        if($stmt->execute()){
            return 0;
        }else{
            return 2 ;
        }
    }else{
        return 3;
    }
}

function dbh(){
    $dbh = new PDO("mysql:host=".FT_MYSQL_COMMON_HOST.";dbname=admin;port=".FT_MYSQL_COMMON_PORT.";", FT_MYSQL_COMMON_ROOT, FT_MYSQL_COMMON_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->query("SET NAMES UTF8");

    return $dbh;
}
## 获取配置的数据
function getShopLimitdData($dbh){
    $page = (int)$_POST['page'];
    $rows = (int)$_POST['rows'];
    if($page <= 0) $page = 1;

    $orderby = ' ORDER BY ID ';
    $limit = ' LIMIT ' . ($page-1)*$rows . ',' . $rows;

    $SetSQL = "SELECT `ID`, `PAGE`, `OBJID`, `OBJNAME`, `SUPERMARKETPOS`, `MONEYTYPE`, `ORIGINALPRICE`, `DISCONTPRICE`, `ISBIND`, `SINGLECANBUYNUM`, `TOTALBUYLIMITNUM`, `OPENTYPE`, `OPENTTIME`, `CLOSETIME`, `LIMITEDPURCHASESTARTTIME`, `LIMITEDPURCHASEENDTIME`, `TAGTYPE`, `NEEDVIPLEVEL`, `INDEXID`, `ICON`, `TITLE`, `WEIGHT`,MONEYID,AWARDS,STAGEPRICES,PRESHOW,`DESC` FROM  shoplimitconf".$orderby.$limit;

    $stmt = $dbh->prepare($SetSQL);
    $stmt->execute();
    $result = $stmt->fetchAll();

    $SetSQL = "select count(*) as num from shoplimitconf";
    $stmt = $dbh->prepare($SetSQL);
    $stmt->execute();
    $row = $stmt->fetch();

    $r['rows'] = $result;
    $r['total'] =$row['num'];

    return $r;
}


## 将配置更新到指定的服务器中
function UpServer($Zone,$starttime){
    $ZoneArr = CheckZone($Zone);
    $SetSQL = MakeSQL($starttime);
   
    foreach($ZoneArr as $i){
        $dburl = getDBUrl($i);
        if(!$dburl){
            echo "<font color='red'>$i 服清理限购物品失败</font><br/>";
            continue;
        }
        $host = explode(':',$dburl);
        $dbh = new PDO("mysql:host=".$host[0].";dbname=".getDBName($i).";port=".$host[1].";", FT_MYSQL_COMMON_ROOT, FT_MYSQL_COMMON_PASS);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $dbh->query("SET NAMES UTF8");
        
        $stmt = $dbh->prepare($SetSQL);
        if($stmt->execute()){
            echo "$i 服配置限购物品成功<br/>";
        }else{
            echo "$i 服配置限购物品失败<br/>";
        }
    }
}


## 拼接sql语句
function MakeSQL($starttime){
    $dbh =  dbh();
    $SetSQL = "SELECT `PAGE`, `OBJID`, `OBJNAME`, `SUPERMARKETPOS`, `MONEYTYPE`, `ORIGINALPRICE`, `DISCONTPRICE`, `ISBIND`, `SINGLECANBUYNUM`, `TOTALBUYLIMITNUM`, `OPENTYPE`, `OPENTTIME`, `CLOSETIME`, `LIMITEDPURCHASESTARTTIME`, `LIMITEDPURCHASEENDTIME`, `TAGTYPE`, `NEEDVIPLEVEL`, `INDEXID`, `ICON`, `TITLE`, `WEIGHT`,MONEYID,AWARDS,STAGEPRICES,PRESHOW,`DESC` FROM  shoplimitconf ORDER BY ID";

    $stmt = $dbh->prepare($SetSQL);
    $stmt->execute();
    $result = $stmt->fetchAll();
     
    $StartDate = date('Y-m-d',$starttime);

    $InserStr = "INSERT INTO `SHOPLIMIT`(`ID`,`PAGE`, `OBJID`, `OBJNAME`, `SUPERMARKETPOS`, `MONEYTYPE`, `ORIGINALPRICE`, `DISCONTPRICE`, `ISBIND`, `SINGLECANBUYNUM`, `TOTALBUYLIMITNUM`, `OPENTYPE`, `OPENTTIME`, `CLOSETIME`, `LIMITEDPURCHASESTARTTIME`, `LIMITEDPURCHASEENDTIME`, `TAGTYPE`, `NEEDVIPLEVEL`, `INDEXID`, `ICON`, `TITLE`, `WEIGHT`,MONEYID,AWARDS,STAGEPRICES,PRESHOW,`DESC`)VALUES";
    $Str = '';
    foreach($result as $v){
        if($v['LIMITEDPURCHASESTARTTIME']==1){
            $v['LIMITEDPURCHASESTARTTIME'] = $StartDate;
        }else{
            $diff = $v['LIMITEDPURCHASESTARTTIME']-1;
            $v['LIMITEDPURCHASESTARTTIME'] = date('Y-m-d',($diff*86400+$starttime));
        }

        if($v['LIMITEDPURCHASEENDTIME']==1){
            $v['LIMITEDPURCHASEENDTIME'] = $StartDate;
        }else{
            $diff = $v['LIMITEDPURCHASEENDTIME']-1;
            $v['LIMITEDPURCHASEENDTIME'] = date('Y-m-d',($diff*86400+$starttime));
        }
        $v['INDEXID'] = time();
        $Str .= "('NULL','".implode("','",array_values($v))."'),";
    }
    $InsertSQL = $InserStr.substr($Str,0,-1);

    return $InsertSQL;

}


## 验证提交服务器的参数
function CheckZone($Zone){
    if(empty($Zone)){
        echo "<script>alert('请填写服务器');history.go(-1);</script>";
    }

    $ZoneArr = array();
    //参数以“-”分割的，为连续服务器
    if(strpos($Zone,'-')){
        $ServerArr = explode('-',$Zone);
        if($ServerArr[0]<=0 || $ServerArr[1]<=0 || $ServerArr[0]>$ServerArr[1]){
            echo "<script>alert('服务器格式有误');history.go(-1);</script>";
        }
        for($i=$ServerArr[0];$i<=$ServerArr[1];$i++){
            $ZoneArr[] = $i;
        }
        return $ZoneArr;
    }elseif(strpos($Zone,',')){//参数以“,”分割的，可连续，也可以不连续
        $ExArr = explode(',',$Zone);
        foreach($ExArr as $k=>$v){
            if($v>0){
                $ZoneArr[] = $v;
            }
        }

        return $ZoneArr;
    }elseif($Zone>0){
        return array($Zone);
    }else{
        echo "<script>alert('服务器格式有误');history.go(-1);</script>";
    }
}
?>
