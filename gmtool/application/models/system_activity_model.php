<?php 
class System_activity_model extends CI_Model {
	var $PROPERTY_NAMES = array(
		'id'            => LG_ACTIVE_ID,
		'type'          => LG_NOTICE_IDX,
		'begin_time'    => LG_BEGIN_TIME,
		'end_time'      => LG_END_TIME,
		'get_award_time'=> LG_GET_AWARD_TIME,
		'desc'          => LG_ACTIVE_DESC,
		'award'         => LG_ACTIVE_AWARD,
		'value'         => LG_ACTIVE_VALUE,
		'is_open'       => LG_STATUS,
	);
	public function __construct(){
		parent::__construct();
	}
	
	public function get_system_activity_info($area_id, $type){
		$db = $this->load->database(get_default_game_db($area_id), true);
		$select_columns = array();
		foreach($this->get_system_activity_property_names() as $property => $name){
			$select_columns[] = "c_system_activity_copy.$property";
		}
		$select = implode(',', $select_columns);
		$query = "select $select from c_system_activity_copy where `type`=$type";
		$sql = $db->query($query);
		$result = $sql->result();
		$db->close();
		
		foreach($result  as $entry){
			foreach($this->get_system_activity_property_names() as $property => $name){
				if ("desc" == $property) {
					$order   = array("\r\n", "\n", "\r");
					$replace = '<br />';
					$str_desc = str_replace($order, $replace, $entry->$property);
					$entry->$property = $str_desc;
				}
				if ('is_open' == $property){
					if(0 == $entry->$property){
						$entry->$property = LG_CLOSE;
					}else{
						$entry->$property = LG_OPEN;
					} 
				}
			}
		}
		return $result;
	}
	
	public function get_one_system_activity_info($area_id, $id){
		$db = $this->load->database(get_default_game_db($area_id), true);
		$select_columns = array();
		foreach($this->get_system_activity_property_names() as $property => $name){
			$select_columns[] = "c_system_activity_copy.$property";
		}
		$select = implode(',', $select_columns);
		$query = "select $select from `c_system_activity_copy` where `id`=$id";
		$sql = $db->query($query);
		$result = $sql->result();
		$first_row = $sql->first_row();
		$db->close();
		return $result;
	}
	
	public function get_types_system_activity_info($area_id){
		$db = $this->load->database(get_default_game_db($area_id), true);
		$select_columns = array();
		$query = "select `type` from `c_system_activity_copy` group by `type`";
		$sql = $db->query($query);
		$result = $sql->result();
		$db->close();
		
		$area_arry = array();
		if ($result){
			foreach($result as $row){
				$area_arry_tmp = array();
				$area_arry_tmp['type'] = $row->type;
				$area_arry_tmp['name'] = $this->get_type($row->type);
				array_push($area_arry, $area_arry_tmp);
			}
		}
		return $area_arry;
	}
	
	public function get_system_activity_property_names(){
		return $this->PROPERTY_NAMES;
	}
	
	public function edit_one_system_activity_active($area_id, $id, $type, $begin_time, $end_time, $get_award_time, $desc, $award, $value, $is_open,$inventory=0,$today_limit_buy_count=0){
		$db = $this->load->database(get_default_game_db($area_id), true);
		$db->trans_begin();
		
		$query = "update `c_system_activity_copy` set `begin_time` = '$begin_time' ,`end_time` = '$end_time' ,`get_award_time` = '$get_award_time' ,
		`award` = ".$db->escape($award)." ,`value`=".$db->escape($value).", `is_open` = '$is_open' ,
		`desc` = ".$db->escape($desc)." where `id` = $id";
		$result = $db->query($query);
		if (!$result || $db->trans_status() === FALSE){
			$db->trans_rollback();
			$db->close();
			return false;
		}
		
		$query = "update `c_system_activity_copy` set `begin_time` = '$begin_time' ,`end_time` = '$end_time' ,`get_award_time` = '$get_award_time'
		where `type` = $type";
		$result = $db->query($query);

		if (!$result || $db->trans_status() === FALSE){
		    $db->trans_rollback();
			$db->close();
			return false;
		}
		
		$db->trans_commit();
		$db->close();
		
		
		return $result;
	}
	
	public function execute_active_take_effect($area_id){
		$db = $this->load->database(get_default_game_db($area_id), true);
		$db->trans_begin();
		
		$query = "delete from `c_system_activity` ";
		$result = $db->query($query);
		if (!$result || $db->trans_status() === FALSE){
			$db->trans_rollback();
			$db->close();
			return false;
		}
		
		$query = "insert into `c_system_activity` select * from `c_system_activity_copy` ";
		$result = $db->query($query);
		if (!$result || $db->trans_status() === FALSE){
			$db->trans_rollback();
			$db->close();
			return false;
		}

	    $db->trans_commit();
		$db->close();
		return true;
	}
	
	public function get_copy_system_activity_sql_data($area_id){
		$db = $this->load->database(get_default_game_db($area_id), true);
		$select_columns = array();
		$query = "select * from `c_system_activity_copy`";
		$sql = $db->query($query);
		$result = $sql->result();
	
		$area_arry = array();
		if ($result){ 
			foreach($result as $row){
				$sql = "INSERT INTO `c_system_activity_copy` VALUES (";
				$values = array();
   			    foreach ($row as $value) {
        	    	$values[] = $db->escape($value);
                }
                $sql .= implode(', ', $values) . ");";
                $sql .= "\n";
				$area_arry[] = $sql;
			}
		}
		#print_r($area_arry);
		$db->close();
		return $area_arry;
	}
	
	public function execute_new_system_activity_sql_data($area_id, $array_sql){
		$db = $this->load->database(get_default_game_db($area_id), true);
		$delete_sql = "delete from `c_system_activity_copy`;";
		$result = $db->query($delete_sql);
		if (!$result){
			return False;
		}
			
	    foreach ($array_sql as $query) {
			$sql = $db->query($query);
			if (!$sql){
				return False;
			}
        }
		$db->close();
		return True;
	}
	
	public function get_type($type){
		if ($type == 1){
			return LG_ACTIVE_CHONGZHI_COUNT;
		}else if($type == 2){
			return LG_ACTIVE_CHONGZHI_LIMIT;
		}else if($type == 3){
			return LG_ACTIVE_INVERSTMENT;
		}else if($type == 4){
			return LG_ACTIVE_SEVEN_DAY_SIGN;
		}else if($type == 5){
			return LG_ACTIVE_SKILLEXCHANGE;
		}else if($type == 6){
			return LG_ACTIVE_UPGRADE;
		}else {
			return LG_NOTHING;
		}
	}
	
	public function get_state(){
		return array(
			array("name" => LG_CLOSE, "value" => 0, ),
			array("name" => LG_OPEN, "value" => 1, ),
		);
	}
}
?>