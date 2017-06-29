<?php
/**
 * GIFT 模型类 从GIFT获取各种数据
 */
class GIFTInfo
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
     * @desc 获取GIFT表详情
     * @param $limit string
     * @return $result array()
     */
    public function get_list($limit='limit 1')
    {
        $sql = "SELECT ACTID,ZONE,NAME,CHARID,ITEMGOT,ITEMID1,ITEMID2,ITEMID3,
                       ITEMNUM1,ITEMNUM2,ITEMNUM3,BIND1,BIND2,BIND3,MAILFROM,
                       MAILTITLE,MAILTEXT 
                       FROM `GIFT` $limit ";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    /**
     * @desc 获取GIFT表总数
     */
    public function get_total()
    {
        $sql = "SELECT COUNT(1) AS nu FROM `GIFT`";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();

        return isset($result['nu']) ? $result['nu'] : 0;
    }

    /**
     * @desc 获取GIFT表详情where NAME = xxx
     * @param $name string 
     * @param $limit string
     * @return $result array()
     */
    public function get_list_name($name, $limit='limit 1')
    {
        $sql = "SELECT ACTID,ZONE,NAME,CHARID,ITEMGOT,ITEMID1,ITEMID2,ITEMID3,
                       ITEMNUM1,ITEMNUM2,ITEMNUM3,BIND1,BIND2,BIND3,MAILFROM,
                       MAILTITLE,MAILTEXT 
                       FROM `GIFT` WHERE NAME = :name $limit ";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    /**
     * @desc 获取GIFT表总数 where NAME = xxx
     */
    public function get_total_name($name)
    {
        $sql = "SELECT COUNT(1) AS nu FROM `GIFT` WHERE NAME = :name";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        $result = $stmt->fetch();

        return isset($result['nu']) ? $result['nu'] : 0;
    }

    public function gift_del($NAME, $MAILTITLE)
    {
        $sql = "DELETE FROM `GIFT` WHERE `NAME`= :NAME AND `ITEMGOT`='0' AND `MAILTITLE` = :MAILTITLE ";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':NAME', $NAME);
        $stmt->bindParam(':MAILTITLE', $MAILTITLE);

        return $stmt->execute();
    }
}
