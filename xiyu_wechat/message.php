<?php
class Message{
	function __autoload($class_name) {
    	require_once $class_name . '.php';
	}
	protected static $instance = NULL;
	public static function instance(){
		if(is_null(self::$instance)){
			self::$instance = new static();
			//print_r(self::$instance);
		}
		return self::$instance;
	}
	protected function __construct(){}
	public function checkRepeat(){}
	public function handle(){}
}
class CommonMessage extends Message{
	protected static $array = array();
	public function checkRepeat($msgid){
		if(in_array($msgid, self::$array)){
			return true;//重复
		}
		return false;
	}
	public function handle(){}
	public function handleJSON(){}
}
class TextMessage extends CommonMessage{
	public function handle($tousername, $fromusername, $createtime, $msgtype, $content, $msgid){
		$response_content = "You sent $content just now";
		require_once "utils.php";
		$keywords_string = utils::getCache("keywords");
		$keywords = unserialize($keywords_string);
		
		if($keywords === false){
			$filename = "keyword.setting";
			if(file_exists($filename)){
				$xml_string = file_get_contents($filename);
				$xml = simplexml_load_string($xml_string);
				$keywords = array();
				foreach($xml->item as $item){
					$keywords["".$item->key] = "".$item->value;
				}
				Utils::setCache("keywords", serialize($keywords), 600);
			}
		}
		
		while(list($key, $value) = each($keywords)){
			if(strpos($content, "$key")!==false){
				$response_content = $value;
				break;
			}
		}
		require_once "sender.php";
		Sender::sendTextMessage($fromusername, $tousername, $response_content);
	}
}

class PicMessage extends CommonMessage{
	public function handle($tousername, $fromusername, $createtime, $msgtype, $picurl, $mediaid, $msgid){
		require_once "sender.php";
		Sender::sendPicMessage($fromusername, $tousername, $mediaid);
	}
}

class AudioMessage extends CommonMessage{
	public function handle($tousername, $fromusername, $createtime, $msgtype, $mediaid, $format, $msgid){
		require_once "sender.php";
		Sender::sendAudioMessage($fromusername, $tousername, $mediaid);
	}
}

class VideoMessage extends CommonMessage{
	public function handle($tousername, $fromusername, $createtime, $msgtype, $mediaid, $thumbmediaid, $msgid){
		require_once "sender.php";
		Sender::sendVideoMessage($fromusername, $tousername, $mediaid, "Response", "YourVedio");
	}
}

class LocationMessage extends CommonMessage{
	public function handle($tousername, $fromusername, $createtime, $msgtype, $locationX, $locationY, $scale, $label, $msgid){
		require_once "sender.php";
		Sender::sendTextMessage($fromusername, $tousername, "Your x: $locationX y: $locationY scale:$scale label: $label");
	}
}

class UrlMessage extends CommonMessage{
	public function handle($tousername, $fromusername, $createtime, $msgtype, $title, $description, $url, $msgid){
		$articles = array(
			array("Title" => $title, "Description" => $description, "PicUrl" => "http://mmbiz.qpic.cn/mmbiz/g5JrZdib7cHRbYKjnbbp6LXIGXMgZpt4q7yBz2UgqPJV9ar7ibWwahSHhSI4JlRObPUYFYicvw1DNCQ91jsiaXTMrQ/0", "Url" => $url),
			array("Title" => $title, "Description" => $description, "PicUrl" => "http://mmbiz.qpic.cn/mmbiz/g5JrZdib7cHRbYKjnbbp6LXIGXMgZpt4q7yBz2UgqPJV9ar7ibWwahSHhSI4JlRObPUYFYicvw1DNCQ91jsiaXTMrQ/0", "Url" => $url),
		);
		require_once "sender.php";
		Sender::sendNewsMessage($fromusername, $tousername, count($articles), $articles);
	}
}

class EventMessage extends Message{
	public function checkRepeat($fromusername, $creatatime){
		
	}
}

class SubscribeMessage extends EventMessage{
	public function handle($tousername, $fromusername, $createtime, $msgtype, $event){
		require_once "sender.php";
		Sender::sendTextMessage($fromusername, $tousername, "你终于来了，我的英雄!西域动荡，天下英才辈出，阁下是时候一展身手了!
		更多信息，点击底部菜单了解吧!");
	}
}

class ScanTicketSubscribeMessage extends EventMessage{
	public function handle($tousername, $fromusername, $createtime, $msgtype, $event, $eventkey, $ticket){

	}
}

class ScanTicketNotSubscribeMessage extends EventMessage{
	public function handle($tousername, $fromusername, $createtime, $msgtype, $event, $eventkey, $ticket){

	}
}

class UpdateLocationMessage extends EventMessage{
	public function handle($tousername, $fromusername, $createtime, $msgtype, $event, $latitude, $longitude, $percision){

	}
}

class ClickMenuGetInfoMessage extends EventMessage{
	public function handle($tousername, $fromusername, $createtime, $msgtype, $event, $eventkey){
		require_once "sender.php";
		require_once "Utils.php";
		if($eventkey == "key1"){
			require_once "article.php";
			$articles = Article::getNewArticles();
			//require_once "utils.php";
			Utils::writeLog(print_r($articles, true));
			Sender::sendNewsMessage($fromusername, $tousername, count($articles), $articles);
		} else if($eventkey == "key_libao") {
			require_once "week_attendance.php";
			$last_got_info = WeekAttendance::get_last_got_info($tousername);
			//Utils::writeLog("key_libao");
			if ($last_got_info == false) {//db操作失败
				Utils::writeLog("got info error");
				Sender::sendTextMessage($fromusername, $tousername, "got info error");
			} else if (1 == $last_got_info) {//没有记录
				//Utils::writeLog("111");
				//暂时获取礼包id为1的序列号
				$keyno_info = WeekAttendance::bind_and_return_keyno($tousername,1);
				if (false == $keyno_info["keyno"]) {
					Sender::sendTextMessage($fromusername, $tousername, $keyno_info["desc"]);
				} else if (-1 == $keyno_info["keyno"]) {
					Sender::sendTextMessage($fromusername, $tousername, $keyno_info["desc"]);
				} else {
					Sender::sendTextMessage($fromusername, $tousername, $keyno_info["keyno"]);
				}
			} else {
				require_once("../unity/self_table_names.php");
				//上次领取日期
				$last_get_date = $last_got_info[enum_player_weixin_cdkey::e_get_time];
				//Utils::writeLog("1234");
				//Utils::writeLog(" last_get_date:".$last_got_info[enum_player_weixin_cdkey::e_get_time]);
				$diff_seconds = time() - strtotime($last_get_date);
				if ($diff_seconds < 0) {
					Sender::sendTextMessage($fromusername, $tousername, "server time has been changed");
				} else if ($diff_seconds/86400 >= 7) {
					//Utils::writeLog("222");
					//暂时获取礼包id为1的序列号
					$keyno_info = WeekAttendance::bind_and_return_keyno($tousername,1);
					if (false == $keyno_info["keyno"]) {
						Sender::sendTextMessage($fromusername, $tousername, $keyno_info["desc"]);
					} else if (-1 == $keyno_info["keyno"]) {
						Sender::sendTextMessage($fromusername, $tousername, $keyno_info["desc"]);
					} else {
						Sender::sendTextMessage($fromusername, $tousername, $keyno_info["keyno"]);
					}
				} else {
					$last_week_day = date('w',strtotime($last_get_date));
					//Utils::writeLog("333");
					//如果上次领取是周日，将last_week_day=7;
					if (0 == $last_week_day) $last_week_day = 7;
					$current_week_day = date('w');
					//如果当前是周日，将current_week_day=7;
					if (0 == $current_week_day) $current_week_day = 7;
					if ($current_week_day - $last_week_day < 0) {//不再同一个星期内
						//暂时获取礼包id为1的序列号
						$keyno_info = WeekAttendance::bind_and_return_keyno($tousername,1);
						if (false == $keyno_info["keyno"]) {
							Sender::sendTextMessage($fromusername, $tousername, $keyno_info["desc"]);
						} else if (-1 == $keyno_info["keyno"]) {
							Sender::sendTextMessage($fromusername, $tousername, $keyno_info["desc"]);
						} else {
							Sender::sendTextMessage($fromusername, $tousername, $keyno_info["keyno"]);
						}
					} else {
						$keyno = $last_got_info[enum_player_weixin_cdkey::e_keyno];
						Sender::sendTextMessage($fromusername, $tousername, "您已获得本周礼包,礼包序列号为$keyno,请登录《西域英雄》兑换每周礼包。记得下周再来领取哦!");
					}
				}
			}
		} else{
			Sender::sendTextMessage($fromusername, $tousername, "you clicked button with key: $eventkey");
		}
	}
}

class ClickMenuToUrlMessage extends EventMessage{
	public function handle($tousername, $fromusername, $createtime, $msgtype, $event, $eventkey){

	}
}
?>