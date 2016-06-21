<?php 
	require_once '../../../unity/self_error_code.php';
	require_once '../../../unity/self_account.php';
	require_once '../../../unity/self_platform_define.php';
	require_once '../../../unity/self_log.php';
	require_once '../../../unity/self_config.php';
	require_once '../../../unity/self_data_operate_factory.php';
	
	//判断是否设置了相应参数
	if (!is_param_right($_REQUEST)) {
		return;
	}
	
	$user_id = urldecode($_REQUEST['user_id']);
	$token = urldecode($_REQUEST['token']);
	
	
	//判断user_id和token是否存在
	$ob_data_factory = new CDataOperateFactory('default_m');
	//判断账号是否已存在
	//memcache里是否有记录
	$memcache_result = $ob_data_factory->memcache_get_data($user_id);
	if ($memcache_result) {//缓存里有数据
		if (0 != strcmp($memcache_result,$token)) {
			make_return_err_code_and_des(ErrorCode::ERROR_TOKEN_ERROR,get_err_desc(ErrorCode::ERROR_TOKEN_ERROR));
			return;
		}
		make_return_err_code_and_des(ErrorCode::SUCCESS,get_err_desc(ErrorCode::SUCCESS));
		return;
	}
	
	$select_sql = "select * from `tbl_user_id_token` where `user_id` = '".$ob_data_factory->db_mysql_escape_string($user_id)."';";
	$db_result = $ob_data_factory->db_get_data($select_sql);
	if (!$db_result) {
		make_return_err_code_and_des(ErrorCode::DB_OPERATION_FAILURE,get_err_desc(ErrorCode::DB_OPERATION_FAILURE));	
		return;	
	}
	if (-1 == $db_result) {
		make_return_err_code_and_des(ErrorCode::ERROR_NOT_FIND_THE_UID,get_err_desc(ErrorCode::ERROR_NOT_FIND_THE_UID));
		return;
	} else {
		$row = &$db_result[0];
		if ($token != $row['token']) {
			make_return_err_code_and_des(ErrorCode::ERROR_TOKEN_ERROR,get_err_desc(ErrorCode::ERROR_TOKEN_ERROR));
			return;	
		}
		make_return_err_code_and_des(ErrorCode::SUCCESS,get_err_desc(ErrorCode::SUCCESS));
		//更新缓存
		$ob_data_factory->update_memcache_data($user_id,$token,0,2592000);
		return;
	}
	
	function is_param_right($request)
	{
		if (!isset($request)) {
			make_return_err_code_and_des(ErrorCode::ERROR_NOT_SET_PARAMS,get_err_desc(ErrorCode::ERROR_NOT_SET_PARAMS));	
			return false;
		}
		if (!isset($request['user_id'])) {
			make_return_err_code_and_des(ErrorCode::ERROR_NOT_SET_UID,get_err_desc(ErrorCode::ERROR_NOT_SET_UID));	
			return false;	
		}
		if (!isset($request['token'])) {
			make_return_err_code_and_des(ErrorCode::ERROR_NOT_SET_PLATE_KEY,get_err_desc(ErrorCode::ERROR_NOT_SET_PLATE_KEY));	
			return false;
		}
		return true;
	}
?>