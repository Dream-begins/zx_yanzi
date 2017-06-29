<?php
/**
 * TRADE 模型类 从TRADE获取各种数据
 */
class TradeInfo
{
    public $dbh = NULL;
    
    function __construct()
    {
        $this->dbh = new PDO(PDO_TradeInfo_host,PDO_TradeInfo_root,PDO_TradeInfo_pass);
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
     * @desc 从TRADE 表获取详情 
     * @param $where array('ZONE'=>'1,2,3', 'ACC'=>'', 'TIME1'=>'时间>=的时间戳', 'TIME2'=>'时间<=的时间戳')
     * @param $orderby string 如 TIME DESC
     * @param $limit string 如 LIMIT 0,10
     */
    public function detailed_list( $where=array('ZONE'=>'', 'ACC'=>'', 'TIME1'=>'', 'TIME2'=>'', 'PF'=>''), $orderby='', $limit='LIMIT 1' )
    {
        $dowhere = '';
        $ZONE = isset( $where['ZONE'] ) ? trim( $where['ZONE'], ',') : NULL;
        $ACC  = isset( $where['ACC'] ) ? trim( $where['ACC'], ',') : NULL;
        $TIME1 = isset( $where['TIME1'] ) ? (int)$where['TIME1'] : NULL;
        $TIME2 = isset( $where['TIME2'] ) ? (int)$where['TIME2'] : NULL;
$PF = isset( $where['PF'] ) ? $where['PF'] : NULL;
if( $ZONE ) $dowhere .= " AND ZONE IN($ZONE) ";
        #if( $ZONE ) $dowhere .= ' AND ZONE IN(:ZONE) ';
        if( $ACC )  $dowhere .= ' AND ACC = :ACC ';
        if( $TIME1 ) $dowhere .= ' AND TIME >= :TIME1 ';
        if( $TIME2 ) $dowhere .= ' AND TIME <= :TIME2 ';


        $pfwhere = '';
        $pfwhere .= " AND PF = '".$PF."' " ;
        if($PF=='other') $pfwhere = " AND PF != 'qqgame' AND PF != 'qzone' " ;
        if($PF=='all') $pfwhere = '';
        $dowhere .= $pfwhere;

        $stmt = $this->dbh->prepare('SELECT OBJID,NUM*PRICE AS SUM_YB, PRICE AS SUM_AMOUNT, FROM_UNIXTIME(TIME) AS YMD, PF, ACC, NAME, ZONE FROM TRADE WHERE STATUS = 1 AND PRICE >=50 ' . $dowhere . $orderby . ' ' . $limit);
        
        //if( $ZONE ) $stmt->bindParam(':ZONE',$ZONE);
        if( $ACC ) $stmt->bindParam(':ACC',$ACC);
        if( $TIME1 ) $stmt->bindParam(':TIME1',$TIME1);
        if( $TIME2 ) $stmt->bindParam(':TIME2',$TIME2);

        $stmt->execute();
        $result = $stmt->fetchaLL();

        return $result;
    }

    /**
     * @desc 根据条件获取 查询详情的总数
     * @param $where array('ZONE'=>'1,2,3', 'ACC'=>'', 'TIME1'=>'时间>=的时间戳', 'TIME2'=>'时间<=的时间戳')
     */
    public function detailed_total( $where=array('ZONE'=>'', 'ACC'=>'', 'TIME1'=>'', 'TIME2'=>'', 'PF'=>'') )
    {
        $dowhere = '';

        $ZONE = isset( $where['ZONE'] ) ? trim( $where['ZONE'], ',') : NULL;
        $ACC  = isset( $where['ACC'] ) ? trim( $where['ACC'], ',') : NULL;
        $TIME1 = isset( $where['TIME1'] ) ? (int)$where['TIME1'] : NULL;
        $TIME2 = isset( $where['TIME2'] ) ? (int)$where['TIME2'] : NULL;
$PF = isset( $where['PF'] ) ? $where['PF'] : NULL;
        #if( $ZONE ) $dowhere .= ' AND ZONE IN(:ZONE) ';
if( $ZONE ) $dowhere .= " AND ZONE IN($ZONE) ";
        if( $ACC )  $dowhere .= ' AND ACC = :ACC ';
        if( $TIME1 ) $dowhere .= ' AND TIME >= :TIME1 ';
        if( $TIME2 ) $dowhere .= ' AND TIME <= :TIME2 ';

        $pfwhere = '';
        $pfwhere .= " AND PF = '".$PF."' " ;
        if($PF=='other') $pfwhere = " AND PF != 'qqgame' AND PF != 'qzone' " ;
        if($PF=='all') $pfwhere = '';
        $dowhere .= $pfwhere;

        $stmt = $this->dbh->prepare('SELECT COUNT(ID) AS total FROM TRADE WHERE STATUS = 1 AND PRICE >=50 ' . $dowhere );
        
       # if( $ZONE ) $stmt->bindParam(':ZONE',$ZONE);
        if( $ACC ) $stmt->bindParam(':ACC',$ACC);
        if( $TIME1 ) $stmt->bindParam(':TIME1',$TIME1);
        if( $TIME2 ) $stmt->bindParam(':TIME2',$TIME2);

        $stmt->execute();
        $total = $stmt->fetch();
        $total = isset($total['total']) ? $total['total'] : 0;

        return $total;
    }

    /**
     * @desc 从TRADE 表获取 ACC 分组后的详情 
     * @param $where array('ZONE'=>'1,2,3', 'ACC'=>'', 'TIME1'=>'时间>=的时间戳', 'TIME2'=>'时间<=的时间戳')
     * @param $orderby string 如 TIME DESC
     * @param $limit string 如 LIMIT 0,10
     */
    public function acc_group_list( $where=array('ZONE'=>'', 'ACC'=>'', 'TIME1'=>'', 'TIME2'=>'','PF'=>''), $orderby='', $limit='LIMIT 1' )
    {
        $dowhere = '';
        $ZONE = isset( $where['ZONE'] ) ? trim( $where['ZONE'], ',') : NULL;
        $ACC  = isset( $where['ACC'] ) ? trim( $where['ACC'], ',') : NULL;
        $TIME1 = isset( $where['TIME1'] ) ? (int)$where['TIME1'] : NULL;
        $TIME2 = isset( $where['TIME2'] ) ? (int)$where['TIME2'] : NULL;
        $PF = isset( $where['PF'] ) ? $where['PF'] : NULL;

        if( $ZONE ) $dowhere .= " AND ZONE IN($ZONE) ";
        #if( $ZONE ) $dowhere .= ' AND ZONE IN(:ZONE) ';
        if( $ACC )  $dowhere .= ' AND ACC = :ACC ';
        if( $TIME1 ) $dowhere .= ' AND TIME >= :TIME1 ';
        if( $TIME2 ) $dowhere .= ' AND TIME <= :TIME2 ';

        $pfwhere = '';
        $pfwhere .= " AND PF = '".$PF."' " ;
        if($PF=='other') $pfwhere = " AND PF != 'qqgame' AND PF != 'qzone' " ;
        if($PF=='all') $pfwhere = '';
        $dowhere .= $pfwhere;

        $stmt = $this->dbh->prepare('SELECT OBJID,SUM(NUM*PRICE) AS SUM_YB, SUM(PRICE) AS SUM_AMOUNT, FROM_UNIXTIME(TIME) AS YMD, PF, ACC, NAME, ZONE FROM TRADE WHERE STATUS = 1 AND PRICE >=50 ' . $dowhere . ' GROUP BY ACC ' . $orderby . ' ' . $limit);
        #if( $ZONE ) $stmt->bindParam(':ZONE',$ZONE);
        if( $ACC ) $stmt->bindParam(':ACC',$ACC);
        if( $TIME1 ) $stmt->bindParam(':TIME1',$TIME1);
        if( $TIME2 ) $stmt->bindParam(':TIME2',$TIME2);

        $stmt->execute();
        $result = $stmt->fetchaLL();

        return $result;
    }

    /**
     * @desc 根据条件获取 查询 ACC 分组后的总数
     * @param $where array('ZONE'=>'1,2,3', 'ACC'=>'', 'TIME1'=>'时间>=的时间戳', 'TIME2'=>'时间<=的时间戳')
     */
    public function acc_group_total( $where=array('ZONE'=>'', 'ACC'=>'', 'TIME1'=>'', 'TIME2'=>'' ,'PF'=>'') )
    {
        $dowhere = '';

        $ZONE = isset( $where['ZONE'] ) ? trim( $where['ZONE'], ',') : NULL;
        $ACC  = isset( $where['ACC'] ) ? trim( $where['ACC'], ',') : NULL;
        $TIME1 = isset( $where['TIME1'] ) ? (int)$where['TIME1'] : NULL;
        $TIME2 = isset( $where['TIME2'] ) ? (int)$where['TIME2'] : NULL;
         $PF = isset( $where['PF'] ) ? $where['PF'] : NULL;
        #if( $ZONE ) $dowhere .= ' AND ZONE IN(:ZONE) ';
       # if( $ZONE ) $dowhere .= ' AND ZONE IN(:ZONE) ';
if( $ZONE ) $dowhere .= " AND ZONE IN($ZONE) ";
        if( $ACC )  $dowhere .= ' AND ACC = :ACC ';
        if( $TIME1 ) $dowhere .= ' AND TIME >= :TIME1 ';
        if( $TIME2 ) $dowhere .= ' AND TIME <= :TIME2 ';

        $pfwhere = '';
        $pfwhere .= " AND PF = '".$PF."' " ;
        if($PF=='other') $pfwhere = " AND PF != 'qqgame' AND PF != 'qzone' " ;
        if($PF=='all') $pfwhere = '';
        $dowhere .= $pfwhere;

        $stmt = $this->dbh->prepare('SELECT count(distinct ACC) AS total FROM TRADE WHERE STATUS = 1 AND PRICE >=50 ' . $dowhere );

        #if( $ZONE ) $stmt->bindParam(':ZONE',$ZONE);
        if( $ACC ) $stmt->bindParam(':ACC',$ACC);
        if( $TIME1 ) $stmt->bindParam(':TIME1',$TIME1);
        if( $TIME2 ) $stmt->bindParam(':TIME2',$TIME2);

        $stmt->execute();
        $total = $stmt->fetch();
        $total = isset($total['total']) ? $total['total'] : 0;

        return $total;
    }

    /**
     * @desc 从TRADE 表获取 ZONE,ACC 分组后的详情 
     * @param $where array('ZONE'=>'1,2,3', 'ACC'=>'', 'TIME1'=>'时间>=的时间戳', 'TIME2'=>'时间<=的时间戳')
     * @param $orderby string 如 TIME DESC
     * @param $limit string 如 LIMIT 0,10
     */
    public function zone_acc_group_list( $where=array('ZONE'=>'', 'ACC'=>'', 'TIME1'=>'', 'TIME2'=>'', 'PF'=>''), $orderby='', $limit='LIMIT 1' )
    {
        $dowhere = '';

        $ZONE = isset( $where['ZONE'] ) ? trim( $where['ZONE'], ',') : NULL;
        $ACC  = isset( $where['ACC'] ) ? trim( $where['ACC'], ',') : NULL;
        $TIME1 = isset( $where['TIME1'] ) ? (int)$where['TIME1'] : NULL;
        $TIME2 = isset( $where['TIME2'] ) ? (int)$where['TIME2'] : NULL;
$PF = isset( $where['PF'] ) ? $where['PF'] : NULL;
     #   if( $ZONE ) $dowhere .= ' AND ZONE IN(:ZONE) ';
if( $ZONE ) $dowhere .= " AND ZONE IN($ZONE) ";
        if( $ACC )  $dowhere .= ' AND ACC = :ACC ';
        if( $TIME1 ) $dowhere .= ' AND TIME >= :TIME1 ';
        if( $TIME2 ) $dowhere .= ' AND TIME <= :TIME2 ';

        $pfwhere = '';
        $pfwhere .= " AND PF = '".$PF."' " ;
        if($PF=='other') $pfwhere = " AND PF != 'qqgame' AND PF != 'qzone' " ;
        if($PF=='all') $pfwhere = '';
        $dowhere .= $pfwhere;

        $stmt = $this->dbh->prepare('SELECT OBJID,SUM(NUM*PRICE) AS SUM_YB, SUM(PRICE) AS SUM_AMOUNT, FROM_UNIXTIME(TIME) AS YMD, PF, ACC, NAME, ZONE FROM TRADE WHERE STATUS = 1 AND PRICE >=50 ' . $dowhere . ' GROUP BY ZONE,ACC ' . $orderby . ' ' . $limit);
        
       # if( $ZONE ) $stmt->bindParam(':ZONE',$ZONE);
        if( $ACC ) $stmt->bindParam(':ACC',$ACC);
        if( $TIME1 ) $stmt->bindParam(':TIME1',$TIME1);
        if( $TIME2 ) $stmt->bindParam(':TIME2',$TIME2);

        $stmt->execute();
        $result = $stmt->fetchaLL();

        return $result;
    }

    /**
     * @desc 根据条件获取 查询 ZONE,ACC 分组后的总数
     * @param $where array('ZONE'=>'1,2,3', 'ACC'=>'', 'TIME1'=>'时间>=的时间戳', 'TIME2'=>'时间<=的时间戳')
     */
    public function zone_acc_group_total( $where=array('ZONE'=>'', 'ACC'=>'', 'TIME1'=>'', 'TIME2'=>'', 'PF'=>'') )
    {
        $dowhere = '';

        $ZONE = isset( $where['ZONE'] ) ? trim( $where['ZONE'], ',') : NULL;
        $ACC  = isset( $where['ACC'] ) ? trim( $where['ACC'], ',') : NULL;
        $TIME1 = isset( $where['TIME1'] ) ? (int)$where['TIME1'] : NULL;
        $TIME2 = isset( $where['TIME2'] ) ? (int)$where['TIME2'] : NULL;
$PF = isset( $where['PF'] ) ? $where['PF'] : NULL;
#        if( $ZONE ) $dowhere .= ' AND ZONE IN(:ZONE) ';
if( $ZONE ) $dowhere .= " AND ZONE IN($ZONE) ";
        if( $ACC )  $dowhere .= ' AND ACC = :ACC ';
        if( $TIME1 ) $dowhere .= ' AND TIME >= :TIME1 ';
        if( $TIME2 ) $dowhere .= ' AND TIME <= :TIME2 ';

        $pfwhere = '';
        $pfwhere .= " AND PF = '".$PF."' " ;
        if($PF=='other') $pfwhere = " AND PF != 'qqgame' AND PF != 'qzone' " ;
        if($PF=='all') $pfwhere = '';
        $dowhere .= $pfwhere;

        $stmt = $this->dbh->prepare('SELECT count(distinct ZONE,ACC) AS total FROM TRADE WHERE STATUS = 1 AND PRICE >=50 ' . $dowhere );

       # if( $ZONE ) $stmt->bindParam(':ZONE',$ZONE);
        if( $ACC ) $stmt->bindParam(':ACC',$ACC);
        if( $TIME1 ) $stmt->bindParam(':TIME1',$TIME1);
        if( $TIME2 ) $stmt->bindParam(':TIME2',$TIME2);

        $stmt->execute();
        $total = $stmt->fetch();
        $total = isset($total['total']) ? $total['total'] : 0;

        return $total;
    }

    /**
     * @desc 获取当前最新的 合并前服ID
     */
    public function get_max_domian()
    {
        $stmt = $this->dbh->prepare('SELECT MAX(ZONE+0) AS max FROM TRADE');
        $stmt->execute();
        $max = $stmt->fetch();
        $max = isset($max['max']) ? $max['max'] : 0;

        return $max;
    }

    /**
     * @desc 获取总收入
     * @param $domian int 合并前服
     * @return $all_income int 指定服总收入 
     */
    public function get_all_income( $domian )
    {
        $domians = trim( $domian, ',' );
        $stmt = $this->dbh->prepare("SELECT SUM(PRICE) AS total FROM TRADE WHERE STATUS = 1 AND PRICE >=50  AND ZONE IN (:zone) "); //[Q战神、焚天]
        $stmt->bindParam(':zone', $domians);
        $stmt->execute();

        $result = $stmt->fetch();
        return $total  = isset($result['total']) ? $result['total'] : NULL;
    }
    public function get_all_pf()
    {
        $stmt = $this->dbh->prepare("SELECT PF FROM TRADE GROUP BY PF");
        $stmt->execute();
        $result = $stmt->fetchAll();

        return $result;
    }



}
