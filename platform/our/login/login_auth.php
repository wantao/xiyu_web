<?php 

	require_once '../../../unity/self_error_code.php';
	require_once '../../../unity/self_account.php';
	require_once '../../../unity/self_platform_define.php';
	require_once '../../../unity/self_log.php';
	require_once '../../../unity/self_data_operate_factory.php';
	require_once '../../../unity/self_global.php';

	$ret_result = array();
	//判断是否设置了相应的登陆参数
	if (!is_param_right($_REQUEST)) {
		return;
	}
	
	$Time = time();
	$token = urldecode($_REQUEST['token']);
	$UserID = urldecode($_REQUEST['user_id']);
	$platform_id = $_REQUEST['platform_id'];
	
	if (!is_numeric($platform_id)) {
		exit;
	}
	
	$curl_param = global_url_prefix::e_login_dir."auth_user_id_and_token.php?user_id=$UserID&token=$token";
	//初始化
	$ch = curl_init();
	//设置选项，包括URL
	curl_setopt($ch, CURLOPT_URL, $curl_param);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	
	//执行并获取HTML文档内容
	$output = curl_exec($ch);
	curl_close($ch);
	//begin 这两句是因为输出的json格式前面有6个怪字符，json解码不了，所以需要去掉
	//这6个怪字符是\n,\n,ef(十进制239),bb(187),bf(191,打不出来，用十六进制表示),\n
	$output = trim($output);
	$output_tmp = json_decode(trim($output,chr(239).chr(187).chr(191)));
	//end
	if (!isset($output_tmp->error_code)) {
		writeLog(get_str_log_prex(__FILE__,__LINE__,__FUNCTION__)." no error_code,desc:".$output_tmp,LOG_NAME::ERROR_LOG_FILE_NAME);
		make_return_err_code_and_des(ErrorCode::ERROR_AUTH_FAILURE,get_err_desc(ErrorCode::ERROR_AUTH_FAILURE));
		exit;
	}
	if (ErrorCode::SUCCESS != $output_tmp->error_code) {
		make_return_err_code_and_des($output_tmp->error_code,get_err_desc($output_tmp->error_code));
		return;	
	}

	$session_key_arr = array();
	$oa = new AccountOperation();
    if (!$oa->update_account_info($UserID,$token,$platform_id, 0)) {
    	make_return_err_code_and_des(ErrorCode::DB_OPERATION_FAILURE,get_err_desc(ErrorCode::DB_OPERATION_FAILURE));	
    	return;
    }
    $session_key_arr["error_code"] = ErrorCode::SUCCESS;
    $session_key_arr["session_key"] = $oa->get_session_key($UserID, $token, $platform_id);
    $ret_result = json_encode($session_key_arr);
    print_r(urldecode($ret_result));
	
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
		return true;
	}		
?>