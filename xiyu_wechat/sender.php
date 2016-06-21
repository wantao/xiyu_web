<?php
class Sender{
 	public static function send($input, $isxml = false){
		if($isxml){
			Utils::writeLog("Send at " . time() . " : $input");
			echo $input;
		}else{
			require_once "utils.php";
			$xml = Utils::makeXML($input);
			Utils::writeLog("Send at " . time() . " : $xml");
			echo $xml;
		}
 	}
	public static function delaySend($input, $isjson = false){
		require_once "utils.php";
		$json = "";
		if($isjson){
			$json = $input;
		}else{
			$json = json_encode($input);
		}
		$access_token = Utils::getAccessToken();
		Utils::postUrl("https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=$access_token", $json);
	}
	public static function sendGroup($input, $isjson = false){
		$json = "";
		if(!$isjson){
			$json = json_encode($input);
		}else{
			$json = $input;
		}
		require_once "utils.php";
		$access_token = Utils::getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=$access_token";
		$response = Utils::postUrl($url, $json);
		$response_ary = json_decode($response);
		Utils::writeLog($response_ary["errmsg"]);
	}
	public static function sendIDList($input, $isjson = false){
		$json = "";
		if(!$isjson){
			$json = json_encode($input);
		}else{
			$json = $input;
		}
		require_once "utils.php";
		$access_token = Utils::getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=$access_token";
		$response = Utils::postUrl($url, $json);
		$response_ary = json_decode($response);
		Utils::writeLog($response_ary["errmsg"]);
	}
	public static function sendEmptyMessage(){
		echo " ";
	}
 	public static function sendTextMessage($tousername, $fromusername, $content, $delay = false){
		if($delay){
			self::delaySend(array(
				"touser" => $tousername,
				"msgtype" => "text",
				"text" => array(
					"content" => $content
				)
			));
		}else{
			self::send(array(
				"ToUserName" => $tousername,
				"FromUserName" => $fromusername,
				"CreateTime" => time(),
				"MsgType" => "text",
				"Content" => $content
			));
		}
 	} 
	public static function sendGroupTextMessage($groupid, $content){
		self::sendGroup(array(
			"filter" => array("group_id" => $groupid),
			"text" => array("content" => $content),
			"msgtype" => "text"
		));
	}
	public static function sendIDListTextMessage($idlist, $content){
		self::sendIDList(array(
			"touser" => $idlist,
			"text" => array("content" => $content),
			"msgtype" => "text"
		));
	}
	public static function sendPicMessage($tousername, $fromusername, $mediaid, $delay = false){
		if($delay){
			self::delaySend(array(
				"touser" => $tousername,
				"msgtype" => "image",
				"image" => array(
					"media_id" => $mediaid
				)
			));
		}else{
			self::send(array(
				"ToUserName" => $tousername,
				"FromUserName" => $fromusername,
				"CreateTime" => time(),
				"MsgType" => "image",
				"Image" => array(
					"MediaId" => $mediaid
				)
			));
		}
	}
	public static function sendGroupPicMessage($groupid, $mediaid){
		self::sendGroup(array(
			"filter" => array("group_id" => $groupid),
			"image" => array("media_id" => $mediaid),
			"msgtype" => "image"
		));
	}
	public static function sendIDListPicMessage($idlist, $mediaid){
		self::sendIDList(array(
			"touser" => $idlist,
			"image" => array("media_id" => $mediaid),
			"msgtype" => "image"
		));
	}
	public static function sendAudioMessage($tousername, $fromusername, $mediaid, $delay = false){
		if($delay){
			self::delaySend(array(
				"touser" => $tousername,
				"msgtype" => "voice",
				"voice" => array(
					"media_id" => $mediaid
				)
			));
		}else{
			self::send(array(
				"ToUserName" => $tousername,
				"FromUserName" => $fromusername,
				"CreateTime" => time(),
				"MsgType" => "voice",
				"Voice" => array(
					"MediaId" => $mediaid
				)
			));
		}
	}
	public static function sendGroupAudioMessage($groupid, $mediaid){
		self::sendGroup(array(
			"filter" => array("group_id" => $groupid),
			"voice" => array("media_id" => $mediaid),
			"msgtype" => "voice"
		));
	}
	public static function sendIDListAudioMessage($idlist, $mediaid){
		self::sendIDList(array(
			"touser" => $idlist,
			"voice" => array("media_id" => $mediaid),
			"msgtype" => "voice"
		));
	}
	public static function sendVideoMessage($tousername, $fromusername, $mediaid, $title, $description, $delay = false){
		if($delay){
			self::delaySend(array(
				"touser" => $tousername,
				"msgtype" => "video",
				"video" => array(
					"media_id" => $mediaid,
					"title" => $title,
					"description" => $descrition
				)
			));
		}else{
			self::send(array(
				"ToUserName" => $tousername,
				"FromUserName" => $fromusername,
				"CreateTime" => time(),
				"MsgType" => "video",
				"Video" => array(
					"MediaId" => $mediaid
					//"Title" => $title,
					//"Description" => $description
				)
			));
		}
	}
	public static function sendGroupVideoMessage($groupid, $mediaid){
		self::sendGroup(array(
			"filter" => array("group_id" => $groupid),
			"mpvideo" => array("media_id" => $mediaid),
			"msgtype" => "mpvideo"
		));
	}
	public static function sendIDListVideoMessage($idlist, $mediaid, $title, $description){
		self::sendIDList(array(
			"touser" => $idlist,
			"video" => array("media_id" => $mediaid, "title" => $title, "description" => $description),
			"msgtype" => "video"
		));
	}
	public static function sendMusicMessage($tousername, $fromusername, $title, $description, $musicurl, $hqmusicurl, $thumb_media_id, $delay = false){
		if($delay){
			self::delaySend(array(
				"touser" => $tousername,
				"msgtype" => "music",
				"music" => array(
					"title" => $title,
					"description" => $descrition,
					"musicurl" => $musicurl,
					"hqmusicurl" => $hqmusicurl,
					"thumb_media_id" => $thumb_media_id
				)
			));
		}else{
			self::send(array(
				"ToUserName" => $tousername,
				"FromUserName" => $fromusername,
				"CreateTime" => time(),
				"MsgType" => "music",
				"Music" => array(
					"Title" => $title,
					"Description" => $description,
					"MusicUrl" => $musicurl,
					"HQMusicUrl" => $hqmusicurl,
					"ThumbMediaId" => $thumb_media_id
				)
			));
		}
	}
	
	
	//articles格式：array(
	//					array("title" => "TITLE1", "description" => "DESC1", "url" => "URL1", "picurl" => "PICURL1"),
	//					array("title" => "TITLE2", "description" => "DESC2", "url" => "URL2", "picurl" => "PICURL2"),
	//					...
	//				)
	public static function sendNewsMessage($tousername, $fromusername, $articlecount, $articles, $delay = false){
		if($delay){
			self::delaySend(array(
				"touser" => $tousername,
				"msgtype" => "news",
				"news" => array(
					"articles" => $articles
				)
			));
		}else{
			//由于图文消息的xml格式略有不同，单独处理
			require_once "utils.php";
			$articles_xml = "";
			foreach($articles as $one){
				$articles_xml = $articles_xml . Utils::makeXML($one, "item");
			}
			$xml = "<xml>";
			$inner = Utils::makeInnerXML(array(
				"ToUserName" => $tousername,
				"FromUserName" => $fromusername,
				"CreateTime" => time(),
				"MsgType" => "news",
				"ArticleCount" => $articlecount,
			));
			$xml = $xml . $inner . "<Articles>$articles_xml</Articles>";
			$xml = $xml . "</xml>";
			self::send($xml, true);
		}
	}
	public static function sendGroupNewsMessage($groupid, $mediaid){
		self::sendGroup(array(
			"filter" => array("group_id" => $groupid),
			"mpnews" => array("media_id" => $mediaid),
			"msgtype" => "mpnews"
		));
	}
	public static function sendIDListNewsMessage($idlist, $mediaid){
		self::sendIDList(array(
			"touser" => $idlist,
			"mpnews" => array("media_id" => $mediaid),
			"msgtype" => "mpnews"
		));
	}
} 
?>