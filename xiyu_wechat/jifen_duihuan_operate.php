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
	if(!isset($_POST['open_id']) || !isset($_POST['libao_id'])){
		echo "Arguments error.";
		return;
	}
	$libao_id = trim(urldecode($_POST['libao_id']));
	if (!is_numeric($libao_id)) {
		echo "Arguments error.";
		return;
	}
	
	$open_id = trim(urldecode($_POST['open_id']));
	$ob_data_factory = new CDataOperateFactory('weixin_db_m');
	$open_id = $ob_data_factory->mysql->my_mysql_real_escape_string($open_id);
	
	//查询积分信息
	$select_sql = "SELECT * FROM `player_weixin_jifen` WHERE `open_id`='$open_id';";
	$db_result = $ob_data_factory->db_get_data($select_sql);
	if (!$db_result) {
		writeLog(get_str_log_prex(__FILE__,__LINE__,__FUNCTION__)." db operate error,sql:".$select_sql." mysql_error:".$ob_data_factory->mysql->my_mysql_error(),LOG_NAME::ERROR_LOG_FILE_NAME);
        return;	
	}
	if (-1 == $db_result) {
		echo "积分不够";
		return;
	}
	$row = $db_result[0];
	$current_num = $row[enum_player_weixin_jifen::e_number];
	//查询礼包信息
	$select_sql = "SELECT * FROM `libao_config` WHERE `id`='$libao_id';";
	$db_result = $ob_data_factory->db_get_data($select_sql);
	if (!$db_result) {
		writeLog(get_str_log_prex(__FILE__,__LINE__,__FUNCTION__)." db operate error,sql:".$select_sql." mysql_error:".$ob_data_factory->mysql->my_mysql_error(),LOG_NAME::ERROR_LOG_FILE_NAME);
        return;	
	}
	if (-1 == $db_result) {
		echo "不存在该礼包";
		return;
	}
	$libao_need_jifen = $db_result[0][enum_libao_config::e_need_jifen];
	if ($current_num < $libao_need_jifen) {
		echo "积分不够";
		return;
	}
	//从兑换码表里获取相应礼包的兑换码，并绑定到该open_id上,要用事物哦
	$select_sql = "SELECT * FROM `player_weixin_cdkey` WHERE `id`='$libao_id' AND `open_id`='' LIMIT 1;";
	$db_result = $ob_data_factory->db_get_data($select_sql);
	if (!$db_result) {
		writeLog(get_str_log_prex(__FILE__,__LINE__,__FUNCTION__)." db operate error,sql:".$select_sql." mysql_error:".$ob_data_factory->mysql->my_mysql_error(),LOG_NAME::ERROR_LOG_FILE_NAME);
        return;	
	}
	if (-1 == $db_result) {
		echo "该礼包已兑换完";
		return;
	}
	$keyno = $db_result[0][enum_player_weixin_cdkey::e_keyno];
	$date_time = date("Y-m-d H:i:s",time());
	//更新玩家积分
	//事物开始
	$ob_data_factory->db_update_data("BEGIN");
	$insert_sql = "UPDATE `player_weixin_jifen` SET `number`=`number`-$libao_need_jifen WHERE `open_id`='$open_id'";
	if (!$ob_data_factory->db_update_data($insert_sql)) {
		writeLog(get_str_log_prex(__FILE__,__LINE__,__FUNCTION__)." db operate error,sql:".$insert_sql." mysql_error:".$ob_data_factory->mysql->my_mysql_error(),LOG_NAME::ERROR_LOG_FILE_NAME);
		$ob_data_factory->db_update_data("ROLLBACK");
		return;
	}
	//绑定兑换码
	$insert_sql = "UPDATE `player_weixin_cdkey` SET `open_id`='$open_id',`get_time`='$date_time' WHERE `keyno`='$keyno'";
	if (!$ob_data_factory->db_update_data($insert_sql)) {
		writeLog(get_str_log_prex(__FILE__,__LINE__,__FUNCTION__)." db operate error,sql:".$insert_sql." mysql_error:".$ob_data_factory->mysql->my_mysql_error(),LOG_NAME::ERROR_LOG_FILE_NAME);
		$ob_data_factory->db_update_data("ROLLBACK");
		return;
	}
	//记录日志
	$jifen_trace = new CJifenTrace();
	$tbl_name = $jifen_trace->get_tbl_name();
	$log_desc = "-:jifen_duihuan";
	$log_sql = "INSERT INTO `".$tbl_name."` SET `open_id`='$open_id',`current_number`=$current_num,`nchange`=-$libao_need_jifen,`log_desc`='$log_desc'";
	$jifen_trace->record_log($log_sql,$ob_data_factory);	
	//事物提交
	$ob_data_factory->db_update_data("COMMIT");
	echo "兑换成功，兑换码为:".$keyno;
?>