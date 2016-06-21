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


<?php
$friend_name = '';
if (isset($_POST["sel_friend_name"]) and $_POST["sel_friend_name"] != '') {
	$friend_name = $_POST["sel_friend_name"];
}

$friend_no_name = array();
$friend_name_id = array();
get_friend_config($friend_no_name, $friend_name_id);

$cur_page = 1;
 if (isset($_POST["cur_page"]) and $_POST["cur_page"] != '') {
	$cur_page = $_POST["cur_page"];
}

$friend_no = $_SESSION["friend_no"];


if (0 == $friend_no) {
	echo <<<EOT
	<table border=0 cellpadding=10 align=center ><span class SYSTEM2>
		<form action="" method=post> 
		</select>
			<script language="javascript" type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
			<tr>
				<td>
EOT;

						echo "<select name=sel_friend_name>";
						if ($friend_name)
							echo "<option selected>$friend_name";
						foreach ($friend_no_name as &$value) {
							echo "<option>$value</option>";
						}
						echo "</select>";

	echo <<<EOT
				</td>
				<td><input type=submit name=sub_get_user_data value="提交"></td>
			</tr>
		</form>
	</table>
EOT;
}


?>

<?php
	if ($friend_name && $friend_name_id[$friend_name]) {
		$friend_no = $friend_name_id[$friend_name];
		$ary_s = normal_select_ex($friend_no);
		echo "<table border=\"1\" cellpadding=\"3\" align=\"center\" >\n";
		
		echo "\t<tr>\n";
		echo "<td  align=\"left\">今日累计充值:</td>\n";
		echo "<td  align=\"left\">".$ary_s['get_today_all']."</td>\n";
		echo "\t</tr>\n";
		
		echo "\t<tr>\n";
		echo "<td  align=\"left\">昨日累计充值:</td>\n";
		echo "<td  align=\"left\">".$ary_s['get_yesterday_all']."</td>\n";
		echo "\t</tr>\n";
		
		echo "\t<tr>\n";
		echo "<td  align=\"left\">本月累计充值:</td>\n";
		echo "<td  align=\"left\">".$ary_s['get_cur_month_all']."</td>\n";
		echo "\t</tr>\n";
		
		echo "\t<tr>\n";
		echo "<td  align=\"left\">上月累计充值:</td>\n";
		echo "<td  align=\"left\">".$ary_s['get_last_month_all']."</td>\n";
		echo "\t</tr>\n";
		
		echo "\t<tr>\n";
		echo "<td  align=\"left\">累计充值:</td>\n";
		echo "<td  align=\"left\">".$ary_s['get_all']."</td>\n";
		echo "\t</tr>\n";
	
		echo "\t<tr>\n";
		echo "<td  align=\"left\">成员数量:</td>\n";
		echo "<td  align=\"left\">".$ary_s['get_assn_all_number']."</td>\n";
		echo "\t</tr>\n";
		
	    echo "</table>\n";    
	}
?>



<p>
</p>

<?php public_tail(); ?>
</body>

</html>

