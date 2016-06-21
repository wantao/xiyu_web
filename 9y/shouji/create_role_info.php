<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
<title>创建角色明细</title>


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
$str_now_t = date('Y-m-d H:m:s',time()-30*24*3600);
$str_now_t1 = date('Y-m-d H:m:s',time());

if (isset($_POST["s_begin_time"]) and $_POST["s_end_time"] != '') {
	$str_now_t = $_POST["s_begin_time"];
	$str_now_t1 = $_POST["s_end_time"];
} 

$area_name = '';
if (isset($_POST["sel_area_name"]) and $_POST["sel_area_name"] != '') {
	$area_name = $_POST["sel_area_name"];
}


$start_title  = 1;
if (isset($_POST["start_title"]) and $_POST["start_title"] != '') {
	$start_title = $_POST["start_title"];
} 

$end_title = 1;
if (isset($_POST["end_title"]) and $_POST["end_title"] != '') {
	$end_title = $_POST["end_title"];
} 

$cur_page = 1;
if (isset($_POST["cur_page"]) and $_POST["cur_page"] != '') {
	$cur_page = $_POST["cur_page"];
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
			displayAreas("sel_area_name", $area_name);
		?>
		
		<td>起始等级</td>
		<td>
			<select name=start_title> <option selected><?php echo $start_title; ?></option><option>2</option><option>3</option><option>4</option><option>5</option><option>6</option><option>7</option><option>8</option><option>9</option><option>10</option><option>11</option></select>
		</td>
		<td>结束等级</td>
		<td>
			<select name=end_title> <option selected><?php echo $end_title; ?></option><option>2</option><option>3</option><option>4</option><option>5</option><option>6</option><option>7</option><option>8</option><option>9</option><option>10</option><option>11</option></select>
		</td>
		
		
		<td><input type="text" class="Wdate" name=s_begin_time onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'2013-01-01 00:00:00',maxDate:'2020-01-01 00:00:00'})" value="<?php echo $str_now_t;?>"/></td>
		<td><input type="text" class="Wdate" name=s_end_time onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'2013-01-01 00:00:00',maxDate:'2020-01-01 00:00:00'})" value="<?php echo $str_now_t1;?>"/></td>
		<td>当前页码  </td>
		<td><input type="text" name=cur_page value="<?php if ($cur_page) echo $cur_page; ?>" /></td>
		<td><input type=submit value="提交"></td>
	</tr>
</form>
</table>


<!-- 结果显示 -->
<?php
	$area_no = $area_name_no[$area_name];
	
	$result = get_create_role_info($cur_page, 20, $area_no, $str_now_t, $str_now_t1, $start_title, $end_title);
	$reslutSet = $result[1];
	echo "<table border=1 cellpadding=3 align=center ><span class SYSTEM2>\n";
	echo  "<tr> 共  " . $result[0] . "页 </tr>";

	$resultSetArray = array();
	$rs = new ResultSet();
	$colname = array();
	$colname[] = "账号"; $colname[] = "角色ID"; $colname[] = "角色名";
	$colname[] = "当前官职"; $colname[] = "VIP等级"; $colname[] = "声望值<";
	$colname[] = "角色总战力"; $colname[] = "当前元宝数"; $colname[] = "当前金币数";
	$colname[] = "角色创建时间"; $colname[] = "最近登陆时间";
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


