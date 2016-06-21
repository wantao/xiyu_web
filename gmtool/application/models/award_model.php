<?php 
include_once(APPPATH.'controllers/common_func.php');
class Award_model extends CI_Model {
	var $gm_tool_str="系统";
	//邮件标题最大长度
	var $email_title_max_length = 30;
	//邮件内容最大长度
	var $email_content_max_length = 600;
	//邮件奖励最大长度
	var $email_award_max_length = 32;
	var $err_success = 0;//成功
	var $err_not_set_server_name = -1;//没有设置服务器名字
	var $err_not_set_award_send_condition_type = -2;//没有设置奖励发送类型
	var $err_not_set_award_send_condition = -3;//没有设置奖励发送条件
	var $err_not_set_msg_award_type = -4;//没有设置邮件奖励类型
	var $err_not_set_msg_award_title = -5;//没有设置邮件奖励主题
	var $err_not_set_msg_award_content = -6;//没有设置邮件奖励消息内容
	var $err_not_set_msg_award = -7;//没有设置邮件奖励
	var $err_not_set_sign = -8;//没有设置签名
	var $err_sign_false = -9;//签名不对
	var $err_not_find_server_name = -10;//没有找到该服务器名
	var $err_award_formate_is_error = -11;//奖励格式错误
	var $err_not_find_player_id = -12;//未找到该玩家id
	var $err_player_id_has_illegal_characters = -13;//玩家id存在非法字符
	var $err_player_level_has_illegal_characters = -14;//玩家等级存在非法字符
	var $err_among_player_ids_foramte_is_error = -15;//玩家id之间格式错误
	var $err_not_write_player_id = -16;//没有填写玩家id
	var $err_not_find_award_id = -17;//不存在的奖励id
	var $err_not_find_award_number = -18;//不存在的奖励数量
	var $err_too_many_player_id = -19;//玩家id太多
	var $err_email_title_too_long = -20;//邮件标题太长
	var $err_email_content_too_long = -21;//邮件内容太长
	var $err_email_award_too_long = -22;//邮件奖励太长
	
	var $BIND_INFO_LIST = array(
		LG_BIND => '1',
		LG_NOT_BIND => '0',
	);
	
	public function get_bind_info_list()
	{
		return $this->BIND_INFO_LIST;	
	}
	
	public function __construct(){
		parent::__construct();
	}
	
	public function is_requst_legal($request_arry) {
		if (!isset($request_arry['server_name'])) {
			return $this->err_not_set_server_name;	
		}
		if (!isset($request_arry['condition_type'])) {
			return $this->err_not_set_award_send_condition_type;	
		}
		if (!isset($request_arry['condition'])) {
			return $this->err_not_set_award_send_condition;	
		}
		if (!isset($request_arry['msg_type'])) {
			return $this->err_not_set_msg_award_type;	
		}
		if (!isset($request_arry['msg_title'])) {
			return $this->err_not_set_msg_award_title;	
		}
		if (!isset($request_arry['msg_content'])) {
			return $this->err_not_set_msg_award_content;	
		}
		if (!isset($request_arry['award'])) {
			return $this->err_not_set_msg_award;	
		}
		if (!isset($request_arry['sign'])) {
			return $this->err_not_set_sign;	
		}	
		return $this->err_success;
	}
	
	public function get_err_desc($err_code) {
		if ($this->err_success == $err_code) {
			return "success";//成功	
		}	
		if ($this->err_not_set_server_name == $err_code) {
			return "not_set_server_name";//没有设置服务器名字	
		}
		if ($this->err_not_set_award_send_condition_type == $err_code) {
			return "not_set_award_send_condition_type";//没有设置奖励发送类型
		}
		if ($this->err_not_set_award_send_condition == $err_code) {
			return "not_set_award_send_condition";//没有设置奖励发送条件
		}
		if ($this->err_not_set_msg_award_type == $err_code) {
			return "not_set_msg_award_type";//没有设置邮件奖励类型	
		}
		if ($this->err_not_set_msg_award_title == $err_code) {
			return "not_set_msg_award_title";//没有设置邮件奖励主题
		}
		if ($this->err_not_set_msg_award_content == $err_code) {
			return "not_set_msg_award_content";//没有设置邮件奖励消息内容
		}
		if ($this->err_not_set_msg_award == $err_code) {
			return "not_set_msg_award";//没有设置邮件奖励	
		}
		if ($this->err_not_set_server_name == $err_code) {
			return "not_set_sign";//没有设置签名
		}
		if ($this->err_sign_false == $err_code) {
			return "sign_false";//签名不对
		}
		if ($this->err_not_find_server_name == $err_code) {
			return "not_find_server_name";//没有找到该服务器名
		}
		if ($this->err_award_formate_is_error == $err_code) {
			return "award_formate_is_error";//奖励格式错误	
		}
		if ($this->err_not_find_player_id == $err_code) {
			return "not_find_player_id";//未找到该玩家id	
		}
		if ($this->err_player_id_has_illegal_characters == $err_code) {
			return "player_id_has_illegal_characters";//玩家id存在非法字符
		}
		if ($this->err_player_level_has_illegal_characters == $err_code) {
			return "player_level_has_illegal_characters";//玩家等级存在非法字符	
		}
		if ($this->err_among_player_ids_foramte_is_error == $err_code) {
			return "among_player_ids_foramte_is_error";//玩家id之间格式错误	
		}
		if ($this->err_not_write_player_id == $err_code) {
			return "not_write_player_id";//没有填写玩家id
		}
		if ($this->err_not_find_award_id == $err_code) {
			return "err_not_find_award_id";//不存在的奖励id
		}
		if ($this->err_not_find_award_number == $err_code) {
			return "err_not_find_award_number";//不存在的奖励数量
		}
		if ($this->err_not_find_award_number == $err_code) {
			return "err_not_find_award_number";//不存在的奖励数量
		}
		if ($this->err_not_find_award_number == $err_code) {
			return "err_not_find_award_number";//不存在的奖励数量
		}
		if ($this->err_too_many_player_id == $err_code) {
			return "err_too_many_player_id";//w玩家id太多
		}
		if ($this->err_email_title_too_long == $err_code) {
			return "err_email_title_too_long";//邮件标题太长
		}
		if ($this->err_email_content_too_long == $err_code) {
			return "err_email_content_too_long";//邮件内容太长
		}
		if ($this->err_email_award_too_long == $err_code) {
			return "err_email_award_too_long";//邮件奖励太长
		}
		return "unkonw_err_desc";//未知错误描述
	}
	
	public function is_length_legal($msg_title, $msg_content, $award) {
		if (strlen($msg_title) > $this->email_title_max_length) {
			$this->make_return_err_code_and_des($this->err_email_title_too_long,$this->get_err_desc($this->err_email_title_too_long));
			exit;	
		}
		if (strlen($msg_content) > $this->email_content_max_length) {
			$this->make_return_err_code_and_des($this->err_email_content_too_long,$this->get_err_desc($this->err_email_content_too_long));
			exit;	
		}
		if (strlen($award) > $this->email_award_max_length) {
			$this->make_return_err_code_and_des($this->err_email_award_too_long,$this->get_err_desc($this->err_email_award_too_long));
			exit;	
		}		
	}
	
	public function  make_return_err_code_and_des($err_code,$err_desc) {
		$result_ret = array();
		$result_ret["err_code"]=$err_code;
		$result_ret["err_desc"]=$err_desc;
		$Res = json_encode($result_ret);
		print_r(urldecode($Res));	
	}
	
	public function make_curl_param($type,$condition,$area,$gm_tool_id) {
		$this->load->model('main_model');
		$server_url = $this->main_model->get_server_url_by_areaid($area);
		if (!$server_url) {
			echo "<br>"."get server_url failure"."<br>";
			return NULL;	
		}
		$url_with_param = sprintf("%s/?type=$type&condition=$condition&gm_tool_id=$gm_tool_id&area=$area",$server_url);	
		return $url_with_param;
	}
	
	public function curl_send($curl_param) {
		if (NULL == $curl_param) {
			return;	
		}
		//初始化
		$ch = curl_init();
		//设置选项，包括URL
		//curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:20000/?type=gm&account=test1&cmd=reload+py_cpp");
		curl_setopt($ch, CURLOPT_URL, $curl_param);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		
		//执行并获取HTML文档内容
		$output = curl_exec($ch);
		echo $output;
		//释放curl句柄
		curl_close($ch);	
	}
	
	public function send_msg_to_transferserver($type,$condition,$area,$gm_tool_id) {
		$this->curl_send($this->make_curl_param($type,$condition,$area,$gm_tool_id));			
	}

	public function is_digit_id_legal($digitid) {
		if(preg_match('/^\d+$/i',$digitid)) {
			return true;		
		}
		$this->make_return_err_code_and_des($this->err_player_id_has_illegal_characters,$this->get_err_desc($this->err_player_id_has_illegal_characters));
		return false;
	}
	
	public function is_level_legal($level) {
		if(preg_match('/^\d+$/i',$level)) {
			return true;		
		}
		$this->make_return_err_code_and_des($this->err_player_level_has_illegal_characters,$this->get_err_desc($this->err_player_level_has_illegal_characters));
		assert(false);
		return false;
	}
	
	public function get_gm_tool_id($area_id) {	
		$db = $this->load->database("default_m",true);
		$insert_sql = "insert `tbl_gm_tool_send_email` set `area_id`=$area_id";
		$db->query($insert_sql);
		return $db->insert_id();
	}
	
	public function is_exist_player($area,$digitid) {
/*		$game_db_name = get_game_db_config_by_player_id($area,$digitid);
		if (!$game_db_name) {
			$this->make_return_err_code_and_des($this->err_not_find_player_id,$this->get_err_desc($this->err_not_find_player_id));
			return false;	
		}*/
		$db = $this->load->database('relation_db',true);	
		$result = $db->query('select * from `player_base` where `digitid`='.$digitid);
		$number = $result->num_rows();
		$db->close();
		if ($number <= 0) return false;
		return true;
	}
	//将玩家id分类属于哪些db
	public function classify_player_ids($area,$digitids) {
		$ret_array = get_all_game_db_config_array($area);
		if (!$ret_array) return false;
		foreach ($ret_array as &$db_array) {
			foreach($digitids as $digitid) {
				$game_db_name = get_game_db_config_by_player_id($area,$digitid);
				if (!$game_db_name) {
					$this->make_return_err_code_and_des($this->err_not_find_player_id,$this->get_err_desc($this->err_not_find_player_id));
					return false;	
				}	
				if (!isset($db_array['player_ids'])) {
					$db_array['player_ids'] = array();
				} 
				array_push($db_array['player_ids'], $digitid);
			}
		}
		return $ret_array;		
	}
	
	public function insert_one($digitid, $msg_title, $msg_content, $award, $area,$bind){
		//玩家id合法性判断
		if (!$this->is_digit_id_legal($digitid)) {
			return false;	
		}
		if (!$this->is_exist_player($area,$digitid)) {
			$this->make_return_err_code_and_des($this->err_not_find_player_id,$this->get_err_desc($this->err_not_find_player_id));
			return false;		
		}
		
		//获取本次邮件发送的gm_tool id
		$gm_tool_id = $this->get_gm_tool_id($area);
		if ($gm_tool_id <= 0) {
			assert(false);
			echo "get gm tool id error area_id:".$area;
			return false;
		}
		$this->load->library('session');	
		
		//插入邮件
		$this->load->database('email_db_m_'.$area);
		$this->db->trans_begin();
		$this->db->insert('player_email', array(
			"digitid" => $digitid,
			"send_name" => $this->gm_tool_str,
			"title" => $msg_title,
			"content" => $msg_content,
			"award" => $award,
			"gm_tool_id" => $gm_tool_id,
			"award_bind" => $bind
		));
		
		if ($this->db->trans_status() === FALSE){	
    		$this->db->trans_rollback();
    		$this->db->close();
			return false;
		} 

		$z_all_db = $this->load->database("default",true);
		$z_all_db->trans_begin();
		$z_all_db->insert('gm_tool_award_send_record', array(
			"gm_tool_id" => $gm_tool_id,
			"area_id" => $area,
			"execute_account" => $this->session->userdata('username'),
			"execute_ip" => $this->session->userdata('ip_address'),
			"recieve_type" => 2,
			"reciever" => $digitid,
			"title" => $msg_title,
			"content" => $msg_content,
			"award" => $award,
			"award_bind" => $bind
		));
		if ($z_all_db->trans_status() === FALSE){	
    		$z_all_db->trans_rollback();
    		$z_all_db->close();
			return false;
		} 
		
    	$this->db->trans_commit();
    	$z_all_db->trans_commit();
    	$this->db->close();
    	$z_all_db->close();
    	$this->load->model('common_model');
    	$this->common_model->writeLog("gm_tool_id:".$gm_tool_id." server_area:".$area." digitid:".$digitid.
			" msg_title:".$msg_title." msg_content:".$msg_content." award:".$award,'gm_award_send_log');
			//通知ts
		$this->send_msg_to_transferserver("to_one",$digitid,$area,$gm_tool_id);
		return true;
	}
	
	public function insert_range($digitids, $msg_title, $msg_content, $award, $area,$bind){
		//验证玩家id的合法性
		$digitid_list = explode(":", $digitids);
		if(!$digitid_list){
			$this->make_return_err_code_and_des($this->err_among_player_ids_foramte_is_error,$this->get_err_desc($this->err_among_player_ids_foramte_is_error));
			return false;
		}
		$digitid_count = count($digitid_list);
		if ($digitid_count <= 0) {
			$this->make_return_err_code_and_des($this->err_not_write_player_id,$this->get_err_desc($this->err_not_write_player_id));	
			return false;	
		}
		foreach($digitid_list as $digitid) {
			if (!$this->is_digit_id_legal($digitid)) {
				return false;	
			}
			if (!$this->is_exist_player($area,$digitid)) {
				$this->make_return_err_code_and_des($this->err_not_find_player_id,$this->get_err_desc($this->err_not_find_player_id));
				assert(false);
				return false;		
			}
		}
		
		//产生本次插入的gm_tool_id
		$gm_tool_id = $this->get_gm_tool_id($area);
		if ($gm_tool_id <= 0) {
			assert(false);
			echo "get gm tool id error area_id:".$area;
			return false;
		}
		
		//插入邮件到玩家对应的db中
		$db_link = $this->load->database('email_db_m_'.$area,true);
		$sql = "insert into `player_email` (`digitid`,`send_name`,`title`,`content`,`award`,`gm_tool_id`,`award_bind`) values ";
		$i = 1;
		foreach($digitid_list as $digitid) {
			if ($digitid_count == 1 || $i == $digitid_count) {
				$sql .= "('$digitid', '$this->gm_tool_str', ".$db_link->escape($msg_title).", ".$db_link->escape($msg_content).", ".$db_link->escape($award).", ".$gm_tool_id.",".$bind.")";	
			} else {
				$sql .= "('$digitid', '$this->gm_tool_str', ".$db_link->escape($msg_title).", ".$db_link->escape($msg_content).", ".$db_link->escape($award).", ".$gm_tool_id.",".$bind."),";
			}	
			$i += 1;
		}
		$db_link->trans_begin();
		$db_link->query($sql);
		if ($db_link->trans_status() === FALSE){	
    		$db_link->trans_rollback();
    		$db_link->close();
    		$this->load->model('common_model');
    		$this->common_model->writeLog("insert error sql:".$sql,'error_log');
			echo "insert error,sql:".$sql;
			assert(false);
			return false;
		} 

		//保存邮件发送记录
		$this->load->library('session');
		$z_all_db = $this->load->database("default",true);
		$z_all_db->trans_begin();
		$z_all_db->insert('gm_tool_award_send_record', array(
			"gm_tool_id" => $gm_tool_id,
			"area_id" => $area,
			"execute_account" => $this->session->userdata('username'),
			"execute_ip" => $this->session->userdata('ip_address'),
			"recieve_type" => 3,
			"reciever" => $digitids,
			"title" => $msg_title,
			"content" => $msg_content,
			"award" => $award,
			"award_bind" => $bind
		));
		if ($z_all_db->trans_status() === FALSE){	
    		$z_all_db->trans_rollback();
    		$z_all_db->close();
			return false;
		} 
		//没有发送错误，全部提交
		$db_link->trans_commit();	
		$db_link->close();
		$z_all_db->trans_commit();
    	$z_all_db->close();
		$this->load->model('common_model');
    	$this->common_model->writeLog("gm_tool_id:".$gm_tool_id." server_area:".$area." digitids:".$digitids.
			" msg_title:".$msg_title." msg_content:".$msg_content." award:".$award,'gm_award_send_log');
			//通知ts
		$this->send_msg_to_transferserver("to_more",$digitids,$area,$gm_tool_id);
		return true;
	}
	
	public function insert_by_player_min_level($min_level, $msg_title, $msg_content, $award, $area,$bind){
		//判断等级输入是否合法
		if (!$this->is_level_legal($min_level)) {
			$this->make_return_err_code_and_des($this->err_player_level_has_illegal_characters,$this->get_err_desc($this->err_player_level_has_illegal_characters));
			return false;	
		}
		if ($min_level < 0) {
			$this->make_return_err_code_and_des($this->err_player_level_has_illegal_characters,$this->get_err_desc($this->err_player_level_has_illegal_characters));
			return false;
		}
		
		//获取所有该区的所有gamedb
		$all_gamedb_config = get_all_game_db_config_array($area);
		if (!$all_gamedb_config) {
			echo "get_all_game_db_config_array error,area:".$area;
			assert(false);
			return false;
		}
		
		//产生本次插入的gm_tool_id
		$gm_tool_id = $this->get_gm_tool_id($area);
		if ($gm_tool_id <= 0) {
			assert(false);
			echo "get gm tool id error area_id:".$area;
			return false;
		}
		
		//用于存放db连接
		$db_arry = get_db_array('email_db_m_'.$area);
		$email_db_name = $db_arry['database'];
		$relation_db_arry = get_db_array('relation_db');
		$relation_db_name = $relation_db_arry['database'];
		$email_db_link = $this->load->database($db_arry,true);
		$email_db_link->trans_begin();
		$sql = "insert into `player_email` (`digitid`,`send_name`,`title`,`content`,`award`,`gm_tool_id`,`award_bind`) "."
		SELECT T2.digitid,  '$this->gm_tool_str',". $email_db_link->escape($msg_title).",". $email_db_link->escape($msg_content).",".
		$email_db_link->escape($award).",".$gm_tool_id.",".$bind." FROM $relation_db_name.`player_exp` AS T2 WHERE T2.`level` >= $min_level";
		$email_db_link->query($sql);
		if ($email_db_link->trans_status() === FALSE){	
    		$email_db_link->trans_rollback();
    		$email_db_link->close();
    		$this->load->model('common_model');
    		$this->common_model->writeLog("insert error sql:".$sql,'error_log');
			echo "insert error,sql:".$sql;
			assert(false);
			return false;
		} 	
		//保存邮件发送记录
		$this->load->library('session');
		$z_all_db = $this->load->database("default",true);
		$z_all_db->trans_begin();
		$z_all_db->insert('gm_tool_award_send_record', array(
			"gm_tool_id" => $gm_tool_id,
			"area_id" => $area,
			"execute_account" => $this->session->userdata('username'),
			"execute_ip" => $this->session->userdata('ip_address'),
			"recieve_type" => 4,
			"reciever" => $min_level,
			"title" => $msg_title,
			"content" => $msg_content,
			"award" => $award,
			"award_bind" => $bind
		));
		if ($z_all_db->trans_status() === FALSE){	
    		$z_all_db->trans_rollback();
    		$z_all_db->close();
			return false;
		} 
		//没有发送错误，全部提交
		$email_db_link->trans_commit();	
		$email_db_link->close();
		$z_all_db->trans_commit();
    	$z_all_db->close();
		$this->load->model('common_model');
    	$this->common_model->writeLog("gm_tool_id:".$gm_tool_id." server_area:".$area." min_level:".$min_level.
			" msg_title:".$msg_title." msg_content:".$msg_content." award:".$award,'gm_award_send_log');
			//通知ts
		$this->send_msg_to_transferserver("other_condition",0,$area,$gm_tool_id);
		return true;
	}
	
	public function get_recent_send_record($area)
	{
		$db = $this->load->database("default",true);	
		$sql = "select * from `gm_tool_award_send_record` where `area_id`=$area order by `currenttime` desc limit 20";
		$query_result = $db->query($sql);
		if (!$query_result) {
			return false;
		}
		$result = $query_result->result();
		$db->close();
		return $result;
	}
	
	public function is_award_legal($award,$area) {
		$entries = explode(",", $award);
		if(!$entries){
			$this->award_model->make_return_err_code_and_des($this->err_among_player_ids_foramte_is_error,$this->award_model->get_err_desc($this->err_among_player_ids_foramte_is_error));
			exit;
		}
		foreach($entries as $entry){
			$args = explode(":", $entry);
			$param_count = count($args);
			if(!($param_count == 2 || $param_count == 3)){
				$this->award_model->make_return_err_code_and_des($this->err_among_player_ids_foramte_is_error,$this->award_model->get_err_desc($this->err_among_player_ids_foramte_is_error));
				exit;
			}
			$id = $args[0];
			$number = $args[1];
			if(!is_numeric($id) || !is_numeric($number)) exit;
			if (!$this->_is_exist_award_id($id,$area)) {
				$this->award_model->make_return_err_code_and_des($this->err_not_find_award_id,$this->award_model->get_err_desc($this->err_not_find_award_id));
				exit;	
			}
			if ($number <= 0 || $number >= 999999) {
				$this->award_model->make_return_err_code_and_des($this->err_not_find_award_number,$this->award_model->get_err_desc($this->err_not_find_award_number));
				exit;	
			}
		}
		return true;
	}
	
	private function  _is_exist_award_id($id,$area) {
		$game_db_name = get_game_db_config_by_player_id($area,1);
		if (!$game_db_name) {
			$this->make_return_err_code_and_des($this->err_not_find_player_id,$this->get_err_desc($this->err_not_find_player_id));
			return false;	
		}	
		$db = $this->load->database($game_db_name,true);
		$query = $db->query('SELECT * FROM `c_goods` where `id`='.$id);
		return (0 >= $query->num_rows()) ? false : true;
	}
}
?>