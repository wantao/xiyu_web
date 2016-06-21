<?php	

class ResultSet{
	public  $col;
	public  $resultset;
}

	/**
	 * 展示一个表格
	 */
	function displayResult($res)
	{
		$col = $res->col;
		$resultset = $res->resultset;
		
		//表头
		echo "<tr>";
		foreach($col as $col_name)
		{
			echo  "<th align=\"left\">";
			echo "$col_name";
			echo "</th>";
		}
		echo "</tr>";
		
		//表内容
		if (count($resultset) > 0)
			foreach ($resultset as $col_value) 
			{
				if (count($col_value) > 0)
				{
					echo "\t<tr>\n";
			    	foreach ($col_value as $elem)
			    	{
			    		echo "<td  align=\"left\">$elem</td>\n";
			    	}
			    	echo "\t</tr>\n";
				}
		    }
    }
    
    
    /**
     * 展示若干个表格
     * @param $resultsets
     */
    function displayTable($resultsets)
    {
    		echo "<table border=1 cellpadding=3 align=center ><span class SYSTEM2>\n";
    		foreach($resultsets as $resultset)
    		{
    			displayResult($resultset);
    		}
    		echo "</table>\n";    
    }
    
////////////////////////////////////////////////////////////////////////////////////////////////////

function getAllPingTai()
{
	$res = array();
	
	$link = my_connect_mysql($GLOBALS["global_db"]["game_config"]);
	$query = "SELECT * FROM ping_tai;  ";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_NUM)) {
		$res[] =  $line[2];
	}
	mysql_free_result($result); 
	
	return  $res;
	
}

/**
 * 显示平台组件
 */
function displayPingTai($unit_name, $default_value, $user_name)
{
	
	echo "<td>";
	echo "<select name=$unit_name>";
	if ($default_value)
		echo "<option selected>$default_value";
	$allPingTai = getAllPingTai();
	foreach ($allPingTai as $elem)
	{
		if (has_right_to_access_ping_tai($user_name, $elem) != 1 )
			countinue;
		else 
			echo "<option>$elem</option>";
	}
	
	echo "</select>";
	echo "</td>";
	
	return;
}
    
/////////////////////////////////////////////////////////////////////////////////////////////
function getAllAreas()
{
	$res = array();
	
	$link = my_connect_mysql($GLOBALS["global_db"]["game_config"]);
	$query = "select area_name from `area_table` ";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_NUM)) {
		$res[] =  $line[0];
	}
	mysql_free_result($result); 
	
	return  $res;
}

function displayAreas($unit_name, $default_value)
{
	
	echo "<td>";
	echo "<select name=$unit_name>";
	if ($default_value)
		echo "<option selected>$default_value";
	$allareas = getAllAreas();
	foreach ($allareas as $elem)
	{
		echo "<option>$elem</option>";
	}
	
	echo "</select>";
	echo "</td>";
	
	return;
}
    

/**
 * 查出玩家的名字
 * @param $playerid
 */
function  getPlayerNameByID($playerid, $area_no) 
{
	//页数
	$link = my_connect_mysql($GLOBALS["global_db"]["gamedb"][$area_no]);
	$query = <<<EOT
SELECT playername 
FROM player_base 
WHERE digitid = $playerid
EOT;

	$result = mysql_query($query) or die('Query failed: ' . mysql_error() . $query);
	$line = mysql_fetch_array($result, MYSQL_NUM);
	$playername='找不到玩家：';
	if ($line){
		$playername = $line[0]; 
	} else {
		$playername = $playername. "$playerid";
	}
	mysql_free_result($result);
	
	return  $playername;
}
?>

