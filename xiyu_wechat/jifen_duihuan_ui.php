<script src="js_public_method.js"></script>
<script>
function execute_libao(m_id){
	var open_id = GetArgsFromHref(location.href, 'open_id');
	//alert(""+platform+area_id+player_id+open_id);
	if(!open_id){
		return;
	}
	var domain_prex = '<?php require_once "Config.php"; echo DOMAIN_PREFIX;?>';
	post(domain_prex+'jifen_duihuan_operate.php', {'open_id':open_id,'libao_id':m_id});
}

</script>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>积分兑换</title>
</head>
<body align="center">
<!--第一行-->
<table align="center" border="true">
<tr>
<?php 
	require_once("../unity/self_data_operate_factory.php");
	require_once("../unity/self_table_names.php");
	require_once("table_operate.php");
	$ob_data_factory = new CDataOperateFactory('weixin_db_m');
	$select_sql = "SELECT * FROM `libao_config`";
	$db_result = $ob_data_factory->db_get_data($select_sql);
	if (!$db_result) {
		writeLog(get_str_log_prex(__FILE__,__LINE__,__FUNCTION__)." db operate error,sql:".$select_sql." mysql_error:".$ob_data_factory->mysql->my_mysql_error(),LOG_NAME::ERROR_LOG_FILE_NAME);
        return;	
	}
	if (-1 == $db_result) {
		echo "not config libao";
		writeLog(get_str_log_prex(__FILE__,__LINE__,__FUNCTION__)." not config libao_config",LOG_NAME::ERROR_LOG_FILE_NAME);
        return;
	}
	foreach($db_result as $key=>$value) {
		echo "<div id=\"".$value[enum_libao_config::e_id]."\">";
		echo "<td><table>";
		echo "<tr>";
		echo "<th>".$value[enum_libao_config::e_pic_url]."</th>";
		echo "</tr>";
		echo "<tr>";
		echo "<td align=center>".$value[enum_libao_config::e_need_jifen]."积分</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<p>";
		echo "<button type=\"button\" id=\"".$value[enum_libao_config::e_id]."\" onclick=\"execute_libao(this.id)\">兑换</button>";
		echo "</p>";
		echo "</tr>";
		echo "</table></td>";
		echo "</div>";
	}
?> 
</tr>
</table>
</body>
</html>