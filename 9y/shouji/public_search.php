<?php
include_once("my_global.php");

function do_session_check() {
	$r = session_check();
	if (0 == $r) {
		echo "<script>alert('无权访问 1');</script>";
	} elseif (-1 == $r) {
		echo "<script>alert('查询开放时间是每天<早九点>到<晚十点>之间');</script>";
	} elseif (-2 == $r) {
		echo "<script>alert('系统维护中...');</script>";
	} elseif ($r > 0) {
		
	} else {
		echo "<script>alert('无权访问 2');</script>";
	}
	return $r;
}

$global_file_pri = array(
		4 => array( //"base_data_search.php" => "基本数据查询",
					"online_log_search.php" => "实时在线",
					"buy_log.php" => "元宝消耗查询",
					"chongzhi_log.php" => "充值查询",
					"chongzhi_total.php" => "充值统计",
					"get_user_data.php" => "用户信息查询",
					"get_arm_data.php" => "用户武将中心",
					"get_goods_data.php" => "道具背包",
					"get_card_data.php" => "卡背包",
					"new_player_everyday.php" => "每日注册数量",
					"create_role_info.php" => "创建角色明细",
					//"wanjiachongzhijilu.php" => "玩家详细充值记录",
					"xiaofeileixingpaihang.php" => "消费类型排行",
					"gonghui_chongzhi.php" => "公会信息",
					"gong_hui_cheng_yuan.php" => "公会成员",
					"gefuhuizong.php" => "各服汇总",
					"gefumingxi.php" => "各服明细",
					"sheng_wang_cha_xun.php" => "声望查询",
					"liquan_xiao_fei.php" => "礼券消费",
					"hui_yuan_xiao_fei_ji_lu.php" => "会员消费记录",
					"watch_system_flag.php" => "查询本区系统表"
		
		),
		
		3 => array(	//"base_data_search.php" => "基本数据查询",
					"online_log_search.php" => "实时在线",
					"buy_log.php" => "元宝消耗查询",
					"chongzhi_log.php" => "充值查询",
					"chongzhi_total.php" => "充值统计",
					"get_user_data.php" => "用户信息查询",
					//"get_arm_data.php" => "用户武将中心",
					//"get_goods_data.php" => "道具背包",
					//"get_card_data.php" => "卡背包",
					"new_player_everyday.php" => "每日注册数量",
					"create_role_info.php" => "创建角色明细",
					"xiaofeileixingpaihang.php" => "消费类型排行",
					//"gonghui_chongzhi.php" => "公会信息",
					"gefuhuizong.php" => "各服汇总",
					//"gefumingxi.php" => "各服明细",
					"sheng_wang_cha_xun.php" => "声望查询",
					"liquan_xiao_fei.php" => "礼券消费",
		),
		
		2 => array(//"base_data_search.php" => "基本数据查询",
					"online_log_search.php" => "实时在线", 
					"chongzhi_log.php" => "充值查询", 
					"buy_log.php" => "宝消耗查询",
					"get_user_data.php" => "用户信息查询", 
					"new_player_everyday.php" => "每日注册数量",
					"xiaofeileixingpaihang.php" => "消费类型排行",
					//"get_arm_data.php" => "用户武将中心", 
					//"get_goods_data.php" => "道具背包", 
					//"get_card_data.php" => "卡背包"
		),
		
		// 合作方查询
		1 => array(//"base_data_search.php" => "基本数据查询",
					"chongzhi_log.php" => "充值查询",
					"new_player_everyday.php" => "每日注册数量",
					"chongzhi_total.php" => "充值统计",
					//"online_log_search.php" => "实时在线", 
					"buy_log.php" => "元宝消耗查询",
					//"get_user_data.php" => "用户信息查询", 
					//"xiaofeileixingpaihang.php" => "消费类型排行",
					//"get_arm_data.php" => "用户武将中心", 
					//"get_goods_data.php" => "道具背包", 
					//"get_card_data.php" => "卡背包"
		),
);


// 检查是否能读取该文件
function check_can_read_my($filename){
	$r = session_check();
	foreach  ($GLOBALS["global_file_pri"] as $key => $items) {
		if ($key == $r) {
			foreach ($items as $key => $item) {
				if ($filename == $key) {
					return 1;
				}
			}
		}
		//echo $key . $items[0] . "</p>";
	}
	
	return 0;
}


// 公共头部
function public_head(){
	$r = session_check();
	echo <<<EOT
		<div id="header">
EOT;
	foreach  ($GLOBALS["global_file_pri"] as $key => $items) {
		if ($key == $r) {
			foreach ($items as $key => $item) {
				echo "<a href=\"" . $key . "\">" . $item . "</a>   ";			
			}
		}
		//echo $key . $items[0] . "</p>";
	}
	echo <<<EOT
		</div>
		<p></p>
EOT;
}

// 公共尾部
function public_tail(){
	echo <<<EOT
	<div id="footer"><a href="index.php">返回登录</a></div>
EOT;
}

// 读取区配置
function get_area_config(&$area_no_name, &$area_name_no) {
	$link = my_connect_mysql($GLOBALS["global_db"]["game_config"]);
	$query = "select area_no, area_name from `area_table` ";
	$result = mysql_query($query);
	while ($line = mysql_fetch_array($result, MYSQL_NUM)) {
		$area_no_name[$line[0]] = $line[1];
		$area_name_no[$line[1]] = $line[0];
	}
	mysql_free_result($result);
}

// 读取平台配置
function get_ping_tai_config(&$ping_tai_no_name, &$ping_tai_name_no) {
	$link = my_connect_mysql($GLOBALS["global_db"]["game_config"]);
	$query = "SELECT ping_tai_id, ping_tai_name  FROM ping_tai;  ";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_NUM)) {
		$ping_tai_no_name[$line[0]] = $line[1];
		$ping_tai_name_no[$line[1]] = $line[0];
	}
	mysql_free_result($result); 
}

// 读取好友(合作公会配置)
function get_friend_config(&$friend_no_name, &$friend_name_id) {
	$link = my_connect_mysql($GLOBALS["global_db"]["game_config"]);
	$query = "select friend_id, friend_name from `friend` ";
	$result = mysql_query($query);
	while ($line = mysql_fetch_array($result, MYSQL_NUM)) {
		$friend_no_name[$line[0]] = $line[1];
		$friend_name_id[$line[1]] = $line[0];
	}
	mysql_free_result($result); 
}

// session 查询
function session_check(){
	session_start();
	if (isset($_SESSION["admin"]) && $_SESSION["admin"]==true) {
		//echo "login success ";
	} else {
		$_SESSION['admin']=false;
		return 0;
	}
	///////////////////////////////////////////////////////////////////
	
	// 非超级管理员的查询时间是早9点到晚10点
	$user_priv = $_SESSION['user_priv'];
	//echo $user_priv  . "</p>";
	/*
	if ($user_priv < g_admin_user) {
	    $localtime_t = localtime(time());
		$localtime_assoc = localtime(time(), true);
	    //print_r($localtime_t);
		#print_r($localtime_assoc);
		//echo $localtime_assoc["tm_hour"] . "</p>";
		if ($localtime_assoc["tm_hour"] < 9 or $localtime_assoc["tm_hour"] >= 22) {
			$_SESSION['admin']=false;
			//die("查询开放时间是每天<早九点>到<晚十点>之间");
			return -1;
		}
	}
	*/
	
	
	if (g_now_debug == 1 && $user_priv != g_supper_user) {
		if ($user_priv < g_supper_user) {
			echo "系统维护中...";
			return -2;
		}
	}

	return $user_priv;
}


// 查询今天消耗的函数 
function get_today_all($friend_no) {
	$r = 0;
	$query = "select sum(t1.amt)as `sum_yuanbao`  													
	from business_r as t1, cooperation as t2  													
	where t1.order_success=0 and t1.has_check=100 and t1.playerid=t2.playerid and t2.relation = $friend_no and to_days(t1.activetime)=to_days(now()); ";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	if ($line) {
		$r = $line["sum_yuanbao"];
	}
	// 释放结果集
	mysql_free_result($result);
	return $r;
}

// 查询昨天消耗的函数
function get_yesterday_all($friend_no) {
	$r = 0;
	$query = "select sum(t1.amt)as `sum_yuanbao`  													
	from business_r as t1, cooperation as t2  													
	where t1.order_success=0 and t1.has_check=100 and t1.playerid=t2.playerid and t2.relation = $friend_no and (to_days(now())-to_days(t1.activetime))=1; ";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	if ($line) {
		$r = $line["sum_yuanbao"];
	}
	// 释放结果集
	mysql_free_result($result);
	return $r;
}

// 查询本月的消耗
function get_cur_month_all($friend_no){
	$r = 0;
	$query = "select sum(t1.amt)as `sum_yuanbao`  													
	from business_r as t1, cooperation as t2  													
	where t1.order_success=0 and t1.has_check=100 and t1.playerid=t2.playerid and t2.relation = $friend_no and DATE_FORMAT(t1.activetime, '%Y%m') = DATE_FORMAT(CURDATE(), '%Y%m'); ";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	if ($line) {
		$r = $line["sum_yuanbao"];
	}
	// 释放结果集
	mysql_free_result($result);
	return $r;
}

// 查询上个月
function get_last_month_all($friend_no){
	$r = 0;
	$query = "select sum(t1.amt)as `sum_yuanbao`  													
	from business_r as t1, cooperation as t2  													
	where t1.order_success=0 and t1.has_check=100 and t1.playerid=t2.playerid and t2.relation = $friend_no and
	 date_format(t1.activetime,'%Y-%m')=date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),'%Y-%m') ; ";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	if ($line) {
		$r = $line["sum_yuanbao"];
	}
	// 释放结果集
	mysql_free_result($result);
	return $r;
}

// 查询目前该公会总人数
function get_assn_all_number($friend_no){
	$r = 0;
	$query = " select count(*) as count_all from cooperation where relation = $friend_no ";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	if ($line) {
		$r = $line["count_all"];
	}
	// 释放结果集
	mysql_free_result($result);
	return $r;
}

// 累计元宝消耗
function get_all($friend_no){
	$r = 0;
	$query = "select sum(t1.amt)as `sum_yuanbao`  													
	from business_r as t1, cooperation as t2  													
	where t1.order_success=0 and t1.has_check=100 and t1.playerid=t2.playerid and t2.relation = $friend_no ; ";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	if ($line) {
		$r = $line["sum_yuanbao"];
	}
	// 释放结果集
	mysql_free_result($result);
	return $r;
}

function get_today_all_ex($friend_no) {
	$r = 0;
	$query = "select sum(t1.money)as `sum_money`  													
	from player_chongzhi as t1, cooperation as t2  													
	where t1.has_add_to_game=1  and t1.playerid=t2.playerid and t2.relation = $friend_no and to_days(t1.activetime)=to_days(now()); ";
	$result = @mysql_query($query) or die('Query failed: ' . mysql_error());
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	if ($line) {
		$r = $line["sum_money"];
	}
	// 释放结果集
	mysql_free_result($result);
	return $r;
}

function get_yesterday_all_ex($friend_no) {
	$r = 0;
	$query = "select sum(t1.money)as `sum_money`  													
	from player_chongzhi as t1, cooperation as t2  													
	where t1.has_add_to_game=1  and t1.playerid=t2.playerid and t2.relation = $friend_no and (to_days(now())-to_days(t1.activetime))=1; ";
	$result = @mysql_query($query) or die('Query failed: ' . mysql_error());
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	if ($line) {
		$r = $line["sum_money"];
	}
	// 释放结果集
	mysql_free_result($result);
	return $r;
}

function get_cur_month_all_ex($friend_no) {
	$r = 0;
	$query = "select sum(t1.money)as `sum_money`  													
	from player_chongzhi as t1, cooperation as t2  													
	where t1.has_add_to_game=1  and t1.playerid=t2.playerid and t2.relation = $friend_no and DATE_FORMAT(t1.activetime, '%Y%m') = DATE_FORMAT(CURDATE(), '%Y%m'); ";
	$result = @mysql_query($query) or die('Query failed: ' . mysql_error());
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	if ($line) {
		$r = $line["sum_money"];
	}
	// 释放结果集
	mysql_free_result($result);
	return $r;
}

function get_last_month_all_ex($friend_no) {
	$r = 0;
	$query = "select sum(t1.money)as `sum_money`  													
	from player_chongzhi as t1, cooperation as t2  													
	where t1.has_add_to_game=1  and t1.playerid=t2.playerid and t2.relation = $friend_no and 
	 date_format(t1.activetime,'%Y-%m')=date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),'%Y-%m') ; ";
	$result = @mysql_query($query) or die('Query failed: ' . mysql_error());
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	if ($line) {
		$r = $line["sum_money"];
	}
	// 释放结果集
	mysql_free_result($result);
	return $r;
}

function get_assn_all_number_ex($friend_no){
	$r = 0;
	$query = " select count(*) as count_all from cooperation where relation = $friend_no ";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	if ($line) {
		$r = $line["count_all"];
	}
	// 释放结果集
	mysql_free_result($result);
	return $r;
}

// 公会累计元宝消耗
function get_all_ex($friend_no){
	$r = 0;
	$query = "select sum(t1.money)as `sum_money` 
	 from player_chongzhi as t1, cooperation as t2 where t1.has_add_to_game=1  and t1.playerid=t2.playerid and t2.relation = $friend_no ; ";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	if ($line) {
		$r = $line["sum_money"];
	}
	// 释放结果集
	mysql_free_result($result);
	return $r;
}

// 查某个公会的基本信息
function normal_select_ex($friend_no){
	$link = my_connect_mysql($GLOBALS["global_db"]["chongzhi"]);
	$arr = array();
	$arr['get_today_all'] = get_today_all_ex($friend_no);
	$arr['get_yesterday_all'] = get_yesterday_all_ex($friend_no);
	$arr['get_cur_month_all'] = get_cur_month_all_ex($friend_no);
	$arr['get_last_month_all'] = get_last_month_all_ex($friend_no);
	$arr['get_assn_all_number'] = get_assn_all_number_ex($friend_no);
	$arr['get_all'] = get_all_ex($friend_no);
	return $arr;
}

// 普通公会管理员能查询的
function normal_select($user_name, $friend_no){
	$link = my_connect_mysql($GLOBALS["global_db"]["chongzhi"]);
	// 输出常用结果
	echo "今日元宝消耗:" . get_today_all($friend_no) . "</p>";
	echo "昨天元宝消耗:" . get_yesterday_all($friend_no) . "</p>";
	echo "本月元宝消耗:" .  get_cur_month_all($friend_no) . "</p>";
	echo "上月元宝消耗:" .  get_last_month_all($friend_no) . "</p>";
	echo "工会总人数:" . get_assn_all_number($friend_no) . "</p>";
	echo "累计总消耗:" . get_all($friend_no) . "</p>";
	
	$_SESSION["cur_select_hui_yuan_friend_no"] = $friend_no;
	
	echo <<<EOT
		<form action=gong_hui_cheng_yuan.php method=POST> 
			<input type=submit value="查看会员记录"> 
		</form>
EOT;

}

// 查询某个公会当前的激活信息
function gong_hui_cheng_yuan_select($friend_no, $page) {
	$link = my_connect_mysql($GLOBALS["global_db"]["chongzhi"]);
	$r = 0;
	$pagenumber = 15;
	$maxpage = 0;
	$query = "SELECT count(*)/$pagenumber as `pagenumber` FROM `cooperation` where `relation`=$friend_no ;";
	echo $query;
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	if ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$maxpage = $line['pagenumber'];
		if ($maxpage < 1){
			$maxpage = 1;
		}
		$intv = intval($maxpage);
		if ($maxpage > $intv)
			$maxpage = $intv+1;
		else 
			$maxpage = $intv;
	}
	mysql_free_result($result);
	
	
	$begin_idx = ($page-1)*$pagenumber;
	$query = <<<EOT
	SELECT t1.`playername`, t1.`activetime` ,  t2.`area_name` , t1.`desc`, t1.`cdkey` 
FROM `cooperation`as t1, a_game_config.area_table as t2  
WHERE t1.`relation`=$friend_no and t1.active_area = t2.area_no LIMIT $begin_idx, $pagenumber ;
EOT;
	//echo $query . "</p>"; 
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	$num = 1;

 
	echo "<table border=\"1\" cellpadding=\"10\" align=\"center\" >\n";
	echo <<<EOT
 	  <tr>
		  <th align="left">玩家名称</th>
		  <th align="left">激活时间</th>
		  <th align="left">激活区</th>
		  <th align="left">描述</th>
		  <th align="left">激活码</th>
 	  </tr>
EOT;
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		echo "\t<tr>\n";
	    foreach ($line as $col_value) {
	    	if (isset($col_value)) {
	    		//$col_value = iconv("UTF-8", "gbk", $col_value);
	        	echo "<td  align=\"left\">$col_value</td>\n";
	    	}
	        else
	        	echo "<td  align=\"left\">null</td>\n"; 
	    }
	    echo "\t</tr>\n";    
	    $num++;  
    }
    echo "</table>\n";
    
    if ($num > 1)
    	echo "当前第 ". $page . "页";
    else
    	echo "已经是最后一页了";

    $str_now_t = date('Y-m-d',time());
    $str_now_t1 = date('Y-m-d',time()+24*3600);
    
	echo <<<EOT
    <form action=gong_hui_cheng_yuan.php method=POST> 输入页码 (1, $maxpage)
		<input type=text name=page_no><br>
		<input type=submit value="查看">
	</form>
	<a href="test1.php">返回</a><P>
	
	<form action=hui_yuan_xiao_fei_ji_lu.php method=POST> 查询消费记录,输入要玩家名称 
		<input type=text name=target_player_name><br>
		<script language="javascript" type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
		<input type="text" class="Wdate" id="d4331" name=s_begin_time onfocus="WdatePicker({minDate:'#F{\$dp.\$D(\\'d4332\\',{M:0,d:-7});}', maxDate:'#F{\$dp.\$D(\\'d4332\\',{M:0,d:-1})||\$dp.\$DV(\\'2020-4-3\\',{M:-3,d:-2})}'})" value="$str_now_t"/>
		<input type="text" class="Wdate" id="d4332" name=s_end_time onfocus="WdatePicker({minDate:'#F{\$dp.\$D(\\'d4331\\',{M:0,d:1});}',maxDate:'%y-%M-{%d+1}'})" value="$str_now_t1"/>
		<input type=submit value="查看" >
	</form>
EOT;

	// 释放结果集
	mysql_free_result($result);
}

// 查询公会某成员的消费记录
function gong_hui_cheng_yuan_xiao_fei_log($friend_no, $playername, $begin_time, $end_time){
	$link = my_connect_mysql($GLOBALS["global_db"]["chongzhi"]);
	//$playername = iconv("gbk", "UTF-8", $playername);
	$begin_time = $begin_time . " 00:00:00";
	$end_time = $end_time . " 00:00:00";
	
	$gameconfig_name = $GLOBALS["global_db"]["game_config"]["database"];
	
	$query = <<<EOT
	SELECT t1.playername, t2.orderid, t3.area_name, t2.amt, t2.payitem, t2.tdesc, t2.activetime  
FROM `cooperation` as t1, business_r as t2, $gameconfig_name.area_table as t3  
WHERE t2.order_success=0 
and t1.relation='$friend_no' and t1.playerid=t2.playerid and t1.playername='$playername'     
and t2.zoneid=t3.area_no 
and t2.activetime>='$begin_time' and t2.activetime<'$end_time';
EOT;
	//echo $query . "</p>"; 
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	
	//echo <<<EOT   and t2.has_check=100 
	//<a href="gong_hui_cheng_yuan.php">返回</a><P>
//EOT;

	echo "<table border=\"1\" cellpadding=\"10\" align=\"center\" >\n";
	echo <<<EOT
 	  <tr>
		  <th align="left">玩家名称</th>
		  <th align="left">订单号</th>
		  <th align="left">区名称</th>
		  <th align="left">消费元宝</th>
		  <th align="left">购买描述(商品编号*单价*数量)</th>
		  <th align="left">商品描述</th>
		  <th align="left">购买时间</th>
 	  </tr>
EOT;

	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		echo "\t<tr>\n";
	    foreach ($line as $col_value) {
	    	if (isset($col_value)) {
	    		//$col_value = iconv("UTF-8", "gbk", $col_value);
	        	echo "<td  align=\"left\">$col_value</td>\n";
	    	}
	        else
	        	echo "<td  align=\"left\">null</td>\n"; 
	    }
	    echo "\t</tr>\n";    
    }
    echo "</table>\n";
	//echo <<<EOT
	//<a href="gong_hui_cheng_yuan.php">返回</a><P>
//EOT;
    // 释放结果集
	mysql_free_result($result);
}

// 查询消费情况(全区收入)
function cha_xun_shou_ru($areaname, $begin_time, $end_time){
	$link = my_connect_mysql($GLOBALS["global_db"]["chongzhi"]);
	$begin_time = $begin_time . " 00:00:00";
	$end_time = $end_time . " 00:00:00";
	if ($areaname == "全区") {
		$query = <<<EOT
		SELECT SUM(amt) FROM `business_r` where order_success=0 and has_check=100 and txevent<>2 and 
		activetime>='$begin_time' and activetime < '$end_time';;
EOT;
		//echo $query . "</p>";
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		$line = mysql_fetch_array($result, MYSQL_NUM);
		$r_sum_amt1 = 0;
		if ($line) {
			$r_sum_amt1 = $line[0];
		}
		mysql_free_result($result);
		
		$query = <<<EOT
		SELECT SUM(fee) FROM `business_r` where order_success=0 and has_check=100 and txevent=2 and 
		activetime>='$begin_time' and activetime < '$end_time';;
EOT;
		//echo $query . "</p>";
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		$line = mysql_fetch_array($result, MYSQL_NUM);
		$r_sum_amt2 = 0;
		if ($line) {
			$r_sum_amt2 = $line[0];
		}
		mysql_free_result($result);
		
		echo "累计消费元宝: " . ($r_sum_amt1+$r_sum_amt2);
		echo <<<EOT
		<a href="test1.php">返回</a><P>
EOT;
	} else {
		$link = my_connect_mysql($GLOBALS["global_db"]["game_config"]);
		//$areaname = iconv("gbk", "UTF-8", $areaname);
		$query = "select `area_no` from `area_table` where `area_name`='$areaname' ";
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		$line = mysql_fetch_array($result, MYSQL_NUM);
		$area_no=0;
		if ($line){
			$area_no = $line[0]; 
		}
		if ($area_no < 1) {
			echo <<<EOT
			<a href="test1.php">返回</a><P>
EOT;
			die("找不到该区");
		}
		mysql_free_result($result);

		$link = my_connect_mysql($GLOBALS["global_db"]["chongzhi"]);
		$query = <<<EOT
		SELECT SUM(amt) FROM `business_r` where order_success=0 and has_check=100 and txevent<>2 and 
		activetime>='$begin_time' and activetime < '$end_time' and `zoneid`=$area_no;
EOT;
		//echo $query . "</p>";
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		$line = mysql_fetch_array($result, MYSQL_NUM);
		$r_sum_amt1 = 0;
		if ($line) {
			$r_sum_amt1 = $line[0];
		}
		mysql_free_result($result);
		
		$query = <<<EOT
		SELECT SUM(fee) FROM `business_r` where order_success=0 and has_check=100 and txevent=2 and 
		activetime>='$begin_time' and activetime < '$end_time' and `zoneid`=$area_no;
EOT;
		//echo $query . "</p>";
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		$line = mysql_fetch_array($result, MYSQL_NUM);
		$r_sum_amt2 = 0;
		if ($line) {
			$r_sum_amt2 = $line[0];
		}
		mysql_free_result($result);
		
		echo "累计消费元宝: " . ($r_sum_amt1+$r_sum_amt2);
		echo <<<EOT
		<a href="test1.php">返回</a><P>
EOT;
	}
}

// 查询菜单
function select_enum($user_priv) {
	if ($user_priv >= 3) {
		$str_now_t = date('Y-m-d',time());
		$str_now_t1 = date('Y-m-d',time()+24*3600);
		$link = my_connect_mysql($GLOBALS["global_db"]["game_config"]);
		$query = "select friend_id from `friend` ";
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		echo "<form action=admin_gonghui_search.php method=POST> 选择公会id";
		echo "<select name=gong_hui_id>";
		while ($line = mysql_fetch_array($result, MYSQL_NUM)) {
			echo "<option >$line[0]";
		}
		mysql_free_result($result);
		echo <<<EOT
			</select>
			<input type=submit>
		</form>
EOT;
		$link = my_connect_mysql($GLOBALS["global_db"]["game_config"]);
		$query = "select area_name from `area_table` ";
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		echo "<form action=cha_xun_shou_ru.php method=POST> 查询区的收入情况";
		echo "<select name=sel_area_name>";
		echo "<option selected>全区";
		while ($line = mysql_fetch_array($result, MYSQL_NUM)) {
			//$col_value = iconv("UTF-8", "GBK", $line[0]);
			echo "<option >$col_value";
		}
		mysql_free_result($result);
		echo <<<EOT
			</select>
			<script language="javascript" type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
			<input type="text" class="Wdate" name=s_begin_time onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',minDate:'2013-01-01 00:00:00',maxDate:'2020-01-01 00:00:00'})" value="$str_now_t"/>
			<input type="text" class="Wdate" name=s_end_time onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',minDate:'2013-01-01 00:00:00',maxDate:'2020-01-01 00:00:00'})" value="$str_now_t1"/>
			<input type=submit>
		</form>
EOT;

		echo <<<EOT
		<form action=/cgi-bin/post-query method=POST> 查询区游戏常规数据
			<input type=submit value="进入">
		</form>
		
		<form action=/cgi-bin/post-query method=POST> 自定义查询
			<input type=submit value="进入">
		</form>
EOT;
	} else if ($user_priv == 2) {
		echo <<<EOT
		<form action=/cgi-bin/post-query method=POST> 查询区游戏常规数据
			<input type=submit value="进入">
		</form>
		
		<form action=/cgi-bin/post-query method=POST> 自定义查询
			<input type=submit value="进入">
		</form>
EOT;
	} else {
		normal_select($_SESSION["user_name"], $_SESSION["friend_no"]);
	}
}

// 查询元宝的消费情况
function get_buy_log($cur_page, $page_size, $area_no, $player_name, $buy_type,
		 $goods_id, $begin_time,  $end_time, $ping_tai_id, $user_priv){
	
	$arr = array();
	if ($area_no < 1) {
		return array(0=>"", 1=>$arr);
	}

	$link = my_connect_mysql($GLOBALS["global_db"]["chongzhi"]);
	$page_count = 1;
	{
		$query = "select count(*) ";
		$query = $query . " from buy_log, player_base where buy_log.playerid = player_base.playerid and buy_log.zoneid=$area_no ";
		if ($player_name) {
			$query = $query . " and `playername`= '$player_name' ";
		}
		if ($buy_type == "不限") {
			
		} else if ($buy_type == "购买道具") {
			$query = $query . " and `txevent`= 1 ";
		} else if ($buy_type == "购买功能") {
			$query = $query . " and `txevent`= 0 ";
		} else if ($buy_type == "寄售交易") {
			$query = $query . " and `txevent`= 2 ";
		}
		
		if ($goods_id) {
			$query = $query . " and `goodsid`=$goods_id ";
		}
		
		$query = $query . " and activetime>='$begin_time' and activetime < '$end_time' " ;
		if (g_supper_user != $user_priv && g_admin_user != $user_priv) {// 不是高级管理员
			$query = $query . " and `ping_tai`=$ping_tai_id ";
		}
		$page_count = get_page_link($cur_page, $page_size, $query);
	}
	
	$query = "select `playername`, `txevent`, `amt`, `price`, `number`, `armid`, `goodsid`, `goodskind`, `goodsname`, `desction`, `activetime` ";
	$query = $query . " from buy_log, player_base where buy_log.playerid = player_base.playerid and buy_log.zoneid=$area_no ";
	
	if ($player_name) {
		$query = $query . " and `playername`= '$player_name' ";
	}
	if ($buy_type == "不限") {
		
	} else if ($buy_type == "购买道具") {
		$query = $query . " and `txevent`= 1 ";
	} else if ($buy_type == "购买功能") {
		$query = $query . " and `txevent`= 0 ";
	} else if ($buy_type == "寄售交易") {
		$query = $query . " and `txevent`= 2 ";
	}
	
	if ($goods_id) {
		$query = $query . " and `goodsid`=$goods_id ";
	}
	
	$query = $query . " and activetime>='$begin_time' and activetime < '$end_time' " ;
	if (g_supper_user != $user_priv && g_admin_user != $user_priv) {// 不是高级管理员
		$query = $query . " and `ping_tai`=$ping_tai_id ";
	}
	$query = $query . " order by activetime desc ";
	$query = $query . " limit " . ($cur_page-1)*$page_size . "," . $page_size;
	
	//echo $query . "</p>";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_NUM)) {
		$arr[] = $line;
    }
	mysql_free_result($result);

	return array(0=>$page_count, 1=>$arr); 
}

// 查询个人或全部人的充值情况
function get_chongzhi_log($cur_page, $page_size, $area_no, $chongzhi_log_player_name, $begin_time, $end_time, $pingtai_no) {	
	// 先通过玩家名称,查询到玩家的id,
	$playerid = 0;
	if ($chongzhi_log_player_name){
		$link = my_connect_mysql($GLOBALS["global_db"]["chongzhi"]);
		$query = "select playerid from player_base where playername='" . $chongzhi_log_player_name . "' " ;
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		$line = mysql_fetch_array($result, MYSQL_NUM);
		if ($line) {
			$playerid = $line[0];
		}
		mysql_free_result($result);
	}
	
	$arr = array();
	
	$link = my_connect_mysql($GLOBALS["global_db"]["chongzhi"]);
	$page_count = 1;
	{
		$query = "select count(*) ";
		$query = $query . " from player_chongzhi  where  ";
		$query = $query . " activetime>='$begin_time' and activetime < '$end_time' " ;
		
		if ($area_no > 0) {
			$query = $query . " and area_no=" . $area_no;
		}
		if ($playerid > 0) {
			$query = $query . " and playerid=" . $playerid . " ";
		}
		//要查询某个平台的数据
		$query = $query . " and player_chongzhi.ping_tai = " . $pingtai_no;
		
		// echo $query . "</p>";
		$page_count = get_page_link($cur_page, $page_size, $query);
	}
	
	$query = "select `area_no`, t2.`playername`, `money`, `yuanbao`, `activetime`, `has_add_to_game`, `successtime`, `orderid` ";
	$query = $query . " from player_chongzhi as t1, player_base as t2  where  ";
	$query = $query . " activetime>='$begin_time' and activetime < '$end_time' and t1.playerid=t2.playerid " . " and t1.ping_tai = " . $pingtai_no;
	
	if ($area_no > 0) {
		$query = $query . " and area_no=" . $area_no;
	}
	if ($playerid > 0) {
		$query = $query . " and t1.playerid=" . $playerid . " ";
	}else {//如果不是查询某个玩家的数据，那么应该是要查询某个平台的数据
		$query = $query . " and t1.ping_tai = " . $pingtai_no;
	}
	
	$query = $query . " order by activetime desc ";
	$query = $query . " limit " . ($cur_page-1)*$page_size . "," . $page_size;
	
	//echo $query  . "</p>";
	
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_NUM)) {
		$arr[] = $line;
    }
	mysql_free_result($result);

	return array(0=>$page_count, 1=>$arr); 
}

// 统计目前累计成功充值
function get_all_total_money($pingtai_no){
	$link = my_connect_mysql($GLOBALS["global_db"]["chongzhi"]);
	$query = "select sum(`money`) from player_chongzhi where `has_add_to_game`=1 ";
	$query = $query. " and player_chongzhi.ping_tai = " . $pingtai_no;
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	
	$total_money = 0;
	if ($line = mysql_fetch_array($result, MYSQL_NUM)) {
		$total_money = $line[0];
	}
	mysql_free_result($result); 
	return $total_money;
}

// 统计各区,或全局今天累计充值
function get_today_total_money($area_no, $pingtai_no){
	
	
	$link = my_connect_mysql($GLOBALS["global_db"]["chongzhi"]);
	$query = "select area_no, sum(`money`)as today_chongzhi from player_chongzhi where  to_days(activetime)=to_days(now()) and `has_add_to_game`=1  ";
	$query = $query. " and player_chongzhi.ping_tai = " . $pingtai_no;
	if ($area_no > 0) {
		$query = $query . " and area_no=" . $area_no;	
	}
	$query = $query . " group by area_no "; 
	
	//echo $query;
	
	$result = mysql_query($query);
	$arr = array();
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$arr[] = $line;
	}
	mysql_free_result($result); 
	return $arr;
}

// 统计各区,每个月累计充值
function get_month_total_money($area_no, $sel_year_month, $pingtai_no) {
	
	$link = my_connect_mysql($GLOBALS["global_db"]["chongzhi"]);
	$query = "select area_no, sum(`money`) as month_chongzhi from player_chongzhi where  EXTRACT(YEAR_MONTH FROM activetime)=$sel_year_month and `has_add_to_game`=1 ";
	$query = $query. " and player_chongzhi.ping_tai = " . $pingtai_no;
	
	if ($area_no > 0) {
		$query = $query . " and area_no=" . $area_no;	
	}
	$query = $query . " group by area_no "; 
	//echo $query;

	$result1 = mysql_query($query) or die('Query failed: ' . mysql_error());
	$arr = array();
	while ($line = mysql_fetch_array($result1, MYSQL_ASSOC)) {
		$arr[] = $line;
	}
	mysql_free_result($result1); 
	return $arr;
}

// 返回结果的总页数量
function get_page_link($cur_page, $page_size, $select_count_sql) {
	//每页数量
	$result = mysql_query($select_count_sql);
	$line = mysql_fetch_array($result, MYSQL_NUM);
	$amount = 0;
	if ($line){
		$amount = $line[0]; 
	}
	mysql_free_result($result);

	// 记算总共有多少页
	$page_count = 0;
	if( $amount ){
		if( $amount < $page_size ){
			$page_count = 1; //如果总数据量小于$PageSize，那么只有一页 
		}
		if( 0 != $page_size && $amount % $page_size ){ //取总数据量除以每页数的余数
			$page_count = (int)($amount / $page_size) + 1; //如果有余数，则页数等于总数据量除以每页数的结果取整再加一
		} else if (0 != $page_size) {
			$page_count = $amount / $page_size; //如果没有余数，则页数等于总数据量除以每页数的结果
		}
	} else {
		$page_count = 0;
	}
	
	return $page_count;
}

// 查询某个区某个时间段里的在线人数
function get_online_log($cur_page, $page_size, $area_no, $begin_time, $end_time){
	$link = my_connect_mysql($GLOBALS["global_db"]["game_config"]);
	
	$arr = array();
	if ($area_no < 1) {
		return array(0=>"", 1=>$arr);
	}

	$link = my_connect_mysql($GLOBALS["global_db"]["namedb"]);
	
$query = <<<EOT
	SELECT  count(*) FROM `online_log` WHERE  `area_no`=$area_no and 
	activetime>='$begin_time' and activetime < '$end_time'  ;
EOT;
	$page_count = get_page_link($cur_page, $page_size, $query);
	
	$query = <<<EOT
	SELECT  total_number, activetime FROM `online_log` WHERE  `area_no`=$area_no and 
	activetime>='$begin_time' and activetime < '$end_time' ORDER BY activetime desc  
EOT;
	$query = $query . "limit " . ($cur_page-1)*$page_size . "," . $page_size;
	
	//echo $query . "</p>";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_NUM)) {
		$arr[] = $line;
    }
	mysql_free_result($result);
	
	return array(0=>$page_count, 1=>$arr); 
}


// 查询system_flag表
function get_system_flag_table($area_no) {
	$arr = array();
	if ($area_no < 1) {
		return array(0=>"", 1=>$arr);
	}
	$link = my_connect_mysql($GLOBALS["global_db"]["gamedb"][$area_no]);
	
$query = <<<EOT
	SELECT  count(*) FROM `system_flag`
EOT;
	$page_count = get_page_link($cur_page, $page_size, $query);
	
	$query = <<<EOT
	SELECT  `flag_key`, `int_val1`, `activetime`, `desc` FROM `system_flag`;
EOT;

	//echo $query . "</p>";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_NUM)) {
		$arr[] = $line;
    }
	mysql_free_result($result);
	
	return array(0=>$page_count, 1=>$arr); 
}

// 查询每日新增人数
function get_mei_ri_xin_zeng_ren_shu($areaname, $begin_time, $end_time){
	$begin_time = $begin_time . " 00:00:00";
	$end_time = $end_time . " 00:00:00";
	$link = my_connect_mysql($GLOBALS["global_db"]["game_config"]);
	
	//$areaname = iconv("gbk", "UTF-8", $areaname);
	$query = "select `area_no` from `area_table` where `area_name`='$areaname' ";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	$line = mysql_fetch_array($result, MYSQL_NUM);
	$area_no=0;
	if ($line){
		$area_no = $line[0]; 
	}
	mysql_free_result($result);
	$arr = array();
	if ($area_no < 1) {
		return $arr; 
	}

	$link = my_connect_mysql($GLOBALS["global_db"]["namedb"]);
	$query = <<<EOT
	SELECT  areaid, DATE_FORMAT(`activetime`, '%Y-%m-%d')as `day`, COUNT(*)as number FROM `playerid` WHERE  `areaid`=$area_no and 
	activetime>='$begin_time' and activetime < '$end_time' GROUP BY `day` ;
EOT;
	//echo $query . "</p>";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$arr[] = $line;
    }
	mysql_free_result($result);
	return $arr; 
}

// 取得当前全区登陆人数
function get_all_deng_lu_ren_shu(){
	$link = my_connect_mysql($GLOBALS["global_db"]["logindb"]);
	$query = "select count(*) from `tblaccount` where DATE_FORMAT(loginTimes,'%Y-%m-%d')=CURDATE()";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	$line = mysql_fetch_array($result, MYSQL_NUM);
	$deng_lu_ren_shu=0;
	if ($line){
		$deng_lu_ren_shu = $line[0]; 
	}
	return $deng_lu_ren_shu;
}

// 取得玩家基础数据
function get_base_user_data($area_id, $target_player_name) {
	//print_r($GLOBALS["global_db"]["gamedb"][$area_id]);
	$link = my_connect_mysql($GLOBALS["global_db"]["gamedb"][$area_id]);
	if (! $link) {
		return array();
	}
	
	$query = <<<EOT
	SELECT `playername`, `exp`, `titleid`, `Qyuanbao`, `viplevel`,  `leijichongzhi`, `leijixiaofei`, `yuanbao`, `np`, `food`, `gold`, `binglizhi`, `yaohun`, `jingpo`  
	from  player0, player_exp, player_np0, player_yuanbao 
	where player0.`digitid`=player_exp.`playerid` and 
	player0.`digitid`=player_np0.`playerid` and player0.`digitid`=player_yuanbao.`player_id` 
		
EOT;
	if ($target_player_name!= '') {
		$query = $query . " and playername= '$target_player_name' ";
	}

	//echo $query . "</p>";
	$arr = array();
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_NUM)) {
		$arr[] = $line;
    }
	mysql_free_result($result);
	return $arr; 
}

// 取得玩家的武将数据
function get_arm_data_log($cur_page, $page_size, $area_id, $target_player_name){
	$link = my_connect_mysql($GLOBALS["global_db"]["gamedb"][$area_id]);
	if (! $link) {
		return array(0=>"", 1=>$arr);
	}
	
	$query = <<<EOT
	SELECT  count(*) FROM `player_base`, `player_arms0` WHERE  `player_base`.playername = '$target_player_name' and 
	`player_base`.`digitid` = `player_arms0`.`digitid` and has_delete=0 ;
EOT;
	$page_count = get_page_link($cur_page, $page_size, $query);
	
	$arr = array();
	$query = <<<EOT
	SELECT `player_arms0`.`id`, `player_arms0`.`cardid`,  `card`.`name`, `player_arms0`.`bind` , `player_arms0`.`xiuliandian`,
	`player_arms0`.`binglizhi`, `player_arms0`.`max_binglizhi`, `player_arms0`.`wu`, 
	`player_arms0`.`fang`, `player_arms0`.`minjie`, `player_arms0`.`zhi`,
	`player_arms0`.`ren`, `player_arms0`.`wu_level`, `player_arms0`.`fang_level`,
	`player_arms0`.`minjie_level`, `player_arms0`.`zhi_level`, `player_arms0`.`ren_level`,
	`player_arms0`.`exploit`, `player_arms0`.`skill_1`, `player_arms0`.`skill_2`,
	`player_arms0`.`skill_3`, `player_arms0`.`official`, `player_arms0`.`level`,
	`player_arms0`.`xin`, `player_arms0`.`exp`, `player_arms0`.`totalshuxing`   
	 FROM `player_base`, `player_arms0`, `card` WHERE  `player_base`.playername = '$target_player_name' and 
	`player_base`.`digitid` = `player_arms0`.`digitid` and has_delete=0 and  `player_arms0`.`cardid`=`card`.`id` 
EOT;
	$query = $query . " limit " . ($cur_page-1)*$page_size . "," . $page_size;
	
	//echo $query . "</p>";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_NUM)) {
		$arr[] = $line;
    }
	mysql_free_result($result);
	
	return array(0=>$page_count, 1=>$arr); 
} 

// 取得玩家的背包数据
function get_goods_data($cur_page, $page_size, $area_id, $target_player_name) {
	$link = my_connect_mysql($GLOBALS["global_db"]["gamedb"][$area_id]);
	if (! $link) {
		return array(0=>"", 1=>$arr);
	}
	
	$query = <<<EOT
	SELECT  count(*) FROM `player_base`, `player_goods0` WHERE  `player_base`.playername = '$target_player_name' and 
	`player_base`.`digitid` = `player_goods0`.`digitid` and player_goods0.goods_kind=2 and has_delete=0 ;
EOT;
	$page_count = get_page_link($cur_page, $page_size, $query);
	
	$arr = array();
	$query = <<<EOT
	select player_goods0.id, player_goods0.goods_id, item.name, player_goods0.bind, player_goods0.number from  player_goods0, item, player_base 
	where  `player_base`.`playername` = '$target_player_name' and 
	`player_base`.`digitid` = `player_goods0`.`digitid` and player_goods0.goods_kind=2 and has_delete=0 and  player_goods0.goods_id=item.id 
EOT;
	$query = $query . " limit " . ($cur_page-1)*$page_size . "," . $page_size;
	
	//echo $query . "</p>";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_NUM)) {
		$arr[] = $line;
    }
	mysql_free_result($result);
	
	return array(0=>$page_count, 1=>$arr); 
}

// 取得玩家的卡包数据
function get_cards_data($cur_page, $page_size, $area_id, $target_player_name) {
	$link = my_connect_mysql($GLOBALS["global_db"]["gamedb"][$area_id]);
	if (! $link) {
		return array(0=>"", 1=>$arr);
	}
	
	$query = <<<EOT
	SELECT  count(*) FROM `player_base`, `player_goods0` WHERE  `player_base`.playername = '$target_player_name' and 
	`player_base`.`digitid` = `player_goods0`.`digitid` and player_goods0.goods_kind=1 and has_delete=0 ;
EOT;
	$page_count = get_page_link($cur_page, $page_size, $query);
	
	$arr = array();
	$query = <<<EOT
	select player_goods0.id, player_goods0.goods_id, card.name, player_goods0.bind, player_goods0.number from  player_goods0, card, player_base 
	where  `player_base`.`playername` = '$target_player_name' and 
	`player_base`.`digitid` = `player_goods0`.`digitid` and player_goods0.goods_kind=1 and has_delete=0 and  player_goods0.goods_id=card.id 
EOT;
	$query = $query . " limit " . ($cur_page-1)*$page_size . "," . $page_size;
	
	//echo $query . "</p>";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_NUM)) {
		$arr[] = $line;
    }
	mysql_free_result($result);
	
	return array(0=>$page_count, 1=>$arr); 
}

/**
 * 返回每天新玩家
 * @param $area_id
 */
function  get_new_player_everyday($area_no, $pingtai_no, $begin_time, $end_time){
	
	$link = my_connect_mysql($GLOBALS["global_db"]["game_config"]);
	
	$arr = array();
	if ($area_no < 1) {
		return array(1=>$arr);
	}
	
	
	if ($pingtai_no < 0) {
		return array(1=>$arr);
	}
	
	
	$link = my_connect_mysql($GLOBALS["global_db"]["namedb"]);
	$query = <<<EOT
	SELECT DATE_FORMAT(playerid.activetime,'%Y-%m-%d') as c1, COUNT(*) as c2,  COUNT(CASE WHEN playerid.areaid = '$area_no' THEN playerid.areaid END) as c3 
FROM playerid 
WHERE playerid.activetime >= '$begin_time' AND playerid.activetime <= '$end_time' AND playerid.ping_tai = '$pingtai_no'   
GROUP BY c1 order by c1 desc;
	
EOT;

	//echo $query . "</p>";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_NUM)) {
		$arr[] = $line;
    }
	mysql_free_result($result);
	
	return array(1=>$arr); 
}



/**
 * 创建玩家的信息
 * @param $area_id
 */
function  get_create_role_info($cur_page, $page_size, $area_no, $begin_time, $end_time, $start_title, $end_title)
{
	$link = my_connect_mysql($GLOBALS["global_db"]["game_config"]);
	
	$arr = array();
	if ($area_no < 1) {
		return array(0=>"", 1=>$arr);
	}
	
	$link = my_connect_mysql($GLOBALS["global_db"]["gamedb"][$area_no]);
	
	$query = <<<EOT
SELECT count(*) 
FROM player_base, player0 ,player_exp, player_yuanbao, player_zhanli 
WHERE player0.digitid = player_exp.playerid AND player0.digitid = player_yuanbao.player_id 
AND player0.digitid = player_zhanli.playerid AND player0.digitid = player_base.digitid 
AND player_base.create_time >= '$begin_time' AND player_base.create_time <= '$end_time' 
AND player_exp.titleid >='$start_title' AND player_exp.titleid <= '$end_title' 
EOT;
	$page_count = get_page_link($cur_page, $page_size, $query);
	//echo $query;
	
	$link = my_connect_mysql($GLOBALS["global_db"]["gamedb"][$area_no]);
	$query = <<<EOT
SELECT player0.account as c1, player0.digitid as c2, player0.playername c3, player_exp.titleid c4, player_yuanbao.viplevel c5, player_exp.exp c6, player_zhanli.totalzhanli c7, 
player_yuanbao.Qyuanbao as c8, player0.gold as c9, player_base.create_time as c10, player0.last_login_time as c11
FROM player_base, player0 ,player_exp, player_yuanbao, player_zhanli 
WHERE player0.digitid = player_exp.playerid AND player0.digitid = player_yuanbao.player_id 
AND player0.digitid = player_zhanli.playerid AND player0.digitid = player_base.digitid 
AND player_base.create_time >= '$begin_time' AND player_base.create_time <= '$end_time' 
AND player_exp.titleid >='$start_title' AND player_exp.titleid <= '$end_title' 
EOT;
	$query = $query . "limit " . ($cur_page-1)*$page_size . "," . $page_size;
	//echo $query;
	
	//echo $query . "</p>";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error()." $query");
	while ($line = mysql_fetch_array($result, MYSQL_NUM)) {
		$arr[] = $line;
    }
	mysql_free_result($result);
	
	return array(0=>$page_count, 1=>$arr); 
	
}


/**
 * 玩家充值记录
 * @param $area_id
 */
function  get_wanjiachongzhijilu($areaname, $begin_time, $end_time)
{
	$link = my_connect_mysql($GLOBALS["global_db"]["game_config"]);
	
	//查出区号
	//$areaname = iconv("gbk", "UTF-8", $areaname);
	$query = "select `area_no` from `area_table` where `area_name`='$areaname' ";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	$line = mysql_fetch_array($result, MYSQL_NUM);
	$area_no=0;
	if ($line){
		$area_no = $line[0]; 
	}
	mysql_free_result($result);
	$arr = array();
	$arr2 = array();
	if ($area_no < 1) {
		return array(0=>$arr, 1=>$arr2);
	}
	
	
	$link = my_connect_mysql($GLOBALS["global_db"]["chongzhi"]);
	
$query = <<<EOT
SELECT	player_chongzhi.playerid as c1, player_base.playername as c2, player_chongzhi.orderid as c3, 
player_chongzhi.money as c4, player_chongzhi.yuanbao as c5, player_chongzhi.successtime as c6
FROM player_chongzhi, player_base 
WHERE player_chongzhi.area_no = '$area_no' AND player_chongzhi.activetime >= '$begin_time' AND player_chongzhi.activetime <= '$end_time' 
AND player_chongzhi.playerid = player_base.playerid AND player_chongzhi.has_add_to_game = 1  
EOT;

	//echo $query . "</p>";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error()." $query");
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$arr[] = $line;
    }
	mysql_free_result($result);
	
	
	
	$link = my_connect_mysql($GLOBALS["global_db"]["chongzhi"]);
	
$query = <<<EOT
SELECT	SUM(player_chongzhi.money) AS c1, SUM(player_chongzhi.yuanbao) AS c2, COUNT(*) as c3, COUNT(DISTINCT player_chongzhi.playerid) as c4 
FROM player_chongzhi, player_base 
WHERE player_chongzhi.area_no = '$area_no' AND player_chongzhi.activetime >= '$begin_time' AND player_chongzhi.activetime <= '$end_time' 
AND player_chongzhi.playerid = player_base.playerid AND player_chongzhi.has_add_to_game = 1  
EOT;

	//echo $query . "</p>";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error()." $query");
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$arr2[] = $line;
    }
	mysql_free_result($result);
	
	
	
	return array(0=>$arr, 1=>$arr2);
}




/**
 * 消费类型排行
 * @param $area_id
 */
function  get_xiaofeileixingpaixing($area_no, $begin_time, $end_time, $xiaofeiconfig)
{
	$link = my_connect_mysql($GLOBALS["global_db"]["game_config"]);
	
	$arr = array();
	$arr2 = array();
	if ($area_no < 1) {
		return array(0=>$arr, 1=>$arr2);
	}
	
	
	$link = my_connect_mysql($GLOBALS["global_db"]["chongzhi"]);
	
$query = <<<EOT
SELECT buy_log.goodsname AS c1 , COUNT(*) AS c2, COUNT(DISTINCT  buy_log.playerid) AS c3, SUM(buy_log.price) AS c4
FROM buy_log
WHERE buy_log.zoneid = '$area_no' AND  buy_log.activetime >= '$begin_time' AND buy_log.activetime <= '$end_time' AND buy_log.goodskind = 2 AND txevent = 1 
GROUP BY buy_log.goodsid;
EOT;

	//echo $query . "</p>";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error()." $query");
	while ($line = mysql_fetch_array($result, MYSQL_NUM)) {
		$arr[] = $line;
    }
	mysql_free_result($result);
	
	
	
	$link = my_connect_mysql($GLOBALS["global_db"]["chongzhi"]);
	
$query = <<<EOT
SELECT buy_log.desction AS c1 , COUNT(*) AS c2, COUNT(DISTINCT  buy_log.playerid) AS c3, SUM(buy_log.price) AS c4
FROM buy_log
WHERE buy_log.zoneid = '$area_no' AND  buy_log.activetime >= '$begin_time' AND buy_log.activetime <= '$end_time' AND txevent = 0 
GROUP BY buy_log.desction;
EOT;

	//echo $query . "</p>";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error()." $query");
	while ($line = mysql_fetch_array($result, MYSQL_NUM)) {
		$arr2[] = $line;
    }
	mysql_free_result($result);
	
	
	
	return array(0=>$arr, 1=>$arr2);
}





/**
 * 各服汇总
 * @param
 */
function  get_gefuhuizong($begin_time, $end_time)
{
	$begin_time = $begin_time . " 00:00:00";
	$end_time = $end_time . " 23:59:59";
	
	$final_result = array();
	
	//1首先，查处有那些区
	$all_area = array();
	
	$link = my_connect_mysql($GLOBALS["global_db"]["game_config"]);
	
	$query = "select * from `area_table`  ";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_NUM)) {
		$all_area[] = $line;
    }
	mysql_free_result($result);
	
	//2.统计每一个区的
	foreach ($all_area as $key=>$value)
	{
		//2.1区的名字
		$area_no = $value[0];
		$area_name = $value[1];
		
		$a_result_record = array();
		$a_result_record[0] = $area_name; //区名
		
		
		//2.2角色总数
		{
			//echo $GLOBALS["global_db"]["gamedb"][$area_no];
			$link = my_connect_mysql($GLOBALS["global_db"]["gamedb"][$area_no]);
			if (! $link) {
				continue;
			}
			$query = <<<EOT
SELECT COUNT(*) 
FROM player_base 
WHERE player_base.create_time >= '$begin_time' AND player_base.create_time <= '$end_time'
EOT;
			
			$result = mysql_query($query) or die('Query failed: ' . mysql_error() . " in:" .__FILE__. " :" .__LINE__. "$query");
			$line = mysql_fetch_array($result, MYSQL_NUM);
			$num_of_player=0;
			if ($line){
				$num_of_player = $line[0]; 
			}
			mysql_free_result($result);
			
			$a_result_record[1] = $num_of_player;
		}
		
		//2.3同时最多在线人数
		{
				$link = my_connect_mysql($GLOBALS["global_db"]["namedb"]);
			$query = <<<EOT
SELECT MAX(online_log.total_number)
FROM online_log 
WHERE online_log.area_no = '$area_no' 
AND online_log.activetime >= '$begin_time' 
AND online_log.activetime <= '$end_time' 
EOT;
			
			$result = mysql_query($query) or die('Query failed: ' . mysql_error());
			$line = mysql_fetch_array($result, MYSQL_NUM);
			$max_onlien_player=0;
			if ($line){
				$max_onlien_player = $line[0]; 
			}
			mysql_free_result($result);
			
			$a_result_record[2] = $max_onlien_player;
		}
		
		
		//2.4充值相关
			{
				$link = my_connect_mysql($GLOBALS["global_db"]["chongzhi"]);
			$query = <<<EOT
SELECT COUNT(DISTINCT player_chongzhi.playerid), COUNT(*), SUM(player_chongzhi.money) 
FROM player_chongzhi 
WHERE player_chongzhi.area_no = '$area_no'
AND player_chongzhi.has_add_to_game = 1 
AND player_chongzhi.activetime >= '$begin_time' 
AND player_chongzhi.activetime <= '$end_time'  
EOT;
			
			$result = mysql_query($query) or die('Query failed: ' . mysql_error());
			$line = mysql_fetch_array($result, MYSQL_NUM);
			$chongzhirenshu=0;
			$chongzhicishu=0;
			$chongzhijie=0;
			if ($line){
				$chongzhirenshu = $line[0];
				$chongzhicishu = $line[1];
				$chongzhijie = $line[2]; 
			}
			mysql_free_result($result);
			
			$a_result_record[3] = $chongzhirenshu;
			$a_result_record[4] = $chongzhicishu;
			$a_result_record[5] = $chongzhijie;
			
		}
		
		// 一些统计
		$timestamp_begin_time = strtotime($begin_time);
		$timestamp_end_time = strtotime($end_time);
		$howManyDay = floor(($timestamp_end_time - $timestamp_begin_time + 1 ) / (3600 * 24));
		
		$pingjunrichognjine = $chongzhijie / $howManyDay;
		$a_result_record[6] = $pingjunrichognjine;
		
		$a_result_record[7] = $howManyDay;
		
		
		
		
		//最后
		$final_result[] = $a_result_record;
	}
	
	return  $final_result;

}

/**
 * 各服明细
 * @param
 */
function  get_gefumingxi($area_no, $begin_time, $end_time)
{
	set_time_limit(1000); // 由于这个函数执行时间很长,所以设置超时很长时间
	
	$begin_time = $begin_time . " 00:00:00";
	$end_time = $end_time . " 23:59:59";
	
	$link = my_connect_mysql($GLOBALS["global_db"]["game_config"]);
	
	if($area_no == 0)
		return  array();
	
	$final_result = array();
	
	$uninxtime_begintime = strtotime($begin_time);
	$uninxtime_endtime   = strtotime($end_time);
	
	while($uninxtime_begintime < $uninxtime_endtime)
	{
		//今天的开始时间
		$today_begin_time = strftime("%Y-%m-%d %H:%M:%S", $uninxtime_begintime);
		//今天的结束时间
		$today_end_time = strftime("%Y-%m-%d %H:%M:%S", $uninxtime_begintime + 24 * 3600 -1);
		
		
		$a_result_record = array();
		$a_result_record[0] = date('Y-m-d', $uninxtime_begintime);
		
		//1.DNU
		{
			$link = my_connect_mysql($GLOBALS["global_db"]["gamedb"][$area_no]);
			$query = <<<EOT
SELECT COUNT(*)
FROM player_base 
WHERE player_base.create_time >= '$today_begin_time' AND 
player_base.create_time <= '$today_end_time'
			
EOT;
			
			$result = mysql_query($query) or die('Query failed: ' . mysql_error());
			$line = mysql_fetch_array($result, MYSQL_NUM);
			$dnu=0;
			if ($line){
				$dnu = $line[0]; 
			}
			mysql_free_result($result);
			
			$a_result_record[1] = $dnu;
		}
		
		
			//2.DAU
			{
				$link = my_connect_mysql($GLOBALS["global_db"]["gamedb"][$area_no]);
			$query = <<<EOT
SELECT COUNT(DISTINCT player_loginlog.playerid)
FROM player_loginlog 
WHERE player_loginlog.login_day >= '$today_begin_time' AND 
player_loginlog.login_day <= '$today_end_time'
EOT;
			
			$result = mysql_query($query) or die('Query failed: ' . mysql_error());
			$line = mysql_fetch_array($result, MYSQL_NUM);
			$dau=0;
			if ($line){
				$dau = $line[0]; 
			}
			mysql_free_result($result);
			
			$a_result_record[2] = $dau;
		}
		
		
		//2.最高在线人数
		{
			$link = my_connect_mysql($GLOBALS["global_db"]["namedb"]);
			$query = <<<EOT
SELECT MAX(online_log.total_number)
FROM online_log 
WHERE online_log.area_no = '$area_no' 
AND online_log.activetime >= '$today_begin_time'  
AND online_log.activetime <= '$today_end_time'  
EOT;
			
			$result = mysql_query($query) or die('Query failed: ' . mysql_error());
			$line = mysql_fetch_array($result, MYSQL_NUM);
			$max_onlien_player=0;
			if ($line){
				$max_onlien_player = $line[0]; 
			}
			mysql_free_result($result);
			
			$a_result_record[3] = $max_onlien_player;
		}
		
		
		//充值相关
		{
			$link = my_connect_mysql($GLOBALS["global_db"]["chongzhi"]);
			$query = <<<EOT
SELECT COUNT(DISTINCT player_chongzhi.playerid), COUNT(*), SUM(player_chongzhi.money) 
FROM player_chongzhi 
WHERE player_chongzhi.area_no = '$area_no'
AND player_chongzhi.has_add_to_game = 1 
AND player_chongzhi.activetime >= '$today_begin_time' 
AND player_chongzhi.activetime <= '$today_end_time'  
EOT;
			$result = mysql_query($query) or die('Query failed: ' . mysql_error());
			$line = mysql_fetch_array($result, MYSQL_NUM);
			$chongzhirenshu=0;
			$chongzhicishu=0;
			$chongzhijie=0;
			if ($line){
				$chongzhirenshu = $line[0];
				$chongzhicishu = $line[1];
				$chongzhijie = $line[2]; 
			}
			mysql_free_result($result);
			
			$a_result_record[4] = $chongzhirenshu;
			$a_result_record[5] = $chongzhicishu;
			$a_result_record[6] = $chongzhijie;
			
		}
		
		//充arpru arppu pr
		{
			if ($dau !=0 )
				$a_result_record[7] = $chongzhijie / $dau;
			if ($chongzhirenshu !=0 )
				$a_result_record[8] = $chongzhijie / $chongzhirenshu;
			if ($dau !=0 )
				$a_result_record[9] = $chongzhirenshu / $dau;
			
		}
		
		//新手无操作率
		{
			$link = my_connect_mysql($GLOBALS["global_db"]["gamedb"][$area_no]);
			$query = <<<EOT
SELECT COUNT(DISTINCT player_base.digitid) AS C1, COUNT(CASE WHEN player_exp.exp <= 0 THEN player_exp.exp END) AS C2  
FROM player_base, player_exp  
WHERE player_base.digitid = player_exp.playerid  
AND player_base.create_time >= '$today_begin_time' AND player_base.create_time <= '$today_end_time'  
EOT;
			
			$result = mysql_query($query) or die('Query failed: ' . mysql_error());
			$line = mysql_fetch_array($result, MYSQL_NUM);
			$create_player_num=0;
			$create_player_num_of_no_exp=0;
			if ($line){
				$create_player_num = $line[0];
				$create_player_num_of_no_exp = $line[1];
			}
			mysql_free_result($result);
			
			if ($create_player_num != 0)
				$a_result_record[10] = $create_player_num_of_no_exp / $create_player_num;
			
		}
	
		//留存率
		{
			$link = my_connect_mysql($GLOBALS["global_db"]["gamedb"][$area_no]);
			$ciri_begin_time = date('Y-m-d', $uninxtime_begintime + 24 *3600);
			$ciri_end_time = date('Y-m-d', $uninxtime_begintime + 24 *3600 + 24 *3600 -1) ;
			
			$query = <<<EOT
SELECT COUNT(DISTINCT player_loginlog.playerid)
FROM player_loginlog 
WHERE player_loginlog.playerid IN
(SELECT DISTINCT player_base.digitid 
FROM player_base  
WHERE  player_base.create_time >= '$today_begin_time' AND player_base.create_time <= '$today_end_time' 
) AND player_loginlog.login_day >= '$ciri_begin_time' AND player_loginlog.login_day <= '$ciri_end_time'
EOT;
			
			$result = mysql_query($query) or die('Query failed: ' . mysql_error());
			$line = mysql_fetch_array($result, MYSQL_NUM);
			$ciri_liucunnum=0;
			if ($line){
				$ciri_liucunnum = $line[0];
			}
			mysql_free_result($result);
			
			if ($create_player_num != 0)
				$a_result_record[11] = $ciri_liucunnum / $create_player_num;
			
			$link = my_connect_mysql($GLOBALS["global_db"]["gamedb"][$area_no]);
			$sanri_begin_time = date('Y-m-d', $uninxtime_begintime + 24 *3600 * 2);
			$sanri_end_time = date('Y-m-d', $uninxtime_begintime + 24 *3600 * 2 + 24 *3600 -1) ;
			
			$query = <<<EOT
SELECT COUNT(DISTINCT player_loginlog.playerid)
FROM player_loginlog 
WHERE player_loginlog.playerid IN
(SELECT DISTINCT player_base.digitid 
FROM player_base  
WHERE  player_base.create_time >= '$today_begin_time' AND player_base.create_time <= '$today_end_time' 
) AND player_loginlog.login_day >= '$sanri_begin_time' AND player_loginlog.login_day <= '$sanri_end_time'
EOT;
			
			$result = mysql_query($query) or die('Query failed: ' . mysql_error());
			$line = mysql_fetch_array($result, MYSQL_NUM);
			$sanri_liucunnum=0;
			if ($line){
				$sanri_liucunnum = $line[0];
			}
			mysql_free_result($result);
			
			if ($create_player_num != 0)
				$a_result_record[12] = $sanri_liucunnum / $create_player_num;

			$link = my_connect_mysql($GLOBALS["global_db"]["gamedb"][$area_no]);
			$liuri_begin_time = date('Y-m-d', $uninxtime_begintime + 24 *3600 * 6);
			$liuri_end_time = date('Y-m-d', $uninxtime_begintime + 24 *3600 * 6 + 24 *3600 -1) ;
			
			$query = <<<EOT
SELECT COUNT(DISTINCT player_loginlog.playerid)
FROM player_loginlog 
WHERE player_loginlog.playerid IN
(SELECT DISTINCT player_base.digitid 
FROM player_base  
WHERE  player_base.create_time >= '$today_begin_time' AND player_base.create_time <= '$today_end_time' 
) AND player_loginlog.login_day >= '$liuri_begin_time' AND player_loginlog.login_day <= '$liuri_end_time'
EOT;
			
			$result = mysql_query($query) or die('Query failed: ' . mysql_error());
			$line = mysql_fetch_array($result, MYSQL_NUM);
			$liuri_liucunnum=0;
			if ($line){
				$liuri_liucunnum = $line[0];
			}
			mysql_free_result($result);
			
			if ($create_player_num != 0)
				$a_result_record[13] = $liuri_liucunnum / $create_player_num;
		}
		
		
		//平均在线时长
		{
			$link = my_connect_mysql($GLOBALS["global_db"]["gamelog"][$area_no]);
			$query = <<<EOT
SELECT SUM(playergametime.onlinetime)
FROM playergametime 
WHERE playergametime.playgamedate >= '$today_begin_time' 
AND playergametime.playgamedate <= '$today_end_time' 
EOT;
			
			$result = mysql_query($query) or die('Query failed: ' . mysql_error());
			$line = mysql_fetch_array($result, MYSQL_NUM);
			$sumofonlinetime=0;
			if ($line){
				$sumofonlinetime = $line[0];
			}
			mysql_free_result($result);
			
			if ($dau != 0)
				$a_result_record[14] = $sumofonlinetime / $dau;
			
		}
		
		$final_result[] = $a_result_record;
		$uninxtime_begintime = $uninxtime_begintime + 24 * 3600;//下一天的开始时间
	}
	
	return  $final_result;

}


/**
 * 查询声望分布
 * @param $min_exp
 * @param $max_exp
 * @param $area_name
 * @param $page_no
 */
function getShengWangFenBu($min_exp, $max_exp, $area_no, $page_no, $page_size)
{
	//页数
	$link = my_connect_mysql($GLOBALS["global_db"]["gamedb"][$area_no]);
	$query = <<<EOT
SELECT CEIL(COUNT(*) / $page_size) 
FROM 
(
SELECT player_exp.exp 
FROM player_exp  
WHERE player_exp.exp >= $min_exp AND player_exp.exp <= $max_exp 
GROUP BY player_exp.exp 
) AS T1
EOT;
	//echo $query;
	$result = mysql_query($query) or die('Query failed: ' . mysql_error() . $query);
	$line = mysql_fetch_array($result, MYSQL_NUM);
	$howmanypage=0;
	if ($line){
		$howmanypage = $line[0]; 
	}
	mysql_free_result($result);
	
	
	
	//数据
	$arr = array();
	$start_no =  ($page_no -1) * $page_size;
	$link = my_connect_mysql($GLOBALS["global_db"]["gamedb"][$area_no]);
	$query = <<<EOT
SELECT player_exp.exp, COUNT(*) 
FROM player_exp  
WHERE player_exp.exp >= $min_exp AND player_exp.exp <= $max_exp 
GROUP BY player_exp.exp ORDER  BY player_exp.exp DESC 
LIMIT $start_no ,$page_size 
	
EOT;
	//echo $query;
	$result = mysql_query($query) or die('Query failed: ' . mysql_error()." $query");
	while ($line = mysql_fetch_array($result, MYSQL_NUM)) {
		$arr[] = $line;
    }
	mysql_free_result($result);
	
	$finalResult = array();
	$finalResult[0] = $howmanypage;
	$finalResult[1] = $arr;
	return  $finalResult;
}


function  getliquanxiaofe($area_no, $year_month_day, $get_or_consume, $page_no, $page_size)
{
	
	//生成表的号码
	$unixtime = strtotime($year_month_day. " 00:00:00");
	$tableName = "yuanbaotrace".strftime("%Y%m", $unixtime);
	$start_time_in_where = $year_month_day. " 00:00:00";
	$end_time_in_where = $year_month_day. " 23:59:59";
	
	
	//页数
	$link = my_connect_mysql($GLOBALS["global_db"]["gamelog"][$area_no]);
	$query = <<<EOT
SELECT CEIL(COUNT(*)/ $page_size) 
FROM $tableName
WHERE activetime >= '$start_time_in_where' AND activetime <= '$end_time_in_where'
EOT;
	if (isset($get_or_consume) && $get_or_consume == "获得") {
		$query = $query . " AND nchange > 0 ";
	} else if (isset($get_or_consume) && $get_or_consume == "消费") {
		$query = $query . " AND nchange < 0 ";
	} else {
		
	}
	
	$result = mysql_query($query) or die('Query failed: ' . mysql_error() . $query);
	$line = mysql_fetch_array($result, MYSQL_NUM);
	$howmanypage=0;
	if ($line){
		$howmanypage = $line[0]; 
	}
	mysql_free_result($result);
	
	
	//总额
	$link = my_connect_mysql($GLOBALS["global_db"]["gamelog"][$area_no]);
	$query = <<<EOT
SELECT sum(nchange) 
FROM $tableName
WHERE activetime >= '$start_time_in_where' AND activetime <= '$end_time_in_where'
EOT;
	if (isset($get_or_consume) && $get_or_consume == "获得") {
		$query = $query . " AND nchange > 0 ";
	} else if (isset($get_or_consume) && $get_or_consume == "消费") {
		$query = $query . " AND nchange < 0 ";
	} else {
		
	}
	
	$result = mysql_query($query) or die('Query failed: ' . mysql_error() . $query);
	$line = mysql_fetch_array($result, MYSQL_NUM);
	$zonghe=0;
	if ($line){
		$zonghe = $line[0]; 
	}
	mysql_free_result($result);
	
	
	//查询数据
	$arr = array();
	$start_no =  ($page_no -1) * $page_size;
	$link = my_connect_mysql($GLOBALS["global_db"]["gamelog"][$area_no]);
	$query = <<<EOT
SELECT player_id, nchange, strDesc, activetime 
FROM $tableName 
WHERE activetime >= '$start_time_in_where' AND activetime <= '$end_time_in_where' 
EOT;

	if (isset($get_or_consume) && $get_or_consume == "获得") {
		$query = $query . " AND nchange > 0 ";
	} else if (isset($get_or_consume) && $get_or_consume == "消费") {
		$query = $query . " AND nchange < 0 ";
	} else {
		
	}

	$query = $query . " order by  activetime desc  LIMIT $start_no ,$page_size  ";
	
	$result = mysql_query($query) or die('Query failed: ' . mysql_error()." $query");
	while ($line = mysql_fetch_array($result, MYSQL_NUM)) {
		$arr[] = $line;
    }
	mysql_free_result($result);
	
	//替换掉id，考虑到每一页的记录不多，这里就一个一个id查，不跨越数据库查询了
	foreach ($arr as  $key => $elem) {
		$arr[$key][0] =  getPlayerNameByID($elem[0], $area_no);
	}
	
	$finalResult = array();
	$finalResult[0] = $howmanypage;
	$finalResult[1] = $arr;
	$finalResult[2] = $zonghe;
	
	return  $finalResult;
	
}
?>




