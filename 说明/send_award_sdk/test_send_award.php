<?php

	//app_acount和app_key，用于签名(一下的account和key均为测试用，实际情况中，需更换实际的app_account和key)
	$app_account = "test_account";
	$award_static_key = '4c513d033825fa0d55332bdc2a43f3a0d37b2436402f7a0e';

	
	//url中的相关参数
	//app_account
	//$app_account = "test_account";
	
	//玩家id
	$player_id = 1;
	
	//邮件类型0：非奖励邮件，2：可领奖邮件
	$msg_type="2";
	//邮件主题
	$msg_title="阿斯顿按时 sdf阿斯顿 啊";
	//邮件消息内容
	$msg_content="阿斯顿 as 按时";
	//邮件奖励
	$award="6:0:10000000";
	//签名
	$sign = md5($app_account.$player_id.$msg_type.$msg_title.$msg_content.$award.$award_static_key);
	
	//对url中相关的参数做urlencode
	$app_account = urlencode($app_account);
	$player_id = urlencode($player_id);
	$msg_type = urlencode($msg_type);
	$msg_title = urlencode($msg_title);
	$msg_content = urlencode($msg_content);
	$award = urlencode($award);
	$sign = urlencode($sign);
	
	//开始发送奖励，下面curl_param中的192.168.1.16中的ip为测试ip，实际情况中需改成游戏web服务器的ip
	$curl_param = "http://11.11.11.11/award_send/award.php?app_account=$app_account&player_id=$player_id&msg_type=$msg_type&msg_title=$msg_title&msg_content=$msg_content&award=$award&sign=$sign";
	
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
	echo "<br>"."err_code:".$output->err_code." err_desc:".$output->err_desc;
	//print_r($output);
	//释放curl句柄
	curl_close($ch);
?>