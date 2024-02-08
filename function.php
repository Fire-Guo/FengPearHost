<?php
define("SERVERROOT",rtrim(dirname(__FILE__),'/\\'));

function sendFile($filePath) {  
    // 检查文件是否存在  
    if (!file_exists($filePath)) {  
        http_response_code(404);  
        exit("File not found");  
    }  
  
    // 尝试获取文件的 MIME 类型，如果文件是 HTML 则手动设置为 text/html  
    $mimeType = mime_content_type($filePath);  
    if (pathinfo($filePath, PATHINFO_EXTENSION) === 'html') {  
        $mimeType = 'text/html';  
    }  
  
    // 设置 Content-Type 头部  
    header("Content-Type: " . $mimeType . "; charset=UTF-8");  
  
    // 不再需要设置 Content-Disposition，除非您想要强制下载  
    // $isImage = preg_match('/\(image\)/', $mimeType);  
    // $disposition = $isImage ? 'inline' : 'attachment';  
    // header("Content-Disposition: " . $disposition . "; filename=\"" . basename($filePath) . "\"");  
  
    // 设置 Content-Length 头部  
    header("Content-Length: " . filesize($filePath));  
  
    // 打开文件  
    $file = @fopen($filePath, 'rb');  
    if ($file === false) {  
        http_response_code(500);  
        exit("Error opening file");  
    }  
  
    // 直接将文件内容发送到浏览器  
    fpassthru($file);  
  
    // 关闭文件  
    fclose($file);  
  
    // 结束脚本执行  
    exit;  
}

function editfile($filePath, $content) {  
    // 检查文件路径是否有效  
    if (!is_writable($filePath)) {  
        die("文件 {$filePath} 不可写");  
    }  
  
    // 尝试将内容写入文件  
    if (file_put_contents($filePath, $content) === false) {  
        die("无法写入文件 {$filePath}");  
    }  
  
    echo "文件 {$filePath} 的内容已成功修改。";  
}  

function newfile($filePath) {  
    // 检查文件路径是否合法  
    if (!is_string($filePath) || empty($filePath)) {  
        throw new Exception('Invalid file path');  
    }  
  
    // 检查目录是否存在且可写  
    $directory = dirname($filePath);  
    if (!is_dir($directory) || !is_writable($directory)) {  
        throw new Exception('Directory does not exist or is not writable');  
    }  
  
    // 尝试创建文件  
    $result = file_put_contents($filePath, ''); // 将空字符串写入文件，以创建新文件  
    if ($result === false) {  
        throw new Exception('Failed to create the file');  
    }  
  
    // 文件创建成功  
    return true;  
}

function getuserdata($accessToken){
    $serconfig = require("config.php");
    $clientSecret = $serconfig['clientsecret']; // 注意：client secret通常不用于获取用户数据，而是用于获取access token  
  
    // OAuth服务器的用户数据端点URL  
    $userDataEndpoint = $serconfig["oauthusrdata"]; // 请替换为实际的用户数据端点  
  
    // 初始化cURL会话  
    $ch = curl_init();  
  
// 设置cURL选项  
curl_setopt($ch, CURLOPT_URL, $userDataEndpoint);  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
curl_setopt($ch, CURLOPT_HTTPHEADER, [  
    'Authorization: Bearer ' . $accessToken, // 在HTTP头部添加access token  
    'Content-Type: application/json', // 根据需要设置内容类型  
    // 如果需要client secret进行身份验证，可以像这样添加它（但这并不常见）  
    // 'Authorization: Basic ' . base64_encode($clientId . ':' . $clientSecret),  
]);  
  
// 发送请求并获取响应  
$response = curl_exec($ch);  
  
// 检查是否有错误发生  
if(curl_errno($ch)){  
    $error_message = curl_error($ch);  
    // 处理错误  
    echo "cURL Error: " . $error_message;  
}  
  
// 关闭cURL会话  
curl_close($ch);  
  
// 将响应的JSON数据解码为PHP对象或数组  
$userData = json_decode($response, true);  
  
// 检查是否成功解码JSON  
if (json_last_error() !== JSON_ERROR_NONE) {  
    // 处理JSON解码错误  
    return 'Failed to decode JSON: ' . json_last_error_msg();  
}  
return $userData;
}
function filecount($directoryPath) {  
    $files = scandir($directoryPath);  
      
    // 排除当前目录（.）和上级目录（..）  
    $numFiles = count($files) - 2;  
      
    return $numFiles;  
}  



function getpath(){
    $serconfig = require("../config.php");
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
        return SERVERROOT.'/host/'.$result['hostpath'].'/';  
    } else {  
        return false;
    }  
} else {  
    // 如果$userdata[id]不存在，则进行错误处理  
    return false;  
}  

} else {  
    // 处理没有获取到用户数据的情况  
    return false;  
}  
}


function rrmdir($dir) {  
    if (is_dir($dir)) {  
        $objects = scandir($dir);  
        foreach ($objects as $object) {  
            if ($object != "." && $object != "..") {  
                if (is_dir($dir . "/" . $object)) {  
                    rrmdir($dir . "/" . $object);  
                } else {  
                    unlink($dir . "/" . $object);  
                }  
            }  
        }  
        reset($objects);  
        rmdir($dir);  
    }  
}  