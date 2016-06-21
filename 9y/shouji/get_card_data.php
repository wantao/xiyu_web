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
$area_name = '';
if (isset($_POST["sel_area_name"]) and $_POST["sel_area_name"] != '') {
	$area_name = $_POST["sel_area_name"];
}

$area_no_name = array();
$area_name_id = array();
{
	$link = my_connect_mysql($GLOBALS["global_db"]["game_config"]);
	$query = "select area_no, area_name from `area_table` ";
	$result = mysql_query($query);
	while ($line = mysql_fetch_array($result, MYSQL_NUM)) {
		$area_no_name[$line[0]] = $line[1];
		$area_name_id[$line[1]] = $line[0];
	}
	mysql_free_result($result); 
	//print_r($area_no_name);
}

$target_player_name="";
if (isset($_POST["sel_player_name"]) and $_POST["sel_player_name"] != '') {
	$target_player_name = $_POST["sel_player_name"];
}

$cur_page = 1;
 if (isset($_POST["cur_page"]) and $_POST["cur_page"] != '') {
	$cur_page = $_POST["cur_page"];
}

?>


<table border=0 cellpadding=10 align=center ><span class SYSTEM2>
	<form action="" method=post> 
	</select>
		<script language="javascript" type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
		<tr>
			<?php 
				session_start();
				displayAreas("sel_area_name", $area_name);
			?>
			<td>玩家名称<input type="text" name=sel_player_name value="<?php if ($target_player_name) echo $target_player_name; ?>" /></td>
			<td>当前页码: <input type="text" name=cur_page value="<?php if ($cur_page) echo $cur_page; ?>" /></td>
			<td><input type=submit name=sub_get_user_data value="提交"></td>
		</tr>
	</form>
</table>

<?php
	$area_no = $area_name_no[$area_name];
	$result = get_cards_data($cur_page, 20, $area_no, $target_player_name);
	$arry_arm_log = $result[1];
	echo "<table border=1 cellpadding=3 align=center ><span class SYSTEM2>\n";
	echo  "<tr> 共  " . $result[0] . "页 </tr>";

	$resultSetArray = array();
	$rs = new ResultSet();
	$colname = array();
	$colname[] = "唯一id"; $colname[] = "道具id"; $colname[] = "名称";
	$colname[] = "绑定"; $colname[] = "数量";
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

