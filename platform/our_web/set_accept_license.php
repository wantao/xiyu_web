<?php

	require_once '../../../unity/self_error_code.php';
	require_once '../../../unity/self_platform_define.php';
	require_once '../../../unity/self_log.php';
	require_once '../../../unity/self_data_operate_factory.php';
	require_once '../../../unity/self_common.php';

	$ret_result = array();
	//判断是否设置了相应的登陆参数
	if (!is_param_right($_REQUEST)) {
		return;
	}
	$UserID = urldecode($_REQUEST['user_id']);
	$platform_id = urldecode($_REQUEST['platform_id']);
	//$platform_id = PLATFORM::HONGXINGLAJIAO;
	
	$ob_data_factory = new CDataOperateFactory('default_m');
	$player_account = $platform_id.'_'.$UserID;
	$escape_player_account = $ob_data_factory->db_mysql_escape_string($player_account);
	//先从memcache里取数据
	$memcache_result = $ob_data_factory->memcache_get_data($player_account);
	if ($memcache_result) {//memcache里有数据
		if(1 == $memcache_result[enum_tbl_account::e_is_accept_license]) {
			make_return_err_code_and_des(ErrorCode::SUCCESS,get_err_desc(ErrorCode::SUCCESS));	
			return;		
		}
		foreach ($memcache_result as $key=>&$value) {
			if (enum_tbl_account::e_is_accept_license == $key) $value = 1;
		}
		$update_sql = "update `tbl_account` set `is_accept_license` = 1 where `account`='".$escape_player_account."'";
		//更新db
		if (!$ob_data_factory->db_update_data($update_sql)) {
			make_return_err_code_and_des(ErrorCode::DB_OPERATION_FAILURE,get_err_desc(ErrorCode::DB_OPERATION_FAILURE));	
			return;	
		}
		//更新缓存，缓存时间10s
		$ob_data_factory->update_memcache_data($player_account,$memcache_result,0,300);		
	} else {//memcache异常或没有数据,查db
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
		//已接受，更新缓存，返回
		if(1 == $db_result[0][enum_tbl_account::e_is_accept_license]) {
			//更新缓存，缓存时间10s
			$ob_data_factory->update_memcache_data($player_account,$db_result[0],0,300);
			make_return_err_code_and_des(ErrorCode::SUCCESS,get_err_desc(ErrorCode::SUCCESS));	
			return;		
		}
		$update_sql = "update `tbl_account` set `is_accept_license` = 1 where `account`='".$escape_player_account."'";
		//更新db
		if (!$ob_data_factory->db_update_data($update_sql)) {
			make_return_err_code_and_des(ErrorCode::DB_OPERATION_FAILURE,get_err_desc(ErrorCode::DB_OPERATION_FAILURE));	
			return;	
		}
		$db_result[0][enum_tbl_account::e_is_accept_license] = 1;
		//更新缓存，缓存时间10s
		$ob_data_factory->update_memcache_data($player_account,$db_result[0],0,300);	
	}
	make_return_err_code_and_des(ErrorCode::SUCCESS,get_err_desc(ErrorCode::SUCCESS));	
	return;

	function is_param_right($request)
	{
		if (!isset($request)) {
			make_return_err_code_and_des(ErrorCode::ERROR_NOT_SET_LOGIN_AUTH_PARAMS,get_err_desc(ErrorCode::ERROR_NOT_SET_LOGIN_AUTH_PARAMS));	
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
		return true;
	}

?>