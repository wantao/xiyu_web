<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
<title>礼券消费</title>

<style>
#header{text-align:center;background-color:#99CCFF}
#contents{text-align:left;margin:10px;line-height:36px;}
#footer{text-align:center;background-color:#99CCFF}
</style>
</head>
<body>

<!--头部+一些权限验证 -->
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
if (isset($_POST["area_name"]) and $_POST["area_name"] != '') {
	$area_name = $_POST["area_name"];
}


$get_or_consume = '';
if (isset($_POST["get_or_consume"]) and $_POST["get_or_consume"] != '') {
	$get_or_consume = $_POST["get_or_consume"];
}


$page_no = 1;
if (isset($_POST["page_no"]) and $_POST["page_no"] != '') {
	$page_no = $_POST["page_no"];
}

$year_month_day = date('Y-m-d',time());
if (isset($_POST["year_month_day"])) {
	$year_month_day = $_POST["year_month_day"];
} 

?>


<!-- 显示 -->
<table border=0 cellpadding=10 align=center ><span class SYSTEM2>
<form action="" method=post> 
</select>
	<script language="javascript" type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
	<tr>
		
		<?php 
			session_start();
			displayAreas("area_name", $area_name);
		?>
		
		<?php 
			echo "<td>";
			echo "<select name=get_or_consume>";
			if ($get_or_consume)
				echo "<option selected>$get_or_consume";
			echo "<option>消费</option>";
			echo "<option>获得</option>";
			echo "</select>";
			echo "</td>";
		?>
		
		<td><input type="text" class="Wdate" name=year_month_day onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',minDate:'2013-1-1',maxDate:'2113-1-1'})" value="<?php echo $year_month_day;?>"/></td>
		
		<td>当前页码  </td>
		<td><input type="text" name=page_no value="<?php if ($page_no) echo $page_no; ?>" /></td>
		
		<td><input type=submit value="提交"></td>
	</tr>
	
</form>
</table>


<!-- 结果显示 -->

<?php

	$result = array();
	if ($area_name != '' && $get_or_consume != ''){
		$area_no = $area_name_no[$area_name];
		$result = getliquanxiaofe($area_no, $year_month_day, $get_or_consume, $page_no, 20);
	} else {
		$result = array();
	}
	
	echo "<table border=1 cellpadding=3 align=center ><span class SYSTEM2>\n";
	echo  "<tr> 共  " . $result[0] . "页 </tr>";
	echo  "<tr> 总额  " . $result[2] . "礼券 </tr>";
	
	$rs = new ResultSet();
	$colname = array();
	$colname[] = "玩家名称"; $colname[] = "消费（负数）/ 获得（整数）";$colname[] = "描述";$colname[] = "时间";
	$resultval = array();
	$resultval = $result[1];
	$rs->col = $colname;
	$rs->resultset = $resultval;
	$resultSetArray[] = $rs;
			    
	displayResult($resultSetArray[0]);
	
	echo "</table>\n";    
?>





<p>
</p>

<?php public_tail(); ?>
</body>

</html>

