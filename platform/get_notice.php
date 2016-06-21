<?php 
	require_once '../unity/self_error_code.php';
	require_once '../unity/self_platform_define.php';
	require_once '../unity/self_log.php';
	require_once '../unity/self_data_operate_factory.php';
	require_once '../unity/self_common.php';
	require_once '../unity/self_memcache_config.php';
	
	
	//header("Content-type: text/html; charset=utf-8");
	
	if (!function_exists('json_decode')){
		exit('您的PHP不支持JSON，请升级您的PHP版本。');
	}
	$server_code = 0;
	if (isset($_REQUEST['server_code'])) {
		$server_code = $_REQUEST['server_code'];	
		if (!is_numeric($server_code)) {
			return;
		}	
	}
	$notice_key = memcache_key::e_notic_prefix.$server_code;
	$ob_data_factory = new CDataOperateFactory('default_m');
	$echo_info = array();
	//先从memcache里取数据
	$memcache_result = $ob_data_factory->memcache_get_data($notice_key);
	if ($memcache_result) {//缓存里有数据
		$echo_info["error_code"] = ErrorCode::SUCCESS;
		$echo_info["notice_info"] = $memcache_result;
		echo urldecode(json_encode($echo_info));
		return;	
	} else {//没有数据，查db
		$select_sql = "SELECT * FROM `system_notice` WHERE `area_id` = $server_code";	
		$db_result = $ob_data_factory->db_get_data($select_sql);
		if (!$db_result) {
			make_return_err_code_and_des(ErrorCode::DB_OPERATION_FAILURE,get_err_desc(ErrorCode::DB_OPERATION_FAILURE));	
			return;	
		}
		if (-1 == $db_result) {
			make_return_err_code_and_des(ErrorCode::ERROR_NOT_FIND_THE_SERVER_CODE,get_err_desc(ErrorCode::ERROR_NOT_FIND_THE_SERVER_CODE));	
			return;
		}
		foreach ($db_result[0] as $key=>&$value) {
			if (enum_system_notice::e_content == $key) $value = urlencode($value);	
		}
		
		$echo_info["error_code"] = ErrorCode::SUCCESS;
		$echo_info["notice_info"] = $db_result[0];
		echo urldecode(json_encode($echo_info));
		
		//更新缓存
		$ob_data_factory->update_memcache_data($notice_key,$db_result[0],0,2592000);
		return;
	}	
	
	
	
	/************************************************************** 
 * 
 *  将数组转换为JSON字符串（兼容中文） 
 *  @param  array   $array      要转换的数组 
 *  @return string      转换得到的json字符串 
 *  @access public 
 * 
 *************************************************************/
function JSON($array) { 
    arrayRecursive($array, 'urlencode', true); 
    $json = json_encode($array); 
    return urldecode($json); 
} 
/************************************************************** 
 * 
 *  使用特定function对数组中所有元素做处理 
 *  @param  string  &$array     要处理的字符串 
 *  @param  string  $function   要执行的函数 
 *  @return boolean $apply_to_keys_also     是否也应用到key上 
 *  @access public 
 * 
 *************************************************************/
function arrayRecursive(&$array, $function, $apply_to_keys_also = false){ 
    static $recursive_counter = 0; 
    if (++$recursive_counter > 1000) { 
        die('possible deep recursion attack'); 
    } 
    foreach ($array as $key => $value) { 
        if (is_array($value)) { 
            arrayRecursive($array[$key], $function, $apply_to_keys_also); 
        } else { 
            $array[$key] = $function($value); 
        }                                        
        if ($apply_to_keys_also && is_string($key)) { 
            $new_key = $function($key); 
            if ($new_key != $key) { 
                $array[$new_key] = $array[$key]; 
                unset($array[$key]); 
            } 
        } 
    } 
    $recursive_counter--; 
}                                                                                     
	
?>