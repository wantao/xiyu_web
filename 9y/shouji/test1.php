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
/////////////////////////////////////////////////////////////////////
if (do_session_check() > 0) {
	echo "登陆成功" ;
	// 跳转到 base_data_search.php
	$url = "new_player_everyday.php";
	echo "<script language='javascript' type='text/javascript'>";
	echo "window.location.href='$url'";
	echo "</script>";
}
?>


<?php public_tail(); ?>
</body>
</html>


