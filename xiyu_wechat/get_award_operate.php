<?php
	require_once("../unity/self_data_operate_factory.php");
	require_once("../unity/self_table_names.php");
	require_once "Config.php";
	require_once "table_operate.php";
	require_once("../unity/self_log.php");
	if($_SERVER['REQUEST_METHOD'] != 'POST'){
		echo "Forbidden. Only POST request is allowed.";
		return;
	}
	if(!isset($_POST['open_id'])){
		echo "Arguments error.";
		return;
	}
	
	$open_id = trim(urldecode($_POST['open_id']));
	$ob_data_factory = new CDataOperateFactory('weixin_db_m');
	$open_id = $ob_data_factory->mysql->my_mysql_real_escape_string($open_id);
	
	//查询领取信息
	$select_sql = "SELECT * FROM `player_weixin_jifen` WHERE `open_id`='$open_id';";
	$db_result = $ob_data_factory->db_get_data($select_sql);
	if (!$db_result) {
		writeLog(get_str_log_prex(__FILE__,__LINE__,__FUNCTION__)." db operate error,sql:".$select_sql." mysql_error:".$ob_data_factory->mysql->my_mysql_error(),LOG_NAME::ERROR_LOG_FILE_NAME);
        return;	
	}
    //获取当日领奖的积分
	$jifen_config = new CJifenConfig();
	$jifen_num = $jifen_config->get_award();
	if ($jifen_num <= 0) {
		writeLog(get_str_log_prex(__FILE__,__LINE__,__FUNCTION__)." jifen not config",LOG_NAME::ERROR_LOG_FILE_NAME);
		exit;
	}
	$current_num = 0;
	$date_time = date("Y-m-d H:i:s",time());
	if (-1 == $db_result) {//还没有领过奖
		//更新玩家积分
		//事物开始
		$ob_data_factory->db_update_data("BEGIN");
		$insert_sql = "INSERT INTO `player_weixin_jifen` SET `open_id`='$open_id',`number`=`number`+$jifen_num,`get_time`='$date_time' ON DUPLICATE KEY UPDATE `get_time`='$date_time'";
		if (!$ob_data_factory->db_update_data($insert_sql)) {
			writeLog(get_str_log_prex(__FILE__,__LINE__,__FUNCTION__)." db operate error,sql:".$insert_sql." mysql_error:".$ob_data_factory->mysql->my_mysql_error(),LOG_NAME::ERROR_LOG_FILE_NAME);
			$ob_data_factory->db_update_data("ROLLBACK");
			return;
		}
		//记录日志
		$jifen_trace = new CJifenTrace();
		$tbl_name = $jifen_trace->get_tbl_name();
		$log_desc = "daliy_attendance";
		$log_sql = "INSERT INTO `".$tbl_name."` SET `open_id`='$open_id',`current_number`=$current_num,`nchange`=$jifen_num,`log_desc`='$log_desc'";
		$jifen_trace->record_log($log_sql,$ob_data_factory);
		//事物提交
		$ob_data_factory->db_update_data("COMMIT");
		echo "领取".$jifen_num."积分成功";
		return;
	}
	//查看是否已经领奖
	$row = $db_result[0];
	$last_get_award_time = strtotime($row[enum_player_weixin_jifen::e_get_time]);
	if (date('Ymd', $last_get_award_time) == date('Ymd',time())) {//同一天
		echo "报告校尉!每天只能签到一次呢!";
		return;
	}
	$current_num = $row[enum_player_weixin_jifen::e_number];
	//更新玩家积分
	//事物开始
	$ob_data_factory->db_update_data("BEGIN");
	$insert_sql = "INSERT INTO `player_weixin_jifen` SET `open_id`='$open_id',`number`=`number`+$jifen_num, `get_time`='$date_time' ON DUPLICATE KEY UPDATE `get_time`='$date_time'";
	if (!$ob_data_factory->db_update_data($insert_sql)) {
		writeLog(get_str_log_prex(__FILE__,__LINE__,__FUNCTION__)."db operate error,sql:".$insert_sql." mysql_error:".$ob_data_factory->mysql->my_mysql_error(),LOG_NAME::ERROR_LOG_FILE_NAME);
		$ob_data_factory->db_update_data("ROLLBACK");
		return;
	}
	//记录日志
	$jifen_trace = new CJifenTrace();
	$tbl_name = $jifen_trace->get_tbl_name();
	$log_desc = "+:daliy_attendance";
	$log_sql = "INSERT INTO `".$tbl_name."` SET `open_id`='$open_id',`current_number`=$current_num,`nchange`=$jifen_num,`log_desc`='$log_desc'";
	$jifen_trace->record_log($log_sql,$ob_data_factory);
	//事物提交
	$ob_data_factory->db_update_data("COMMIT");
	echo "领取".$jifen_num."积分成功";
?>