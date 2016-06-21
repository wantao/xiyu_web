<?php
    require_once  '..\..\..\unity\self_http.php';
	if($_SERVER['REQUEST_METHOD'] != 'POST'){
		echo "Forbidden. Only POST request is allowed.";
		return;
	}
	
	if(!isset($_POST['game_order'])){
		echo "Arguments error";
		return;
	}
	
	$game_order = $_POST['game_order'];
    //如果是订单号里有字符串，则是lua传过来的十六进制订单，需要转换为10进制
	$preg2= '/[A-Za-z]/';
	if(preg_match($preg2,$game_order)){
		//16进制 转 10进制
		$game_order = hexdec($game_order); 
	}
	$transaction_id = $game_order;
	
	$params = "game_order=$game_order&transaction_id=$transaction_id";
	
	$url = "http://myxiyu.com/platform/our/charge/charge.php";
    $http = new CMyHttp();
	$content = $http->post($url, $params);
	
	print_r($content);
?>