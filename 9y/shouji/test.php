<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
<title>PDENV</title>
</head>
<body bgcolor="white" text="black" >

</body>
</html>


<?php
include_once("public_search.php"); 
include_once("my_global.php");
session_start();

$posts = $_POST;

//先验证验证码
 if ($_SESSION['session_code'] != $posts['session_code'])
 {
 	echo "用户名或密码错误" ;
 	return ;
 }
 	

foreach ($posts as $key=>$value)
{
	$posts[$key] = trim($value);
}

$_SESSION["admin"] = null;
$user_name = $posts["user_name"];
$pass_word = $posts["user_password"];
$link = my_connect_mysql($GLOBALS["global_db"]["chongzhi"]);
$query = " SELECT * FROM `read_user` WHERE `user_name`='$user_name' ";
$result = mysql_query($query) or die('Query failed: ' . mysql_error());

echo "<table>\n";
$line = mysql_fetch_array($result, MYSQL_ASSOC);
if ($line) {
	if ($line["user_password"] == md5($pass_word) && $line['enable'] > 0){
		// 验证通过
		session_start();
		$_SESSION["admin"] = true;
		$_SESSION["friend_no"] = $line["friend_no"];
		$_SESSION["user_name"] = $user_name;
		$_SESSION["cur_select_hui_yuan_friend_page_no"] = 1;
		$_SESSION['user_priv'] = $line['priv'];
		$_SESSION['user_enable'] = $line['enable'];
		$_SESSION['ping_tai_id'] = $line['ping_tai'];
		
		print_r($_SESSION);
		
		// 跳转到 test1.php
		$url = "test1.php";
		echo "<script language='javascript' type='text/javascript'>";
		echo "window.location.href='$url'";
		echo "</script>";
	} else {
		echo "用户名或密码错误" ;
	}
} else {
	echo "用户名或密码错误";
}

// 释放结果集
mysql_free_result($result);
?>

