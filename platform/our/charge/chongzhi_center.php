<script>
function post(URL, PARAMS) {      
    var temp = document.createElement("form");      
    temp.action = URL;      
    temp.method = "post";      
    temp.style.display = "none";      
    for (var x in PARAMS) {      
        var opt = document.createElement("textarea");      
        opt.name = x;      
        opt.value = PARAMS[x];      
        // alert(opt.name)      
        temp.appendChild(opt);      
    }      
    document.body.appendChild(temp);      
    temp.submit();      
    return temp;      
}      
function GetArgsFromHref(sHref, sArgName) 
{ 
var args = sHref.split("?"); 
var retval = ""; 
if(args[0] == sHref) /*参数为空*/ 
{ 
return retval; /*无需做任何处理*/ 
} 
var str = args[1]; 
args = str.split("&"); 
for(var i = 0; i < args.length; i ++) 
{ 
str = args[i]; 
var arg = str.split("="); 
if(arg.length <= 1) continue; 
if(arg[0] == sArgName) retval = arg[1]; 
} 
return retval; 
}

window.onload=function(){
	var game_order = GetArgsFromHref(location.href, 'game_order');
	var transaction_id = GetArgsFromHref(location.href, 'transaction_id');
	alert(""+game_order+transaction_id);
	if(!game_order || !transaction_id ){
		return;
	}
	var url_prefix = <?php require_once  '..\..\..\unity\self_global.php'; echo global_url_prefix::e_charge_dir;?>;
	post(url_prefix.'platform.php', {'game_order' :game_order, 'transaction_id':transaction_id});
};
</script>
<html>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>充值中心</title>
<body>
<form action="platform.php" method="POST">
<a>game_order<input type="text" name="game_order" value=<?php echo $_POST['game_order']?>></a><br>
<a>transaction_id<input type="text" name="transaction_id" value=<?php echo time() ?>></a><br>
<input type="submit" value="submit">
</form>
</body>
</html>