<?php
define("ARTICLE_DIR", "article/");
define("PIC_DIR", "article/pic/");
define("WECHAT_URL", "http://niuwa123.com/wechat/");
$max_length = array(
	"title" => 100,
	"description" => 100,
	"picurl" => 255,
);
while(list($key, $value) = each($max_length)){
	if(strlen($_POST["$key"]) > $value){
		return "$key is out of length";
	}
}
if(!isset($_POST["content"])){
	echo "no content";
	return;
}

$content = $_POST["content"];
if(strlen($content) == 0){
	echo "content is null";
	return;
}
$content = makeHtmlContent($content);
$title = (isset($_POST["title"]) ? $_POST["title"] : "");
$description = (isset($_POST["description"]) ? $_POST["description"] : "");
$picurl = (isset($_POST["picurl"]) ? $_POST["picurl"] : "");
if(strpos($picurl, "/") === false){
	$picurl = WECHAT_URL . PIC_DIR . $picurl;
}



//echo $content;
$html_filename = time().".html";
require_once "article.php";
Article::addArticle($html_filename, $content, $title, $description, $picurl);

function makeHtmlContent($content){
	$imgpos = 0;
	$startflag = "[img]";
	$endflag = "[/img]";
	while(($imgpos = strpos($content, $startflag, $imgpos))!==false){
	//$imgpos = strpos($content, $startflag, $imgpos);	
		$imgendpos = strpos($content, $endflag, $imgpos);
		$filename = substr($content, $imgpos+strlen($startflag), $imgendpos-($imgpos+strlen($startflag)));
		echo $filename;
		if(file_exists("article/pic/$filename")){
			$src = "<img src=pic/$filename></img>";
			echo "file exists";
		}
		$content = str_replace("[img]".$filename."[/img]", $src, $content);
		$imgpos = $imgendpos + strlen($endflag);
	}
	
	return $content;
}
?>