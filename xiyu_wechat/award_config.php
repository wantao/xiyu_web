<?php
$award_config = array(
	//周日
	'0'=>'3:10,10000001:2',
	//周一
	'1'=>'3:60,10000001:3',
	//周二
	'2'=>'3:100,10000001:5',
	//周三
	'3'=>'3:150,10000001:6',
	//周四
	'4'=>'3:200,10000001:10',
	//周五
	'5'=>'3:250,10000001:20',
	//周六
	'6'=>'3:300,10000001:30',
);

function get_award() {
	global $award_config;
	return $award_config[date("w")];
}

?>