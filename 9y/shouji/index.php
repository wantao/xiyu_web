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

	
	<?php
		session_start();
		$_SESSION['admin']=false;
		include_once("public_search.php");
		public_head(); 
	?>
	
	<style type="text/css">
	.iw{width:200px;}
	</style>
	<div style="margin-top:40px;">
	<form id="loginform" method="post" action=test.php >
		<table align="center" style="width:450px;">
			<td><?php echo '用户名称'?>:</td>
			<td><input name="user_name" type="text" id="user_name" class="iw" /></td>
			</tr>
			<tr>
			<td><?php echo '密码'?>:</td>
			<td><input name="user_password" type="password" id="user_password" class="iw" /></td>
			</tr>
			
			
			<tr>
			<td>验证码(红色字符)  </td>
			<td><input type="text" name=session_code value="" /></td>
			<td><img src="validatecode.php"/></td>
			</tr>
			
			
			<tr>
			  <td colspan="2"><div align="center">
			    <button type="submit" id="submitbtn"><?php echo '进入'?></button>
			  </div></td>
			  </tr>
		</table>
	</form>
	</div>
		
	<?php public_tail(); ?>
</body>
</html>
