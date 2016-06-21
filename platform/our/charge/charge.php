<?php
	require_once  '..\..\..\unity\self_http.php';
	require_once  '..\..\..\unity\self_data_operate_factory.php';
	if($_SERVER['REQUEST_METHOD'] != 'POST'){
		echo "Forbidden. Only POST request is allowed.";
		exit;
	}

	if(!isset($_POST['game_order']) || !isset($_POST['transaction_id'])){
		echo "Arguments error.";
		exit;
	}

	require_once("config.php");
	$url = $MY_CHARGE_DB_CONFIG['url'];
	$user = $MY_CHARGE_DB_CONFIG['user'];
	$password = $MY_CHARGE_DB_CONFIG['password'];
	$database = $MY_CHARGE_DB_CONFIG['database'];
	$table = $MY_CHARGE_DB_CONFIG['table'];
	$order_tbl = $MY_CHARGE_DB_CONFIG['order_table'];
	
	$ob_data_factory = new CDataOperateFactory('default_m');
	
	$game_order = $ob_data_factory->db_mysql_escape_string($_POST['game_order']);
	$transaction_id = $ob_data_factory->db_mysql_escape_string($_POST['transaction_id']);
    
    $select_sql = "select * from $order_tbl where `id`=$game_order and `pay_ok`=0";
    $db_result = $ob_data_factory->db_get_data($select_sql);
    if (!$db_result) {
		echo "db operate failure";
		exit;	
    }
	if (-1 == $db_result) {
		echo "not find the game_order".$game_order;
		return;
	}	
	$row = $db_result[0];
    
    $player_id = $row['player_id'];
    $area_id = $row['area_id'];
    $money = $row['money'];
    $currency = $row['currency'];
    $shop_type = $row['shop_type'];
    $product_id = $row['product_id'];
    $yuanbao = $row['yuanbao'];
    $item_id = $row['item_id'];
    
    $transaction_platform_id = 'Niuwa';
    $player_from = 'Niuwa';
    $mysqli_tmp = &$ob_data_factory->mysql->get_mysqli();
    if (!$mysqli_tmp->autocommit(false)) {
        echo "autocommit false error";
        $mysqli_tmp->autocommit(true);
        exit;
    }
	if (!$mysqli_tmp->begin_transaction()) {
	    echo "begin_transaction error";
	    $mysqli_tmp->autocommit(true);
	    exit;
	}
	try{
		$update_pay_ok = "update $order_tbl set `pay_ok`=1 where `id`=$game_order ";
		if (!$ob_data_factory->db_update_data($update_pay_ok)) {
		    echo "update_pay_ok failure";
		    if (!$mysqli_tmp->rollback()) {
		          echo "rollback failure game_order:".$game_order;    
		    }
		    $mysqli_tmp->autocommit(true);
		    exit;
		}
		
		$insert_sql = "insert into $table set `Id` = $game_order, `player_id` = '$player_id', `player_from` = '$player_from', 
        `area_id`=$area_id, `money` = $money, `currency`='$currency',`yuanbao` = $yuanbao, `transaction_platform_id`='$transaction_platform_id',
        `transaction_id` = '$transaction_id', `shop_type` = '$shop_type', `product_id`='$product_id',`item_id`=$item_id";
		if (!$ob_data_factory->db_update_data($insert_sql)) {
			echo "insert failure,game_order:".$game_order;
		    if (!$mysqli_tmp->rollback()) {
		          echo "insert rollback failure game_order:".$game_order;    
		    }
		    $mysqli_tmp->autocommit(true);
		    exit;
		}
	} catch (Exception $e) {
		echo "throw exception";
		if (!$mysqli_tmp->rollback()) {
		    echo "rollback failure game_order:".$game_order;
		}
		$mysqli_tmp->autocommit(true);
		exit;
	}
	if (!$mysqli_tmp->commit()) {
	    echo "commit failure,game_order:".$game_order;
	    if (!$mysqli_tmp->rollback()) {
	        echo "commit rollback failure game_order:".$game_order;
	    }
	    $mysqli_tmp->autocommit(true);
	    exit;
	}
	$mysqli_tmp->autocommit(true);
	
    $type='chongzhi_ntf';
	$url = "http://127.0.0.1:24000?type=$type&game_order=$game_order";
    $http = new CMyHttp();
	$content = $http->get($url,5);
    
    echo $content;
	echo "Charge finished.";
?>