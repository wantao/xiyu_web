<html>
<head>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script>
function addRule(){
	var ruleList = $("div.ruleList");
	ruleList.append('<div class="rule"><a>key:<input type="text" class="keyWord" value=""></a><a>value:<input type="text" class="valueWord" value=""></a></div>');
}
function saveRule(){
	var form = $(document.createElement("form"));
	form.attr("action", "edit.php").attr("method", "post");
	$("div.ruleList .rule").each(function(index){
		var keyWord = $(this).find("input.keyWord").val();
		var valueWord = $(this).find("input.valueWord").val();
		if(!keyWord || !valueWord){
			return;
		}
		if(keyWord.length > 10 || valueWord.length > 10){
			alert((index + 1) + "th key or word is too long");
			return;
		}
		$(document.createElement("input")).attr("name", keyWord).attr("value", valueWord).appendTo(form);
	});
	form.submit();
}
</script>
</head>
<body>
<div class="ruleList">
<?php 
$filename = "keyword.setting";
if(file_exists($filename)){
	$xml_string = file_get_contents($filename);
	$xml = simplexml_load_string($xml_string);
	foreach($xml->item as $item){
		echo '<div class="rule"><a>key:<input type="text" class="keyWord" value="'.$item->key.'"></a><a>value:<input type="text" class="valueWord" value="'.$item->value.'"></a></div>';
	}
}
?>
</div>
<button onclick="addRule();">Add</button>
<button onclick="saveRule();">Save</button>
</body>
</html>