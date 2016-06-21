<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
<title>数据查询</title>


<style>
#header{text-align:center;background-color:#99CCFF}
#contents{text-align:left;margin:10px;line-height:36px;}
#footer{text-align:center;background-color:#99CCFF}
</style>
</head>
<body>
<?php include_once("public_search.php"); include_once('common_unit.php'); public_head(); $r=do_session_check(); if ($r < 1) { public_tail();die();} 
$arr = explode('/',$_SERVER['PHP_SELF']); if (check_can_read_my($arr[count($arr)-1]) == 0) { public_tail();die();}  ?>

<?php 
	//读出数据库配置
	$area_no_name = array();
	$area_name_no = array();
	get_area_config($area_no_name, $area_name_no);
?>
<?php 
	//读出数据库配置
	$ping_tai_no_name = array();
	$ping_tai_name_no = array();
	get_ping_tai_config($ping_tai_no_name, $ping_tai_name_no);
?>
<?php
$str_now_t = date('Y-m-d H:m:s',time()-24*3600);
$str_now_t1 = date('Y-m-d H:m:s',time());

if (isset($_POST["s_begin_time"]) and $_POST["s_end_time"] != '') {
	$str_now_t = $_POST["s_begin_time"];
	$str_now_t1 = $_POST["s_end_time"];
} 

$chongzhi_log_area_name = "";
if (isset($_POST["sel_area_name"]) and $_POST["sel_area_name"] != '') {
	$chongzhi_log_area_name = $_POST["sel_area_name"];
}

$chongzhi_log_player_name = '';
if (isset($_POST["text_player_name"]) and $_POST["text_player_name"] != '') {
	$chongzhi_log_player_name = $_POST["text_player_name"];
}


$cur_page = 1;
 if (isset($_POST["cur_page"]) and $_POST["cur_page"] != '') {
	$cur_page = $_POST["cur_page"];
}

$area_no_name = array();
$area_name_no = array();
get_area_config($area_no_name, $area_name_no);

$pingtai_name =  '';
if (isset($_POST["sel_pingtai_name"]) and $_POST["sel_pingtai_name"] != '') {
	$pingtai_name = $_POST["sel_pingtai_name"];
}

?>


<table border=0 cellpadding=10 align=center ><span class SYSTEM2>
<form action="" method=post> 
</select>
	<script language="javascript" type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
	<tr>
		
		<?php 
			session_start();
			displayAreas("sel_area_name", $chongzhi_log_area_name);
		?>
		
		<?php
			session_start();
			displayPingTai("sel_pingtai_name", $pingtai_name, $_SESSION["user_name"]);
		?>
		
		<td><input type="text" class="Wdate" name=s_begin_time onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'2013-01-01 00:00:00',maxDate:'2020-01-01 00:00:00'})" value="<?php echo $str_now_t;?>"/></td>
		<td><input type="text" class="Wdate" name=s_end_time onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'2013-01-01 00:00:00',maxDate:'2020-01-01 00:00:00'})" value="<?php echo $str_now_t1;?>"/></td>
	</tr>
	
	<tr>
		<td>玩家名称(可选) </td>
		<td><input type="text" name=text_player_name value="<?php if ($chongzhi_log_player_name) echo $chongzhi_log_player_name; ?>" /></td>
		<td>当前页码  </td>
		<td><input type="text" name=cur_page value="<?php if ($cur_page) echo $cur_page; ?>" /></td>
		<td><input type=submit value="提交"></td>
	</tr>
	
</form>
</table>

<?php

	session_start();
	$user_name = $_SESSION["user_name"];
	if (has_right_to_access_ping_tai($user_name, $pingtai_name) != 1){
		//echo "你没有权限访问";
		return ;
	}

	$area_no = $area_name_no[$chongzhi_log_area_name];
	$pingtai_no = $ping_tai_name_no[$pingtai_name];
	
	$result = get_chongzhi_log($cur_page, 20, $area_no, $chongzhi_log_player_name, $str_now_t, $str_now_t1, $pingtai_no);
	$arry_chongzhi_log = $result[1];
	echo "<table border=1 cellpadding=3 align=center ><span class SYSTEM2>\n";
	echo  "<tr> 共  " . $result[0] . "页 </tr>";

	

	$resultSetArray = array();
	$rs = new ResultSet();
	$colname = array();
	$colname[] = "区"; $colname[] = "玩家名称"; $colname[] = "金额"; $colname[] = "游戏币"; 
	$colname[] = "充值时间"; $colname[] = "状态"; $colname[] = "到帐时间"; $colname[] = "订单号";
	$resultval = array();
	$resultval = $result[1];
	$rs->col = $colname;
	$rs->resultset = $resultval;
	$resultSetArray[] = $rs;
			    
	displayTable($resultSetArray);
	
    echo "</table>\n";    
    
    
    
?>


<p>
</p>

<?php public_tail(); ?>
</body>

</html>

