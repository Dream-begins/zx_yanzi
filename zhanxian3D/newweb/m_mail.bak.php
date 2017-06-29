<?php
/**
 * MAIL 模型类 从MAIL获取各种数据
 */
class MAILInfo
{
    public $dbh = NULL;
    
    function __construct( $zone_msg_info )
    {
        if(!$zone_msg_info) return NULL;

        $this->dbh = new PDO('mysql:host='.$zone_msg_info['mysql_ip'].';dbname='.$zone_msg_info['mysql_dbName'].';port='.$zone_msg_info['mysql_port'].';charset=utf8', PDO_ZONE_ROOT, PDO_ZONE_PASS);

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
     * @desc 获取MAIL表详情
     * @param $limit string
     * @return $result array()
     */
    public function get_list($limit='limit 1')
    {
        $sql = "SELECT ID,STATE,FROMZONE,FROMNAME,TOZONE,TONAME,
                       TITLE,TYPE,FROM_UNIXTIME(CREATETIME) AS CREATETIME,
                       FROM_UNIXTIME(DELTIME) AS DELTIME,ACCESSORY,ITEMGOT,
                       `TEXT`,SENDMONEYNUMA,SENDMONEYNUMB,TOID,FROMID 
                       FROM `MAIL` $limit ";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    /**
     * @desc 获取MAIL表总数
     */
    public function get_total()
    {
        $sql = "SELECT COUNT(1) AS nu FROM `MAIL`";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();

        return isset($result['nu']) ? $result['nu'] : 0;
    }

    /**
     * @desc 获取MAIL表详情where TONAME = xxx
     * @param $name string 
     * @param $limit string
     * @return $result array()
     */
    public function get_list_name($name, $limit='limit 1')
    {
        $sql = "SELECT ID,STATE,FROMZONE,FROMNAME,TOZONE,TONAME,
                       TITLE,TYPE,FROM_UNIXTIME(CREATETIME) AS CREATETIME,
                       FROM_UNIXTIME(DELTIME) AS DELTIME,ACCESSORY,ITEMGOT,
                       `TEXT`,SENDMONEYNUMA,SENDMONEYNUMB,TOID,FROMID 
                       FROM `MAIL` WHERE TONAME = :name $limit ";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    /**
     * @desc 获取MAIL表总数 where TONAME = xxx
     */
    public function get_total_name($name)
    {
        $sql = "SELECT COUNT(1) AS nu FROM `MAIL` WHERE TONAME = :name";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        $result = $stmt->fetch();

        return isset($result['nu']) ? $result['nu'] : 0;
    }
}
