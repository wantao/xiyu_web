<?php

require_once 'self_table_names.php';
require_once 'self_memcache.php';
require_once 'self_mysql.php';
require_once 'self_log.php';

//用于获取/更新db/memcahe数据
class CDataOperateFactory
{
	private $memcache = false;
	public $mysql = false;
	
	//构造函数
	public function __construct($db_key,$no_connect_cache=false) {	
		$ob_my_mysql = new CMyMysql();
		if ($ob_my_mysql->my_connect_mysql($db_key)) $this->mysql = $ob_my_mysql;
		if (!$no_connect_cache) {
			$ob_my_memcahe = new CMyMemcache();
			if ($ob_my_memcahe->memcache_default_connect()) $this->memcache = $ob_my_memcahe;
		}
	}
	
	//析构函数
	public function __destruct() {
	}
	
	public function db_mysql_escape_string($unescaped_string) {
		return $this->mysql->my_mysql_real_escape_string($unescaped_string);
	}
	
	//从db获取数据
	/*
	 * @return:db操作失败，返回fasle,结果为空，返回-1,其他返回结果
	 * */
	public function db_get_data($query) {	
		if (!$this->mysql) return false;
		if (!$this->mysql->my_mysql_query($query)) {
			writeLog(get_str_log_prex(__FILE__,__LINE__,__FUNCTION__)." db_get_data failure,sql:".$query." error info:".$this->mysql->my_mysql_error(),LOG_NAME::ERROR_LOG_FILE_NAME); 
			return false;
		}
		$result_array = array();
		while ($row = $this->mysql->my_mysql_fetch_assoc()) {
			array_push($result_array, $row);
		}
		if (empty($result_array)) return -1; 
		return $result_array;
	}
	
	//更新db数据
	public function db_update_data($query) {
		if (!$this->mysql) return false;
		if (!$this->mysql->my_mysql_query($query)) {
			writeLog(get_str_log_prex(__FILE__,__LINE__,__FUNCTION__)." db_update_data failure,sql:".$query." error info:".$this->mysql->my_mysql_error(),LOG_NAME::ERROR_LOG_FILE_NAME); 
			return false;	
		}
		return true;
	}
	
	//从memcache获取数据
	public function memcache_get_data($key) {
		if (!$this->memcache) return false;
		return $this->memcache->memcache_get($key);	
	}
	
	//更新memcache数据(需要考虑失败后，db与缓存不一致的情况)
	public function update_memcache_data($key,$var,$flag,$expire) {
		if (!$this->memcache) return false;	
		return $this->memcache->memcache_set($key,$var,$flag,$expire);
	}
}
?>

