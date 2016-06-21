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
		<td><input type="text" class="Wdate" name=s_begin_time onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'2013-01-01 00:00:00',maxDate:'2020-01-01 00:00:00'})" value="<?php echo $str_now_t;?>"/></td>
		<td><input type="text" class="Wdate" name=s_end_time onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'2013-01-01 00:00:00',maxDate:'2020-01-01 00:00:00'})" value="<?php echo $str_now_t1;?>"/></td>
		<td><input type=submit value="提交"></td>
	</tr>
</form>
</table>


<!-- 结果显示 -->
<?php
	$xiaofeiconfig = array(		
		//道具
		0=>array(
			80=>'低级固魂石',
			81=>'中级固魂石',
			82=>'高级固魂石',
			20015=>'青铜如意',
			20016=>'白银如意',
			
			20017=>'黄金如意',
			73=>'天眼石',
			74=>'心眼石',
			154=>'穿越白石',
			156=>'穿越碧玉',
			
			157=>'穿越蓝宝',
			158=>'穿越紫晶',
			159=>'穿越橙钻',
			246=>'秘策卷轴',
			268=>'低级功勋石',
			
			240=>'辎重包',
			97=>'包子',
		),
		1=>array(),);
	
	$area_no = $area_name_no[$area_name];
	$result = get_xiaofeileixingpaixing($area_no, $str_now_t, $str_now_t1, $xiaofeiconfig);  

	$resultSetArray = array();
	$rs = new ResultSet();
	$colname = array();
	$colname[] = "消费去向"; $colname[] = "消费次数"; $colname[] = "消费人数"; $colname[] = "消费元宝";
	$resultval = array();
	$resultval = $result[0];
	$rs->col = $colname;
	$rs->resultset = $resultval;
	$resultSetArray[] = $rs;
			    
	displayTable($resultSetArray);
     

	$resultSetArray = array();
	$rs = new ResultSet();
	$colname = array();
	$colname[] = "消费去向"; $colname[] = "消费次数"; $colname[] = "消费人数"; $colname[] = "消费元宝";
	$resultval = array();
	$resultval = $result[1];
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


