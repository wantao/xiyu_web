<?php
if($_SERVER['REQUEST_METHOD'] != 'POST'){
	echo "Forbidden. Only POST request is allowed.";
	return;
}
$xml = "<xml>";
$keywords = array();
require_once "utils.php";
while(list($key, $value) = each($_POST)){
	$xml = $xml . "<item><key>$key</key><value>$value</value></item>";
	$keywords["$key"] = $value;
} 
$xml = $xml . "</xml>";
$file = "keyword.setting";
file_put_contents($file, $xml);
print_r($keywords);
print_r(serialize($keywords));
print_r(unserialize(serialize($keywords)));
Utils::setCache("keywords", serialize($keywords), 600);
?>