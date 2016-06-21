<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
<title>PDENV</title>
</head>
<body bgcolor="white" text="black" style="text-align:center;">

<?php include_once("public_search.php"); public_head(); if (do_session_check() < 1) { public_tail();die();} ?>

<?php
/////////////////////////////////////////////////////////////////////

if (do_session_check() > 0) {
	normal_select($_SESSION["user_name"], $_POST["gong_hui_id"]);
}
    
?>

<?php public_tail(); ?>

</body>
</html>
