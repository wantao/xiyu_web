<?php
define("ARTICLE_URL_DIR", "http://niuwa123.com/wechat/article/");
class Article{
	public static function getNewArticles($count = 10){
		if(!is_numeric($count)){
			return array();
		}
		$articles = array();
		require_once "utils.php";
		$result = Utils::runSqlQuery("select * from `article` order by `create_time` desc limit $count");
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
			array_push($articles, array(
				"Title" => $row["title"],
				"Description" => $row["description"],
				"PicUrl" => $row["picurl"],
				"Url" => ARTICLE_URL_DIR.$row["filename"]
			));
		}
		return $articles;
	}
	public static function addArticle($html_filename, $content, $title, $desc, $picurl){
		file_put_contents("article/".$html_filename, $content);
		require_once "utils.php";
		Utils::runSqlQuery("insert into `article` set `filename` = '$html_filename', `title` = '$title' ,`description` = '$desc', `picurl` = '$picurl'");
	}
}
?>