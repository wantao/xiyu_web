<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
<title>玩家详细充值记录</title>

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
$str_now_t = date('Y-m-d H:m:s',time()-15*24*3600);
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
		<td>
			<?php
				$link = my_connect_mysql($GLOBALS["global_db"]["game_config"]);
				$query = "select area_name from `area_table` ";
				$result = mysql_query($query) or die('Query failed: ' . mysql_error());
				echo "<select name=sel_area_name>";
				if ($area_name)
					echo "<option selected>$area_name";
				while ($line = mysql_fetch_array($result, MYSQL_NUM)) {
					echo "<option>$line[0]</option>";
				}
				echo "</select>";
				mysql_free_result($result); 
			?>
		</td>
		
		
		<td><input type="text" class="Wdate" name=s_begin_time onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'2013-01-01 00:00:00',maxDate:'2020-01-01 00:00:00'})" value="<?php echo $str_now_t;?>"/></td>
		<td><input type="text" class="Wdate" name=s_end_time onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'2013-01-01 00:00:00',maxDate:'2020-01-01 00:00:00'})" value="<?php echo $str_now_t1;?>"/></td>
		<td><input type=submit value="提交"></td>
	</tr>
</form>
</table>


<!-- 结果显示 -->
<?php
	$result = get_wanjiachongzhijilu($area_name, $str_now_t, $str_now_t1);
	$reslutSet = $result[0];
	echo "<table border=1 cellpadding=3 align=center ><span class SYSTEM2>\n";
	echo <<<EOT
 	  <tr>
		  <th align="left">角色ID</th>
		  <th align="left">角色名</th>
		  <th align="left">订单号</th>
		  <th align="left">充值金额</th>
		  <th align="left">充值元宝数</th>
		  <th align="left">充值成功时间</th>
 	  </tr>
EOT;
	
    foreach ($reslutSet as $col_value) {
    	echo "\t<tr>\n";
	    if (isset($col_value)) {
	    	//print_r($col_value);
	        echo "<td  align=\"left\">$col_value[c1]</td>\n";
	        echo "<td  align=\"left\">$col_value[c2]</td>\n";
	        echo "<td  align=\"left\">$col_value[c3]</td>\n";
	        echo "<td  align=\"left\">$col_value[c4]</td>\n";
	        echo "<td  align=\"left\">$col_value[c5]</td>\n";
	       	echo "<td  align=\"left\">$col_value[c6]</td>\n";
	    }
	    echo "\t</tr>\n";
    }
    echo "</table>\n";    
    
    
    echo "<table border=1 cellpadding=3 align=center ><span class SYSTEM2>\n";
	echo <<<EOT
 	  <tr>
		  <th align="left">充值总金额</th>
		  <th align="left">充值总元宝</th>
		  <th align="left">充值总次数</th>
		  <th align="left">充值总人数</th>
 	  </tr>
EOT;
	
	$reslutSet = $result[1];
    foreach ($reslutSet as $col_value) {
    	echo "\t<tr>\n";
	    if (isset($col_value)) {
	    	//print_r($col_value);
	        echo "<td  align=\"left\">$col_value[c1]</td>\n";
	        echo "<td  align=\"left\">$col_value[c2]</td>\n";
	        echo "<td  align=\"left\">$col_value[c3]</td>\n";
	        echo "<td  align=\"left\">$col_value[c4]</td>\n";
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


