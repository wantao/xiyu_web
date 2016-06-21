<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'common_func.php';
class Gm_plan extends CI_Controller{
	var $CURRENT_PAGE = "LG_GM_PLAN";
	var $GM_LEVEL = "-1";
	var $INPUT_LOG_DIR_NAME = "input_file_log";

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

		$data["area_id"] = isset($params["area_id"]) ? $params["area_id"]:0;
		$data["execut_msg"] = isset($params["execut_msg"]) ? $params["execut_msg"]:'';
		 
		$date_time = date('Y-m-d H:i:s',time());
		$data['execute_time'] = (isset($params["execute_time"]) ? $params["execute_time"] : $date_time);

		$gmcommand_list = $this->main_model->get_gmcmdlist();
		$this->load->model('common_model');
		$data['area_list'] = $this->common_model->add_selected_key_for_arealist($this->common_model->get_arealist());
		$data['gmcommand_list'] = $gmcommand_list;
		$this->load->model('gm_plan_model');
		$gm_plan_ary = array();
		$gm_plan_ary['info'] = $this->gm_plan_model->get_plan();
		$gm_plan_ary['property_name'] = $this->gm_plan_model->get_property_names();
		$data['gm_plan'] = $gm_plan_ary;
		$this->load->view("templates/header", $data);
		$this->load->view("gm_plan/gm_plan_show", $data);
		$this->load->view("templates/footer");
	}
	public function add_plan($plan_name, $area_id, $gm_account, $gm_cmd, $gm_cmd_param, $execute_time){
		$msg = 'gm plan success';

		//echo $plan_name . "</p>";

		$data['plan_name'] = urldecode($this->getParam($plan_name));
		$data['area_id'] = $this->getParam($area_id);
		$data['gm_account'] = urldecode($this->getParam($gm_account));
		$data['gm_cmd'] = urldecode($this->getParam($gm_cmd));
		$data['gm_cmd_param'] = urldecode($this->getParam($gm_cmd_param));
		$data['execute_time'] = urldecode($this->getParam($execute_time));

		if (empty($data['plan_name'])) {
			$this->show(array( "area_id" => $area_id, "execut_msg" => "plan_name is null",));
			return;
		}
		if (empty($data['gm_account'])) {
			$this->show(array( "area_id" => $area_id, "execut_msg" => "gm_account is null",));
			return;
		}
		if (empty($data['gm_cmd'])){
			$this->show(array( "area_id" => $area_id, "execut_msg" => "gm_cmd is null",));
			return;
		}

		// gm 参数可以允许空
		//if (empty($data['gm_cmd_param'])){
		//	$this->show(array( "area_id" => $area_id, "execut_msg" => "gm_cmd_param is null",));
		//	return;
		//}

		//print_r($data);

		if (isset($_FILES["up_file"]) && $_FILES["up_file"]["size"] > 0) {
			$files_type = array("xml", "sql", "py");
			$this_file_type = strtolower(end(explode('.',$_FILES["up_file"]['name'])));

			if ($_FILES["up_file"]["size"] > 2*1024*1024 || $_FILES["up_file"]["size"] < 1){
				$msg = "file size > 2*1024*102";
				$this->show(array( "area_id" => $area_id, "execut_msg" => $msg,));
				return;
			} else if ($_FILES["up_file"]["error"] > 0){
				$msg = "input file fail Return Code: " . $_FILES["up_file"]["error"];
				$this->show(array( "area_id" => $area_id, "execut_msg" => $msg,));
				return;
			} else if ( ! in_array($this_file_type, $files_type) ){
				$msg = "wrong file name: " . $_FILES["up_file"]["name"] . " only *.sql, *.py, *.xml ";
				$this->show(array( "area_id" => $area_id, "execut_msg" => $msg,));
				return;
			}else {
				// 获取上传文件内容, 计算md5
				$data['file_name'] = $_FILES["up_file"]['name'];
				$data['file_data'] = file_get_contents($_FILES["up_file"]['tmp_name']);
				$data['file_size'] = $_FILES["up_file"]["size"];
				$data['file_md5'] = md5_file($_FILES["up_file"]['tmp_name']);
		  	}
		}
		
		$this->load->model('gm_plan_model');
	  	$msg = $this->gm_plan_model->add($data);
	  	if ("success" == $msg) {
	  		$this->load->helper('url');
			redirect('/gm_plan/show', 'auto');
			return;
	  	}
		$this->show(array( "area_id" => $area_id, "execut_msg" => $msg,));
	}
	
	public function del_plan($plan_id) {
		$data['plan_id'] = $this->getParam($plan_id);
		echo $data['plan_id'];
		$this->load->model('gm_plan_model');
		$msg = $this->gm_plan_model->del($data);
		
		$this->load->helper('url');
		redirect('/gm_plan/show', 'auto');
	}
	
	private function getParam($param)
	{
		 return substr($param, 1, strlen($param) - 2);
	}
}

?>

