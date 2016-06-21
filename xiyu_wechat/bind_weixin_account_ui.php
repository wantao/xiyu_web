<html>
<head>
<script src="js_public_method.js"></script>
<script>
window.onload=function(){
	var platform = GetArgsFromHref(location.href, 'platform');
    var area_id = GetArgsFromHref(location.href, 'area_id');
	var player_id = GetArgsFromHref(location.href, 'player_id');
	var open_id = GetArgsFromHref(location.href, 'open_id');
	//alert(""+platform+area_id+player_id+open_id);
	if(!platform || !area_id || !player_id || !open_id){
		return;
	}
	var domain_prex = '<?php require_once "Config.php"; echo DOMAIN_PREFIX;?>';
	post(domain_prex+'bind_weixin_account_operate.php', {'platform':platform, 'area_id':area_id, 'player_id':player_id, 'open_id':open_id});
};

function execute(){
	var platform = document.getElementById("platform").value; 
	var area_id = document.getElementById("area_id").value; 
	var player_id = document.getElementById("player_id").value; 
	var open_id = GetArgsFromHref(location.href, 'open_id');
	//alert(""+platform+area_id+player_id+open_id);
	if(!platform || !area_id || !player_id || !open_id){
		return;
	}
	var domain_prex = '<?php require_once "Config.php"; echo DOMAIN_PREFIX;?>';
	post(domain_prex+'bind_weixin_account_operate.php', {'platform':platform, 'area_id':area_id, 'player_id':player_id, 'open_id':open_id});
}

</script>


<style type="text/css">
body
{
  text-align:center; 
  height:100%;
  width:950px;
  margin:0 atuto;
 <!-- transform: scale(1.2);-->
}
BODY {background-image: URL(yxjs.jpg); 
background-position: center; 
background-repeat: no-repeat; 
background-size:cover;
background-attachment: fixed;
} 
</style>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>绑定游戏帐号</title>
</head>
<body>
<!--<form action="bind_weixin_account_operate.php" method="POST">-->
<a></a>
<a></a>
<a></a>
<a>请选择游戏平台</a><br>
<a><input id="platform" type="text" name="platform" value=""></a><br>
<!--<a><input type="text" name="platform" value=""></a><br>-->
<a>请选择游戏区服</a><br>
<!--<a><input type="text" name="area_id" value=""></a><br>-->
<a><input id="area_id" type="text" name="area_id" value=""></a><br>
<a>请选择角色ID</a><br>
<!--<a><input type="text" name="player_id" value=""></a><br>-->
<a><input id="player_id" type="text" name="player_id" value=""></a><br>
<button type="button" onclick="execute()">立即绑定</button>
<!-- <input type="submit" value="立即绑定"> -->
<!--</form>-->
</body>
</html>