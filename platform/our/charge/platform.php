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
    //����Ƕ����������ַ���������lua��������ʮ�����ƶ�������Ҫת��Ϊ10����
	$preg2= '/[A-Za-z]/';
	if(preg_match($preg2,$game_order)){
		//16���� ת 10����
		$game_order = hexdec($game_order); 
	}
	$transaction_id = $game_order;
	
	$params = "game_order=$game_order&transaction_id=$transaction_id";
	
	$url = "http://myxiyu.com/platform/our/charge/charge.php";
    $http = new CMyHttp();
	$content = $http->post($url, $params);
	
	print_r($content);
?>