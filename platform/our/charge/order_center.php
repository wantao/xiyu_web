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
	var player_id = GetArgsFromHref(location.href, 'player_id');
    var area_id = GetArgsFromHref(location.href, 'area_id');
	var money = GetArgsFromHref(location.href, 'money');
    var currency = GetArgsFromHref(location.href, 'currency');
    var yuanbao = GetArgsFromHref(location.href, 'yuanbao');
    var shop_type = GetArgsFromHref(location.href, 'shop_type');
    var product_id = GetArgsFromHref(location.href, 'product_id');
    var item_id = GetArgsFromHref(location.href, 'item_id');
	alert(""+player_id+area_id+money+currency+yuanbao+shop_type+product_id+item_id);
	if(!player_id || !area_id || !money || !currency || !yuanbao || !shop_type || !product_id || !item_id){
		return;
	}
	post('http://127.0.0.1/xiyu/platform/our/generate_order.php', {'player_id' :player_id, 'area_id':area_id, 'money':money,
    'currency' :currency, 'yuanbao':yuanbao, 'shop_type':shop_type,'product_id':product_id,'item_id':item_id});
};
</script>
<html>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>下单中心</title>
<body>
<form action="generate_order.php" method="POST">
<a>player_id<input type="text" name="player_id" value=""></a><br>
<a>area_id<input type="text" name="area_id" value=""></a><br>
<a>money<input type="text" name="money" value=""></a><br>
<a>currency<input type="text" name="currency" value=""></a><br>
<a>yuanbao<input type="text" name="yuanbao" value=""></a><br>
<a>shop_type<input type="text" name="shop_type" value=""></a><br>
<a>product_id<input type="text" name="product_id" value=""></a><br>
<a>item_id<input type="text" name="item_id" value=""></a><br>
<input type="submit" value="submit">
</form>
</body>
</html>