<?php 
class Gm_plan_model extends CI_Model {
	var $PROPERTY_NAMES = array(//对应表里必须含有Digitid列才可使用该结构进行查询
		'tbl_gm_plan' => array(
			'id'        => "id",
			'plan_name'=> "plan_name",
			'area_id' => "area_id",
			'gm_account'   => "gm_account",
			'gm_cmd' => "gm_cmd",
			'gm_cmd_param'=> "gm_cmd_param",
			'begin_execute_time'  => "begin_execute_time",
			'has_execute'  => "has_execute",
			'execute_time'      => "execute_time",
			'file_name'  => "file_name",
			'execute_rmsg'      => "execute_rmsg",
		)
	);

	public function __construct(){
		parent::__construct();
	}
	public function get_plan(){
		$db = $this->load->database("default", true);
		$select = implode(',', $this->PROPERTY_NAMES['tbl_gm_plan']);
		$sql = "SELECT $select FROM `tbl_gm_plan` ORDER BY `id` DESC limit 30 ";
		$query = $db->query($sql);
		$result = $query->result();
		$db->close();
		return $result;
	}

	public function get_property_names(){
		return $this->PROPERTY_NAMES['tbl_gm_plan'];
	}

	public function add($data) {
		$db = $this->load->database("default", true);
		//print_r($data);

		if (empty($data['file_size']))
			$data['file_size'] = 0;

		$sql = "INSERT INTO `tbl_gm_plan` SET `plan_name`=".$db->escape($data['plan_name']).
				", `area_id`=".$data['area_id'].", `gm_account`=".$db->escape($data['gm_account']).", `gm_cmd`=".$db->escape($data['gm_cmd']).",
		 		`gm_cmd_param`=".$db->escape($data['gm_cmd_param']).
		 		", `begin_execute_time`=".$db->escape($data['execute_time']).", `file_data`=".$db->escape($data['file_data']).
		 		", `file_md5`='".$data['file_md5']."', `file_name`=".$db->escape($data['file_name']).
		 		", `file_data_real_len`=".$data['file_size'] ;

		//echo $sql;
		$query = $db->query($sql);
		$db->close();
		return "success";
	}

	public function del($data) {
		$db = $this->load->database("default", true);
		$sql = "UPDATE `tbl_gm_plan` SET `has_execute`=3, `execute_rmsg`='gm remove' WHERE `id`=".$data['plan_id'];
		$query = $db->query($sql);
		$db->close();
	}
}
?>


