<?php
	if($_SERVER['REQUEST_METHOD'] != 'POST'){
		echo "Forbidden. Only POST request is allowed.";
		return;
	}
	
	if(!isset($_POST['player_id']) || !isset($_POST['area_id']) || !isset($_POST['money']) || 
    !isset($_POST['currency']) || !isset($_POST['yuanbao']) || !isset($_POST['shop_type']) || !isset($_POST['product_id'])
	    || !isset($_POST['item_id'])){
		echo "Arguments error.";
		return;
	}
    
    $player_id = trim(urlencode($_POST['player_id']));
    $area_id = trim(urlencode($_POST['area_id']));
    $money = trim(urlencode($_POST['money']));
    $currency = trim(urlencode($_POST['currency']));
    $yuanbao = trim(urlencode($_POST['yuanbao']));
    $shop_type = trim(urlencode($_POST['shop_type']));
    $product_id = trim(urlencode($_POST['product_id']));
    $item_id = trim(urlencode($_POST['item_id']));
    
    require_once("config.php");
	$url = $MY_CHARGE_DB_CONFIG['url'];
	$user = $MY_CHARGE_DB_CONFIG['user'];
	$password = $MY_CHARGE_DB_CONFIG['password'];
	$database = $MY_CHARGE_DB_CONFIG['database'];
	$table = $MY_CHARGE_DB_CONFIG['table'];
	$order_tbl = $MY_CHARGE_DB_CONFIG['order_table'];

	require_once  '..\..\..\unity\self_data_operate_factory.php';
	$ob_data_factory = new CDataOperateFactory('default_m');
	
	$player_id = $ob_data_factory->db_mysql_escape_string($_POST['player_id']);
	$area_id = $ob_data_factory->db_mysql_escape_string($_POST['area_id']);
	$money = $ob_data_factory->db_mysql_escape_string($_POST['money']);
    $currency = $ob_data_factory->db_mysql_escape_string($_POST['currency']);
	$yuanbao = $ob_data_factory->db_mysql_escape_string($_POST['yuanbao']);
	$shop_type = $ob_data_factory->db_mysql_escape_string($_POST['shop_type']);
    $product_id = $ob_data_factory->db_mysql_escape_string($_POST['product_id']);
    $item_id = $ob_data_factory->db_mysql_escape_string($_POST['item_id']);
	
    $insert_sql = "insert into $order_tbl set `player_id` = $player_id, `area_id` = $area_id, `money` = $money, `currency` = '$currency', `yuanbao` = $yuanbao, `shop_type` = $shop_type, `product_id` = '$product_id',
    `item_id`=$item_id";
    if (!$ob_data_factory->db_update_data($insert_sql)) {
        echo "generate order failure,sql:".$insert_sql." mysql_error:".mysql_error();
        return;
    }
    $mysqli_tmp = &$ob_data_factory->mysql->get_mysqli();
    echo "generate order success,order_id:".$mysqli_tmp->insert_id;
	
?>