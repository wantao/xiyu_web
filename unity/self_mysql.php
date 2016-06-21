<?php

require_once  'self_log.php';
require_once  'self_config.php';
class CMyMysql {
	
	private $mysqli;
	
	//构造函数
	public function __construct() {		
	}
	
	//析构函数
	public function __destruct() {
	}
	
	function &get_mysqli() {
		return $this->mysqli;
	}
	
	function my_connect_mysql($db_key){
		global $global_db;
		$db_config = $global_db[$db_key];
		//
		if (0 == count($db_config)) {
			writeLog(get_str_log_prex(__FILE__,__LINE__,__FUNCTION__)."my_connect_mysql not find db_key:".$db_key,LOG_NAME::ERROR_SYSTEM_ERROR_LOG_FILE_NAME); 
			return false;
		}
		if (!isset($db_config['database'])) {
			writeLog(get_str_log_prex(__FILE__,__LINE__,__FUNCTION__)."my_connect_mysql not set database,db_key:".$db_key,LOG_NAME::ERROR_SYSTEM_ERROR_LOG_FILE_NAME); 
			return false;	
		}
		$this->mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'],$db_config['database']);
	
		if (mysqli_connect_errno()) {
		    writeLog(get_str_log_prex(__FILE__,__LINE__,__FUNCTION__)."my_connect_mysql connect error:".mysqli_connect_error(),LOG_NAME::ERROR_SYSTEM_ERROR_LOG_FILE_NAME); 
			return false;
		}
		
		/* change character set to utf8 */
		if (!$this->mysqli->set_charset("utf8")) {
		    writeLog(get_str_log_prex(__FILE__,__LINE__,__FUNCTION__)."my_connect_mysql set link utf8 faliure,db_key:".$db_key.",error:".$this->mysqli->error,LOG_NAME::ERROR_SYSTEM_ERROR_LOG_FILE_NAME); 
			return false;
		} 
		return true;
	}
	
	/*
	 * @description:返回上一个 MySQL 操作产生的文本错误信息
	 * @note:返回上一个 MySQL 函数的错误文本，如果没有出错则返回 ''（空字符串）。
	 * 如果没有指定连接资源号，则使用上一个成功打开的连接从 MySQL 服务器提取错误信息
	 * 从 MySQL 数据库后端来的错误不再发出警告，要用 mysql_error() 来提取错误文本。
	 * 注意本函数仅返回最近一次 MySQL 函数的执行（不包括 mysql_error() 和 mysql_errno()）的错误文本，
	 * 因此如果要使用此函数，确保在调用另一个 MySQL 函数之前检查它的值。
	 * @return:上一个 MySQL 操作产生的文本错误信息
	 * */
	public function my_mysql_error() {
		return mysqli_error();
	}
	
	/*
	 * @description:本函数将 unescaped_string 转义，使之可以安全用于 mysql_query()。
	 * @note:并不转义 % 和 _。 本函数和 mysql_real_escape_string() 完全一样，
	 * 除了 mysql_real_escape_string() 接受的是一个连接句柄并根据当前字符集转移字符串之外。
	 * mysql_escape_string() 并不接受连接参数，也不管当前字符集设定
	 * @return:转义后的字符串
	 * */
	public function my_mysql_escape_string($unescaped_string) {
		return $this->mysqli->escape_string($unescaped_string);	
	}
	
	
	/*
	 * @description:从结果集中取得一行作为关联数组
	 * @warning:本扩展自 PHP 5.5.0 起已废弃，并在将来会被移除。应使用 MySQLi 或 PDO_MySQL 扩展来替换之。
	 * 参见 MySQL：选择 API 指南以及相关 FAQ 以获取更多信息。用以替代本函数的有：mysqli_fetch_assoc(),PDOStatement::fetch(PDO::FETCH_ASSOC)
	 * @note:返回对应结果集的关联数组，并且继续移动内部数据指针。 mysql_fetch_assoc() 和用 mysql_fetch_array() 加上第二个可选参数 MYSQL_ASSOC 完全相同。它仅仅返回关联数组
	 * 如果结果中的两个或以上的列具有相同字段名，最后一列将优先。要访问同名的其它列，要么用 mysql_fetch_row() 来取得数字索引或给该列起个别名。 参见 mysql_fetch_array() 例子中有关别名说明。
	 * @return:返回根据从结果集取得的行生成的关联数组；如果没有更多行则返回 FALSE。
	 * */
	public function my_mysql_fetch_assoc() {
		return mysqli_fetch_assoc($this->result);	
	}
	
	
	/*
	 * @description:发送一条 MySQL 查询
	 * @param:
	 * query:SQL 查询语句,查询字符串不应以分号结束。 查询中被嵌入的数据应该正确地转义。
	 * @return:对 SELECT，SHOW，DESCRIBE, EXPLAIN 和其他语句 语句返回一个 resource，如果查询出现错误则返回 FALSE
	 * 其它类型的 SQL 语句，比如INSERT, UPDATE, DELETE, DROP 之类， mysql_query() 在执行成功时返回 TRUE，出错时返回 FALSE
	 * 返回的结果资源应该传递给 mysql_fetch_array() 和其他函数来处理结果表,取出返回的数据
	 * 假定查询成功，可以调用 mysql_num_rows() 来查看对应于 SELECT 语句返回了多少行
	 * 或者调用 mysql_affected_rows() 来查看对应于 DELETE，INSERT，REPLACE 或 UPDATE 语句影响到了多少行。
	 * 如果没有权限访问查询语句中引用的表时，mysql_query() 也会返回 FALSE。
	 * @Warning本扩展自 PHP 5.5.0 起已废弃，并在将来会被移除。应使用 MySQLi 或 PDO_MySQL 扩展来替换之。
	 * 参见 MySQL：选择 API 指南以及相关 FAQ 以获取更多信息。用以替代本函数的有：mysqli_query(),PDO::query()
	 * */
	public function my_mysql_query($query) {
		$this->result = $this->mysqli->query($query);
		return $this->result;
	}
	
	/*
	 * @description:转义 SQL 语句中使用的字符串中的特殊字符，并考虑到连接的当前字符集,可以安全用于 mysql_query()。
	 * @note:mysql_real_escape_string() 并不转义 % 和 _。
	 * */
	public function my_mysql_real_escape_string($unescaped_string) {
		return $this->mysqli->escape_string($unescaped_string);	
	}
}

?>