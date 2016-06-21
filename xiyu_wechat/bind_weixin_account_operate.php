<?php
	require_once("../unity/self_data_operate_factory.php");
	require_once("../unity/self_table_names.php");
	require_once("Config.php");
	
	if($_SERVER['REQUEST_METHOD'] != 'POST'){
		echo "Forbidden. Only POST request is allowed.";
		return;
	}
	if(!isset($_POST['platform']) || !isset($_POST['area_id']) || !isset($_POST['player_id']) || !isset($_POST['open_id'])){
		echo "Arguments error.";
		return;
	}
	
	$ob_data_factory = new CDataOperateFactory('default_m');
	$platform = $ob_data_factory->mysql->my_mysql_real_escape_string(trim(urldecode($_POST['platform'])));
	$player_id = $ob_data_factory->mysql->my_mysql_real_escape_string(trim(urldecode($_POST['player_id'])));
	$area_id = $ob_data_factory->mysql->my_mysql_real_escape_string(trim(urldecode($_POST['area_id'])));
	$open_id = $ob_data_factory->mysql->my_mysql_real_escape_string(trim(urldecode($_POST['open_id'])));
	
	//查询玩家信息是否正确
	$select_sql = "SELECT * FROM `tbl_user` WHERE `id`=$player_id";
	$db_result = $ob_data_factory->db_get_data($select_sql);
	if (!$db_result) {
		echo "db operate error,sql:".$select_sql." mysql_error:".$ob_data_factory->mysql->my_mysql_error();
        return;	
	}
	if (-1 == $db_result) {
		echo $select_sql;
		echo "报告校尉！绑定失败了呢，请检查您输入的信息是否有误~";
		return;
	}
	$row = $db_result[0];
	if ($platform != $row[enum_tbl_user::e_platform] || $area_id != $row[enum_tbl_user::e_areaid]) {
		echo "报告校尉！绑定失败了呢，请检查您输入的信息是否有误~";
		return;
	}
	
	//查询是否已经绑定
	$select_sql = "SELECT * FROM `player_weixin` WHERE `digitid`=$player_id;";
	$db_result = $ob_data_factory->db_get_data($select_sql);
	if (!$db_result) {
        echo "db operate error,sql:".$select_sql." mysql_error:".mysql_error();
        return;
    }
	if (-1 != $db_result) {
		$row = $db_result[0];
		if ($open_id != $row[enum_player_weixin::e_open_id]) {
			echo "报告校尉！绑定失败了呢，该玩家ID已经绑定了其他微信号~";
			return;
		} else {
			$location = DOMAIN_PREFIX."get_award_ui.php?open_id=".urlencode($open_id);
			//Utils::writeLog("location:".$location);
			header("location:$location");
			return;
		}
	}
    $insert_sql = "INSERT INTO `player_weixin` set `digitid` = $player_id, `open_id` = '$open_id', `areaid`=$area_id";
	if (!$ob_data_factory->db_update_data($insert_sql)) {
		echo "db operate error,sql:".$insert_sql." mysql_error:".$ob_data_factory->mysql->my_mysql_error();
        return;
	}
	//跳转到领奖ui
	$location = DOMAIN_PREFIX."get_award_ui.php?open_id=".urlencode($open_id);
	//Utils::writeLog("location:".$location);
	header("location:$location");	
?>