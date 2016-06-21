<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<head>
<title>后台管理页面</title>
<style type="text/css"> 
/* CSS Document */ </p> <p>body { 
font: normal 11px auto "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif; 
color: #4f6b72; 
background: #E6EAE9; 
} </p> <p>a { 
color: #c75f3e; 
} </p> <p>#mytable { 
width: 700px; 
padding: 0; 
margin: 0; 
} </p> <p>caption { 
padding: 0 0 5px 0; 
width: 700px; 
font: italic 11px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif; 
text-align: right; 
} </p> <p>th { 
font: bold 11px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif; 
color: #4f6b72; 
border-right: 1px solid #C1DAD7; 
border-bottom: 1px solid #C1DAD7; 
border-top: 1px solid #C1DAD7; 
letter-spacing: 2px; 
text-transform: uppercase; 
text-align: left; 
padding: 6px 6px 6px 12px; 
background: #CAE8EA url(images/bg_header.jpg) no-repeat; 
} </p> <p>th.nobg { 
border-top: 0; 
border-left: 0; 
border-right: 1px solid #C1DAD7; 
background: none; 
} </p> <p>td { 
border-right: 1px solid #C1DAD7; 
border-bottom: 1px solid #C1DAD7; 
background: #fff; 
font-size:11px; 
padding: 6px 6px 6px 12px; 
color: #4f6b72; 
} </p> <p>
td.alt { 
background: #F5FAFA; 
color: #797268; 
} </p> <p>th.spec { 
border-left: 1px solid #C1DAD7; 
border-top: 0; 
background: #fff url(images/bullet1.gif) no-repeat; 
font: bold 10px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif; 
} </p> <p>th.specalt { 
border-left: 1px solid #C1DAD7; 
border-top: 0; 
background: #f5fafa url(images/bullet2.gif) no-repeat; 
font: bold 10px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif; 
color: #797268; 
} 
/*---------for IE 5.x bug*/ 
html>body td{ font-size:11px;} 
</style>

</head>
<body>
<table border="1" width="100%" cellSpacing=1 cellPadding=1 align="center">
<tr>
<?php
	foreach ($pages as $page){
		if($page["title"] == $current_page){
			echo "<td><a><b>" . $page["title"] . "</b></a></td>";
		}
		else{
			echo "<td><a href=" . $page["url"] . ">" . $page["title"] . "</a></td>";
		}
	}
?>
<td><a href="/index.php/login/logout"><?php echo LG_LOGOUT?></a></td>
</tr>
</table>