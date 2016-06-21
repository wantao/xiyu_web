<?php
class User{
	public static function createGroup($name){
		$json = "\"group\":{\"name\":\"$name\"}";
		require_once "utils.php";
		$access_token = Utils::getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/groups/create?access_token=$access_token";
		$response = Utils::postUrl($url, $json);
		$response_ary = json_decode($response, true);
		Utils::writeLog("A new group created. id: ".$response_ary["group"]["id"]);
	}
	public static function listGroup(){
		require_once "utils.php";
		$access_token = Utils::getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/groups/get?access_token=$access_token";
		$response = Utils::getUrl($url);
		$response_ary = json_decode($response, true);
		if(isset($response_ary["errcode"])){
			Utils::writeLog("Get all group error. ".$response_ary["errmsg"]);
		}else{
			return $response_ary["groups"];
		}
	}
	public static function findUserGroup($openid){
		$json = "{\"openid\":\"$openid\"}";
		require_once "utils.php";
		$access_token = Utils::getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/groups/getid?access_token=$access_token";
		$response = Utils::postUrl($url, $json);
		$response_ary = json_decode($response, true);
		if(isset($response_ary["errcode"])){
			Utils::writeLog("Get user group error. ".$response_ary["errmsg"]);
		}else{
			return $response_ary["groupid"];
		}
	}
	public static function updateGroupName($groupid, $groupname){
		$json = "\"group\":{\"id\":\"$groupid\", \"name\":\"$groupname\"}";
		require_once "utils.php";
		$access_token = Utils::getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/groups/getid?access_token=$access_token";
		$response = Utils::postUrl($url, $json);
		$response_ary = json_decode($response, true);
		Utils::writeLog("Update group name: ".$reponse["errmsg"]);
	}
	public static function updateUserGroup($openid, $togroupid){
		$json = "{\"openid\":\"$openid\", \"to_groupid\":\"$togroupid\"}";
		require_once "utils.php";
		$access_token = Utils::getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=$access_token";
		$response = Utils::postUrl($url, $json);
		$response_ary = json_decode($response, true);
		Utils::writeLog("Update user group: ".$reponse["errmsg"]);
	}
	public static function getUserInfo($openid, $lang = ""){
		require_once "utils.php";
		$access_token = Utils::getAccessToken();
		$url = "";
		if($lang == "zh_CN" || $lang == "zh_TW" || $lang == "en"){
			$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=$lang";
		}else{
			$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid";
		}
		$response = Utils::getUrl($url);
		$response_ary = json_decode($reponse, true);
		if(isset($response_ary["errcode"])){
			Utils::writeLog("Get user info error: ".$response_ary["errmsg"]);
		}else{
			return $response_ary;
		}
	}
	public static function getFollowUser($nextopenid = ""){
		require_once "utils.php";
		$access_token = Utils::getAccessToken();
		$url = "";
		if(is_numeric($nextopenid)){
			$url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=$access_token&next_openid=$nextopenid";
		}else{
			$url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=$access_token";
		}
		$response = Utils::getUrl($url);
		$response_ary = json_decode($reponse, true);
		if(isset($response_ary["errcode"])){
			Utils::writeLog("Get follower list error: ".$response_ary["errmsg"]);
		}else{
			return $response_ary;
		}
	}
}
?>