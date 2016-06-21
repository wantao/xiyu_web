<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
<title>PDENV</title>
</head>
<body bgcolor="white" text="black" style="text-align:center;">

<?php include_once("public_search.php"); public_head(); $r=do_session_check(); if ($r < 1) { public_tail();die();} 
$arr = explode('/',$_SERVER['PHP_SELF']); if (check_can_read_my($arr[count($arr)-1]) == 0) { public_tail();die();}  ?>

<?php
/////////////////////////////////////////////////////////////////////

if (do_session_check() > 0) {
	if (isset($_POST["s_begin_time"]) and $_POST["s_end_time"] != '') {
	} else {
		echo <<<EOT
		<a href="test1.php">返回</a><P>
EOT;
		die("请输入要查询时间范围");
	}
	
	// 取得选择的区名称
	if (isset($_POST["sel_area_name"])) {
		$_SESSION["shou_ru_cha_xun_sel_area_name"] = $_POST["sel_area_name"];
	} else {
		$_SESSION["shou_ru_cha_xun_sel_area_name"] = "全区";
	}
	
	cha_xun_shou_ru($_SESSION["shou_ru_cha_xun_sel_area_name"], $_POST["s_begin_time"], $_POST["s_end_time"]);
}
?>


</body>
</html>


