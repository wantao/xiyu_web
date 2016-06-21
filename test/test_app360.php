<?php

		$url = "https://api.app360.vn/scoped_id/v1/sessions/";
		$access_token='eyJhbGciOiJIUzI1NiJ9.eyJzY29wZWRfaWQiOiIxMzMzMDhjMC1kZWEyLTRiODItOTE5Mi0yZGUwYjliMzkwNjMiLCJjaGFubmVsIjoidGVzdF9jaGFubmVsXzIiLCJjbGllbnRfaWQiOiIxMDA0MDk1ODQzNDAyOTI3MDY0OTQ2Iiwic3ViX2NoYW5uZWwiOiJ0ZXN0X3N1Yl9jaGFubmVsXzIifQ.fNt-J7jyxwje5qDC7ec2765WpferLVh-f6lmDEG0ZuQ';
		$json_r = array();
        $ci = curl_init();
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ci, CURLOPT_TIMEOUT, 30);
  
        $headers[] = "Authorization: Bearer ".$access_token;
 
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ci, CURLOPT_URL, $url);
 
        $response = curl_exec($ci);
        print_r($response);
        curl_close($ci);
        if($response != '')
        {
            $json_r = json_decode($response, true);
        }
        print_r($json_r);
?>