<?php
/**
 * CHARBASE 模型类 从CHARBASE获取各种数据
 */

class CharbaseInfo
{
    public $dbh = NULL;
    
    function __construct( $zone_msg_info )
    {
        if(!$zone_msg_info) return NULL;
        if(!isset($zone_msg_info['mysql_ip'])) return NULL;

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
     * @desc 获取开服时间
     * @param $domian int 合并前服
     * @return $createtime string Y-m-d H:i:s
     */
    public function get_zone_start_time( $domian )
    {
        $stmt = $this->dbh->prepare("SELECT FROM_UNIXTIME( MIN(CREATETIME), '%Y-%m-%d %H:%i:%s' ) AS createtime FROM CHARBASE WHERE CREATETIME > 0");
        
        $stmt->execute();
        $createtime = $stmt->fetch();
        return isset($createtime['createtime']) ? $createtime['createtime'] : NULL;
    }

    /**
     * @desc 获取总创建账号数
     */
    public function get_total_acc()
    {
        $stmt = $this->dbh->prepare("SELECT COUNT(*) AS total FROM CHARBASE"); 
        $stmt->execute();
        $result = $stmt->fetch();
        
        return $total = isset($result['total']) ? $result['total'] : NULL;
    }

    /**
     * @desc 根据条件查询帐号信息
     */
    public function get_acc_list( $ACCNAME='', $NAME='', $orderby='', $limit='limit 1' )
    {
        if( !$ACCNAME && !$NAME )
        {
            $sql = "SELECT ACCNAME, NAME, ZONE, LEVEL, VIP, SHENQI, COUNTRY, MONEY1, MONEY3, MONEY5,
                    FROM_UNIXTIME(CREATETIME) AS CREATETIME, LASTACTIVEDATE, FROM_UNIXTIME(FORBIDTALK) AS FORBIDTALK, 
                    CHARID, MAPID, LINEID, BITMASK, ACCPRIV, HUANGZAN, HP, MONEY2, ZHENQI, 
                    MONEY4, MONEY5, MONEY6, MONEY9, MONEY10, MONEY13,CREATEIP FROM CHARBASE " . $orderby . ' ' . $limit ;

            $stmt = $this->dbh->prepare($sql);

            $stmt->execute();
            $result = $stmt->fetchAll();
        }else
        {
            $sql = "SELECT ACCNAME, NAME, ZONE, LEVEL, VIP, SHENQI, COUNTRY, MONEY1, MONEY3, 
                    FROM_UNIXTIME(CREATETIME) AS CREATETIME, LASTACTIVEDATE, FROM_UNIXTIME(FORBIDTALK) AS FORBIDTALK, 
                    CHARID, MAPID, LINEID, BITMASK, ACCPRIV, HUANGZAN, HP, MONEY2, ZHENQI, 
                    MONEY4, MONEY5, MONEY6, MONEY9, MONEY10, MONEY13,CREATEIP FROM CHARBASE WHERE ACCNAME = :ACCNAME OR NAME = :NAME ";
            $stmt = $this->dbh->prepare($sql);

            $stmt->bindParam(':ACCNAME', $ACCNAME);
            $stmt->bindParam(':NAME', $NAME);
            $stmt->execute();
            $result = $stmt->fetchAll();
        }

        return $result;
    }
    public function get_acc_list3( $ACCNAME='', $NAME='', $orderby='', $limit='limit 1' )
    {
       if(!isset($zone_msg_info['mysql_ip'])) return NULL;
        if( !$ACCNAME && !$NAME )
        {
            $sql = "SELECT ACCNAME, NAME, ZONE, LEVEL, VIP, SHENQI, COUNTRY, MONEY1, MONEY3, MONEY5,
                    FROM_UNIXTIME(CREATETIME) AS CREATETIME, LASTACTIVEDATE, FROM_UNIXTIME(FORBIDTALK) AS FORBIDTALK,
                    CHARID, MAPID, LINEID, BITMASK, ACCPRIV, HUANGZAN, HP, MONEY2, ZHENQI,
                    MONEY4, MONEY5, MONEY6, MONEY9, MONEY10, MONEY13,CREATEIP FROM CHARBASE " . $orderby . ' ' . $limit ;

            $stmt = $this->dbh->prepare($sql);

            $stmt->execute();
            $result = $stmt->fetchAll();
        }else
        {
            $sql = "SELECT ACCNAME, NAME, ZONE, LEVEL, VIP, SHENQI, COUNTRY, MONEY1, MONEY3,
                    FROM_UNIXTIME(CREATETIME) AS CREATETIME, LASTACTIVEDATE, FROM_UNIXTIME(FORBIDTALK) AS FORBIDTALK,
                    CHARID, MAPID, LINEID, BITMASK, ACCPRIV, HUANGZAN, HP, MONEY2, ZHENQI,
                    MONEY4, MONEY5, MONEY6, MONEY9, MONEY10, MONEY13,CREATEIP FROM CHARBASE WHERE ACCNAME = :ACCNAME AND NAME = :NAME ";
            $stmt = $this->dbh->prepare($sql);

            $stmt->bindParam(':ACCNAME', $ACCNAME);
            $stmt->bindParam(':NAME', $NAME);
            $stmt->execute();
            $result = $stmt->fetchAll();
        }

        return $result;
    }
    public function get_acc_list2( $ACCNAME='', $NAME='', $orderby='', $limit='limit 1' )
    {
        if( !$ACCNAME && !$NAME )
        {
            $sql = "SELECT ACCNAME, NAME, ZONE, LEVEL, VIP, SHENQI, COUNTRY, MONEY1, MONEY3, MONEY5,
                    FROM_UNIXTIME(CREATETIME) AS CREATETIME, LASTACTIVEDATE, FROM_UNIXTIME(FORBIDTALK) AS FORBIDTALK, 
                    CHARID, MAPID, LINEID, BITMASK, ACCPRIV, HUANGZAN, HP, MONEY2, ZHENQI, 
                    MONEY4, MONEY5, MONEY6, MONEY9, MONEY10, MONEY13,CREATEIP FROM CHARBASE where MONEY5 > '50000000'  " . $orderby . ' ' . $limit ;

            $stmt = $this->dbh->prepare($sql);

            $stmt->execute();
            $result = $stmt->fetchAll();
        }else
        {
            $sql = "SELECT ACCNAME, NAME, ZONE, LEVEL, VIP, SHENQI, COUNTRY, MONEY1, MONEY3, 
                    FROM_UNIXTIME(CREATETIME) AS CREATETIME, LASTACTIVEDATE, FROM_UNIXTIME(FORBIDTALK) AS FORBIDTALK, 
                    CHARID, MAPID, LINEID, BITMASK, ACCPRIV, HUANGZAN, HP, MONEY2, ZHENQI, 
                    MONEY4, MONEY5, MONEY6, MONEY9, MONEY10, MONEY13,CREATEIP FROM CHARBASE WHERE ACCNAME = :ACCNAME OR NAME = :NAME ";
            $stmt = $this->dbh->prepare($sql);

            $stmt->bindParam(':ACCNAME', $ACCNAME);
            $stmt->bindParam(':NAME', $NAME);
            $stmt->execute();
            $result = $stmt->fetchAll();
        }

        return $result;
    }
}
