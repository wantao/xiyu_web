<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'common_func.php';
class Award extends CI_Controller{
	var $CURRENT_PAGE = "发送奖励";
	var $GM_LEVEL = "-1";

	var $award_static_key = '4c513d033825fa0d55332bdc2a43f3a0d37b2436402f7a0e';
	
	public function show($params = array()){
		$this->load->library('session');
		$logged_in = $this->session->userdata('logged_in');		
		if($logged_in == false){
			$this->load->view("main/main_not_logged");
			return;
		}
		$gm_level = $this->session->userdata('gm_level');
		$this->load->model('main_model');
		$pages = $this->main_model->get_pages($gm_level);
		if(!isset($pages)){
			$this->load->view("main/main_not_enough_level");
			$this->load->view("templates/footer");
			return;
		}
		$data['current_page'] = $this->CURRENT_PAGE;
		$data['pages'] = $pages;
		
		$uri_string = $this->session->CI->uri->segments[1];
		if (!is_sub_url_string($data['pages'],$uri_string)) {
			$this->load->view("main/main_not_enough_level");
			$this->load->view("templates/footer");
			return;	
		}
		$this->load->model('common_model');
		$data['server_list'] = (isset($params["server_list"]) ? $params["server_list"] : $this->common_model->add_selected_key_for_arealist($this->common_model->get_arealist()));;
		//print_r($server_list);
		$area = 0;
		$data['sys_msg_type_list'] = (isset($params["sys_msg_type_list"])) ? $params["sys_msg_type_list"] : array(array('id'=>2,'name'=>LG_CAN_AWARD_EMAIL),array('id'=>0,'name'=>LG_NON_REWARD_EMAIL));	
		$this->load->model('award_model');
		if ($area == 0) {
			$area = $data['server_list'][0]['id'];
		} 
		$data['send_status'] = (isset($params["send_status"])) ? $params["send_status"] : '';
		$data['recent_record'] = (isset($params["recent_record"])) ? $params["recent_record"] : $this->award_model->get_recent_send_record($area);
		$data['bind_list'] = $this->award_model->get_bind_info_list();
		$this->load->view("templates/header", $data);
		$this->load->view("award/award_show", $data);
		$this->load->view("templates/footer");	
	}
	
	public function execute($mode, $value, $msg_title, $msg_content, $award, $area=-1,$bind){	
		$mode = $this->_getParam($mode);
		$value = $this->_getParam($value);
		$msg_title = $this->_getParam($msg_title);
		$msg_content = $this->_getParam($msg_content);
		$award = $this->_getParam($award);
		$area = $this->_getParam($area);
		$bind = $this->_getParam($bind);
		
		if (!is_numeric($area)) exit;	
		$this->load->model('award_model');
		$award = $this->_to_string(urldecode($award));
		$msg_title = urldecode($msg_title);
		$msg_content = urldecode($msg_content);
		
		//长度判断
		$this->load->model('award_model');
		$this->award_model->is_length_legal($msg_title, $msg_content, $award);
		
		//奖励合法性简单判断	
		if (!$this->award_model->is_award_legal($award,$area)) exit; 
		$value = $this->_to_string($value);
		$ret_flag = false;
		switch($mode){
		case 1:
			$ret_flag =  $this->_send_to_one($value, $msg_title, $msg_content, $award, $area,$bind);
			break;
		case 2:
			$ret_flag =  $this->_send_to_group($value, $msg_title, $msg_content, $award, $area,$bind);
			break;
		case 3:
			$ret_flag =  $this->_send_to_player_by_min_level($value, $msg_title, $msg_content, $award, $area,$bind);
			break;
		default:
			break;
		}
		//下面函数可以打印出相关操作的执行情况
		//$this->output->enable_profiler(TRUE);
		
		$this->show(array(
			"recent_record" => $this->award_model->get_recent_send_record($area),
			"send_status" => $ret_flag ? 'send success' : 'send failure',
			"server_list" => $this->common_model->set_selected_flag_for_arealist($area,$this->common_model->get_arealist()),
		));
		
		return false;
	}
	
	private function _to_string($escaped_string){
		return strtr($escaped_string, array("%3A" => ':', "%2C" => ','));
	}
	
	private function _send_to_one($digitid, $msg_title, $msg_content, $award, $area,$bind){
		$this->load->model("award_model");
		return $this->award_model->insert_one($digitid, $msg_title, $msg_content, $award, $area,$bind);
	}
	private function _send_to_group($digitids, $msg_title, $msg_content, $award, $area,$bind){
		$this->load->model("award_model");
		return $this->award_model->insert_range($digitids,$msg_title, $msg_content, $award, $area,$bind);
	}
	private function _send_to_player_by_min_level($min_level,$msg_title, $msg_content, $award, $area,$bind){
		$this->load->model("award_model");
		return $this->award_model->insert_by_player_min_level($min_level, $msg_title, $msg_content, $award, $area,$bind);
	}
	public function _getParam($param){
		$this->load->model("common_model");
		return $this->common_model->deprefix($param);
	}
}

?>

