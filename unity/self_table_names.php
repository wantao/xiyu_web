<?php
	
	class tbl_names
	{
		const tbl_account = 'tbl_account';
		const system_notice = 'system_notice';	
		const tbl_gmlist = 'tbl_gmlist';
		const tbl_server = 'tbl_server';	
	}

	//tbl_account
	class enum_tbl_account
	{
	    const e_account = 'account';//帐号只允许英文字母+数字+@+. 组合
	    const e_session_key = 'session_key';//回话密钥，只允许英文字母+数字组合
	    const e_session_key_time = 'session_key_time';//session key 的有效期。如果无限有效期，则默认值为空或者是1970-01-01 08:00:00
	    const e_platform = 'platform';//平台id
	    const e_login_time = 'login_time';//最近一次登录时间
	    const e_access_token = 'access_token';//平台返回的token
	    const e_enable = 'enable';//限号测试里,如果有需要激活码的; 0标识没有激活,1标识激活了
	    const e_register_time = 'register_time';//注册时间
	    const e_last_areaid = 'last_areaid';//上次登录的区号
		const e_is_accept_license = 'is_accept_license';//是否同意游戏条款0:不同意，1:同意',
	}
	
	//system_notice
	class enum_system_notice
	{
		const e_area_id = 'area_id';//区号,0表示所有区都有的公告
	    const e_idx = 'idx';//本区系统公告编号
	    const e_begin_time = 'begin_time';//开始时间
	    const e_end_time = 'end_time';//结束时间
	    const e_frequency = 'frequency';//广播频率。单位秒
	    const e_delay_begin = 'delay_begin';//几秒后开始
	    const e_content = 'content';//公告内容
	}

	//tbl_gmlist
	class enum_tbl_gmlist
	{
		const e_account = 'account';//玩家账号
	    const e_level = 'level';//gm等级
	    const e_ps = 'ps';//加密串
	}
	
	//tbl_server
	class enum_tbl_server
	{
		const e_id = 'id';//区号
	    const e_name = 'name';//区名
	    const e_url = 'url';//后台工具连接gs的httpd ip
	    const e_port = 'port';//端口
	    const e_belong_to = 'belong_to';//该区归属0:android区,1:ios区
	    const e_current_code = 'current_code';//当前区号
	    const e_login_server_ip = 'login_server_ip';//登陆服务器ip
	    const e_login_server_port = 'login_server_port';//登陆服务器端口
	    const e_udp_server_ip = 'udp_server_ip';//udp服务器ip
	    const e_udp_server_port = 'udp_server_port';//udp服务器端口
	    const e_udp_key = 'udp_key';//udp_key
	    const e_run_status = 'run_status';//服务器运行状态0：正常运行，2:维护
	    const e_fluency_status = 'fluency_status';//服务器流程状态0：新区，1：爆满
	    const e_is_trial = 'is_trial';//是否是送审机,0:不是，1:是
	}

	//player_weixinss
	class enum_player_weixin
	{
		const e_digitid = 'digitid';//玩家唯一id
	    const e_open_id = 'open_id';//微信open_id
	    const e_areaid = 'areaid';//区号
	    const e_create_time = 'create_time';//绑定时间
	    const e_get_award_time = 'get_award_time';//领取奖励时间
	}

	class enum_tbl_user
	{
		const e_id = 'id';//玩家唯一id
	    const e_account = 'account';//帐号
	    const e_areaid = 'areaid';//区号
	    const e_old_area_id = 'old_area_id';//创建帐号时所在的区号
	    const e_valid = 'valid';//1表示有效，0表示无效
		const e_activetime = 'id';//创建时间
	    const e_platform = 'platform';//平台
	    const e_dru1 = 'dru1';//次日上线记录
	    const e_dru3 = 'dru3';//第三日上线记录
	    const e_dru7 = 'dru7';//第七日上线记录
		const e_dru15 = 'dru15';//第15日上线记录
	    const e_dru30 = 'dru30';//第三十天上线记录
	}
	
	//jifen_config
	class enum_jifen_config
	{
		const e_weekday = 'weekday';//周几
	    const e_number = 'number';//周几领取时，能得到的积分数
	}
	
	//player_weixinss
	class enum_player_weixin_jifen
	{
	    const e_open_id = 'open_id';//微信open_id
	    const e_number = 'number';//积分数
	    const e_get_time = 'get_time';//获取时间
	}

	//player_weixinss
	class enum_libao_config
	{
	    const e_id = 'id';//礼包id
	    const e_pic_url = 'pic_url';//图片url
	    const e_need_jifen = 'need_jifen';//兑换该礼包所需要的积分
	}
	
	//player_weixin_cdkey
	class enum_player_weixin_cdkey
	{
	    const e_keyno = 'keyno';//领取序列号（只能使用字母+数字）
	    const e_id = 'id';//礼包id
	    const e_open_id = 'open_id';//玩家微信openid
		const e_get_time = 'get_time';//获取时间
	}
	
?>