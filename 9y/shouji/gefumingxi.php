<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
<title>各服汇总</title>

<style>
#header{text-align:center;background-color:#99CCFF}
#contents{text-align:left;margin:10px;line-height:36px;}
#footer{text-align:center;background-color:#99CCFF}
</style>
</head>


<body>

<?php include_once("public_search.php"); public_head(); $r=do_session_check(); if ($r < 1) { public_tail();die();} 
$arr = explode('/',$_SERVER['PHP_SELF']); if (check_can_read_my($arr[count($arr)-1]) == 0) { public_tail();die();}  ?>
<?php include_once 'common_unit.php'; 
?>
<?php 
	//读出数据库配置
	$area_no_name = array();
	$area_name_no = array();
	get_area_config($area_no_name, $area_name_no);
?>
<?php
$str_now_t = date('Y-m-d',time()-7*24*3600);
$str_now_t1 = date('Y-m-d',time());

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
		<td><input type="text" class="Wdate" name=s_begin_time onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',minDate:'2013-01-01 00:00:00',maxDate:'2020-01-01 00:00:00'})" value="<?php echo $str_now_t;?>"/></td>
		<td><input type="text" class="Wdate" name=s_end_time onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',minDate:'2013-01-01 00:00:00',maxDate:'2020-01-01 00:00:00'})" value="<?php echo $str_now_t1;?>"/></td>

		
		<td><input type=submit value="提交"></td>
	</tr>
</form>
</table>



<!-- 结果显示 -->
<?php
	$area_no = $area_name_no[$area_name];
	$result = get_gefumingxi($area_no, $str_now_t, $str_now_t1);
	
		echo "<table border=1 cellpadding=3 align=center ><span class SYSTEM2>\n";
	echo <<<EOT
 	  <tr>
 	  		<th align="left">日期</th>
 	  	  	<th align="left">DNU</th>
 	  	  	<th align="left">DAU</th>
 	  	  	<th align="left">最高在线人数</th>
 	  	  	<th align="left">充值人数</th>
 	  	  	<th align="left">充值次数</th>
 	  	  	<th align="left">充值金额</th>
 	  	  	<th align="left">ARPRU</th>
 	  	  	<th align="left">ARPPU</th>
 	  	  	<th align="left">PR</th>
 	  	  	<th align="left">新手无操作率</th>
 	  	  	<th align="left">次日留存率</th>
 	  	  	<th align="left">三日留存率</th>
 	  	  	<th align="left">七日无操作率</th>
 	  	  	<th align="left">平均在线时长</th>

 	  </tr>
EOT;
	
    foreach ($result as $col_value) {
    	echo "\t<tr>\n";
	    if (isset($col_value)) {
	    	//print_r($col_value);
	        echo "<td  align=\"left\">$col_value[0]</td>\n";
	        echo "<td  align=\"left\">$col_value[1]</td>\n";
	        echo "<td  align=\"left\">$col_value[2]</td>\n";
	        echo "<td  align=\"left\">$col_value[3]</td>\n";
	        echo "<td  align=\"left\">$col_value[4]</td>\n";
	        echo "<td  align=\"left\">$col_value[5]</td>\n";
	        echo "<td  align=\"left\">$col_value[6]</td>\n";
	        echo "<td  align=\"left\">$col_value[7]</td>\n";
	        echo "<td  align=\"left\">$col_value[8]</td>\n";
	        echo "<td  align=\"left\">$col_value[9]</td>\n";
	        echo "<td  align=\"left\">$col_value[10]</td>\n";
	        echo "<td  align=\"left\">$col_value[11]</td>\n";
	        echo "<td  align=\"left\">$col_value[12]</td>\n";
	        echo "<td  align=\"left\">$col_value[13]</td>\n";
	        echo "<td  align=\"left\">$col_value[14]</td>\n";
	        
	        

	    }
	    echo "\t</tr>\n";
    }
    echo "</table>\n";
	
?>



<p>
</p>

<?php public_tail(); ?>
</body>

</html>

