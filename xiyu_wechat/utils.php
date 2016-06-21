<?php
class Utils {
	public static function postUrl($url, $post_data = '', $timeout = 5){
		$ch = curl_init();
	    curl_setopt ($ch, CURLOPT_URL, $url);
	    curl_setopt ($ch, CURLOPT_POST, 1);
	    if($post_data != ''){
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	    }
	    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
	    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  false);
	    curl_setopt($ch, CURLOPT_HEADER, false);
	    $file_contents = curl_exec($ch);
	    curl_close($ch);
	    return $file_contents;
	}
	public static function getUrl($url, $timeout = 5){
		$ch = curl_init();
	    curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_HTTPGET, 1);
	    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
	    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  false);
	    curl_setopt($ch, CURLOPT_HEADER, false);
	    $file_contents = curl_exec($ch);
	    curl_close($ch);
	    return $file_contents;
	}
	public static function getAccessToken(){
		$token = self::getCachedAccessToken();
		if(!is_null($token)){
			self::writeLog("get a cached token " . $token);
			return $token;
		}
		require_once "Config.php";
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".APPID."&secret=".APPSECRET;
        $json = self::getUrl($url);
        $params = json_decode($json, true);
        if(!isset($params["errcode"])){
			self::writeLog("get a new token " . $params["access_token"]);
			self::setCachedAccessToken($params["access_token"], $params["expires_in"] - 600);
        	return $params["access_token"];
        }else{
        	self::writeLog($params["errmsg"]);
        	return ;
        }
	}
	public static function getCachedAccessToken(){
		require_once "Config.php";
		$cached = self::getCache(ACCESS_TOKEN);
		if($cached){
			return $cached;
		}
		return NULL;
	}
	public static function setCachedAccessToken($access_token, $expires_in){
		require_once "Config.php";
		self::setCache(ACCESS_TOKEN, $access_token, $expires_in);
	}
	public static function getCache($key){
		$memcache = new Memcache();
		$value = false;
		if($memcache->connect("localhost", 11211)){
			$value = $memcache->get("$key");
			$memcache->close();
		}
		return $value;
	}
	public static function setCache($key, $value, $expire_in){
		$memcache = new Memcache();
		if($memcache->connect("localhost", 11211)){
			$value = $memcache->set("$key", $value, false, $expire_in);
			$memcache->close();
		}
	}
	public static function deleteCache($key, $timeout = 0){
		$memcache = new Memcache();
		if($memcache->connect("localhost", 11211)){
			$memcache->delete("$key", $timeout);
			$memcache->close();
		}
	}
	public static function makeXML($ary, $tag = "xml"){
		$xml = "<$tag>".self::makeInnerXML($ary)."</$tag>";
		return $xml;
	}
	public static function makeInnerXML($ary){
		$xml = "";
		while(list($key, $value) = each($ary)){
			if(is_array($value)){
				$xml = $xml . "<$key>". self::makeInnerXML($value)."</$key>";
			}else{
				$xml = $xml . "<$key><![CDATA[$value]]></$key>";
			}
		}
		return $xml;
	}
	public static function runSqlQuery($query){
		$link = mysql_connect("localhost:3380", "root", "123!@#") or die("error " . mysql_error());
		mysql_select_db("wechat") or die("can't select database");
		$result = mysql_query($query) or die("Invalid ".mysql_error());
		mysql_close($link);
		return $result;
	}
	public static function writeLog($msg){
 		$logFile = date('Y-m-d').'.txt';
 		$msg = date('Y-m-d H:i:s').' >>> '.$msg."\r\n";
 		file_put_contents($logFile,$msg,FILE_APPEND );
	}
}
?>