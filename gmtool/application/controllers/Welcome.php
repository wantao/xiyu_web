<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
/*		
		//��ʼ��
		$ch = curl_init();
		//����ѡ�����URL
		curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1/?type=gm&account=test1&cmd=reload+py_cpp");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
	
		//ִ�в���ȡHTML�ĵ�����
		$output = curl_exec($ch);
	
		//�ͷ�curl���
		curl_close($ch);
	
		//��ӡ��õ�����
		print_r($output);
*/
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */