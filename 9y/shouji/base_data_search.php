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
<?php include_once("public_search.php"); public_head(); if (do_session_check() < 1) { public_tail();die();} ?>

<?php
$str_now_t = date('Y-m-d',time()-24*3600);
$str_now_t1 = date('Y-m-d',time() + 24*3600);

if (isset($_POST["s_begin_time"]) and $_POST["s_end_time"] != '') {
	$str_now_t = $_POST["s_begin_time"];
	$str_now_t1 = $_POST["s_end_time"];
} 

$area_name = '';
if (isset($_POST["sel_area_name"]) and $_POST["sel_area_name"] != '') {
	$area_name = $_POST["sel_area_name"];
}

?>


<table border=0 cellpadding=10 align=center ><span class SYSTEM2>
<form action="" method=post> 
</select>
	<script language="javascript" type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
	<tr>
		<td>每日发起登陆的总人数: <?php echo get_all_deng_lu_ren_shu(); ?></td>
	</tr>
	<tr>
		<td>查询每日新增人数</td>
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
		<td><input type="text" class="Wdate" name=s_begin_time onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',minDate:'2013-01-01 00:00:00',maxDate:'2020-01-01 00:00:00'})" value="<?php echo $str_now_t;?>"/></td>
		<td><input type="text" class="Wdate" name=s_end_time onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',minDate:'2013-01-01 00:00:00',maxDate:'2020-01-01 00:00:00'})" value="<?php echo $str_now_t1;?>"/></td>
		<td><input type=submit value="提交"></td>
	</tr>
</form>
</table>

<?php
	//echo $area_name . $str_now_t . $str_now_t1;
	$arry_mei_ri_xin_zeng_ren_shu = get_mei_ri_xin_zeng_ren_shu($area_name, $str_now_t, $str_now_t1);
	
	echo "<table border=\"1\" cellpadding=\"3\" align=\"center\" >\n";
	echo <<<EOT
 	  <tr>
 	  	  <th align="left">区名</th>
		  <th align="left">区号</th>
		  <th align="left">新增人数</th>
		  <th align="left">日期</th>
 	  </tr>
EOT;
	
    foreach ($arry_mei_ri_xin_zeng_ren_shu as $col_value) {
    	echo "\t<tr>\n";
	    if (isset($col_value)) {
	    	//print_r($col_value);
	        echo "<td  align=\"left\">$area_name</td>\n";
	        echo "<td  align=\"left\">$col_value[areaid]</td>\n";
	        echo "<td  align=\"left\">$col_value[number]</td>\n";
	        echo "<td  align=\"left\">$col_value[day]</td>\n";
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

