<table border=1 align="center">
		<?php
			echo "<tr>";
			foreach($property_name as $property => $name){
				if ('type' == $property)continue;
				if (($type != 7 && $type !=8) && 'get_award_time' == $property )continue;
				echo "<th>$name</th>";
			}
			echo "</tr>";
			foreach($info as $entry){
				echo "<tr>";
				$id = 0;
				foreach($property_name as $property => $name){
					if ('type' == $property)continue;
					if (($type != 7 && $type !=8) && 'get_award_time' == $property )continue;
					if($id == 0){
						$id = $entry->$property;
					}
					echo "<td>{$entry->$property}</td>";
				}
				$answer = "<button onclick=opt_edit($area_id,$id)>".LG_MODIFY."";
				echo "<td>$answer</td>";
				echo "</tr>";
			}
		?>
</table>
