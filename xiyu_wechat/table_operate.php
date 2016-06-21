<?php
	require_once("../unity/self_data_operate_factory.php");
	require_once("../unity/self_table_names.php");
	require_once("../unity/self_log.php");
	
	class CJifenConfig{
		function get_award() {
			$ob_data_factory = new CDataOperateFactory('weixin_db_m',true);
			$open_id = $ob_data_factory->mysql->my_mysql_real_escape_string($open_id);
	
			//查询是否已经绑定
			$select_sql = "SELECT * FROM `jifen_config` WHERE `".enum_jifen_config::e_weekday."`=".date("w");
			$db_result = $ob_data_factory->db_get_data($select_sql);
			if (!$db_result) {
				writeLog(get_str_log_prex(__FILE__,__LINE__,__FUNCTION__)." db operate error,sql:".$select_sql." mysql_error:".$ob_data_factory->mysql->my_mysql_error(),LOG_NAME::ERROR_LOG_FILE_NAME);
				return 0;	
			}
			if (-1 == $db_result) {
				writeLog(get_str_log_prex(__FILE__,__LINE__,__FUNCTION__)." get_award find nothing,sql:".$select_sql,LOG_NAME::ERROR_LOG_FILE_NAME);
				return 0;	
			}
			return $db_result[0][enum_jifen_config::e_number];
		}
	}
	
	class CJifenTrace {
		private $_tbl_name;
		//构造函数
		public function __construct() {	
			$this->_tbl_name = "jifen_trace".date("Ym");
		}
	
		//析构函数
		public function __destruct() {
		}
		
		public function get_tbl_name() {
			return $this->_tbl_name;
		}
		
		public function record_log(&$log_sql,&$ob_data_factory){
			//判断表是否存在
			$select_sql = "SELECT `TABLE_NAME` FROM `information_schema`.TABLES WHERE `TABLE_NAME` ='".$this->_tbl_name."'";
			$db_result = $ob_data_factory->db_get_data($select_sql);
			if (!$db_result) {//db操作异常
				writeLog(get_str_log_prex(__FILE__,__LINE__,__FUNCTION__)."db operate error,sql:".$select_sql." mysql_error:".$ob_data_factory->mysql->my_mysql_error(),LOG_NAME::ERROR_LOG_FILE_NAME);
				return false;	
			}
			if (-1 == $db_result) {//不存在
				//创建表
				$create_sql = "CREATE TABLE `".$this->_tbl_name."` (
				`open_id` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '玩家微信openid',
				`current_number`  int(11) unsigned NOT NULL DEFAULT '0' COMMENT '当前积分',
				`nchange`  int(11) NOT NULL DEFAULT '0' COMMENT '变动值',
				`log_desc` varchar(255) DEFAULT NULL COMMENT '日志描述',
				`activetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'activetime',
				KEY `idx_open_id` (`open_id`),
				KEY `idx_log_desc` (`log_desc`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='积分追踪表';";
				if (!$ob_data_factory->db_update_data($create_sql)) {
					writeLog(get_str_log_prex(__FILE__,__LINE__,__FUNCTION__)."db operate error,sql:".$create_sql." mysql_error:".$ob_data_factory->mysql->my_mysql_error(),LOG_NAME::ERROR_LOG_FILE_NAME);
					return false;
				}
			}
			//记录日志
			if (!$ob_data_factory->db_update_data($log_sql)) {
				writeLog(get_str_log_prex(__FILE__,__LINE__,__FUNCTION__)."db operate error,sql:".$log_sql." mysql_error:".$ob_data_factory->mysql->my_mysql_error(),LOG_NAME::ERROR_LOG_FILE_NAME);
				return false;
			}
			return true;
		}
	}
	

?>