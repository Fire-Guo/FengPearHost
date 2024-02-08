<?php  
$serconfig=require("../config.php");
session_start(); // 开始会话  
  
// 检查是否存在session中的state  
if (empty($_SESSION['oauth_state']) || empty($_GET['state'])) {  
    die('Invalid state');  
}  
  
// 比较session中的state和GET参数中的state是否一致  
if ($_SESSION['oauth_state'] !== $_GET['state']) {  
    die('State does not match');  
}  
  
// 如果state匹配，则可以继续处理OAuth回调流程  
// 例如，使用code参数请求access_token  
  
// 清除session中的state，因为已经验证过了  
unset($_SESSION['oauth_state']);  
  
// 这里继续处理OAuth回调逻辑...  
  
// OAuth 2.0回调处理    
// 接收OAuth服务器返回的code参数  
$code = $_GET['code'];  
  
// 你的OAuth服务器的授权回调URL（与WordPress站点相关）  
$redirect_uri = 'https://host.flweb.cn/callback/';  
  
// 你的WordPress OAuth服务器的token端点URL  
$token_url = $serconfig["oauthtoken"];  
  
// 你的客户端ID和客户端密钥（在WordPress OAuth服务器中设置）  
$client_id = $serconfig["clientid"];  
$client_secret = $serconfig["clientsecret"];  
  
// 使用cURL发送请求到token端点以获取访问令牌  
$ch = curl_init($token_url);  
$data = array(  
    'code' => $code,  
    'redirect_uri' => $redirect_uri,  
    'client_id' => $client_id,  
    'client_secret' => $client_secret,  
    'grant_type' => 'authorization_code'  
);  
  
curl_setopt($ch, CURLOPT_POST, 1);  
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
  
$response = curl_exec($ch);  
curl_close($ch);  
  
// 解析响应以获取访问令牌  
$token_response = json_decode($response, true);  
$access_token = $token_response['access_token'];  
  
// 将访问令牌存储在cookie中  
setcookie('access_token', $access_token, time() + (86400 * 30), '/'); // 存储30天  
  
// 重定向到成功页面或进行其他操作  
header('Location: /');  
//echo $response;
exit;  
  
?>