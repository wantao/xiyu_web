<?php
	require_once 'self_config.php';
	require_once 'self_log.php';
	require_once 'self_error_code.php';
	
	function get_remote_ip() {
		$cip = '';
		if(!empty($_SERVER["HTTP_CLIENT_IP"])){
			$cip = $_SERVER["HTTP_CLIENT_IP"];
		} elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
			$cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		} elseif(!empty($_SERVER["REMOTE_ADDR"])){
			$cip = $_SERVER["REMOTE_ADDR"];
		} else{
			exit("can not get ip");
		}
		
		return $cip;	
	}
	
	function get_session_key($player_account, $access_token)
    {
        $session_key = md5($player_account . $access_token); // 签名
        return $session_key;
    }

?>