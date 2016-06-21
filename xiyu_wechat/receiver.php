<?php
class Receiver{ 
	public function checkSignature()
	{
		$signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        
		$token = TOKEN;
		Utils::writeLog("TOKEN:".$token);
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
		Utils::writeLog("Receive at ". time() ." : $content");
		$xml = simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NOCDATA);
		require_once "messagehandler.php";
		MessageHandler::processEvent($xml);
	}
}
require_once "Config.php";
require_once "utils.php";
$receiver = new Receiver();
if(!$receiver->checkSignature()){
	return ;
}
//$receiver->checkValidateUrlStr();
$receiver->sendToHandler();

?>