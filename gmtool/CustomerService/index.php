<script src="md5.js"></script>
<script type="text/javascript">
var xmlHttp;
function request(page){
	xmlHttp = GetXmlHttpObject();
	if(xmlHttp == null){
		return;
	}
	var name = document.getElementById("player_name").value;
	var dest = "search.php";
	var str;
	if(name){
		str = ("name=" + name);
	}
	if(page){
		if(str){
			str += "&";
		}
		str = ("page=" + page);
	}
	xmlHttp.onreadystatechange = stateChanged;
	xmlHttp.open("post", dest, true);//true：异步方式  false：同步方式
	if(str){
		xmlHttp.setRequestHeader("CONTENT-TYPE","application/x-www-form-urlencoded");
		xmlHttp.send(str);
	}else{
		xmlHttp.send(null);
	}
	document.getElementById("result").innerHTML = "正在查询，请稍候";
}

function stateChanged(){
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{ 
		document.getElementById("result").innerHTML = xmlHttp.responseText + "<br>";
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

function question(method){
	if(!method){
		return;
	}
	var key = "asd234hks";
	var f = document.getElementById("question_" + method);
	var inputs = f.getElementsByTagName("input");
	
	addinput(f, "key",MD5("" + inputs[0] + inputs[1] + key));
	addinput(f, "player_name", "test");
	addinput(f, "area_id", 1);
	addinput(f, "area_name", "Server 1");

	f.action = "question.php";
	f.method = method;
	f.submit();
}

function addinput(root, name, value){
	var d = document.createElement("input");
	d.type = "hidden";
	d.name = name;
	d.value = value;
	root.appendChild(d);
}

function answer(id){
	var form = document.createElement("form");
	form.action = "answer.php";
	form.method = "get";
	var input = document.createElement("input");
	input.name = "id";
	input.value = id;
	form.appendChild(input);
	form.submit();
}

window.onload = function(){
	request();
}
 
</script>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf8" />

<head>
Customer Servie
</head>

<body>
<a>玩家的名称</a><input type="text" name="name" id="player_name"/><button type="button" onclick=request()>查询</button>
<div id="result">
</div>
<!--
<form id="question_post" method="post">
<input name="player_digitid" /><input name="area_id" /><input name="question" />
</form>
<button onclick="question('post')">post</button>
<form id="question_get" method="get">
<input name="player_digitid" /><input name="page" />
</form>
<button onclick="question('get')">get</button>
-->
</body>

</html>