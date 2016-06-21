<?php 
	/*函数功能：限制结束时间和开始时间在同一个月内
	     参数格式：$start_date="xxxx-xx-xx" $end_date="xxxx-xx-xx",$end_time="xx:xx:xx"
	    返回:限制后的$end_date
	*/
	include_once($system_path.'helpers/date_helper.php');
	include_once(APPPATH.'config/database.php');
	function limit_end_date_and_start_date_in_the_same_month($start_date,$end_date,$end_time) {	
		$start_date_param = explode("-", $start_date);
		$start_year = $start_date_param[0];
		$start_month = $start_date_param[1];
		
		$end_date_param = explode("-", $end_date);
		$end_year = $end_date_param[0];
		$end_month = $end_date_param[1];
		$end_days = $end_date_param[2];
			
		if ($end_year == $start_year) {
			if ($end_month != $start_month) {
				$end_days = days_in_month($start_month,$start_year);
				if (!empty($end_time)) {
					return date("Y-m-d H:i:s", mktime(23, 59, 59, $start_month, $end_days, $start_year));	
				}
				return date("Y-m-d", mktime(0, 0, 0, $start_month, $end_days, $start_year));
			}
		} else {
			$end_days = days_in_month($start_month,$start_year);
			if (!empty($end_time)) {
				return date("Y-m-d H:i:s", mktime(23, 59, 59, $start_month, $end_days, $start_year));	
			}
			return date("Y-m-d", mktime(0, 0, 0, $start_month, $end_days, $start_year));
		}
		if (!empty($end_time)) {
			$end_time_param = explode(":", $end_time);	
			return date("Y-m-d H:i:s", mktime($end_time_param[0],$end_time_param[1],$end_time_param[2],$end_month, $end_days, $end_year));
		}
		return date("Y-m-d", mktime(0, 0, 0, $end_month, $end_days, $end_year));
	}
	
	function is_sub_url_string($url_arry,$url_string) {
		if (empty($url_string) || !isset($url_arry)) {
			return false;
		}
		foreach($url_arry as $one_url){
			if (stripos($one_url['url'],$url_string)) {
				return true;
			}
		}
		return false;
	}
	
	//查找玩家id在哪个game_db
	function get_game_db_config_by_player_id($area_id,$player_id) {
		global $game_db;
		if (!isset($game_db[$area_id])) {
			echo "not find area_id:".$area_id;
			assert(false);
			return false;	
		}
		$area_game_db_array = $game_db[$area_id];
		if (!isset($area_game_db_array['master'])) {
			echo "has no key master";
			assert(false);
			return false;
		}
		$db_array = $area_game_db_array['master']; 	
		foreach ($db_array as $key => $value) {
			if (!isset($value['begin_id']) || !isset($value['end_id'])) {
				echo "has no key begin_id or end_id";
				assert(false);
				return false;
			}
			if ($player_id >= $value['begin_id'] && $player_id <= $value['end_id']) return $value;
		}
		return false;
	}
	
	//获取所有的game_db array
	function get_all_game_db_config_array($area_id) {
		global $game_db;
		if (!isset($game_db[$area_id])) {
			echo "not find area_id:".$area_id;
			assert(false);
			return false;	
		}
		$area_game_db_array = $game_db[$area_id];
		if (!isset($area_game_db_array['master'])) {
			echo "has no key master";
			assert(false);
			return false;
		}
		$db_array = $area_game_db_array['master'];
		foreach ($db_array as $key => $value) {
			if (!isset($value['begin_id']) || !isset($value['end_id'])) {
				echo "has no key begin_id or end_id";
				assert(false);
				return false;
			}
		}
		return $db_array;
	}
	
	//获取默认的game_db 默认为z_gamedb_xiyu_1
	function get_default_game_db($area_id) {
		$area_game_db_array = get_all_game_db_config_array($area_id);
		if (!$area_game_db_array) return false;
		if (!isset($area_game_db_array['z_gamedb_xiyu_1'])) {
			echo "not find z_gamedb_xiyu_1";
			assert(false);
			return false;
		}
		return $area_game_db_array['z_gamedb_xiyu_1'];
	}
	
	//获取的db array
	function get_db_array($key_name) {
		global $db;
		if (!isset($db[$key_name])) {
			echo "not find key_name:".$key_name;
			assert(false);
			return false;	
		}
		return $db[$key_name];
	}
?>