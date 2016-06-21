<?php
	require_once '../unity/self_error_code.php';
	require_once '../unity/self_platform_define.php';
	require_once '../unity/self_memcache.php';
	require_once '../unity/self_log.php';
	require_once '../unity/self_data_operate_factory.php';
	require_once '../unity/self_account.php';
	require_once '../unity/self_common.php';
	
	//header("Content-type: text/html; charset=utf-8");
	
	if (!function_exists('json_decode')){
	exit('您的PHP不支持JSON，请升级您的PHP版本。');
	}
	
	$ret_result = array();
	//判断是否设置了相应的登陆参数
	if (!is_param_right($_REQUEST)) {
		return;
	}
	
	$user_id = urldecode($_REQUEST['user_id']);
	$token = urldecode($_REQUEST['token']);
	$platform_id = urldecode($_REQUEST['platform_id']);
	$server_id = urldecode($_REQUEST['server_id']);	
	
	if (!is_numeric($platform_id) || !is_numeric($server_id)) {
		exit;		
	}
	
	$ob_data_factory = new CDataOperateFactory('default_m');
	$player_account = $platform_id.'_'.$user_id;
	$escape_player_account = $ob_data_factory->db_mysql_escape_string($player_account);
	$login_server_info = array();
	$player_account_info = array();
	//先从缓存里获取该账号的相关信息
	$memcache_result = $ob_data_factory->memcache_get_data($player_account);
	if ($memcache_result) {//缓存里有数据
		if(0 == $memcache_result[enum_tbl_account::e_is_accept_license]) {
			make_return_err_code_and_des(ErrorCode::ERROR_NOT_ACCEPT_LICENSE,get_err_desc(ErrorCode::ERROR_NOT_ACCEPT_LICENSE));	
			return;		
		}	
		if($token != $memcache_result[enum_tbl_account::e_access_token]) {
			make_return_err_code_and_des(ErrorCode::ERROR_TOKEN_ERROR,get_err_desc(ErrorCode::ERROR_TOKEN_ERROR));	
			return;		
		}
		$player_account_info = $memcache_result;
	} else {
		$select_sql = "SELECT * FROM `tbl_account` WHERE `account`='".$escape_player_account."'";	
		$db_result = $ob_data_factory->db_get_data($select_sql);
		if (!$db_result) {
			make_return_err_code_and_des(ErrorCode::DB_OPERATION_FAILURE,get_err_desc(ErrorCode::DB_OPERATION_FAILURE));	
			return;	
		}
		if (-1 == $db_result) {
			make_return_err_code_and_des(ErrorCode::ERROR_NOT_FIND_THE_UID,get_err_desc(ErrorCode::ERROR_NOT_FIND_THE_UID));	
			return;
		}	
		if(0 == $db_result[0][enum_tbl_account::e_is_accept_license]) {
			make_return_err_code_and_des(ErrorCode::ERROR_NOT_ACCEPT_LICENSE,get_err_desc(ErrorCode::ERROR_NOT_ACCEPT_LICENSE));	
			return;		
		}	
		if($token != $db_result[0][enum_tbl_account::e_access_token]) {
			make_return_err_code_and_des(ErrorCode::ERROR_TOKEN_ERROR,get_err_desc(ErrorCode::ERROR_TOKEN_ERROR));	
			return;		
		}
		//更新缓存，缓存时间10s
		$ob_data_factory->update_memcache_data($player_account,$db_result[0],0,300);
		$player_account_info = $db_result[0];
	}
	
	//先从缓存里获取区表
	$memcache_result = $ob_data_factory->memcache_get_data(memcache_key::e_server_list);
	if ($memcache_result) {
		foreach ($memcache_result as $key=>&$value) {
			if ($server_id == $value[enum_tbl_server::e_id]) {
				$value[enum_tbl_server::e_name] = urlencode($value[enum_tbl_server::e_name]);
				$login_server_info = $value;
				break;
			}
		}      
	} else {//从db里查询
		$select_sql = "SELECT * FROM `tbl_server`";	
		$db_result = $ob_data_factory->db_get_data($select_sql);
		if (!$db_result) {
			make_return_err_code_and_des(ErrorCode::DB_OPERATION_FAILURE,get_err_desc(ErrorCode::DB_OPERATION_FAILURE));	
			return;	
		}	
		if (-1 == $db_result) {
			make_return_err_code_and_des(ErrorCode::ERROR_NOT_CONFIG_SERVER_LIST,get_err_desc(ErrorCode::ERROR_NOT_CONFIG_SERVER_LIST));	
			return;	
		}
		//更新缓存，缓存时间1天
		$ob_data_factory->update_memcache_data(memcache_key::e_server_list,$db_result,0,2592000);	
		foreach ($db_result as $key=>&$value) {
			if ($server_id == $value[enum_tbl_server::e_id]) {
				$value[enum_tbl_server::e_name] = urlencode($value[enum_tbl_server::e_name]);
				$login_server_info = $value;
				break;
			}
		}
	}
	
	if (empty($login_server_info)) {
		make_return_err_code_and_des(ErrorCode::ERROR_NOT_FIND_THE_SERVER_CODE,get_err_desc(ErrorCode::ERROR_NOT_FIND_THE_SERVER_CODE));	
		return;
	}
	
	//判断是否是gm账号
	//从memcahe拿到gmlist表
	$is_gm = false;
	$memcache_result = $ob_data_factory->memcache_get_data(memcache_key::e_gm_list);
	if ($memcache_result) {//缓存里数据
		foreach ($memcache_result as $key=>$value) {
			if ($player_account == $value[enum_tbl_gmlist::e_account]) {
				$is_gm = true;
				break;	
			}
		}      
	} else {//从db里查询
		$select_sql = "SELECT * FROM `tbl_gmlist`";	
		$db_result = $ob_data_factory->db_get_data($select_sql);
		if (!$db_result) {
			make_return_err_code_and_des(ErrorCode::DB_OPERATION_FAILURE,get_err_desc(ErrorCode::DB_OPERATION_FAILURE));	
			return;	
		}	
		if (-1 != $db_result) {
			foreach ($db_result as $key=>$value) {
				if ($player_account == $value[enum_tbl_gmlist::e_account]) {
					$is_gm = true;
					break;	
				}
			}
			//更新缓存，缓存时间1天
			$ob_data_factory->update_memcache_data(memcache_key::e_gm_list,$db_result,0,2592000);		
		}	
	}
	
	if (2 == $login_server_info['run_status'] && !$is_gm_account) {
		make_return_err_code_and_des(ErrorCode::ERROR_SERVER_IS_MAINTAINING,get_err_desc(ErrorCode::ERROR_SERVER_IS_MAINTAINING));	
		return;		
	}

	// 发送账号等相关信息给登入服务器
	$session_key = get_session_key($player_account,$token);
	$sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
	$msg = $player_account . "|" . $session_key . "|" . $platform_id . "|" .$server_id . "|" .$login_server_info["udp_key"] . "|" .$player_account_info[enum_tbl_account::e_enable];
	$len = strlen($msg);
	socket_sendto($sock, $msg, $len, 0, $login_server_info["udp_server_ip"], $login_server_info["udp_server_port"]);
	socket_close($sock);
	
	$Res = array();
	
	//unset($login_server_info["current_code"]);
	unset($login_server_info['url']);
	unset($login_server_info['port']);
	unset($login_server_info["udp_server_ip"]);
	unset($login_server_info["udp_server_port"]);
	unset($login_server_info["udp_key"]);
	unset($login_server_info['belong_to']);
	$Res["error_code"] = ErrorCode::SUCCESS;
	$Res["login_server_info"] = $login_server_info;
	
	$Res = json_encode($Res);
	print_r(urldecode($Res));
	
	
	function is_param_right($request)
	{
		if (!isset($request)) {
			make_return_err_code_and_des(ErrorCode::ERROR_NOT_SET_LOGIN_AUTH_PARAMS,get_err_desc(ErrorCode::ERROR_NOT_SET_LOGIN_AUTH_PARAMS));	
			return false;
		}
		if (!isset($request['token'])) {
			make_return_err_code_and_des(ErrorCode::ERROR_NOT_SET_PLATE_KEY,get_err_desc(ErrorCode::ERROR_NOT_SET_PLATE_KEY));	
			return false;
		}
		if (!isset($request['user_id'])) {
			make_return_err_code_and_des(ErrorCode::ERROR_NOT_SET_UID,get_err_desc(ErrorCode::ERROR_NOT_SET_UID));	
			return false;	
		}
		if (!isset($request['platform_id'])) {
			make_return_err_code_and_des(ErrorCode::ERROR_NOT_SET_PING_TAI_ID,get_err_desc(ErrorCode::ERROR_NOT_SET_PING_TAI_ID));	
			return false;	
		}
		if (!isset($request['server_id'])) {
			make_return_err_code_and_des(ErrorCode::URL_HAS_NO_SERVER_CODE,get_err_desc(ErrorCode::URL_HAS_NO_SERVER_CODE));	
			return false;	
		}
		return true;
	}
?>