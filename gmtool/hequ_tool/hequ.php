<?php 
	require_once 'config.php';
	
	class Hequ {
	public function __construct(){
	}
	public function backup_db($arr=array()){
		if (!isset($arr["host"]) || !isset($arr["username"]) || !isset($arr["password"]) || !isset($arr["db_name"]) || !isset($arr["backup_db_name_prex"])) {
			echo "not set host or username or password or db_name\n";
			return false;	
		}
		$statement = 'mysqldump -h'.$arr["host"].' -u'.$arr["username"].' -p'.$arr["password"].' '.$arr["db_name"].' > '.
		$arr["backup_db_name_prex"].$arr["db_name"].'.sql';
		
		if (!system($statement)) {
			echo "backup failure,statement:".$statement."\n";
			return false;
		}
		return true;
	}
	
	public function get_merge_table_list($db_name,$con) {		
		if (!$con) {
			echo "get_merge_table_list db not connect\n";
			return false;
		}

		$statement = "select table_name from information_schema.tables where table_schema = '".$db_name."' and 
		table_name like "."'player\_%";
		
		if (!mysql_select_db($db_name, $con)) {
			echo "get_merge_table_list mysql_select_db failure\n";
			return false;		
		}
		return  mysql_query($statement);
	}
	
	public function merge_db ($src_arr=array(), $dst_arr=array()) {
		if (!backup_db($src_arr)) {
			echo "backup_db src_db failure\n";
			return false;	
		}
		if (!backup_db($dst_arr)) {
			echo "backup_db dst_db failure\n";
			return false;	
		}
		$src_con = my_connect_mysql($src_arr);
		if (!$src_con) {
			echo "connect src_db failur\n";
			return false;
		}
		
		$dst_con = my_connect_mysql($dst_arr);
		if (!$dst_con) {
			echo "connect dst_db failur\n";
			return false;
		} 
		
		$src_table_list = get_merge_table_list($src_arr["db_name"],$src_con);
		if (!$src_table_list) {
			return false;
		}
		
		$dst_table_list = get_merge_table_list($dst_arr["db_name"],$src_con);
		if (!$dst_table_list) {
			return false;
		}
		
		
	}
}
?>