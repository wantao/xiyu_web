<?php
	function display_picture()
	{
	
		$session_code = '';
		
		//生成验证码 
		$rand_num = rand(1, 1000000);
		$chars = dechex($rand_num);
		
		// Create a blank image and add some text
		$im = imagecreatetruecolor(60, 40);
		$text_color = imagecolorallocate($im, 233, 14, 91);
		imagestring($im, 5, 5, 5,  $chars, $text_color);
		
		{//扰乱字符
			$false_rand_num = rand(1, 1000000);
			$false_chars = dechex($false_rand_num);
			
			// Create a blank image and add some text
			$false_text_color = imagecolorallocate($im, 56, 100, 134);
			imagestring($im, 5, 5, 20,  $false_chars, $false_text_color);
			
		}
	
		// Set the content type header - in this case image/jpeg
		header('Content-Type: image/jpeg');
	
		// Output the image
		imagejpeg($im);
	
		// Free up memory
		imagedestroy($im);
		
		session_start();
		$_SESSION['session_code'] = $chars;
		return $chars;
		
	}
	
	display_picture();
?>