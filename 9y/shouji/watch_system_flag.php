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

$area_name = '';
if (isset($_POST["sel_area_name"]) and $_POST["sel_area_name"] != '') {
	$area_name = $_POST["sel_area_name"];
}

?>

<?php 
	//读出数据库配置
	$area_no_name = array();
	$area_name_no = array();
	get_area_config($area_no_name, $area_name_no);
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
		<td><input type=submit value="提交"></td>
	</tr>
</form>
</table>

<?php
	
	$area_no = $area_name_no[$area_name];
	$result = get_system_flag_table($area_no);

	$resultSetArray = array();
	$rs = new ResultSet();
	$colname = array();
	$colname = array("flag_key", "int_val1", "activetime", "desc");
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

