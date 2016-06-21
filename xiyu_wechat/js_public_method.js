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