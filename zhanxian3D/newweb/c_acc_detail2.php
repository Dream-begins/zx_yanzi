<?php
define('WITHOUT_AUTH','1');
include "h_header.php";
include "m_zone_msg.php";
include 'm_charbase.php';
include 'm_alluser.php';

$action = isset($_GET['action']) ? $_GET['action'] : NULL;

if($action == "list")
{
    $blacklist = new BlackListInfo;

	$page = (int)$_POST['page'];
    $rows = (int)$_POST['rows'];
    $sort = isset($_POST['sort']) ?  htmlspecialchars( $_POST['sort'] )  : 'LEVEL';
    $order = isset($_POST['order']) ?  htmlspecialchars( $_POST['order'] )  : 'desc';
    $zones = isset($_POST['zones']) ? (int)$_POST['zones'] : '';
    $zone_id = isset($_POST['zone_id']) ? (int)$_POST['zone_id'] : '';
    
    $ACCNAME = isset($_POST['ACCNAME']) ? trim($_POST['ACCNAME']) : '';
    $NAME = isset($_POST['NAME']) ? trim($_POST['NAME']) : '';
 
    if($page <= 0) $page = 1;
    $zone_msg = new ZoneMsgInfo;

    $domians2zoneid_array = $zone_msg->domians2zoneid_array();
    $domians2zones_array = $zone_msg->domians2zones_array();

    if( $zones || $zone_id )
    {
        if($zones)
        {
            $zones_info = $zone_msg->zones2infos( $zones );
        }elseif(!$zones && $zone_id)
        {
            $zones_info = $zone_msg->zone_id2infos( $zone_id );
        }

        if( !$zones_info ) exit(json_encode( array() ));

        $charbase = new CharbaseInfo($zones_info);

        $orderby = ' ORDER BY ' . $sort . '*1  ' .$order;
        $limit = ' LIMIT ' . ($page-1)*$rows . ',' . $rows;

        $result = $charbase->get_acc_list( $ACCNAME, $NAME, $orderby, $limit );
    	$now = time();
        foreach ($result as $key => $value) 
        {
            if($now - strtotime($result[$key]['LASTACTIVEDATE']) >= 86400*7) $result[$key]['LASTACTIVEDATE'] = "<font color='red'>".$result[$key]['LASTACTIVEDATE']."</font>";
            $result[$key]['zone'] = $domians2zones_array[$value['ZONE']];
            $result[$key]['zone_id'] = $domians2zoneid_array[$value['ZONE']];
            $result[$key]['heimingdan'] = $blacklist->inblacklist($value['ACCNAME']);
		}

        $array1['rows'] = $result;
        $array1['total'] = ($NAME || $ACCNAME) ? 1 : $charbase->get_total_acc();
        echo json_encode($array1);
    }
    elseif( !$zones && !$zone_id && $ACCNAME )
    {
        $alluser = new AlluserInfo();
        $domains = $alluser->get_openid_zones( $ACCNAME );

        $domains_arr = explode(',', $domains);

        $result = array();
        $flag_arr = array();
        $flag = '';
        foreach ($domains_arr as $key => $value) 
        {
            $zones_info = $zone_msg->domians2infos( $value );
            if( !$zones_info ) continue;
            if( $flag == $zones_info['zone_id'] ) continue;
            $flag = $zones_info['zone_id'];
            $charbase = new CharbaseInfo($zones_info);
            $flag_arr = $charbase->get_acc_list($ACCNAME);
            $result = array_merge($flag_arr,$result); 
       }
 
        $now = time();
        foreach ($result as $key => $value) 
        {
            if($now - strtotime($result[$key]['LASTACTIVEDATE']) >= 86400*7) $result[$key]['LASTACTIVEDATE'] = "<font color='red'>".$result[$key]['LASTACTIVEDATE']."</font>";
            $result[$key]['zone'] = $domians2zones_array[$value['ZONE']];
            $result[$key]['zone_id'] = $domians2zoneid_array[$value['ZONE']];
            $result[$key]['heimingdan'] = $blacklist->inblacklist($value['ACCNAME']);
        }
        $array1['rows'] = $result;
        $array1['total'] = count($result);
        echo json_encode($array1);

    }
    else{
        exit(json_encode(array()));
    }
}

class BlackListInfo
{
	public $dbh = NULL;

	function __construct()
	{
		$this->dbh = new PDO('mysql:host=117.103.235.92;dbname=LoginServer;port=3306;charset=utf8','root','hoolai@123');
		$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		$this->dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$this->dbh->query("SET NAMES utf8");
	}

	function __destruct()
	{
		$this->dbh = NULL;
	}

	public function inblacklist($acc)
	{
		$return = array();

		$sql = "SELECT COUNT(1) AS flag FROM BLACKLIST WHERE ACC = :acc";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(':acc',$acc);
		$stmt->execute();
		$result = $stmt->fetch();
		return (isset($result['flag']) && $result['flag'] > 0) ? 1 : 0;
	}
}



