<?php

//memcache 配置
$memcache_config = array(
	'hostname' => '127.0.0.1',
	'port'     => '11211',
	'weight'   => '1',
	'timeout'  => '1',
);

class memcache_key {
	const e_gm_list = 'gm_list';//gm列表的key
	const e_server_list = 'server_list';//区表的key
	const e_notic_prefix = 'notice_prefix';//公告key前缀
}

?>

