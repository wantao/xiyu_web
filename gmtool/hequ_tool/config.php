<?php
$global_db = array(
	"z_gamedb" => array("server"=>"127.0.0.1:3306", "user"=>"root", "password"=>"wt315771557", "database"=>"z_gamedb",
	"merge_to" => ""),
	"z1_gamedb" => array("server"=>"127.0.0.1:3306", "user"=>"root", "password"=>"wt315771557", "database"=>"z1_gamedb",
	"merge_to" => "z_gamedb"),

	
	"z_gamelog" => array("server"=>"127.0.0.1:3306", "user"=>"root", "password"=>"wt315771557", "database"=>"z_gamelog",
	"merge_to" => ""),
	"z1_gamelog" => array("server"=>"127.0.0.1:3306", "user"=>"root", "password"=>"wt315771557", "database"=>"z1_gamelog",
	"merge_to" => "z_gamelog"),
	);
	
	function my_connect_mysql($db){
		if (isset($db["host"]) && isset($db["user"]) && isset($db["password"])) {
			$link = @mysql_connect($db["host"],$db["user"],$db["password"]) or die('Could not connect: ' . mysql_error());
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
?>