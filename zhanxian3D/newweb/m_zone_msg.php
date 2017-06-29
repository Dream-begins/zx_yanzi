<?php
/**
 * zone_msg 模型类 从zone_msg获取各种数据
 */
class ZoneMsgInfo
{
    public $dbh = NULL;
    
    function __construct()
    {
        $this->dbh = new PDO(PDO_ZoneMsgInfo_host,PDO_ZoneMsgInfo_root,PDO_ZoneMsgInfo_pass);
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
     * @desc 获取所有 合并后区
     */
    public function get_all_zone_id()
    {
        $return = array();

        $sql = "SELECT zone_id FROM zone_msg";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        foreach ($result as $key => $value)
        {
            $return[] = $value['zone_id'];
        }

        return $return;
    }
    /**
     * @param int $zone_id 合并后 区
     * @return array array('zone_id'=>'xxx', 'server_id'=>'xxx', 'domians'=>'xxx', 'mysql_ip'=>'xxx', 'mysql_port'='xxx', mysql_dbName=>'xxx' );
     */
    public function zone_id2infos($zone_id)
    {
        $sql = "SELECT zone_id, server_id, zones, domians, mysql_ip, mysql_port, mysql_dbName,server_out_ip FROM zone_msg WHERE zone_id = :zone_id ";
        //var_dump($stmt);exit;
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':zone_id', $zone_id);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * @param int $server_d 合并后 服
     * @return array array('zone_id'=>'xxx', 'server_id'=>'xxx', 'domians'=>'xxx', 'mysql_ip'=>'xxx', 'mysql_port'=>'', mysql_dbName=>'xxx' );
     */
    public function server_id2infos($server_id)
    {
        $sql = "SELECT zone_id, server_id, zones, domians, mysql_ip, mysql_port, mysql_dbName,server_out_ip FROM zone_msg WHERE server_id = :server_id ";
        
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':server_id', $server_id);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    /**
     * @param int $zone 合并前 区
     * @return array array('domian'=>'xxx', 'zone_id'=>'xxx', 'server_id'=>'xxx', 'domians'=>'xxx', 'mysql_ip'=>'xxx', 'mysql_port'=>'', mysql_dbName=>'xxx' );
     */
    public function zones2infos($zone)
    {
        $return_arr = array();
        $new_zone = "%,".$zone.",%";

        $sql = "SELECT zone_id, server_id, zones, domians, mysql_ip, mysql_port, mysql_dbName,server_out_ip FROM zone_msg WHERE CONCAT(',',zones,',') LIKE :zone ";
        
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':zone', $new_zone);
        $stmt->execute();
        
        $return_arr = $stmt->fetch();
        
        $zones_str  = isset($return_arr['zones']) ? trim($return_arr['zones'], ',') : '';
        $domians_str    = isset($return_arr['domians']) ? trim($return_arr['domians'], ',') : '';

        $zones_arr = explode(',', $zones_str);
        $domians_arr = explode(',', $domians_str);

        $zones2domians_arr = array_combine($zones_arr, $domians_arr);

        $return_arr['domian'] = isset($zones2domians_arr[$zone]) ? $zones2domians_arr[$zone] : '';

        return $return_arr;
    }

    /**
     * @param int $domian 合并前 服
     * @return array array('zone'=>'xxx', zone_id'=>'xxx', 'server_id'=>'xxx', 'domians'=>'xxx', 'mysql_ip'=>'xxx', 'mysql_port'=>'', mysql_dbName=>'xxx' );
     */
    public function domians2infos($domian)
    {
        $return_arr = array();
        $new_domian = "%,".$domian.",%";

        $sql = "SELECT zone_id, server_id, zones, domians, mysql_ip, mysql_port, mysql_dbName,server_out_ip FROM zone_msg WHERE CONCAT(',',domians,',') LIKE :domian ";
        
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':domian', $new_domian);
        $stmt->execute();
        
        $return_arr = $stmt->fetch();
        
        $zones_str  = isset($return_arr['zones']) ? trim($return_arr['zones'], ',') : '';
        $domians_str    = isset($return_arr['domians']) ? trim($return_arr['domians'], ',') : '';

        $zones_arr = explode(',', $zones_str);
        $domians_arr = explode(',', $domians_str);

        $domians2zones_arr = array_combine($domians_arr, $zones_arr);

        $return_arr['zone'] = isset($domians2zones_arr[$domian]) ? $domians2zones_arr[$domian] : '';

        return $return_arr;
    }

    /**
     * @desc 获取 合并前区 => 合并后服 映射数组
     * @return array('合并前区1'=>'合并后服1', '合并前区2'=>'合并后服2' ...);
     */
    public function zones2server_id()
    {
        $stmt = $this->dbh->prepare('SELECT zone_id, zones, domians,server_out_ip FROM zone_msg');
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
                $zones2server_id_array[$v] = $value['server_id'];
            }
        }

        return $zones2server_id_array;
    }

    /**
     * @desc 获取 合并前服 => 合并前区 映射数组
     * @return array('合并前服1'=>'合并前区1', '合并前服2'=>'合并前区2' ...);
     */
    public function domians2zones_array()
    {
        $stmt = $this->dbh->prepare('SELECT zone_id, zones, domians,server_out_ip FROM zone_msg');
        $stmt->execute();
        $result = $stmt->fetchAll();

        $domians2zones_array = array();
        $flag_arr = array();
        foreach ($result as $key => $value) 
        {
            $domians_arr = explode(',', trim( $value['domians'], ',' ) );
            $zones_arr   = explode(',', trim( $value['zones'], ',' ) );
            if(count($domians_arr) == count($zones_arr))  $flag_arr = array_combine($domians_arr, $zones_arr);

            $domians2zones_array += $flag_arr;
        }

        return $domians2zones_array;
    }

    /**
     * @desc 获取 合并前服 => 合并后区 映射数组
     * @return array('合并前服1'=>'合并后区1', '合并前服2'=>'合并后区2' ...);
     */
    public function domians2zoneid_array()
    {
        $stmt = $this->dbh->prepare('SELECT zone_id, zones, domians,server_out_ip FROM zone_msg');
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

    /**
     * @desc 获取zone_msg 数据列表
     * @param $orderby string 如 zone_id desc
     * @param $limit string 如 limit 0,10
     */
    public function zone_msg_list( $orderby='', $limit='limit 1' )
    {
        $stmt = $this->dbh->prepare("SELECT zone_id, zones, server_id, domians, mysql_ip, mysql_port, mysql_dbName,server_out_ip FROM zone_msg " . $orderby . ' ' . $limit);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    /**
     * @desc 返回zone_msg 数组总条数
     */
    public function zone_msg_total()
    {
        $stmt = $this->dbh->prepare("SELECT COUNT(*) AS total FROM zone_msg ");
        $stmt->execute();
        $result = $stmt->fetch();
        $total = isset($result['total']) ? $result['total'] : 0;
        return $total;

    }
}
