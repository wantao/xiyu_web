<script src="js_public_method.js"></script>
<script>
function execute(){
	var open_id = GetArgsFromHref(location.href, 'open_id');
	//alert(""+platform+area_id+player_id+open_id);
	if(!open_id){
		return;
	}
	var domain_prex = '<?php require_once "Config.php"; echo DOMAIN_PREFIX;?>';
	post(domain_prex+'get_award_operate.php', {'open_id':open_id});
}
</script>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>每日签到</title>
</head>
<body align="center">
<!--第一行-->
<table align="center" border="true">
<tr>
<td><table>
<?php 
	/* echo "<tr>";
	if (1 == date("w")) {
		echo "<th colspan=2 style="."\"color:red;\"".">".周一."</th>";
	} else {
		echo "<th colspan=2>".周一."</th>";
	}
	echo "</tr>";
	echo "<tr>";
	require_once "Config.php";
	$pic_a_path = DOMAIN_PIC_PREFIX."aa.jpg";
	$pic_b_path = DOMAIN_PIC_PREFIX."bb.jpg";
	echo "<td align=center><img src=\"".$pic_a_path."\" width=50 height=50/></td>";
	echo "<td align=center><img src=\"".$pic_b_path."\" width=50 height=50/></td>";
	echo "</tr>"; 
	echo "<tr>";
	echo "<td align=center>x2</td>";
	echo "<td align=center>x3</td>";
	echo "</tr>"; */
	require_once("table_operate.php");
	$weixindb = new CJifenConfig();
	$award = $weixindb->get_award();
	echo "<tr>";
	if (1 == date("w")) {
		echo "<th style="."\"color:red;\"".">".周一."</th>";
	} else {
		echo "<th>".周一."</th>";
	}
	echo "</tr>";
	echo "<tr>";
	echo "<td align=center>x".$award."积分</td>";
	echo "</tr>";
?> 
</table></td>
<td><table>
<?php 
	/* echo "<tr>";
	if (2 == date("w")) {
		echo "<th colspan=2 style="."\"color:red;\"".">".周二."</th>";
	} else {
		echo "<th colspan=2>".周二."</th>";
	}
	echo "</tr>";
	echo "<tr>";
	require_once "Config.php";
	$pic_a_path = DOMAIN_PIC_PREFIX."aa.jpg";
	$pic_b_path = DOMAIN_PIC_PREFIX."bb.jpg";
	echo "<td align=center><img src=\"".$pic_a_path."\" width=50 height=50/></td>";
	echo "<td align=center><img src=\"".$pic_b_path."\" width=50 height=50/></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td align=center>x2</td>";
	echo "<td align=center>x3</td>";
	echo "</tr>";	 */	
	require_once("table_operate.php");
	$weixindb = new CJifenConfig();
	$award = $weixindb->get_award();
	echo "<tr>";
	if (2 == date("w")) {
		echo "<th style="."\"color:red;\"".">".周二."</th>";
	} else {
		echo "<th>".周二."</th>";
	}
	echo "</tr>";
	echo "<tr>";
	echo "<td align=center>x".$award."积分</td>";
	echo "</tr>";	
?> 
</table></td>
</tr>
</table>
<!--换行-->
<tr></tr>
<!--第二行-->
<table align="center" border="true">
<tr>
<td><table>
<?php 
	/* echo "<tr>";
	if (3 == date("w")) {
		echo "<th colspan=2 style="."\"color:red;\"".">".周三."</th>";
	} else {
		echo "<th colspan=2>".周三."</th>";
	}
	echo "</tr>";
	echo "<tr>";
	require_once "Config.php";
	$pic_a_path = DOMAIN_PIC_PREFIX."aa.jpg";
	$pic_b_path = DOMAIN_PIC_PREFIX."bb.jpg";
	echo "<td align=center><img src=\"".$pic_a_path."\" width=50 height=50/></td>";
	echo "<td align=center><img src=\"".$pic_b_path."\" width=50 height=50/></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td align=center>x2</td>";
	echo "<td align=center>x3</td>";
	echo "</tr>"; */
	require_once("table_operate.php");
	$weixindb = new CJifenConfig();
	$award = $weixindb->get_award();
	echo "<tr>";
	if (3 == date("w")) {
		echo "<th style="."\"color:red;\"".">".周三."</th>";
	} else {
		echo "<th>".周三."</th>";
	}
	echo "</tr>";
	echo "<tr>";
	echo "<td align=center>x".$award."积分</td>";
	echo "</tr>";	
?> 
</table></td>
<td><table>
<?php 
	/* echo "<tr>";
	if (4 == date("w")) {
		echo "<th colspan=2 style="."\"color:red;\"".">".周四."</th>";
	} else {
		echo "<th colspan=2>".周四."</th>";
	}
	echo "</tr>";
	echo "<tr>";
	require_once "Config.php";
	$pic_a_path = DOMAIN_PIC_PREFIX."aa.jpg";
	$pic_b_path = DOMAIN_PIC_PREFIX."bb.jpg";
	echo "<td align=center><img src=\"".$pic_a_path."\" width=50 height=50/></td>";
	echo "<td align=center><img src=\"".$pic_b_path."\" width=50 height=50/></td>";
	echo "</tr>"; 
	echo "<tr>";
	echo "<td align=center>x2</td>";
	echo "<td align=center>x3</td>";
	echo "</tr>"; */
	require_once("table_operate.php");
	$weixindb = new CJifenConfig();
	$award = $weixindb->get_award();
	echo "<tr>";
	if (4 == date("w")) {
		echo "<th style="."\"color:red;\"".">".周四."</th>";
	} else {
		echo "<th>".周四."</th>";
	}
	echo "</tr>";
	echo "<tr>";
	echo "<td align=center>x".$award."积分</td>";
	echo "</tr>";
?> 
</table></td>
</tr>
</table>
<!--换行-->
<tr></tr>
<!--第三行-->
<table align="center" border="true">
<tr>
<td><table>
<?php 
	/* echo "<tr>";
	if (5 == date("w")) {
		echo "<th colspan=2 style="."\"color:red;\"".">".周五."</th>";
	} else {
		echo "<th colspan=2>".周五."</th>";
	}
	echo "</tr>";
	echo "<tr>";
	require_once "Config.php";
	$pic_a_path = DOMAIN_PIC_PREFIX."aa.jpg";
	$pic_b_path = DOMAIN_PIC_PREFIX."bb.jpg";
	echo "<td align=center><img src=\"".$pic_a_path."\" width=50 height=50/></td>";
	echo "<td align=center><img src=\"".$pic_b_path."\" width=50 height=50/></td>";
	echo "</tr>"; 
	echo "<tr>";
	echo "<td align=center>x2</td>";
	echo "<td align=center>x3</td>";
	echo "</tr>"; */
	require_once("table_operate.php");
	$weixindb = new CJifenConfig();
	$award = $weixindb->get_award();
	echo "<tr>";
	if (5 == date("w")) {
		echo "<th style="."\"color:red;\"".">".周五."</th>";
	} else {
		echo "<th>".周五."</th>";
	}
	echo "</tr>";
	echo "<tr>";
	echo "<td align=center>x".$award."积分</td>";
	echo "</tr>";
?> 
</table></td>
<td><table>
<?php 
	/* echo "<tr>";
	if (6 == date("w")) {
		echo "<th colspan=2 style="."\"color:red;\"".">".周六."</th>";
	} else {
		echo "<th colspan=2>".周六."</th>";
	}
	echo "</tr>";
	echo "<tr>";
	require_once "Config.php";
	$pic_a_path = DOMAIN_PIC_PREFIX."aa.jpg";
	$pic_b_path = DOMAIN_PIC_PREFIX."bb.jpg";
	echo "<td align=center><img src=\"".$pic_a_path."\" width=50 height=50/></td>";
	echo "<td align=center><img src=\"".$pic_b_path."\" width=50 height=50/></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td align=center>x2</td>";
	echo "<td align=center>x3</td>";
	echo "</tr>"; */
	require_once("table_operate.php");
	$weixindb = new CJifenConfig();
	$award = $weixindb->get_award();
	echo "<tr>";
	if (6 == date("w")) {
		echo "<th style="."\"color:red;\"".">".周六."</th>";
	} else {
		echo "<th>".周六."</th>";
	}
	echo "</tr>";
	echo "<tr>";
	echo "<td align=center>x".$award."积分</td>";
	echo "</tr>";
?> 
</table></td>
</tr>
</table>
<!--换行-->
<tr></tr>
<table align="center" border="true">
<tr>
<td><table>
<?php 
	/* echo "<tr>";
	if (0 == date("w")) {
		echo "<th colspan=2 style="."\"color:red;\"".">".周日."</th>";
	} else {
		echo "<th colspan=2>".周日."</th>";
	}
	echo "</tr>";
	echo "<tr>";
	require_once "Config.php";
	$pic_a_path = DOMAIN_PIC_PREFIX."aa.jpg";
	$pic_b_path = DOMAIN_PIC_PREFIX."bb.jpg";
	echo "<td align=center><img src=\"".$pic_a_path."\" width=50 height=50/></td>";
	echo "<td align=center><img src=\"".$pic_b_path."\" width=50 height=50/></td>";
	echo "</tr>"; 
	echo "<tr>";
	echo "<td align=center>x2</td>";
	echo "<td align=center>x3</td>";
	echo "</tr>"; */
	require_once("table_operate.php");
	$weixindb = new CJifenConfig();
	$award = $weixindb->get_award();
	echo "<tr>";
	if (1 == date("w")) {
		echo "<th style="."\"color:red;\"".">".周日."</th>";
	} else {
		echo "<th>".周日."</th>";
	}
	echo "</tr>";
	echo "<tr>";
	echo "<td align=center>x".$award."积分</td>";
	echo "</tr>";
?> 
</table></td>
</tr>
</table>
<!--换行-->
<tr></tr>
<!--设置按钮和上面图片之间的行距-->
<p style="line-height:100%">
<button type="button" onclick="execute()">点击领取</button>
</p>
</body>
</html>