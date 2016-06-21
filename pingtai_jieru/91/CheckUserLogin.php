<?php
/**
 * 这个是调用Sdk里检查用户登陆SessionId是否有效的DEMO
 * 
 */
require_once 'Sdk.php';
require_once '../unity/self_account.php';
require_once '../unity/self_log.php';
require_once '../unity/self_error_code.php';
require_once '../unity/self_platform_define.php';

$Sdk = new Sdk();
$Result = array();//存放结果数组
if (!isset($_REQUEST['uin']) || !isset($_REQUEST['session_id']) || !isset($_REQUEST['server_code'])) {
    $Result["ErrorCode"] = ErrorCode::LOGIN_PARAMS_ERROR;
    $Result["ErrorDesc"] =  urlencode("please set uin or session_id or server_code");
	$Res = json_encode($Result);
	print_r(urldecode($Res));
	return;  
}

//用户的91Uin
$Uin = $_REQUEST['uin'];

//用户登陆SessionId
$SessionId = $_REQUEST['session_id'];

//服务器编号
$ServerCode = $_REQUEST['server_code'];

$Res = $Sdk->check_user_login($Uin,$SessionId);

//如果SessionId有效
if ($Res["ErrorCode"] == 1) {
    $oa = new AccountOperation();
    $login_server_info_and_session_key = array();
    $login_server_info_and_session_key = $oa->updateAccount($Uin, $SessionId, PLATFORM::BAI_DU, $ServerCode);
    $ret_result = json_encode($login_server_info_and_session_key);
    print_r(urldecode($ret_result));
} else {
	$Res["ErrorDesc"] = urlencode($Res["ErrorDesc"]);
	print_r(urldecode(json_encode($Res)));	
}

?>