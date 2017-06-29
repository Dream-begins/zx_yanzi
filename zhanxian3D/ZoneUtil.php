<?php 
include_once "newweb/h_header.php";

class ZoneUtil
{
    private static $zoneToIndexMap = Array();
    private static $indexToZoneMap = Array();
    private static $zoneToDb = Array();
    private static $DBUrl = FT_MYSQL_ZONE_MSG_HOST;
    private static $DB_USER = FT_MYSQL_ZONE_MSG_ROOT;
    private static $DB_PASS= FT_MYSQL_ZONE_MSG_PASS;
    private static $lastUpdateTm = 0;

    public static function zoneToIndex($zone)
    {
        $idx = $zone;
        try{
            self::$lastUpdateTm = time();
            $conzone = mysql_connect(self::$DBUrl,self::$DB_USER,self::$DB_PASS);
    
            if($conzone)
            {
                mysql_query("set names 'utf8'");
                mysql_select_db(FT_MYSQL_ZONE_MSG_DBNAME, $conzone) or die("mysql select db error". mysql_error());
                $sql = "SELECT zone_id from zone_msg where server_id=".$zone." or concat(',',domians,',') like concat('%,','".$zone."',',%')";
                $result = mysql_query($sql,$conzone);
                $row = @mysql_fetch_array($result);
                
                if($row) $idx = $row[0];
            }
            else
                echo mysql_error();
                if($conzone != null) mysql_close($conzone);
            }
        catch(Exception $e)
        {
            echo mysql_error();
            if($con != null) mysql_close($conzone);
        }
    
        return $idx;
    }
 
    public static function reloadData()
    {
       try{
            self::$lastUpdateTm = time();
            $conzone = mysql_connect(self::$DBUrl,self::$DB_USER,self::$DB_PASS);
    
            if($conzone)
            {
                mysql_query("set names 'utf8'");
                mysql_select_db(FT_MYSQL_ZONE_MSG_DBNAME, $conzone) or die("mysql select db error". mysql_error());
                self::$zoneToIndexMap = Array();
                self::$indexToZoneMap = Array();
                $sql = "SELECT * from zone_msg";
                $result = mysql_query($sql,$conzone);
    
                if(!$result)
                {
                    echo "no result\n";
                    return;
                }

                while ($row = mysql_fetch_assoc($result))
                {
                    self::$indexToZoneMap[$row["zone_id"]] = $row["server_id"];
                    self::$zoneToIndexMap[$row["server_id"]] = $row["zone_id"];
                    self::$zoneToDb[$row["server_id"]] = $row;
    
                    if(!empty($row["zones"]))
                    {
                        $zones = explode(",",$row["zones"]);
                        foreach($zones as $zone)
                            self::$indexToZoneMap[intval($zone)] = $row["server_id"];
                    }
                }
            }
            else
                echo mysql_error();
        
            if($conzone != null) mysql_close($conzone);
        }
        catch(Exception $e)
        {
            echo mysql_error();
            if($con != null) mysql_close($con);
        }
    }

    public static function indexToZoneOrig($idx)
    {
        $zone = $idx;
      
        try{
            $conzone = mysql_connect(self::$DBUrl,self::$DB_USER,self::$DB_PASS);
            if($conzone)
            {
                mysql_query("set names 'utf8'");
                mysql_select_db(FT_MYSQL_ZONE_MSG_DBNAME, $conzone) or die("mysql select db error". mysql_error());
                $sql = "SELECT server_id,domians,zone_id,zones from zone_msg where zone_id=".$idx." or concat(',',zones,',') like concat('%','".$idx."','%')";
                $result = mysql_query($sql,$conzone);
                $row = mysql_fetch_array($result);
                
                if($row)
                {
                    if($row[2] == $idx)
                        $zone = $row[0];
                    else
                    {
                        $zones = explode(",",$row[3]);
                        $serverids = explode(",",$row[1]);
                        
                        for($i=0;$i<count($zones);$i++)
                        {
                            if(intval($zones[$i]) == intval($idx))
                            {
                                $zone = $serverids[$i];
                            }
                        }
                    }
                }
            }
            else
                echo mysql_error();
            
            if($conzone != null) mysql_close($conzone);
        }
        catch(Exception $e)
        {
            echo mysql_error();
            if($con != null) mysql_close($conzone);
        }
        return $zone;
    }

    public static function zoneToIndexOrig($zone)
    {
        $idx = $zone;
        try{
            $conzone = mysql_connect(self::$DBUrl,self::$DB_USER,self::$DB_PASS);
    
            if($conzone)
            {
                mysql_query("set names 'utf8'");
                mysql_select_db(FT_MYSQL_ZONE_MSG_DBNAME, $conzone) or die("mysql select db error". mysql_error());
                $sql = "SELECT server_id,domians,zone_id,zones from zone_msg where server_id=".$zone." or concat(',',domians,',') like concat('%','".$zone."','%')";
                $result = mysql_query($sql,$conzone);
                $row = mysql_fetch_array($result);
    
                if($row)
                {
                    if($row[0] == $zone)
                        $idx = $row[2];
                    else
                    {
                        $zones = explode(",",$row[3]);
                        $serverids = explode(",",$row[1]);
    
                        for($i=0;$i<count($serverids);$i++)
                        {
                            if(intval($serverids[$i]) == intval($zone))
                            {
                                $idx = $zones[$i];
                            }
                        }
                    }
                }
            }
            else
                echo mysql_error();
            
            if($conzone != null) mysql_close($conzone);
        }
        catch(Exception $e)
        {
            echo mysql_error();
            if($con != null) mysql_close($conzone);
        }
    
        return $idx;
    }

    public static function indexToZone($idx)
    {
        $zone = $idx;
        try{
            $conzone = mysql_connect(self::$DBUrl,self::$DB_USER,self::$DB_PASS);
            if($conzone)
            {
                mysql_query("set names 'utf8'");
                mysql_select_db(FT_MYSQL_ZONE_MSG_DBNAME, $conzone) or die("mysql select db error". mysql_error());
                $sql = "select server_id from zone_msg where zone_id=".$idx." or concat(',',zones,',') like concat('%','".$idx."','%')";
                $result = mysql_query($sql,$conzone);
                $row = mysql_fetch_array($result);
                if($row) $zone = $row[0];
            }
            else
                echo mysql_error();
            
            if($conzone != null) mysql_close($conzone);
        }
        catch(Exception $e)
        {
            echo mysql_error();
            if($con != null) mysql_close($conzone);
        }
        
        return $zone;

        if(array_key_exists($idx,self::$indexToZoneMap))
        {
            return self::$indexToZoneMap[$idx];
        }
        else
        {
            if(time() - self::$lastUpdateTm>60)
            {
                self::reloadData();
                return self::indexToZone($idx);
            }
            
            return $idx;
        }
    }

    public static function zoneToDb($zone)
    {
        if(array_key_exists($zone,self::$zoneToDbMap))
        {
            return self::$zoneToDbMap[$zone];
        }
        else
        {
            if(time() - $self::lastUpdateTm>60)
            {
                self::reloadData();
                return self::zoneToDb($zone);
            }
            return null;
        }
    }
}

