<?php
	
	/*$user_name = '551e20be6803fa1c883be0fe';
	$game_code = 'trieu-hoi-3d';
	$token = md5('9chau_sdk_'.$game_code.'_'.$user_name);
	
	$param = "username=$user_name&game_code=$game_code&token=$token";
	echo $param;
	$curl_param = "http://127.0.0.1/vietnam_branch/login_auth/eway/login_notify.php?".$param;
	//$curl_param = "http://127.0.0.1/vietnam_branch/charge/vietnam_store/appota_payment_confirm.php?".$param;
	//初始化
	$ch = curl_init();
	//设置选项，包括URL
	//curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:20000/?type=gm&account=test1&cmd=reload+py_cpp");
	curl_setopt($ch, CURLOPT_URL, $curl_param);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	
	//执行并获取HTML文档内容
	$output = curl_exec($ch);
	print_r($output);
	$output = json_decode($output);
	//print_r($output);
	//释放curl句柄
	curl_close($ch);*/







	$username = urlencode('4_551e20be6803fa1c883be0fe');
	$game_code = 'trieu-hoi-3d';
	$trans_id = '111';
	$telco = '123567';
	$serial = '222';
	$pincode = '4333';
	$money = '10000';
	$currency = 'VND';
	$game_money = '23';
	$token = md5('9chau_sdk_'.$serial.'_'.$pincode);
	$game_order = '8522';
	
	
	
	
	$param = "username=$username&game_code=$game_code&trans_id=$trans_id&telco=$telco&serial=$serial&pincode=$pincode&".
	"money=$money&currency=$currency&game_money=$game_money&token=$token&game_order=$game_order";
	//echo $param;
	$curl_param = "www.vietnam_branch.com/charge/eway/eway_payment_notify.php?".$param;
	//$curl_param = "http://127.0.0.1/vietnam_branch/charge/vietnam_store/appota_payment_confirm.php?".$param;
	//初始化
	$ch = curl_init();
	//设置选项，包括URL
	//curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:20000/?type=gm&account=test1&cmd=reload+py_cpp");
	curl_setopt($ch, CURLOPT_URL, $curl_param);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	
	//执行并获取HTML文档内容
	$output = curl_exec($ch);
	print_r($output);
	$output = json_decode($output);
	//print_r($output);
	//释放curl句柄
	curl_close($ch);
?>