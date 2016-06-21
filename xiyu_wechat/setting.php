<html>
<head>
<meta charset="utf8">
<script>
var xmlHttp;
function request(url, cmd, arg, ac, pwd){
	xmlHttp = GetXmlHttpObject();
	if(xmlHttp == null){
		return;
	}	
	var dest = "";
	xmlHttp.onreadystatechange = stateChanged;
	xmlHttp.setRequestHeader("Content-Type", "multipart/form-data; charset=UTF-8");
	xmlHttp.open("post", dest, true);//true：异步方式  false：同步方式
	xmlHttp.send(null);
}

function stateChanged(){
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{ 
		document.getElementById("result").innerHTML += xmlHttp.responseText + "<br>";
		execute_next();
	}
}

function GetXmlHttpObject()
{
var xmlHttp=null;
try
 {
 // Firefox, Opera 8.0+, Safari
 xmlHttp=new XMLHttpRequest();
 }
catch (e)
 {
 // Internet Explorer
 try
  {
  xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
  }
 catch (e)
  {
  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
 }
return xmlHttp;
}

</script>
</head>
<body>
<form action="setting.php" method="put" enctype="multipart/form-data">
<label for="file">Filename:</label>
<input type="file" name="file" id="file" /> 
<br />
<input type="submit" name="submit" value="Submit" />
</form>
<?php echo "$_PUT"?>
</body>
</html>