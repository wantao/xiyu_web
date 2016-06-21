<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'common_func.php';
class Main extends CI_Controller{
	var $CURRENT_PAGE = "模块1";
	var $GM_LEVEL = "-1";
	public function show($content = 1){
		$this->load->library('session');
		$logged_in = $this->session->userdata('logged_in');
		if($logged_in == false){
			$this->load->view("main/main_not_logged");
			return;
		}
		$gm_level = $this->session->userdata('gm_level');
		$excute_ip = $this->session->userdata('ip_address');
		$excute_account = $this->session->userdata('username');
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

		$gmcommand_list = $this->main_model->get_gmcmdlist();
		$this->load->model('common_model');
		$data['server_list'] = $this->common_model->get_arealist();
		$data['gmcommand_list'] = $gmcommand_list;
		$this->load->view("templates/header", $data);
		$this->load->view("main/main_show", $data);
		$this->load->view("templates/footer");
	}
	public function execute($server_id,$url, $cmd, $arg, $account, $password){		
		$server_id = urldecode($this->getParam($server_id));
		$url = urldecode($this->getParam($url));
		$cmd = $this->getParam($cmd);
		$arg = $this->getParam($arg);
		$account = $this->getParam($account);
		$password = $this->getParam($password);
		$gm_id = 0;
		{
			$this->load->library('session');
			$excute_ip = $this->session->userdata('ip_address');
			$excute_account = $this->session->userdata('username');
			$arg_tmp = $arg;
			$gm_full_cmd = $cmd.' ';
			$gm_full_cmd .= str_replace("%20"," ",$arg_tmp);
			//产生gm_id（编号）
			$insert_sql = "insert into `tbl_web_gm_excute_record` (`area_id`,`excute_ip`,`excute_account`,
			`gm_full_cmd`) values($server_id,'$excute_ip','$excute_account','$gm_full_cmd')";
			$db = $this->load->database("default",true);
			$query_result = $db->query($insert_sql);
			$gm_id = $db->insert_id();
			echo "gm_id:".$gm_id."<br>";
		}
		
		/*{
			$this->load->library('session');
			$gm_level = $this->session->userdata('gm_level');
			$excute_ip = $this->session->userdata('ip_address');
			$excute_account = $this->session->userdata('username');
			$arg_tmp = $arg;
			//发送前先把插入记录
			$insert_sql = "insert into `tbl_gm_excute_record` (`excute_ip`,`excute_account`,
			`gmlevel`,`area_id`,`gm_full_cmd`) values('".$excute_ip."','".$excute_account 
			."',".$gm_level.",".$server_id.",'".$cmd.' '.str_replace("%20"," ",$arg_tmp)."')";
			echo $insert_sql;
			$db = $this->load->database("default",true);
			$query_result = $db->query($insert_sql);
			print_r($db->insert_id());
				
			$db->close();
		}*/
		
		$url_with_param = sprintf("%s/?gm_id=%d&area_id=%s&type=gm&account=%s&psw=%s&cmd=%s+%s",$url,$gm_id,$server_id,$account, $password, $cmd, $arg);
		//初始化
		$ch = curl_init();
		//设置选项，包括URL
		//curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:20000/?type=gm&account=test1&cmd=reload+py_cpp");
		curl_setopt($ch, CURLOPT_URL, $url_with_param);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		
		//执行并获取HTML文档内容
		$output = curl_exec($ch);
		
		//释放curl句柄
		curl_close($ch);
		
		//打印获得的数据
		print_r($output);	
		
		echo "<br>";
		sleep(3);
		$this->load->model('main_model');
		$this->main_model->print_web_gm_result($gm_id);
		
	}
	private function getParam($param)
	{
		 return substr($param, 1, strlen($param) - 2);
	}
}

?>

