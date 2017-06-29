<?php
/**
 * AllUser 0~F 模型类 从TRADE获取各种数据
 */
class AlluserInfo
{
    public $dbh = NULL;
    
    function __construct()
    {
        $this->dbh = new PDO(PDO_AllUserInfo_host,PDO_AllUserInfo_root,PDO_AllUserInfo_pass);
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
     * 根据openid 获取 zones
     */
    public function get_openid_zones( $openid )
    {
        if(!$openid) return NULL;
	$pre = strtoupper(md5($openid));
        $table = 'AllUser' . substr(trim( $pre ),0,1);

        $stmt = $this->dbh->prepare('SELECT zones FROM ' . $table . ' WHERE openid = :openid ');
        $stmt->bindParam(':openid', $openid);
        $stmt->execute();
        $result = $stmt->fetch();

        return isset( $result['zones'] ) ? $result['zones'] : NULL;
    }
}
