<?php
	$code = $_GET['code'];
	//发送到transfer
	$url_with_param = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx06e4d8d9f089d009&secret=d4624c36b6795d1d99dcf0547af5443d&code=$code&grant_type=authorization_code";	
	//Utils::writeLog("url_with_param:".$url_with_param);
	//初始化
	$ch = curl_init();
	//设置选项，包括URL
	//curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:20000/?type=gm&account=test1&cmd=reload+py_cpp");
	curl_setopt($ch, CURLOPT_URL, $url_with_param);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);	// https请求 不验证证书和hosts
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	//执行并获取HTML文档内容
	$output = curl_exec($ch);
	$output_tmp = json_decode($output);
	if (isset($output_tmp->errcode)) {
		echo $output_tmp->errmsg;
		//Utils::writeLog("errocode:".$output_tmp->errcode." errmsg:".$output_tmp->errmsg);
		return;
	}
	print_r($output_tmp);
	echo "openid:".$output_tmp->openid;
	//Utils::writeLog("output1:".$output->openid);
	//释放curl句柄
	curl_close($ch);
?>