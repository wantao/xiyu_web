<?php
    require_once  '..\unity\self_http.php';
    require_once  '..\unity\self_global.php';
    
    $platform_id = 0;
  
    //测试获取uid和token
    echo "test get_user_id_and_token.php"."<br>";
    $user_id_and_token_info = trim(trim(test_get_user_id_and_token()),chr(239).chr(187).chr(191));
    $user_id_and_token_info = json_decode($user_id_and_token_info);
    print_r($user_id_and_token_info);
    //认证uid和token
    echo "<br>"."test auth_user_id_and_token.php"."<br>";
    $auth_uid_token_info = trim(trim(test_auth_user_id_and_token($user_id_and_token_info->user_id,$user_id_and_token_info->token)),chr(239).chr(187).chr(191));
    $auth_uid_token_info = json_decode($auth_uid_token_info);
    print_r($auth_uid_token_info);
    //许可协议测试
    echo "<br>"."test set_accept_license.php"."<br>";
    $set_accept_info = trim(trim(test_set_accept_license($user_id_and_token_info->user_id,$platform_id)),chr(239).chr(187).chr(191));
    $set_accept_info = json_decode($set_accept_info);
    print_r($auth_uid_token_info);
    //登陆测试
    echo "<br>"."test login_auth.php"."<br>";
    //function test_login_auth($token,$user_id,$platform_id)
    $login_auth_info = trim(trim(test_login_auth($user_id_and_token_info->token,$user_id_and_token_info->user_id,$platform_id)),chr(239).chr(187).chr(191));
    $login_auth_info = json_decode($login_auth_info);
    print_r($login_auth_info);
    
    //测试获取区表
    echo "<br>"."test test_get_server_list.php"."<br>";
    $get_server_list_info = trim(trim(test_get_server_list($user_id_and_token_info->token,$user_id_and_token_info->user_id,$platform_id)),chr(239).chr(187).chr(191));
    $get_server_list_info = json_decode($get_server_list_info);
    print_r($get_server_list_info);
    //测试获区信息
    $server_id_array = $get_server_list_info->server_list;
    $server_id = 0;
    foreach ($server_id_array as $key=>$value) {
        $server_id = $value->id;
        break;
    }
    //$server_id = $server_id_array[0]->id;
    echo "<br>"."test test_get_server_info.php"."<br>";
    $get_server_list_info = trim(trim(test_get_server_info($user_id_and_token_info->token,$user_id_and_token_info->user_id,$platform_id,$server_id)),chr(239).chr(187).chr(191));
    $get_server_list_info = json_decode($get_server_list_info);
    print_r($get_server_list_info);
    //测试获取区公告
    echo "<br>"."test test_get_notice.php"."<br>";
    $get_notice_info = trim(trim(test_get_notice($server_id)),chr(239).chr(187).chr(191));
    $get_notice_info = json_decode($get_notice_info);
    print_r($get_notice_info);
    
    
    
    function test_get_user_id_and_token()
    {
        $account="aaaa";
        $password='123456';
        $http = new CMyHttp();
        $params = "account=$account&password=$password";
        $url = global_url_prefix::e_login_dir.'get_user_id_and_token.php';
        return $http->post($url, $params);
    }
    
    function test_auth_user_id_and_token($user_id,$token)
    {
        $http = new CMyHttp();
        $params = "user_id=$user_id&token=$token";
        $url = global_url_prefix::e_login_dir.'auth_user_id_and_token.php';
        return $http->post($url, $params);
    }
    
    function test_set_accept_license($user_id,$platform_id)
    {
        $http = new CMyHttp();
        $params = "user_id=$user_id&platform_id=$platform_id";
        $url = global_url_prefix::e_login_dir.'set_accept_license.php';
        return $http->post($url, $params);
    }
    
    function test_login_auth($token,$user_id,$platform_id)
    {
        $http = new CMyHttp();
        $params = "token=$token&user_id=$user_id&platform_id=$platform_id";
        $url = global_url_prefix::e_login_dir.'login_auth.php';
        return $http->post($url, $params);
    }
    
    function test_get_server_list($token,$user_id,$platform_id)
    {
        $http = new CMyHttp();
        $params = "token=$token&user_id=$user_id&platform_id=$platform_id";
        $url = global_url_prefix::e_root_dir.'get_server_list.php';
        return $http->post($url, $params);
    }
    
    function test_get_server_info($token,$user_id,$platform_id,$server_id)
    {
        $http = new CMyHttp();
        $params = "token=$token&user_id=$user_id&platform_id=$platform_id&server_id=$server_id";
        $url = global_url_prefix::e_root_dir.'get_server_info.php';
        return $http->post($url, $params);
    }
    
    function test_get_notice($server_code)
    {
        $http = new CMyHttp();
        $params = "server_code=$server_code";
        $url = global_url_prefix::e_root_dir.'get_notice.php';
        return $http->post($url, $params);
    }
    
    
?>