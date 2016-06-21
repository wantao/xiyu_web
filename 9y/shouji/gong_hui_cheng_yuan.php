<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
<title>PDENV</title>

<style>
#header{text-align:center;background-color:#99CCFF}
#contents{text-align:left;margin:10px;line-height:36px;}
#footer{text-align:center;background-color:#99CCFF}
</style>
</head>
<body bgcolor="white" text="black" style="text-align:center;">

<?php include_once("public_search.php"); public_head(); $r=do_session_check(); if ($r < 1) { public_tail();die();} 
$arr = explode('/',$_SERVER['PHP_SELF']); if (check_can_read_my($arr[count($arr)-1]) == 0) { public_tail();die();}  ?>


<?php
$friend_name = '';
if (isset($_POST["sel_friend_name"]) and $_POST["sel_friend_name"] != '') {
	$friend_name = $_POST["sel_friend_name"];
}

$friend_no_name = array();
$friend_name_id = array();
get_friend_config($friend_no_name, $friend_name_id);


$friend_no = $_SESSION["friend_no"];


if (0 == $friend_no) {
   echo <<<EOT
   <table border=0 cellpadding=10 align=center ><span class SYSTEM2>
	<form action="" method=post> 
	</select>
		<script language="javascript" type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
		<tr>
				<td>
EOT;

						echo "<select name=sel_friend_name>";
						if ($friend_name)
							echo "<option selected>$friend_name";
						foreach ($friend_no_name as &$value) {
							echo "<option>$value</option>";
						}
						echo "</select>";

	echo <<<EOT
			<td><input type=submit value="提交"></td>

		</tr>
	</form>
	</table>
EOT;
}

?>

<?php
/////////////////////////////////////////////////////////////////////
// 统计各区,每个成员信息
	if ($friend_name && $friend_name_id[$friend_name]) {
		$friend_no = $friend_name_id[$friend_name];
	}
	$link = my_connect_mysql($GLOBALS["global_db"]["chongzhi"]);
	$query = "select * from `cooperation` where `relation`= '$friend_no' ";
	$result = mysql_query($query);
	$member_info = array();
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$member_info[] = $line;
	}
	mysql_free_result($result); 

	//$member_info = get_gonghui_member($gonghui_id);
	echo "<table border=1 cellpadding=3 align=center ><span class SYSTEM2>\n";
	echo <<<EOT
 	  <tr>
 	  	  <th align="left">玩家id</th>
 	  	  <th align="left">玩家名字</th>
 	  	  <th align="left">账号</th>
		  <th align="left">公会ID</th>
		  <th align="left">激活所在区</th>
		  <th align="left">激活时间</th>
 	  </tr>
EOT;

    foreach ($member_info as $col_value) {
    	echo "\t<tr>\n";
	    if (isset($col_value)) {
	    	//print_r($col_value);
	        echo "<td  align=\"left\">$col_value[playerid]</td>\n";
	        echo "<td  align=\"left\">$col_value[playername]</td>\n";
	        echo "<td  align=\"left\">$col_value[account]</td>\n";
	        echo "<td  align=\"left\">$col_value[relation]</td>\n";
	        echo "<td  align=\"left\">$col_value[active_area]</td>\n";
	        echo "<td  align=\"left\">$col_value[activetime]</td>\n";
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



