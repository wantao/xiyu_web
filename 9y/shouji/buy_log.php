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
$str_now_t = date('Y-m-d H:m:s',time()-24*3600);
$str_now_t1 = date('Y-m-d H:m:s',time());

if (isset($_POST["s_begin_time"]) and $_POST["s_end_time"] != '') {
	$str_now_t = $_POST["s_begin_time"];
	$str_now_t1 = $_POST["s_end_time"];
} 

$buy_log_area_name = "";
if (isset($_POST["sel_area_name"]) and $_POST["sel_area_name"] != '') {
	$buy_log_area_name = $_POST["sel_area_name"];
}

$buy_log_player_name = '';
if (isset($_POST["text_player_name"]) and $_POST["text_player_name"] != '') {
	$buy_log_player_name = $_POST["text_player_name"];
}

$buy_log_buy_type = '不限';
if (isset($_POST["sel_buy_type_name"]) and $_POST["sel_buy_type_name"] != '') {
	$buy_log_buy_type = $_POST["sel_buy_type_name"];
}

$buy_log_goodsid = '';
if (isset($_POST["txt_goods_id"]) and $_POST["txt_goods_id"] != '') {
	$buy_log_goodsid = $_POST["txt_goods_id"];
}

$cur_page = 1;
 if (isset($_POST["cur_page"]) and $_POST["cur_page"] != '') {
	$cur_page = $_POST["cur_page"];
}

$array_buy_type = array(-1 => "不限", 0 => "购买功能", 1=>"购买道具", 2=>"寄售交易" );

?>

<table border=0 cellpadding=10 align=center ><span class SYSTEM2>
<form action="" method=post> 
</select>
	<script language="javascript" type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
	<tr>
		<?php 
			session_start();
			displayAreas("sel_area_name", $buy_log_area_name);
		?>
		<td><input type="text" class="Wdate" name=s_begin_time onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'2013-01-01 00:00:00',maxDate:'2020-01-01 00:00:00'})" value="<?php echo $str_now_t;?>"/></td>
		<td><input type="text" class="Wdate" name=s_end_time onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'2013-01-01 00:00:00',maxDate:'2020-01-01 00:00:00'})" value="<?php echo $str_now_t1;?>"/></td>
	</tr>
	
	<tr>
		<td>玩家名称(可选) </td>
		<td><input type="text" name=text_player_name value="<?php if ($buy_log_player_name) echo $buy_log_player_name; ?>" /></td>
	</tr>
	
	<tr>
		<td>消费类型(可选) </td>
		<td>
			<select name=sel_buy_type_name>
				<option selected><?php echo $buy_log_buy_type; ?></option>>;
			<?php
			for ($i = -1; $i <= 2; $i++) {
				echo "<option>$array_buy_type[$i]</option>";
			}
			?>
			</select>
		</td>
	</tr>
	
	<tr>
		<td>道具ID(可选) </td>
		<td><input type="text" name=txt_goods_id value="<?php if ($buy_log_goodsid) echo $buy_log_goodsid; ?>" /></td>
		<td>当前页码 <input type="text" name=cur_page value="<?php if ($cur_page) echo $cur_page; ?>" /></td>
		<td><input type=submit value="提交"></td>
	</tr>
</form>
</table>

<?php
	$area_no = $area_name_no[$buy_log_area_name];
	
	$result = get_buy_log($cur_page, 20, $area_no, $buy_log_player_name, $buy_log_buy_type,
						 $buy_log_goodsid, $str_now_t, $str_now_t1, $_SESSION['ping_tai_id'], $_SESSION['user_priv']);
	$arry_buy_log = $result[1];
	
	echo "<table border=1 cellpadding=3 align=center ><span class SYSTEM2>\n";
	echo  "<tr> 共  " . $result[0] . "页 </tr>";

	$resultSetArray = array();
	$rs = new ResultSet();
	$colname = array();
	$colname[] = "玩家名称"; $colname[] = "消费类型"; $colname[] = "消费元宝";
	$colname[] = "单价"; $colname[] = "数量"; $colname[] = "武将唯一id";
	$colname[] = "道具id"; $colname[] = "道具类型"; $colname[] = "道具名称";
	$colname[] = "描述"; $colname[] = "购买时间";
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

