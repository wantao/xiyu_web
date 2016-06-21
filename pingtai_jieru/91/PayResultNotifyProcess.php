<?php
/**
 * PHP SDK for  OpenAPI
 *
 * @version 1.0
 * @author dev.91.com
 */
 
require_once '../unity/self_pay.php';
require_once '../unity/self_log.php';
require_once '../unity/self_error_code.php';
require_once '../unity/self_platform_define.php';

header("Content-type: text/html; charset=utf-8");

if (!function_exists('json_decode')){
	exit('您的PHP不支持JSON，请升级您的PHP版本。');
}

/**
 * 应用服务器接收91服务器端发过来支付购买结果通知的接口DEMO
 * 当然这个DEMO只是个参考，具体的操作和业务逻辑处理开发者可以自由操作
 */
/*
 * 这里的MyAppId和MyAppKey是我们自己做测试的
 * 开发者可以自己根据自己在dev.91.com平台上创建的具体应用信息进行修改
 */
$MyAppId = 115507; 
$MyAppKey = '4c513d033825fa0d55332bdc2a43f3a0d37b2436402f7a0e';

$Res = pay_result_notify_process($MyAppId,$MyAppKey);

print_r($Res);

/**
 * 此函数就是接收91服务器那边传过来传后进行各种验证操作处理代码
 * @param int $MyAppId 应用Id
 * @param string $MyAppKey 应用Key
 * @return json 结果信息
 */
function pay_result_notify_process($MyAppId,$MyAppKey){
	
	$Result = array();//存放结果数组
	
	if(empty($_GET)||!isset($_GET['AppId'])||!isset($_GET['Act'])||!isset($_GET['ProductName'])||!isset($_GET['ConsumeStreamId'])
		||!isset($_GET['CooOrderSerial'])||!isset($_GET['Uin'])||!isset($_GET['GoodsId'])||!isset($_GET['GoodsInfo'])||!isset($_GET['GoodsCount'])||!isset($_GET['OriginalMoney'])
		||!isset($_GET['OrderMoney'])||!isset($_GET['Note'])||!isset($_GET['PayStatus'])||!isset($_GET['CreateTime'])||!isset($_GET['Sign'])){
		
		$Result["ErrorCode"] =  "0";//注意这里的错误码一定要是字符串格式
		$Result["ErrorDesc"] =  urlencode("接收失败");
		$Res = json_encode($Result);
		return urldecode($Res);
	}
	
	$AppId 				= $_GET['AppId'];//应用ID
	$Act	 			= $_GET['Act'];//操作
	$ProductName		= $_GET['ProductName'];//应用名称
	$ConsumeStreamId	= $_GET['ConsumeStreamId'];//消费流水号
	$CooOrderSerial	 	= $_GET['CooOrderSerial'];//商户订单号
	$Uin			 	= $_GET['Uin'];//91帐号ID
	$GoodsId		 	= $_GET['GoodsId'];//商品ID
	$GoodsInfo		 	= $_GET['GoodsInfo'];//商品名称
	$GoodsCount		 	= $_GET['GoodsCount'];//商品数量
	$OriginalMoney	 	= $_GET['OriginalMoney'];//原始总价（格式：0.00）
	$OrderMoney		 	= $_GET['OrderMoney'];//实际总价（格式：0.00）
	$Note			 	= $_GET['Note'];//支付描述
	$PayStatus		 	= $_GET['PayStatus'];//支付状态：0=失败，1=成功
	$CreateTime		 	= $_GET['CreateTime'];//创建时间
	$Sign		 		= $_GET['Sign'];//91服务器直接传过来的sign
	
	//因为这个DEMO是接收验证支付购买结果的操作，所以如果此值不为1时就是无效操作
	if($Act != 1){
		$Result["ErrorCode"] =  "3";//注意这里的错误码一定要是字符串格式
		$Result["ErrorDesc"] =  urlencode("Act无效");
		$Res = json_encode($Result);
		return urldecode($Res);
	}
	
	//如果传过来的应用ID开发者自己的应用ID不同，那说明这个应用ID无效
	if($MyAppId != $AppId){
		$Result["ErrorCode"] =  "2";//注意这里的错误码一定要是字符串格式
		$Result["ErrorDesc"] =  urlencode("AppId无效");
		$Res = json_encode($Result);
		return urldecode($Res);
	}
	
	//按照API规范里的说明，把相应的数据进行拼接加密处理
	$sign_check = md5($MyAppId.$Act.$ProductName.$ConsumeStreamId.$CooOrderSerial.$Uin.$GoodsId.$GoodsInfo.$GoodsCount.$OriginalMoney.$OrderMoney.$Note.$PayStatus.$CreateTime.$MyAppKey);
	
	if($sign_check == $Sign){//当本地生成的加密sign跟传过来的sign一样时说明数据没问题
		
		/*
		 * 
		 * 开发者可以在此处进行订单号是否合法、商品是否正确等一些别的订单信息的验证处理
		 * 相应的别的错误用不同的代码和相应说明信息，数字和信息开发者可以自定义（数字不能重复）
		 * 如果所有的信息验证都没问题就可以做出验证成功后的相应逻辑操作
		 * 不过最后一定要返回上面那样格式的json数据
		 * 
		 */
		//为了防止是伪造的支付通知，先向91查询该订单是否已完成支付
		$Res = $sdk->query_pay_result($CooOrderSerial);
		if($Res["ErrorCode"] != 1){
			$Result["ErrorCode"] =  "7";//注意这里的错误码一定要是字符串格式
    		$Result["ErrorDesc"] =  urlencode("91服务端没有该订单或未支付成功");
    		$Res = json_encode($Result);
    		return urldecode($Res);	
		}
		 //记录91通知过来的玩家购买记录
		$userBuyMsg = "AppId:".$AppId." Act:".$Act." ProductName:".$ProductName." ConsumeStreamId:".$ConsumeStreamId." CooOrderSerial:".$CooOrderSerial;
    	$userBuyMsg .= " Uin:".$Uin." GoodsId:".$GoodsId." GoodsInfo:".$GoodsInfo." GoodsCount:".$GoodsCount." OriginalMoney:".$OriginalMoney;
    	$userBugMys .= " OrderMoney:".$OrderMoney." Note:".$Note." PayStatus:".$PayStatus." CreateTime:".$CreateTime." Sign:".$Sign;
    	writeLog($userBuyMsg,"log");
    	$op = new OrderOperation();
    	$res = $op->processOrder($CooOrderSerial,PLATFORM::BAI_DU,$ConsumeStreamId);
    	if ($res == ErrorCode::SUCCESS || $res == ErrorCode::PROCESSED_ORDER) {
    	    $Result["ErrorCode"] =  "1";//注意这里的错误码一定要是字符串格式
    		$Result["ErrorDesc"] =  urlencode("接收成功");
    		$Res = json_encode($Result);
    		return urldecode($Res);   
    	} 
    	if ($res == ErrorCode::NOT_FIND_ORDER) {
    	    $Result["ErrorCode"] =  "6";//注意这里的错误码一定要是字符串格式
    		$Result["ErrorDesc"] =  urlencode("没有该订单");
    		$Res = json_encode($Result);
    		return urldecode($Res);
    	}
	}else{
		$Result["ErrorCode"] =  "5";//注意这里的错误码一定要是字符串格式
		$Result["ErrorDesc"] =  urlencode("Sign无效");
		$Res = json_encode($Result);
		return urldecode($Res);
	}
	
}
?>