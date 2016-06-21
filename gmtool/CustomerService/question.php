<?php
	require_once("config.php");
	if($_SERVER['REQUEST_METHOD'] == 'GET'){
		if(!array_key_exists('player_digitid', $_GET) || !array_key_exists('page', $_GET) || !array_key_exists('key', $_GET)){
			echo "player_digitid and page can't be empty";
			return;
		}
		$player_digitid = $_GET['player_digitid'];
		$page = $_GET['page'];
		$key = $_GET['key'];
		
		$decoded_key = base64_decode($key);
		$correct_key = $player_digitid.$page.$KEY;
		if($correct_key != $decoded_key){
			echo "{\"response\":[], \"error\":\"invalidation error. Get $decoded_key after decoded but correct key is $correct_key\"}";
			return;
		}
		
		$url = $DB_CONFIG['url'];
		$user = $DB_CONFIG['user'];
		$password = $DB_CONFIG['password'];
		$database = $DB_CONFIG['database'];
		$table = $DB_CONFIG['table'];
		
		$count_per_page = $PAGE_CONFIG['count_per_page'];
		
		$start_index = ($page - 1) * $count_per_page;
		$count_from_db = $count_per_page + 1;//每次查询数据库时多查询一条记录，用来判断是否还有下一页
				
		$link = mysql_connect($url, $user, $password) or die("can't" . mysql_error());
		mysql_query("set charset utf8");
		mysql_query("set names utf8");
		mysql_select_db($database) or die("can't select database");
		
		mysql_query("delete from `$table` where `player_digitid` = '$player_digitid' and datediff(`answer_time`, `question_time`) >= $AUTO_DELETE_PERIOD and status = '解决'");
		
//		$search_result = mysql_query("select * from $table where `player_digitid` = '$player_digitid' order by `question_time` desc limit $start_index, $count_from_db") or die("Invalid ".mysql_error());
		$search_result = mysql_query("select * from $table where `player_digitid` = '$player_digitid' order by `question_time` desc") or die("Invalid ".mysql_error());
		
		$counter = 0;
		echo "{\"response\":[";
		while($row = mysql_fetch_array($search_result, MYSQL_ASSOC)){
			$question = $row['question'];
			$answer = $row['answer'];
			$status = $row['status'];
			
			//$answer_show = ($status == '解决' ? $answer : "提交中");
			
//			if($counter < $count_per_page){
				if($counter != 0){
					echo ",";
				}
				echo "{\"question\":\"$question\", \"answer\":\"$answer\"}";
//			}
			$counter++;
		}
		echo "]}";
	}else if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$url = $DB_CONFIG['url'];
		$user = $DB_CONFIG['user'];
		$password = $DB_CONFIG['password'];
		$database = $DB_CONFIG['database'];
		$table = $DB_CONFIG['table'];
		$link = mysql_connect($url, $user, $password) or die("can't" . mysql_error());
		
		$str = "";
		for($i = 0; $i < count($USER_PARAM); $i++){
			if(!array_key_exists($USER_PARAM[$i], $_POST)){
				echo "$USER_PARAM[$i] is missing";
				return;
			}
			$key = $USER_PARAM[$i];
			if($key == 'question' && count($_POST[$key]) > $MAX_QUESTION_LENGTH){
				echo "问题太长!";
				return;
			}
			$str .= "`$USER_PARAM[$i]` = '" .mysql_real_escape_string($_POST[$key]) . "',";
		}
		$str = rtrim($str, ",");
		
		$player_digitid = $_POST['player_digitid'];
		$area_id = $_POST['area_id'];
		
		if(base64_decode($key) == $player_digitid.$area_id.$KEY){
			echo "验证失败";
			return;
		}

		mysql_query("set names utf8");
		mysql_select_db($database) or die("can't select $table");
		
		$count_result = mysql_query("select count(*) as `count` from $table where player_digitid = '$player_digitid'") or die("Invalid ".mysql_error());
		$row = mysql_fetch_array($count_result, MYSQL_ASSOC);
		if($row['count'] >= $MAX_QUESTION_COUNT){
			echo "已经达到提问最大数量，无法继续提问";
			return;
		}
		
		$sql = "insert into $table set ".$str;
		$search_result = mysql_query($sql) or die("Invalid ".mysql_error());
	}

?>