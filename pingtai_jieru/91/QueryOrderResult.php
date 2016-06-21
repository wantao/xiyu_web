<?php
/**
 * 这个是调用Sdk里查询支付购买结果的DEMO
 * 
 */
require_once 'Sdk.php';
require_once '../unity/self_error_code.php';
require_once '../unity/self_platform_define.php';

$sdk = new Sdk();


$Result = array();//存放结果数组
if (empty($_GET) || !isset($_GET['CooOrderSerial'])) {
    $Result["ErrorCode"] = ErrorCode::ORDER_QUERY_PARAMS_ERROR;
    $Result["ErrorDesc"] =  urlencode("please use GET way and set CooOrderSerial");
	$Res = json_encode($Result);
	print_r(urldecode($Res)); 
	return; 
}

//商户订单号
$CooOrderSerial = $_GET['CooOrderSerial'];

$Res = $sdk->query_pay_result($CooOrderSerial);
$retRes = process_query_pay_result($Res);
print_r($retRes);

//根据平台返回的该订单的支付情况，返回对应的信息给前端
function process_query_pay_result($query_res) {
    
    //订单支付成功
    if ($Res["ErrorCode"] == 1) {
        $op = new OrderOperation();
        //得不到平台的消费流水号，先用订单号替代
        $res = $op->processOrder($CooOrderSerial,PLATFORM::BAI_DU,$CooOrderSerial);
        if ($res == ErrorCode::SUCCESS || $res == ErrorCode::PROCESSED_ORDER) {
    	    $Result["ErrorCode"] =  ErrorCode::SUCCESS;
    		$Result["ErrorDesc"] =  urlencode("支付成功");  
        } 
    	if ($res == ErrorCode::NOT_FIND_ORDER) {
    	    $Result["ErrorCode"] =  ErrorCode::NOT_FIND_ORDER;
    		$Result["ErrorDesc"] =  urlencode("没有该订单");
    	}
    	if ($res == ErrorCode::DB_OPERATION_FAILURE) {
    	    $Result["ErrorCode"] =  ErrorCode::DB_OPERATION_FAILURE;
    		$Result["ErrorDesc"] =  urlencode("系统错误");
    	}
    } else if ($Res["ErrorCode"] == 0) {
        $Result["ErrorCode"] =  ErrorCode::ORDER_PAY_FAILURE;
    	$Result["ErrorDesc"] =  urlencode("订单支付失败");    
    } else if ($Res["ErrorCode"] == 2) {
        $Result["ErrorCode"] =  ErrorCode::ORDER_IS_PROCESSING;
    	$Result["ErrorDesc"] =  urlencode("订单正在处理中"); 
    } else {
        $Result["ErrorCode"] =  ErrorCode::UNKOWN_ERROR;
    	$Result["ErrorDesc"] =  urlencode("未知错误");
    }
    $Res = json_encode($Result);
    return urldecode($Res); 
}

?>