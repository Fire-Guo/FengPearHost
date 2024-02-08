<?php
require_once "function.php";
$serconfig = require("config.php");
$domain = $_SERVER['HTTP_HOST'];
    // 获取请求的文件路径  
$file = $_SERVER['REQUEST_URI'];  
$currentFilePath = __FILE__;  
$directoryPath = rtrim(dirname($currentFilePath), '/\\');  
// 定义托管目录和默认首页文件  
$path = "$directoryPath/host"; // 替换为实际的托管目录路径  
$defhome = 'index.html';  
  
// 判断域名是否为泛域名，如果是则截取二级域名  
if (strpos($domain, '.kobai.asia') !== false) {  
    $hostname = explode('.', $domain)[0];  
    $path .= "/$hostname";  
  
// 构建完整的托管文件路径  
if ($file=="/"){
$hostfile = "$path/index.html";  }
else{
$hostfile = "$path$file";
}
  //echo $hostfile;
// 检查托管文件是否存在  
if (file_exists($hostfile)) {  
    // 直接输出托管文件内容  
    sendFile($hostfile);  
} else { 
        // 默认首页也不存在，返回404错误页面  
        header("HTTP/1.1 404 Not Found");  
        echo "文件不存在！</br>Powered by <a href=\"\/\/host.flweb.cn\">疯梨网页托管</a>";  
}
}
else{
    //如果是访问管理站
    //$accessToken = $_COOKIE['access_token'];
    if (isset($_COOKIE['access_token'])) {  
    $accessToken = $_COOKIE['access_token'];  
    // 现在你可以使用$accessToken变量了  
    //echo "Access Token: " . $accessToken;  
} else {  
    // 如果cookie不存在，可以显示错误消息或执行其他逻辑  
    echo "Access Token cookie not found.";  
    exit;
}  
$userData = getuserdata($accessToken);
// 显示用户数据  
if (isset($userData) && is_array($userData)) {  
    // 假设用户数据是一个数组，你可以根据实际情况遍历和显示数据  
    /*foreach ($userData as $key => $value) {  
        echo $key . ': ' . $value . '<br>';  
    }  */
    //echo $userdata['ID'];
    if (isset($userData['ID'])) {  
    $pdo = new PDO('mysql:host='.$serconfig["dbhost"].';dbname='.$serconfig["dbname"], $serconfig["dbuser"], $serconfig["dbpass"]);
      $stmt = $pdo->prepare("SELECT hostpath FROM hostpath WHERE id = :id");  
    $stmt->bindParam(':id', $userData['ID'], PDO::PARAM_INT);  
    $stmt->execute();  
    $result = $stmt->fetch(PDO::FETCH_ASSOC);  
      
    // 检查是否查询到了结果  
    if ($result && isset($result['hostpath'])) {  
        // 如果有hostpath字段，则进行后续操作  
        // 例如，输出hostpath字段的值  
        header("Location: user.php"); 
    } else {  
        // 如果没有hostpath字段，则跳转到create.php  
        header("Location: path.php");  
        exit;  
    }  
} else {  
    // 如果$userdata[id]不存在，则进行错误处理  
    //header("Location: login.php"); 
    echo 1;
}  

} else {  
    // 处理没有获取到用户数据的情况  
    //header("Location: login.php"); 
    echo 2;
}  
}