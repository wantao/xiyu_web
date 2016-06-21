<?php
	$error = "";
	$msg = "";
	$fileElementName = 'fileToUpload';
	$save_file_name = "";
	if(!empty($_FILES[$fileElementName]['error']))
	{
		switch($_FILES[$fileElementName]['error'])
		{

			case '1':
				$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
				break;
			case '2':
				$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
				break;
			case '3':
				$error = 'The uploaded file was only partially uploaded';
				break;
			case '4':
				$error = 'No file was uploaded.';
				break;

			case '6':
				$error = 'Missing a temporary folder';
				break;
			case '7':
				$error = 'Failed to write file to disk';
				break;
			case '8':
				$error = 'File upload stopped by extension';
				break;
			case '999':
			default:
				$error = 'No error code avaiable';
		}
	}elseif(empty($_FILES['fileToUpload']['tmp_name']) || $_FILES['fileToUpload']['tmp_name'] == 'none')
	{
		$error = 'No file was uploaded..';
	}else 
	{
			$msg .= " File Name: " . $_FILES['fileToUpload']['name'] . ", ";
			$msg .= " File Size: " . @filesize($_FILES['fileToUpload']['tmp_name']);
			$type_ary = explode("/", $_FILES['fileToUpload']['type']);
			$type = $type_ary[0];
			$save_file_name = md5($_FILES['fileToUpload']['tmp_name'].time());
			$save_file_path = "article/pic/";
			move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $save_file_path.$save_file_name);
			//for security reason, we force to remove all uploaded file
			@unlink($_FILES['fileToUpload']);
					
	}		
	echo "{";
	echo				"savename: '" . $save_file_name . "',\n";
	echo "}";
?>