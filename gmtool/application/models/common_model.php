<?php
class Common_model extends CI_Model {
	public function delete_merged_server($area_list) {
		$tmp_arry_area_id = array();
		$index = 0;
		foreach($area_list as $area){
			//tbl_server表里的is_trial字段等于3，代表该区被合并了
			if (3 == $area->is_trial) {
				unset($area_list[$index]);	
			}	
			$index += 1;
		}	
		return $area_list;
	}
	public function get_arealist(){
		$db = $this->load->database("default", true);
		$query = $db->get("tbl_server");
		$ary = $query->result();
		$db->close();
		return $this->delete_merged_server($ary);
	}
	
	public function add_selected_key_for_arealist($area_list){
		if (empty($area_list)) {
			return false;
		}
		$area_arry = array();
		foreach($area_list as $area){
			$area_arry_tmp = array();
			$area_arry_tmp['id'] = $area->current_code;
			$area_arry_tmp['name'] = $area->name;
			$area_arry_tmp['url'] = $area->url;
			$area_arry_tmp['port'] = $area->port;
			$area_arry_tmp['selected'] = 0;	
			array_push($area_arry, $area_arry_tmp);
		}
		return $area_arry;
	}
	
	public function set_selected_flag_for_arealist($area_id,$area_list){
		$area_arry = array();
		foreach($area_list as $area){
			$area_arry_tmp = array();
			$area_arry_tmp['id'] = $area->current_code;
			$area_arry_tmp['name'] = $area->name;
			$area_arry_tmp['url'] = $area->url;
			$area_arry_tmp['port'] = $area->port;
			if ($area_id == $area_arry_tmp['id']) {
				$area_arry_tmp['selected'] = 1;		
			} else {
				$area_arry_tmp['selected'] = 0;	
			}
			array_push($area_arry, $area_arry_tmp);
		}
		return $area_arry;
	}
	
	
	//检查输入日期yy-mm-dd
	public function check_string_date($date_string)
	{
		$ary = explode("-", $date_string);
		if(count($ary) != 3){
			return false;
		}
		return checkdate($ary[1], $ary[2], $ary[0]);
	}
	
	//检查时间hh:mm:ss
	public function check_string_time($time_string)
	{
		$ary = explode(":", $time_string);
		if(count($ary) != 3){
			return false;
		}
		if(!is_numeric($ary[0]) || !is_numeric($ary[1]) || !is_numeric($ary[2])){
			return false;
		}
		if($ary[0] < 0 || $ary[0] >= 24 || $ary[1] < 0 || $ary[1] >= 60 || $ary[2] < 0 || $ary[2] >= 60){
			return false;
		}
		return true;
	}
	
	//计算日期差，单位日
	public function get_days($start_date, $end_date){

		if(is_string($start_date)){
			$start_date = strtotime($start_date);
		}
 		if(is_string($end_date)){
 			$end_date = strtotime($end_date);
 		}
 		return ($end_date - $start_date)/86400 + 1;//头尾都包括
	}
	
	//去掉参数前后的_字段
	public function deprefix($param){
		return substr($param, 1, strlen($param) - 2);
	}
	
	//获取日志前缀，记录哪个文件，哪一行出现的日志
	function get_str_log_prex($file_name,$line_num,$function_name) {
		return "[".$file_name."][".$line_num."][".$function_name."] ";	
	}
    function writeLog($msg,$log_dir){
		if (!file_exists($log_dir) && !mkdir($log_dir)){
			$err_logFile = date('Y-m-d').'.log';
 			$msg = date('H:i:s').': '.' mkdir '."$log_dir"." error"."\r\n";
 			file_put_contents($logFile,$msg,FILE_APPEND);	

 			$logFile = 'log-'.date('Y-m-d').'.log';
 			$msg = date('H:i:s').': '.$msg."\r\n";
 			file_put_contents($logFile,$msg,FILE_APPEND);
 			return;
		}
 		$logFile = "$log_dir".'/'.date('Y-m-d').'.log';
 		$msg = date('H:i:s').': '.$msg."\r\n";
 		file_put_contents($logFile,$msg,FILE_APPEND);
	}
	
	public function get_server_info_areaid($area)
	{
		$db = $this->load->database("default",true);
		$sql = "select * from tbl_server where id='$area'";
		$query_result = $db->query($sql);
		$result = $query_result->result();
		$db->close();
		return $result[0];
	}
	
    public function get_playerid_info($playerid){
    	$db = $this->load->database("default",true);
    	if (!$db) {
    		$this->writeLog($this->get_str_log_prex(__FILE__,__LINE__,__FUNCTION__)." my_connect_mysql failure", ERROR_LOG_FILE_NAME);
    		return false;	
    	}
    	$select_sql = "select * from tbl_user where id=$playerid";
    	$result = $db->query($select_sql);
    	if(!$result) {
    		$this->writeLog($this->get_str_log_prex(__FILE__,__LINE__,__FUNCTION__)." sql:".$select_sql, ERROR_LOG_FILE_NAME);
    		$db->close();
    		return false;
    	}
    	if ($result->num_rows() <= 0) {
    		$db->close();
    		$this->writeLog($this->get_str_log_prex(__FILE__,__LINE__,__FUNCTION__)." not find playerid,sql:".$select_sql, ERROR_LOG_FILE_NAME);
    		return false;
    	}
	    foreach ($result->result() as $row) {
	    	$db->close();  
	    	return $row;
	   	}
    	return false;
    }
    
    public function curl_send($url,$params) {
    	//初始化
		$ch = curl_init();
		$curl_param = sprintf("%s/?%s", $url,$params);
		#echo $curl_param;
		curl_setopt($ch, CURLOPT_URL, $curl_param);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		//执行并获取HTML文档内容
		$output = curl_exec($ch);
		#echo $output;
		//释放curl句柄
		curl_close($ch);
    	
    }

}

class FanJianConvert{
	private static $sd="皑";
	private static $td="皚";
	public static function tradition2simple($sContent){
		$simpleCN = '';
		$iContent=mb_strlen($sContent,'UTF-8');
		for($i=0;$i<$iContent;$i++){
			$str=mb_substr($sContent,$i,1,'UTF-8');
			$match=mb_strpos(FanJianConvert::$td,$str,null,'UTF-8');
			$simpleCN.=($match!==false )?mb_substr(FanJianConvert::$sd,$match,1,'UTF-8'):$str;
		}
		return $simpleCN;
	}
	
	public static function simple2tradition($sContent){
		$traditionalCN = '';
		$iContent=mb_strlen($sContent,'UTF-8');
		for($i=0;$i<$iContent;$i++){
			$str=mb_substr($sContent,$i,1,'UTF-8');
			$match=mb_strpos(FanJianConvert::$sd,$str,null,'UTF-8');
			$traditionalCN.=($match!==false )?mb_substr(FanJianConvert::$td,$match,1,'UTF-8'):$str;
		}
		return $traditionalCN;
	} 
}

?>