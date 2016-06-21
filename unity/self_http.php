<?php
/*
http����ͨ����
*/
class CMyHttp
{
    function post($url, $post_data = '', $timeout = 5){//curl
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_POST, 1);
        if($post_data != ''){
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
    }
    
    function get($url,$timeout = 5){//curl
        $ch = curl_init();
        //����ѡ�����URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    // https���� ����֤֤���hosts
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        
        //ִ�в���ȡHTML�ĵ�����
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
    }
}
?>