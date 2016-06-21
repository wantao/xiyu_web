<?php
require_once("../unity/self_data_operate_factory.php");
require_once("../unity/self_table_names.php");
//每周签到
class WeekAttendance{
	//获取上次领取信息
	public static function get_last_got_info($open_id) {
		$ob_data_factory = new CDataOperateFactory('weixin_db_m',true);
		//获取上次签到的信息
		$select_sql = "SELECT * FROM `player_weixin_cdkey` WHERE `open_id`='$open_id' ORDER BY `get_time` DESC LIMIT 1;";
		$db_result = $ob_data_factory->db_get_data($select_sql);
		if (!$db_result) {
			//db操作失败
			writeLog(get_str_log_prex(__FILE__,__LINE__,__FUNCTION__)." db operate error,sql:".$select_sql." mysql_error:".$ob_data_factory->mysql->my_mysql_error(),LOG_NAME::ERROR_LOG_FILE_NAME);
			return false;	
		}
		if (-1 == $db_result) {
			//没有记录
			return 1;
		}
		//返回记录信息
		return $db_result[0];
	}
	
	//绑定并返回兑换码
	public static function bind_and_return_keyno($open_id,$libao_id) {
		$ob_data_factory = new CDataOperateFactory('weixin_db_m',true);
		//事物开始
		$ob_data_factory->db_update_data("BEGIN");
		$select_sql = "SELECT * FROM `player_weixin_cdkey` WHERE `id`='$libao_id' AND `open_id`='' LIMIT 1 FOR UPDATE;";
		$db_result = $ob_data_factory->db_get_data($select_sql);
		if (!$db_result) {
			writeLog(get_str_log_prex(__FILE__,__LINE__,__FUNCTION__)." db operate error,sql:".$select_sql." mysql_error:".$ob_data_factory->mysql->my_mysql_error(),LOG_NAME::ERROR_LOG_FILE_NAME);
			return array("keyno"=>false,"desc"=>"db operate error");	
		}
		if (-1 == $db_result) {
			writeLog(get_str_log_prex(__FILE__,__LINE__,__FUNCTION__)." 每周礼包的兑换码已用完",LOG_NAME::ERROR_LOG_FILE_NAME);
			return array("keyno"=>1,"desc"=>"每周礼包的兑换码已用完");	
		}
		//获取兑换码
		$keyno = $db_result[0][enum_player_weixin_cdkey::e_keyno];
		$date_time = date("Y-m-d H:i:s",time());
		//绑定兑换码
		$insert_sql = "UPDATE `player_weixin_cdkey` SET `open_id`='$open_id',`get_time`='$date_time' WHERE `keyno`='$keyno'";
		if (!$ob_data_factory->db_update_data($insert_sql)) {
			writeLog(get_str_log_prex(__FILE__,__LINE__,__FUNCTION__)." db operate error,sql:".$insert_sql." mysql_error:".$ob_data_factory->mysql->my_mysql_error(),LOG_NAME::ERROR_LOG_FILE_NAME);
			$ob_data_factory->db_update_data("ROLLBACK");
			return array("keyno"=>false,"desc"=>"db operate error");
		}
		//事物提交
		$ob_data_factory->db_update_data("COMMIT");
		return array("keyno"=>$keyno,"desc"=>"success");
	}
}
?>