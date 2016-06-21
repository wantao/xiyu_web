<?php

	if($_SERVER['REQUEST_METHOD'] == 'GET'){
		if(!array_key_exists('id', $_GET)){
			return;
		}
	}else if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(!array_key_exists('id', $_POST) || !array_key_exists('answer', $_POST) || !array_key_exists('status', $_POST)){
			return;
		}
	}
	require_once("config.php");
	$url = $DB_CONFIG['url'];
	$user = $DB_CONFIG['user'];
	$password = $DB_CONFIG['password'];
	$database = $DB_CONFIG['database'];
	$table = $DB_CONFIG['table'];
	
	$link = mysql_connect($url, $user, $password) or die("can't" . mysql_error());
	mysql_query("set charset utf8");
	mysql_query("set names utf8");
	mysql_select_db($database) or die("can't select database");
	
	
	

	if($_SERVER['REQUEST_METHOD'] == 'GET'){
		$id = $_GET['id'];
		$search_result = mysql_query("select * from $table where `id` = $id") or die("Invalid ".mysql_error());
		
		if($row = mysql_fetch_array($search_result, MYSQL_ASSOC)){
			$id = $row['id'];
			$area_id = $row['area_id'];
			$area_name = $row['area_name'];
			$player_name = $row['player_name']; 
			$player_digitid = $row['player_digitid'];
			$question = $row['question'];
			$question_time = $row['question_time'];
			$answer = $row['answer'];
			$answer_time = $row['answer_time'];
			$status = $row['status'];
			echo '<meta http-equiv="content-type" content="text/html;charset=utf8" />';
			echo "<form method=\"post\"><table>";
			echo "<tr><td><a>区号</a></td><td>$area_id</td></tr>";
			echo "<tr><td><a>区名</a></td><td>$area_name</td></tr>";
			echo "<tr><td><a>玩家名称</a></td><td>$player_name</td></tr>";
			echo "<tr><td><a>玩家ID</a></td><td>$player_digitid</td></tr>";
			echo "<tr><td><a>提问时间</a></td><td>$question_time</td></tr>";
			echo "<tr><td><a>问题</a></td><td>$question</td></tr>";
			echo "<tr><td><a>回复</a></td><td><textarea name=\"answer\" row=3 col=20>$answer</textarea></td></tr>";
			echo "<tr><td><a>状态</a></td><td><select name=\"status\"><option value=\"未解决\" ". ($status == "未解决" ? "selected" : "").">待处理</option><option value=\"解决\" ". ($status == "解决" ? "selected" : "") .">解决</option></select></td></tr>";
			echo "</table><input type=hidden name=\"id\" value=$id /><input type=submit value=\"确认回复\"></form>";
			return;
		}
	}else if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$id = $_POST['id'];
		$answer = $_POST['answer'];
		$status = $_POST['status'];
		$search_result = mysql_query("update `$table` set `answer` = '$answer', `answer_time` = CURRENT_TIMESTAMP, `status` = '$status' where `id` = '$id'") or die("Invalid ".mysql_error());
		header('Location:index.php');
	}
?>