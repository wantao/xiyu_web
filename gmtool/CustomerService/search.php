<?php
	if($_SERVER['REQUEST_METHOD'] != 'POST'){
		echo "Forbidden. Only POST request is allowed.";
		return;
	}
	
	require_once("config.php");
	$url = $DB_CONFIG['url'];
	$user = $DB_CONFIG['user'];
	$password = $DB_CONFIG['password'];
	$database = $DB_CONFIG['database'];
	$table = $DB_CONFIG['table'];
	
	$count_per_page = $PAGE_CONFIG['count_per_page'];
	
	$link = mysql_connect($url, $user, $password) or die("can't" . mysql_error());
	mysql_query("set charset utf8");
	mysql_query("set names utf8");
	mysql_select_db($database) or die("can't select database");
	$page_no = 0;
	$player_name = '';
	if(array_key_exists("name", $_POST)){
		$player_name = $_POST["name"];
	}
	if(array_key_exists("page", $_POST)){
		$page_no = $_POST["page"];
	}
	
	
	if(!$page_no){
		$page_no = 1;
	}
	$start_index = ($page_no - 1) * $count_per_page;
	$count_from_db = $count_per_page + 1;//每次查询数据库时多查询一条记录，用来判断是否还有下一页
	
	$search_result = "";
	if($player_name){
		$search_result = mysql_query("select * from $table where `player_name` = '$player_name' order by `question_time` desc limit $start_index, $count_from_db") or die("Invalid ".mysql_error());
	}else{
		$search_result = mysql_query("select * from $table order by `question_time` desc limit $start_index, $count_from_db") or die("Invalid ".mysql_error());
	}
	echo "<table border=1>";
	echo "<tr><td>区号</td><td>区名</td><td>玩家名</td><td>玩家ID</td><td>问题</td><td>提问时间</td><td>客服回复</td><td>回复时间</td><td>状态</td></tr>";
	$counter = 0;
	while($row = mysql_fetch_array($search_result, MYSQL_ASSOC)){
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
		$answer = ($status == '解决' ? $answer : "$answer <button onclick=answer($id)>回答</button>");
		if($counter < $count_per_page){
			echo "<tr><td>$area_id</td><td>$area_name</td><td>$player_name</td><td>$player_digitid</td><td>$question</td><td>$question_time</td><td>$answer</td><td>$answer_time</td><td>$status</td></tr>";
		}
		$counter++;
	}
	echo "</table>";
	if($page_no != 1){
		$previous_page = $page_no - 1;
		echo "<a href=javascript:void(0) onclick=request($previous_page)>上一页</a>";
	}
	if($counter == $count_from_db){
		$next_page = $page_no + 1;
		echo "<a href=javascript:void(0) onclick=request($next_page)>下一页</a>";
	}
?>