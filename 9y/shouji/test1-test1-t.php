<?
$p=$_GET['p'];
$p = max(1, (int) $_GET['p']); 
$page=$p*20;
$url='http://m.baidu.com/img?tn=bdjsonliulan&pu=sz%401320_2001&bd_page_type=1&tag1=%E7%BE%8E%E5%A5%B3&realword=%E7%BE%8E%E5%A5%B3&word=%E7%BE%8E%E5%A5%B3&pn='.$page.'&rn=20';
$jsoncode=file_get_contents($url);
$mm=json_decode($jsoncode);
for($i=0;$i<20;$i++)
{
	echo "<center><img width=\"640px\" src=\"" . $mm->data[$i]->picurl . "\"></center><br>";
}



$p=$p+1;
echo '<center><a href="?p='.$p.'">Next Page</a></center>';
?>

