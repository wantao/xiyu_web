<?php
require_once "Config.php";
class Menu{
	public static function create($ary){
		require_once "utils.php";
		//Utils::deleteCache(ACCESS_TOKEN);
		$access_token = Utils::getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$access_token";
		$json = json_encode($ary, JSON_UNESCAPED_UNICODE);
		$response = Utils::postUrl($url, $json);
		$response_object = json_decode($response);
		if($response_object->errcode == 0){
			return $response_object->errmsg;
		}else{
			return $response_object->errmsg;
		}
	}
	public static function search(){
		require_once "utils.php";
		$access_token = Utils::getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=$access_token";
		$response = Utils::getUrl($url);
	}
	public static function delete(){
		$access_token = Utils::getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=$access_token";
		$response = Utils::getUrl($url);
	}
}

class Receiver{ 
	public function checkSignature()
	{
		$signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
	public function checkValidateUrlStr(){
		if(isset($_GET["echostr"])){
			echo $_GET["echostr"];
		}
	}
	public function sendToHandler(){
		$content = $GLOBALS["HTTP_RAW_POST_DATA"];
		$xml = simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NOCDATA);
		require_once "messagehandler.php";
		MessageHandler::processEvent($xml);
	}
}

$qiandao_location = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".APPID."&redirect_uri=".DOMAIN_PREFIX."daily_attendance.php&response_type=code&scope=snsapi_base&state=1&connect_redirect=1#wechat_redirect";
$jifen_duihuan_location = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".APPID."&redirect_uri=".DOMAIN_PREFIX."jifen_duihuan.php&response_type=code&scope=snsapi_base&state=1&connect_redirect=1#wechat_redirect";
$lucky_turntable_location = DOMAIN_PREFIX."lucky_turntable/index.html";
$xiyu_luntuan = "http://shequ.yunzhijia.com/thirdapp/forum/network/56c3dd0ee4b0af7bdb3b19c6";
//"http://shequ.yunzhijia.com/thirdapp/forum/network/569c8cf1e4b01b76b8b2be2a";
$ary = array("button" => array(
	array("name" => "西域生活", "sub_button" => array(
			array("type" => "view", "name" => "攻略合集", "url" => "http://www.niuwa123.com/"),
			array("type" => "view", "name" => "官方网站", "url" => "http://www.niuwa123.com/"),
			array("type" => "view", "name" => "游戏下载", "url" => "http://www.niuwa123.com/"),
			array("type" => "view", "name" => "西域论坛", "url" => "$xiyu_luntuan"),
			array("type" => "view", "name" => "无聊点我", "url" => "http://www.niuwa123.com/"),
		)),
	array("name" => "西域福利", "sub_button" => array(
			array("type" => "view", "name" => "积分兑换", "url" => "$jifen_duihuan_location"),
			array("type" => "click", "name" => "每周礼包", "key" => "key_libao"),
			//array("type" => "view", "name" => "每周礼包", "url" => "$qiandao_location"),
			array("type" => "view", "name" => "幸运转盘", "url" => "$lucky_turntable_location"),
			array("type" => "view", "name" => "礼包粮饷", "url" => "http://www.niuwa123.com/"),
			array("type" => "view", "name" => "福利活动", "url" => "http://www.niuwa123.com/"),
		)), 
	array("type" => "view", "name" => "每日话题", "url" => "http://www.niuwa123.com/"),
)); 
print_r(Menu::create($ary));
$receiver = new Receiver();
/* if(!$receiver->checkSignature()){
	return ;
}
$receiver->checkValidateUrlStr(); */
$receiver->sendToHandler(); 
?>