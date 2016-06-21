<?php
	require_once  '..\..\..\unity\self_http.php';
	require_once  '..\..\..\unity\self_data_operate_factory.php';
	require_once  '..\..\..\unity\self_global.php';
	require_once  '..\..\..\unity\self_error_code.php';
	if($_SERVER['REQUEST_METHOD'] != 'POST'){
		make_return_err_code_and_des(ErrorCode::ERROR_ONLY_POST_IS_ALLOWED,get_err_desc(ErrorCode::ERROR_ONLY_POST_IS_ALLOWED));
		return;
	}

	if(!isset($_POST['game_order']) || !isset($_POST['transaction_id'])){
		make_return_err_code_and_des(ErrorCode::ERROR_PARAMS_ERROR,get_err_desc(ErrorCode::ERROR_PARAMS_ERROR));
		return;
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
		writeLog("db operate failure,sql:".$select_sql,LOG_NAME::ERROR_LOG_FILE_NAME);
        make_return_err_code_and_des(ErrorCode::ERROR_INNER_ERROR,get_err_desc(ErrorCode::ERROR_INNER_ERROR));
        return;
    }
	if (-1 == $db_result) {
		writeLog("not find the game_order".$game_order,LOG_NAME::ERROR_LOG_FILE_NAME);
		make_return_err_code_and_des(ErrorCode::NOT_FIND_ORDER,get_err_desc(ErrorCode::NOT_FIND_ORDER));
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
        $mysqli_tmp->autocommit(true);
        writeLog("autocommit false error,game_order:".$game_order,LOG_NAME::ERROR_LOG_FILE_NAME);
        make_return_err_code_and_des(ErrorCode::ERROR_INNER_ERROR,get_err_desc(ErrorCode::ERROR_INNER_ERROR));
        return;
    }
	if (!$mysqli_tmp->begin_transaction()) {
	    $mysqli_tmp->autocommit(true);
	    writeLog("begin_transaction error,game_order:".$game_order,LOG_NAME::ERROR_LOG_FILE_NAME);
	    make_return_err_code_and_des(ErrorCode::ERROR_INNER_ERROR,get_err_desc(ErrorCode::ERROR_INNER_ERROR));
	    return;
	}
	try{
		$update_pay_ok = "update $order_tbl set `pay_ok`=1 where `id`=$game_order ";
		if (!$ob_data_factory->db_update_data($update_pay_ok)) {
		    echo "update_pay_ok failure";
		    if (!$mysqli_tmp->rollback()) {    
		          writeLog("rollback failure game_order::".$game_order,LOG_NAME::ERROR_LOG_FILE_NAME);
		          make_return_err_code_and_des(ErrorCode::ERROR_INNER_ERROR,get_err_desc(ErrorCode::ERROR_INNER_ERROR));
		    }
		    $mysqli_tmp->autocommit(true);
		    return;
		}
		
		$insert_sql = "insert into $table set `Id` = $game_order, `player_id` = '$player_id', `player_from` = '$player_from', 
        `area_id`=$area_id, `money` = $money, `currency`='$currency',`yuanbao` = $yuanbao, `transaction_platform_id`='$transaction_platform_id',
        `transaction_id` = '$transaction_id', `shop_type` = '$shop_type', `product_id`='$product_id',`item_id`=$item_id";
		if (!$ob_data_factory->db_update_data($insert_sql)) {
			echo "insert failure,game_order:".$game_order;
		    if (!$mysqli_tmp->rollback()) {
		          writeLog("insert rollback failure game_order:".$game_order,LOG_NAME::ERROR_LOG_FILE_NAME);
		          make_return_err_code_and_des(ErrorCode::ERROR_INNER_ERROR,get_err_desc(ErrorCode::ERROR_INNER_ERROR));
		    }
		    $mysqli_tmp->autocommit(true);
		    return;
		}
	} catch (Exception $e) {
		if (!$mysqli_tmp->rollback()) {
		    writeLog("throw exception rollback failure game_order:".$game_order,LOG_NAME::ERROR_LOG_FILE_NAME);
		    make_return_err_code_and_des(ErrorCode::ERROR_INNER_ERROR,get_err_desc(ErrorCode::ERROR_INNER_ERROR));
		}
		$mysqli_tmp->autocommit(true);
		return;
	}
	if (!$mysqli_tmp->commit()) {
	    if (!$mysqli_tmp->rollback()) {
	        writeLog("commit rollback failure game_order:".$game_order,LOG_NAME::ERROR_LOG_FILE_NAME);
	        make_return_err_code_and_des(ErrorCode::ERROR_INNER_ERROR,get_err_desc(ErrorCode::ERROR_INNER_ERROR));
	    }
	    $mysqli_tmp->autocommit(true);
	    return;
	}
	$mysqli_tmp->autocommit(true);
	
    $type='chongzhi_ntf';
	$url = global_url_prefix::e_transfer_server_url."?type=$type&game_order=$game_order";
    $http = new CMyHttp();
	$content = $http->get($url,5);
    
	$result_ret = array();
	$result_ret["error_code"]=ErrorCode::SUCCESS;
	$result_ret["error_desc"]="Charge finished ".$content;
	$Res = json_encode($result_ret);
	print_r(urldecode($Res));
?>