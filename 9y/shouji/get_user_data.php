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



$target_player_name="";
if (isset($_POST["sel_player_name"]) and $_POST["sel_player_name"] != '') {
	$target_player_name = $_POST["sel_player_name"];
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
			<td><input type=submit name=sub_get_user_data value="提交"></td>
		</tr>
	</form>
</table>

<?php
	//print_r($area_name_id);
	//echo $area_name;
	$area_no = $area_name_no[$area_name];
	$result = get_base_user_data($area_no, $target_player_name);

	$resultSetArray = array();
	$rs = new ResultSet();
	$colname = array();
	$colname[] = "名称"; $colname[] = "声望"; $colname[] = "爵位";
	$colname[] = "元宝"; $colname[] = "vip等级"; $colname[] = "累计充值";
	$colname[] = "累计消费"; $colname[] = "礼券"; $colname[] = "将魂";
	$colname[] = "兵粮"; $colname[] = "金币"; $colname[] = "兵力";
	$colname[] = "妖魂"; $colname[] = "精魄";	
	
	$resultval = array();
	$resultval = $result;
	$rs->col = $colname;
	$rs->resultset = $resultval;
	$resultSetArray[] = $rs;
			    
	displayTable($resultSetArray);
?>

<p>
</p>

<?php public_tail(); ?>
</body>

</html>

