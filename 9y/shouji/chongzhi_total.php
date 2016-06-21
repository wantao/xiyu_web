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
<?php include_once("public_search.php"); public_head(); $r=do_session_check(); if ($r < 1) { public_tail();die();} 
$arr = explode('/',$_SERVER['PHP_SELF']); if (check_can_read_my($arr[count($arr)-1]) == 0) { public_tail();die();}  ?>
<?php include_once 'common_unit.php';?>

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
$sel_year_month = date('Ym',time());
if (isset($_POST["time_sel_year_month"])) {
	$sel_year_month = $_POST["time_sel_year_month"];
} 

$today_total_area_name = "";
if (isset($_POST["today_area_name"]) and $_POST["today_area_name"] != '') {
	$today_total_area_name = $_POST["today_area_name"];
}

$month_total_area_name = "";
if (isset($_POST["month_area_name"]) and $_POST["month_area_name"] != '') {
	$month_total_area_name = $_POST["month_area_name"];
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
{
	$link = my_connect_mysql($GLOBALS["global_db"]["game_config"]);
	$query = "select area_no, area_name from `area_table` ";
	$result = mysql_query($query);
	while ($line = mysql_fetch_array($result, MYSQL_NUM)) {
		$area_no_name[$line[0]] = $line[1];
	}
	mysql_free_result($result); 
	//print_r($area_no_name);
}

$sub_current_total = 0;
$sub_today_total = 0;
$sub_month_total = 0;

 if (isset($_POST["sub_current_total"])) {
 	$sub_current_total = 1;
	$sub_today_total = 0;
	$sub_month_total = 0;
 }
	

 if (isset($_POST["sub_today_total"])) {
 	$sub_current_total = 0;
	$sub_today_total = 1;
	$sub_month_total = 0;
 }
	
 if (isset($_POST["sub_month_total"])) {
 	$sub_current_total = 0;
 	$sub_today_total = 0;
	$sub_month_total = 1;

 }
 
 $current_total_ping_tai_name = "";
 if (isset($_POST["current_total_ping_tai_name"]) and $_POST["current_total_ping_tai_name"] != '') {
	$current_total_ping_tai_name = $_POST["current_total_ping_tai_name"];
 }
 
 $month_total_ping_tai_name = "";
 if (isset($_POST["month_total_ping_tai_name"]) and $_POST["month_total_ping_tai_name"] != '') {
	$month_total_ping_tai_name = $_POST["month_total_ping_tai_name"];
	
 }
 $today_total_ping_tai_name = "";
 if (isset($_POST["today_total_ping_tai_name"]) and $_POST["today_total_ping_tai_name"] != '') {
	$today_total_ping_tai_name = $_POST["today_total_ping_tai_name"];
 }

?>


<table border=0 cellpadding=10 align=center ><span class SYSTEM2>
	<form action="" method=post> 
	</select>
		<script language="javascript" type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
		<tr>
		
			<td>目前全区累计充值:</td>
			
			<?php
			session_start();
			displayPingTai("current_total_ping_tai_name", $current_total_ping_tai_name, $_SESSION["user_name"]);
			?>
		
			<td><input type=submit value="提交" name=sub_current_total ></td>
		</tr>
	</form>
</table>

<?php
	session_start();
	$user_name = $_SESSION["user_name"];
	if (has_right_to_access_ping_tai($user_name, $current_total_ping_tai_name) != 1){
		//echo "你没有权限访问";
	} else {
		if ($sub_current_total) {
			echo "<table border=1 cellpadding=3 align=center ><span class SYSTEM2>\n";
			echo "<tr>";
			echo 	"<td>目前全区累计充值: </td>\n";
			$pingtai_no = $ping_tai_name_no[$current_total_ping_tai_name];
			$total_momey = get_all_total_money($pingtai_no);
			echo 	"<td>$total_momey</td>\n";
			echo "</tr>";
		}
	}

?>

<table border=0 cellpadding=10 align=center ><span class SYSTEM2>
	<form action="" method=post> 
	</select>
		<script language="javascript" type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
		<tr>
			<td>
				<?php
					echo "<select name=today_area_name>";
					echo "<option>全区</option>";
					if ($today_total_area_name)
						echo "<option selected>$today_total_area_name";
					foreach ($area_no_name as &$value) {
						echo "<option>$value</option>";
					}
					echo "</select>";
				?>
			</td>
			
			<?php
			session_start();
			displayPingTai("today_total_ping_tai_name", $today_total_ping_tai_name, $_SESSION["user_name"]);
			?>
			
			<td>今天累计充值</td>
			<td><input type=submit value="提交" name=sub_today_total ></td>
		</tr>
	</form>
</table>

<?php
	session_start();
	$user_name = $_SESSION["user_name"];
	if (has_right_to_access_ping_tai($user_name, $today_total_ping_tai_name) != 1){
		//echo "你没有权限访问";
	} else {
		if ($sub_today_total) {
			$area_no = 0;
			if ($today_total_area_name != '全区')
				$area_no = $area_name_no[$today_total_area_name];
			$pingtai_no = $ping_tai_name_no[$today_total_ping_tai_name];
			$today_chongzhi_log = get_today_total_money($area_no, $pingtai_no);
			echo "<table border=1 cellpadding=3 align=center ><span class SYSTEM2>\n";
			echo <<<EOT
		 	  <tr>
		 	  	  <th align="left">区</th>
				  <th align="left">日充值总额</th>
		 	  </tr>
EOT;
		    foreach ($today_chongzhi_log as $col_value) {
		    	echo "\t<tr>\n";
			    if (isset($col_value)) {
			    	if ($today_total_area_name == '全区') {
			    		echo "<td  align=\"left\">全区</td>\n";
			    	} else {
			    		$area_id = $col_value[area_no];
			        	echo "<td  align=\"left\">$area_no_name[$area_id]</td>\n";	
			    	}
			        echo "<td  align=\"left\">$col_value[today_chongzhi]</td>\n";
			    }
			    echo "\t</tr>\n";
		    }
		    echo "</table>\n"; 
		}
	}

	
?>

<table border=0 cellpadding=10 align=center ><span class SYSTEM2>
	<form action="" method=post> 
	</select>
		<script language="javascript" type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
		<tr>
			<td>
				<?php
					echo "<select name=month_area_name>";
					echo "<option>全区</option>";
					if ($month_total_area_name)
						echo "<option selected>$month_total_area_name";
					foreach ($area_no_name as &$value) {
						echo "<option>$value</option>";
					}
					echo "</select>";
				?>
			</td>
			
			<?php
			session_start();
			displayPingTai("month_total_ping_tai_name", $month_total_ping_tai_name, $_SESSION["user_name"]);
			?>
			
			<td><input type="text" class="Wdate" name=time_sel_year_month onfocus="WdatePicker({dateFmt:'yyyyMM',minDate:'2013-8',maxDate:'2023-8'})" value="<?php echo $sel_year_month;?>"/></td>
			<td><input type=submit value="提交" name=sub_month_total ></td>
		</tr>
		
	</form>
</table>

<?php

	session_start();
	$user_name = $_SESSION["user_name"];
	if (has_right_to_access_ping_tai($user_name, $month_total_ping_tai_name) != 1){
		//echo "你没有权限访问";
	} else {
			if ($sub_month_total) {
				$area_no = 0;
				if ($month_total_area_name != '全区')
					$area_no = $area_name_no[$month_total_area_name];
			$pingtai_no = $ping_tai_name_no[$month_total_ping_tai_name];
			$month_money_log = get_month_total_money($area_no, $sel_year_month, $pingtai_no);
			echo "<table border=1 cellpadding=3 align=center ><span class SYSTEM2>\n";
			echo <<<EOT
		 	  <tr>
		 	  	  <th align="left">区</th>
				  <th align="left">月充值总额</th>
		 	  </tr>
EOT;
		    foreach ($month_money_log as $col_value) {
		    	echo "\t<tr>\n";
			    if (isset($col_value)) {
			    	if ($month_total_area_name == '全区') {
			    		echo "<td  align=\"left\">全区</td>\n";
			    	} else {
			    		$area_id = $col_value[area_no];
			        	echo "<td  align=\"left\">$area_no_name[$area_id]</td>\n";	
			    	}
			        echo "<td  align=\"left\">$col_value[month_chongzhi]</td>\n";
			    }
			    echo "\t</tr>\n";
		    }
		    echo "</table>\n"; 
		}
	}
	

?>

<p>
</p>

<?php public_tail(); ?>
</body>

</html>

