<script src="<?php $this->load->helper('url');echo base_url("jquery-1.10.2.js");?>"></script>
<script src="<?php $this->load->helper('url');echo base_url("jquery-ui.js");?>"></script>

<script language="javascript" type="text/javascript" src="<?php $this->load->helper('url');echo base_url("My97DatePicker/WdatePicker.js");?>"></script>

<script type="text/javascript">
var xmlHttp;
function request(area, type){
	xmlHttp = GetXmlHttpObject();
	if(xmlHttp == null){
		return;
	}
	var dest = "/index.php/system_activity/execute/_"+
	area +"_/_" +
	type + "_/";;
	xmlHttp.onreadystatechange = stateChanged;
	xmlHttp.open("get", dest, true);//true：异步方式  false：同步方式
	xmlHttp.send(null);
	document.getElementById("result").innerHTML = "<?php echo LG_QUERY_PROMPT?>";
}

function stateChanged(){
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{ 
		document.getElementById("result").innerHTML = xmlHttp.responseText + "<br>";
	}
}

function GetXmlHttpObject()
{
var xmlHttp=null;
try
 {
 // Firefox, Opera 8.0+, Safari
 xmlHttp=new XMLHttpRequest();
 }
catch (e)
 {
 // Internet Explorer
 try
  {
  xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
  }
 catch (e)
  {
  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
 }
return xmlHttp;
}

function take_effect(){
	var form = document.createElement("form");
	form.action = "/index.php/system_activity/execute_take_effect/_" + 
	document.getElementById("area").value +"_/_" +
	document.getElementById("type").value + "_/";
	form.method = "post";
	form.submit();
}

function input_file(){
	form_input.action = "/index.php/gm_plan/add_plan/_" + 
	document.getElementById("plan_name").value +"_/_" +
	document.getElementById("area_id").value +"_/_" +
	document.getElementById("gm_account").value +"_/_" +
	document.getElementById("gm_cmd").value +"_/_" +
	document.getElementById("gm_cmd_param").value +"_/_" +
	document.getElementById("execute_time").value + "_/";
	form_input.method = "post";
	form_input.submit();
}

function opt_delete(id) {
	var form = document.createElement("form");
	form.action = "/index.php/gm_plan/del_plan/_" + 
	id + "_/";
	form.method = "post";
	form.submit();
}


</script>
<meta http-equiv="content-type" content="text/html;charset=utf8" />
<head>
</head>
<body>

<table align="center">
<tr valign="top">
	<td>plan_name</td>
	<td><input id="plan_name" type="text" /></td>
</tr>

<tr valign="top">
	<td>area_id</td>
	<td>
		<select name="area" id="area_id">
		<?php 
			foreach($area_list as $area){
				if (1 == $area['selected']) {
					echo "<option value=". $area['id']." selected>".$area['name'].$area['id']."</option>";	
				} else {
					echo "<option value=". $area['id'].">".$area['name'].$area['id']."</option>";	
				}
			}
		?>
		</select>
	</td>
</tr>

<tr valign="top">
	<td>gm_account</td>
	<td><input id="gm_account" type="text" /></td>
</tr>

<tr valign="top">
	<td>gm_cmd</td>
	<td><input id="gm_cmd" type="text" /></td>
</tr>

<tr valign="top">
	<td>gm_cmd_param</td>
	<td><input id="gm_cmd_param" type="text" /></td>
</tr>

<tr valign="top">
	<td>begin_execute_time</td>
	<td><input type="text" class="Wdate" name="execute_time" id="execute_time" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'2013-01-01 00:00:00',maxDate:'2020-01-01 00:00:00'})" value="<?php echo $execute_time;?>"/></td>
</tr>

<tr valign="top">
	<td> select file </td>
	<td>
	<form  enctype="multipart/form-data" id="form_input" >
	<div class="ehdel_upload_show">
	<input id="ehdel_upload_text" type="text" name="txt" />
	<span style="font-size:12px;">&nbsp&nbsp&nbsp</span>
	<input type="file" name="up_file"  onchange="ehdel_upload_text.value=this.value" class="ehdel_upload" />
	</div>

	<div class="clear">
	</p></p>
	<button type="button" onclick="input_file()"><?php echo "submit"?></button>
	</div>

	</form>
	</td>
</tr>


<tr>
	<td><?php echo $execut_msg; ?></td>
</tr>

</table>

</p></p></p>

<table border="1" width="100%" cellSpacing=1 cellPadding=1 align="center">
	<?php
		echo "<tr>";
		foreach($gm_plan['property_name'] as $property => $name){
			echo "<td><strong> $name </strong></td>";
		}
		echo "</tr>";

		foreach($gm_plan['info'] as $entry){ 
			$number = 0;
			$idx = 0;
			$has_execute=0;
			echo "<tr>";
			foreach ($entry as $key => $value) {
				$number += 1;
				if(1 == $number){
					$idx = $value;
				}
				if (8 == $number) {
					if ($value != 0)
						$has_execute = 1;
				}
				echo "<td> $value </td>";			
			}
			//$answer = "<button onclick=opt_edit($idx)>".LG_MODIFY."</button><button onclick=opt_delete($idx)>".LG_DELETE."</button>";
			$answer = "</button><button onclick=opt_delete($idx)>".LG_DELETE."</button>";
			if (0 == $has_execute) {
				echo "<td>$answer</td>";
			}
			echo "</tr>";
		}
		
	?>

	<tr>
		
	</tr>

</table>


