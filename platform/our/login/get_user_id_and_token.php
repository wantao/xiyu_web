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
	
	$account = urldecode($_REQUEST['account']);
	$password = urldecode($_REQUEST['password']);
	
	//账号密码长度判断	
	if (strlen($account) <= 0 || strlen($account) > 32) {
		make_return_err_code_and_des(ErrorCode::ERROR_ACCOUNT_IS_TOO_LONG,get_err_desc(ErrorCode::ERROR_ACCOUNT_IS_TOO_LONG));	
		return;	
	}
	
	if (strlen($password) <= 0 || strlen($password) > 32) {
		make_return_err_code_and_des(ErrorCode::ERROR_PASSWORD_IS_TOO_LONG,get_err_desc(ErrorCode::ERROR_PASSWORD_IS_TOO_LONG));	
		return;	
	}
	
	$ob_data_factory = new CDataOperateFactory('default_m');
	//判断账号是否已存在
	//memcache里是否有记录
	$memcache_result = $ob_data_factory->memcache_get_data($account);
	if ($memcache_result) {//缓存里有数据
		if (0 != strcmp($memcache_result['password'],$password)) {
			make_return_err_code_and_des(ErrorCode::ERROR_PASSWORD_IS_WRONG,get_err_desc(ErrorCode::ERROR_PASSWORD_IS_WRONG));
			return;
		}
		unset($memcache_result['password']);
		$Res = json_encode($memcache_result);
		print_r(urldecode($Res));
		return;
	}
	$select_sql = "select * from `tbl_user_id_token` where `account` = '".$ob_data_factory->db_mysql_escape_string($account)."';";
	$db_result = $ob_data_factory->db_get_data($select_sql);
	if (!$db_result) {
		make_return_err_code_and_des(ErrorCode::DB_OPERATION_FAILURE,get_err_desc(ErrorCode::DB_OPERATION_FAILURE));	
		return;	
	}
	$result_ret = array();
	$cache_password = $password;
	if (-1 == $db_result) {
		//不存在该记录，则插入
		//产生token
		$token = generate_token(32);
		$insert_sql = "insert into `tbl_user_id_token` (`account`,`password`,`token`) values('".$ob_data_factory->db_mysql_escape_string($account)."','".$ob_data_factory->db_mysql_escape_string($password)."','".$token."');";
		if (!$ob_data_factory->db_update_data($insert_sql)) {
			make_return_err_code_and_des(ErrorCode::DB_OPERATION_FAILURE,get_err_desc(ErrorCode::DB_OPERATION_FAILURE));
			return;
		}
		$mysqli_tmp = &$ob_data_factory->mysql->get_mysqli();
		$user_id = $mysqli_tmp->insert_id;
		$result_ret["error_code"]=0;
		//$result_ret["user_id"]=$user_id; // 这个格式化 user_id 是个整数, 所以要转换
		$result_ret["user_id"]=strval($user_id); // 转成字符串
		$result_ret["token"]=$token;
		$Res = json_encode($result_ret);
		print_r(urldecode($Res));
	} else {
		$row = &$db_result[0];
		if (0 != strcmp($row['password'],$password)) {
			make_return_err_code_and_des(ErrorCode::ERROR_PASSWORD_IS_WRONG,get_err_desc(ErrorCode::ERROR_PASSWORD_IS_WRONG));
			return;
		}
		//存在该记录
		//判断时间是否在一分钟内（token有效期），
		$token_time = $row['token_time'];
		$token = $row['token'];
		$user_id = $row['user_id'];
		$password = $row['password'];
		if (time() - $token_time >= 60) {
			$token = generate_token(32);
			//更新token
			$update_sql = "update `tbl_user_id_token` set `token`='$token' where `account`='".$ob_data_factory->db_mysql_escape_string($account)."';";
			if (!$ob_data_factory->db_update_data($update_sql)) {
				make_return_err_code_and_des(ErrorCode::DB_OPERATION_FAILURE,get_err_desc(ErrorCode::DB_OPERATION_FAILURE));
				return;
			}
		} 
		$result_ret["error_code"]=0;
		$result_ret["user_id"]=$user_id; // 这个时候 user_id 是个字符串
		$result_ret["token"]=$token;
		$Res = json_encode($result_ret);
		print_r(urldecode($Res));	

	}
	//更新缓存
	$result_ret['password'] = $password;
	$ob_data_factory->update_memcache_data($account,$result_ret,0,2592000);
	
	function is_param_right($request)
	{
		if (!isset($request)) {
			make_return_err_code_and_des(ErrorCode::ERROR_NOT_SET_PARAMS,get_err_desc(ErrorCode::ERROR_NOT_SET_PARAMS));	
			return false;
		}
		if (!isset($request['account'])) {
			make_return_err_code_and_des(ErrorCode::ERROR_NOT_SET_ACCOUNT,get_err_desc(ErrorCode::ERROR_NOT_SET_ACCOUNT));	
			return false;	
		}
		if (!isset($request['password'])) {
			make_return_err_code_and_des(ErrorCode::ERROR_NOT_SET_PASSWORD,get_err_desc(ErrorCode::ERROR_NOT_SET_PASSWORD));	
			return false;
		}
		return true;
	}
	
	function generate_token($token_len)
	{
		$str = null;
	   	$strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
	   	$max = strlen($strPol)-1;
		
	   	for($i=0;$i<$token_len;$i++){
	    	$str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
	   	}
	
	   	return $str;
	}
?>