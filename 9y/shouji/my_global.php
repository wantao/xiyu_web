<?php
$global_db = array(
	"chongzhi" => array("server"=>"127.0.0.1", "user"=>"onlyread", "password"=>"onlyread", "database"=>"wx_9y_chongzhi"),

	"namedb" => array("server"=>"127.0.0.1:3306",   "user"=>"onlyread", "password"=>"onlyread", "database"=>"wx_9y_ns"),

	"game_config" => array("server"=>"127.0.0.1:3306",   "user"=>"onlyread", "password"=>"onlyread", "database"=>"wx_9y_game_config"),

	"logindb" => array("server"=>"127.0.0.1:3306",   "user"=>"onlyread", "password"=>"onlyread", "database"=>"wx_9y_logindb"),

	"gamedb" => array(
			// 区号=>数据库信息
			1=>array("server"=>"127.0.0.1:3306",   "user"=>"onlyread", "password"=>"onlyread", "database"=>"wx_9y_1_gamedb"),
			2=>array("server"=>"127.0.0.1:3306",   "user"=>"onlyread", "password"=>"onlyread", "database"=>"wx_9y_2_gamedb"),
		),
	"gamelog" => array(
			// 区号=>数据库信息
			1=>array("server"=>"127.0.0.1:3306",   "user"=>"onlyread", "password"=>"onlyread", "database"=>"wx_9y_1_gamelog"),
			2=>array("server"=>"127.0.0.1:3306",   "user"=>"onlyread", "password"=>"onlyread", "database"=>"wx_9y_2_gamelog"),
		),
);

function my_connect_mysql($db){
	/*
	print_r($GLOBALS["global_db"]["chongzhi"]);
	echo "</p>";
	print_r($GLOBALS["global_db"]["namedb"]);
	echo "</p>";
	print_r($db);
	echo "</p>";
	print_r($db['server']);
	echo "</p>";
	*/
	
	if (isset($db["server"]) && isset($db["server"]) && isset($db["server"])) {
		$link = @mysql_connect($db["server"],$db["user"],$db["password"]) or die('Could not connect: ' . mysql_error());
		mysql_set_charset('utf8', $link); 
		//$charset = mysql_client_encoding($link);
		//printf ("current character set is %s\n", $charset);
		if (isset($db["database"])){
			mysql_select_db($db["database"], $link) or die('Could not select database');
			return $link;
		}	
	}
	return null;
}

define("g_now_debug", 0); //是否现在开始调试
define("g_supper_user", 4); //调试员
define("g_admin_user", 3); //高级管理
define("g_normal_user", 2); //普通管理员
define("g_friend_user", 1); //好友

/**
 * 1表示有。其余表示没有。
 * @param $playe_name
 * @param $pingtai_name
 */
function has_right_to_access_ping_tai($playe_name, $pingtai_name)
{
	$link = my_connect_mysql($GLOBALS["global_db"]["chongzhi"]);
	$query = "SELECT read_user.priv, read_user.ping_tai FROM read_user WHERE read_user.user_name = '$playe_name'";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	$line = mysql_fetch_array($result, MYSQL_NUM);
	$pri = -1;//给一个最低权限
	$ping_tai = -1;
	if ($line){
		$pri = $line[0]; 
		$ping_tai = $line[1];
	} else {
	}
	
	
	
	$link = my_connect_mysql($GLOBALS["global_db"]["game_config"]);
	$query = "select ping_tai_id from `ping_tai` where ping_tai_name='$pingtai_name' ";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	$line = mysql_fetch_array($result, MYSQL_NUM);
	$want_to_access_ping_tai = -1;
	if ($line){
		$want_to_access_ping_tai = $line[0]; 
	} else {
	}
	
	if ($pri == -1 || $ping_tai == -1 || $want_to_access_ping_tai == -1)
		return 0;
	
	if ($pri == 3 || $pri == 4)//超级用户和调试zhe
		return 1;
	
	if ($ping_tai == $want_to_access_ping_tai)
		return  1;
	else 
		return  0;
}


?>

