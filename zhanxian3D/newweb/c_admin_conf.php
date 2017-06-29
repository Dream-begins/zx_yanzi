<?php 
include 'h_menu.php';
include 'h_header.php';

$qx_ch2en_arr = array();
foreach ($menu_arrays as $key => $value)
{
    foreach ($value as $k => $v)
    {
        $qx_ch2en_arr[$v['name']] = $v['index']; //中 => 英
    }
}

$action = $_GET['action'];

$dbh = new PDO(PDO_gonggaoInfo_host, PDO_gonggaoInfo_root, PDO_gonggaoInfo_pass);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbh->query("SET NAMES UTF8");

if( $action == 'user_list' )
{
    $sql = "SELECT * FROM ADMIN";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();


    foreach ($result as $key => $value)
    {
        $result[$key]['passChange'] = $value['passChange'] ? '是' : '<font color="red">否</font>';
        $a = array_filter( explode(',', $value['priv']) );

        foreach ($a as $k => $v)
        {
            if(!in_array($v, $qx_ch2en_arr)) unset($a[$k]);
        }

        $result[$key]['priv_nu'] = count($a) . '/' . count($qx_ch2en_arr);
    }

    exit( json_encode($result) );
}

if( $action == 'user_add' )
{
    $user = $_POST['user'];

    $sql = "INSERT INTO ADMIN(user, pass, priv, passChange) VALUES(:user, '123456', '', '0')";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(":user", $user);
    echo $stmt->execute();
    
    exit;
}

if( $action == 'user_del' )
{
    $id = $_POST['id'];

    $sql = "DELETE FROM ADMIN WHERE ID = :id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(":id", $id);

    echo $stmt->execute();
    exit;
}

if( $action == 'qx_update' )
{
    $postdatas = $_POST['postdatas'];
    $priv_arr = array();
    foreach ($postdatas as $key => $value)
    {
        if(in_array($value, $qx_ch2en_arr)) $priv_arr[] = $value;
    }

    $priv = implode(',', $priv_arr);
    $id = $_GET['id'];

    $sql = "UPDATE ADMIN SET priv=:priv WHERE ID = :id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":priv", $priv);
    echo $stmt->execute();
    exit;
}

if( $action == 'qx_list' )
{
    $id = $_GET['id'];

    $sql = "SELECT priv FROM ADMIN WHERE ID=:id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $result = $stmt->fetch();

    $flag = explode(',',$result['priv']);

    $return_arr = array();

    foreach ($menu_arrays as $key => $value)
    {
        $return_arr[$key]['id'] = $key;
        $return_arr[$key]['text'] = $key;
        $return_arr[$key]['state'] = 'closed';

        foreach ($value as $k => $v)
        {
            $checked = in_array($v['index'], $flag) ? 1 : 0;
            $return_arr[$key]['children'][] = array(
                "id" => $v['index'],
                "text" => $v['name'], 
                "checked" => $checked,
            );
        }
    }

    sort($return_arr);

    exit(json_encode($return_arr));
}
