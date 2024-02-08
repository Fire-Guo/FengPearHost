<?php  
$serconfig=require("config.php");
// login.php  
  
// OAuth 2.0相关配置  
$oauth_server_authorize_url = $serconfig["oauthauthorize"]; // OAuth服务器的授权URL  
$client_id = $serconfig["clientid"]; // 你在OAuth服务器上注册的客户端ID  
$redirect_uri = 'https://host.flweb.cn/callback/'; // OAuth服务器授权后的回调URL  
  
// 生成一个随机的state参数，用于防止跨站请求伪造攻击  
$state = bin2hex(random_bytes(16));  
session_start();  
$_SESSION['oauth_state'] = $state; // 将state存储在session中以便后续验证  
  
// 构建OAuth授权URL  
$url_params = [  
    'response_type' => 'code', // 授权类型，这里使用授权码模式  
    'client_id' => $client_id, // 客户端ID  
    'redirect_uri' => $redirect_uri, // 重定向URI  
    'state' => $state, // 附加state参数  
    // 还可以添加其他需要的参数，如scope等  
];  
  
// 重定向到OAuth服务器的登录页面  
$auth_url = $oauth_server_authorize_url . '?' . http_build_query($url_params);  
header('Location: ' . $auth_url);  
exit;  
?>