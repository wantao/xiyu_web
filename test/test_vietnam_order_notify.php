<?php

	$status = 1;
	$app_id = "4150ee1fedfb016340a5d7f0a5bbf082";
	//oppo store
	//$app_id = "e659f8af4336490427be06d2b239c91a";
	$transaction_type = "CARD";
	$country_code = "country_code";
	$response_time = "response_time";
	$user_id = "test";
	$username = "test";
	$sign = "ass";
	$card_code = "card_code";
	$card_serial = "card_serial";
	$card_vendor = "card_vendor";
	$google_id = "google_id";
	$apple_id = "apple_id";
	$bank_id = "bank_id";
	
	$game_order = "8520";
	$transaction_id = "teststest235";
	$currency = "VND";
	$amount = "200000";
	

	$param = "game_order=$game_order&transaction_id=$transaction_id&currency=$currency&amount=$amount".
	"&status=$status&app_id=$app_id&transaction_type=$transaction_type&country_code=$country_code&response_time=$response_time".
	"&user_id=$user_id&username=$username&sign=$sign&card_code=$card_code&card_serial=$card_serial&card_vendor=$card_vendor".
	"&google_id=$google_id&apple_id=$apple_id&bank_id=$bank_id";
	//开始发送奖励，下面curl_param中的192.168.1.16中的ip为测试ip，实际情况中需改成游戏web服务器的ip
	$curl_param = "http://127.0.0.1/vietnam_branch/charge/vietnam_store/payment_callback.php?".$param;
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