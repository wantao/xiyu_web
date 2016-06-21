<?php
class MessageHandler{
	public static function processEvent($xml){
		require_once "Utils.php";
		require_once "message.php";
		if($xml->MsgType == "text"){
			Utils::writeLog("This is a text message");
			TextMessage::Instance()->handle($xml->ToUserName, $xml->FromUserName, $xml->CreateTime, $xml->MsgType, $xml->Content, $xml->MsgId);
		}else if($xml->MsgType == "image"){
			Utils::writeLog("This is a image message. MediaId: ".$xml->MediaId);
			PicMessage::Instance()->handle($xml->ToUserName, $xml->FromUserName, $xml->CreateTime, $xml->MsgType, $xml->PicUrl, $xml->MediaId, $xml->MsgId);
		}else if($xml->MsgType == "voice"){
			Utils::writeLog("This is a voice message");
			AudioMessage::Instance()->handle($xml->ToUserName, $xml->FromUserName, $xml->CreateTime, $xml->MsgType, $xml->MediaId, $xml->Format, $xml->MsgId);
		}else if($xml->MsgType == "video"){
			Utils::writeLog("This is a video message");
			VideoMessage::Instance()->handle($xml->ToUserName, $xml->FromUserName, $xml->CreateTime, $xml->MsgType, $xml->MediaId, $xml->ThumbMediaId, $xml->MsgId);
		}else if($xml->MsgType == "location"){
			Utils::writeLog("This is a location message");
			LocationMessage::Instance()->handle($xml->ToUserName, $xml->FromUserName, $xml->CreateTime, $xml->MsgType, $xml->Location_X, $xml->Location_Y, $xml->Scale, $xml->Label, $xml->MsgId);
		}else if($xml->MsgType == "link"){
			Utils::writeLog("This is a link message");
			UrlMessage::Instance()->handle($xml->ToUserName, $xml->FromUserName, $xml->CreateTime, $xml->MsgType, $xml->Title, $xml->Description, $xml->Url, $xml->MsgId);
		}else if($xml->MsgType == "event"){
			if($xml->Event == "subscribe"){
				Utils::writeLog("This is a subscribe event message");
				SubscribeMessage::Instance()->handle($xml->ToUserName, $xml->FromUserName, $xml->CreateTime, $xml->MsgType, $xml->Event);
			}else if($xml->Event == "unsubscribe"){
				Utils::writeLog("This is a unsubscribe event message");
			}else if($xml->Event == "SCAN"){
				Utils::writeLog("This is a scan event message");
			}else if($xml->Event == "LOCATION"){
				Utils::writeLog("This is a location event message");
			}else if($xml->Event == "CLICK"){
				Utils::writeLog("This is a click event message");
				ClickMenuGetInfoMessage::Instance()->handle($xml->ToUserName, $xml->FromUserName, $xml->CreateTime, $xml->MsgType, $xml->Event, $xml->EventKey);
			}else if($xml->Event == "VIEW"){
				Utils::writeLog("This is a view event message,open_id:".$xml->FromUserName);  
			}
		}
	} 
}
?>