<?php
	require_once "Utils.php";
	require_once "Config.php";
	require_once("../unity/self_http.php");
	require_once("../unity/self_data_operate_factory.php");
	require_once("../unity/self_table_names.php");
	//获取code
	if (!isset($_GET['code'])) {
		echo "no code";
		return;
	}
	$code = $_GET['code'];
	//Utils::writeLog("get code:".$code);
	//用code获取open_id
	$url_with_param = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".APPID."&secret=".APPSECRET."&code=$code&grant_type=authorization_code";	
	//Utils::writeLog("url_with_param:".$url_with_param);
	$my_http = new CMyHttp();
	$output_tmp = json_decode($my_http->get($url_with_param));
	if (isset($output_tmp->errcode)) {
		echo $output_tmp->errmsg;
		Utils::writeLog("errocode:".$output_tmp->errcode." errmsg:".$output_tmp->errmsg);
		return;
	}
	$open_id=$output_tmp->openid;
	$location = DOMAIN_PREFIX."jifen_duihuan_ui.php?open_id=".urlencode($open_id);
	Utils::writeLog("location:".$location);
	header("location:$location");
?>