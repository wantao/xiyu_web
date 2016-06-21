<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
<title>PDENV</title>

<style>
#header{text-align:center;background-color:#99CCFF}
#contents{text-align:left;margin:10px;line-height:36px;}
#footer{text-align:center;background-color:#99CCFF}
</style>
</head>
<body bgcolor="white" text="black" style="text-align:center;">

<?php include_once("public_search.php"); public_head(); if (do_session_check() < 1) { public_tail();die();} ?>


<?php
/////////////////////////////////////////////////////////////////////

$str_now_t = date('Y-m-d H:m:s',time()-24*3600);
$str_now_t1 = date('Y-m-d H:m:s',time());

if (isset($_POST["s_begin_time"]) and $_POST["s_end_time"] != '') {
	$str_now_t = $_POST["s_begin_time"];
	$str_now_t1 = $_POST["s_end_time"];
} 

$buy_log_player_name = '';
if (isset($_POST["text_player_name"]) and $_POST["text_player_name"] != '') {
	$buy_log_player_name = $_POST["text_player_name"];
}

$friend_no=$_SESSION["friend_no"];

$friend_name = '';
if (isset($_POST["sel_friend_name"]) and $_POST["sel_friend_name"] != '') {
	$friend_name = $_POST["sel_friend_name"];
}

$friend_no_name = array();
$friend_name_id = array();
get_friend_config($friend_no_name, $friend_name_id);


?>

<table border=0 cellpadding=10 align=center ><span class SYSTEM2>
<form action="" method=post> 
</select>
	<script language="javascript" type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
	<tr>
				<td>
					<select name=sel_friend_name>
					<?php
					    if (0 == $friend_no){
							if ($friend_name)
								echo "<option selected>$friend_name";
							foreach ($friend_no_name as &$value) {
								echo "<option>$value</option>";
							}
					    }
					?>
					</select>
				</td>
		<td><input type="text" class="Wdate" name=s_begin_time onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'2013-01-01 00:00:00',maxDate:'2020-01-01 00:00:00'})" value="<?php echo $str_now_t;?>"/></td>
		<td><input type="text" class="Wdate" name=s_end_time onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'2013-01-01 00:00:00',maxDate:'2020-01-01 00:00:00'})" value="<?php echo $str_now_t1;?>"/></td>
	</tr>
	
	<tr>
		<td>玩家名称 ：<input type="text" name=text_player_name value="<?php if ($buy_log_player_name) echo $buy_log_player_name; ?>" /></td>
		<td><input type=submit value="提交"></td>
	</tr>
	
</form>
</table>

<?php
	if (do_session_check() > 0) {
		if ($friend_name && $friend_name_id[$friend_name]) {
			$friend_no = $friend_name_id[$friend_name];
		}
		gong_hui_cheng_yuan_xiao_fei_log($friend_no,
		$buy_log_player_name,
		$str_now_t,
		$str_now_t1);	

}
?>


<?php public_tail(); ?>
</body>
</html>



