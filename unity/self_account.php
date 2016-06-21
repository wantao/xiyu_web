<?php

require_once 'self_config.php';
require_once 'self_error_code.php';
require_once 'self_log.php';

/**
 * 对本地帐号表的一些操作
 */
class AccountOperation
{
    /**
     * 更新或插入一个用户的登录记录
     * @param string $account  平台返回的唯一id
     * @param string $access_token  平台返回的token
     * @param int  $platform_id  平台id. 我们自己定义
     * @return array("login_server_ip","login_server_port","server_code","session_key") , 失败返回 带错误码的数组
     */
    public function send_account_info_to_lg_server($account, $access_token, $platform_id, $server_code, $enable)
    {
    	$login_server_info = get_login_server_info($server_code);
    	if(empty($login_server_info)) 
    		return;
    	$self_account = $platform_id . "_" . $account; // 使用平台id 作为前缀
        $session_key = md5($self_account . $access_token); // 签名
		{// 发送账号等相关信息给登入服务器
			$sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
			$msg = $self_account . "|" . $session_key . "|" . $platform_id . "|" .$server_code . "|" .$login_server_info["udp_key"] . "|" .$enable;
			$len = strlen($msg);
			socket_sendto($sock, $msg, $len, 0, $login_server_info["udp_server_ip"], $login_server_info["udp_server_port"]);
			socket_close($sock);
		}
    }
    
    public function get_session_key($account, $access_token, $platform_id)
    {
    	$self_account = $platform_id . "_" . $account; // 使用平台id 作为前缀
        $session_key = md5($self_account . $access_token); // 签名
        return $session_key;
    }
    
    public function update_account_info($account, $access_token, $platform_id,$cellphone_os_type)
    {
    	$ob_data_factory = new CDataOperateFactory('default_m',true);
    	$session_key = $this->get_session_key($account, $access_token, $platform_id);
    	$self_account = $ob_data_factory->db_mysql_escape_string($platform_id . "_" . $account); // 使用平台id 作为前缀
    	$query = " INSERT INTO `tbl_account` SET `account`='$self_account', `session_key`='$session_key', `platform`=$platform_id, 
    	`access_token`='$access_token', `login_time`=NOW(),`register_time`=NOW() 
        ON DUPLICATE KEY UPDATE `session_key`='$session_key', `access_token`='$access_token',`login_time`=NOW(),`register_time`=`register_time`" ;
    	if (!$ob_data_factory->db_update_data($query)) {
			return false;
		}
    	return true;
    }
}

