<?php
require_once 'function.php';
$serconfig = require("config.php");
$accessToken = $_COOKIE['access_token']; 
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
        ?>
        <!DOCTYPE html>  
<html>  
<head>  
  <title>用户中心</title>  
  <style>  
    body {  
      display: flex;  
      justify-content: center;  
      align-items: center;  
      height: 100vh;  
      margin: 0;  
      background-color: #f0f0f0;  
    }  
  
    .container {  
      text-align: center;  
      padding: 20px;  
      background-color: #fff;  
      border-radius: 10px;  
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);  
    }  
  
    h1 {  
      margin-top: 0;  
    }  
  
    button {  
      display: block;  
      margin-top: 10px;  
      padding: 10px 20px;  
      font-size: 16px;  
      border: none;  
      border-radius: 5px;  
      background-color: #007BFF;  
      color: #fff;  
      cursor: pointer;  
    }  
  
    button:hover {  
      background-color: #0056b3;  
    }  
  </style>  
</head>  
<body>  
  <div class="container">  
    <h1>用户中心</h1>  
    <button onclick="editFile()">编辑文件</button>  
    <button onclick="modifyDomain()">修改托管域名</button>  
    <button onclick="jumpToCommunity()">跳转社区</button>  
  </div>  
  
  <script>  
    function editFile() {  
      // 编辑文件的跳转逻辑  
      window.location.href = "/edit";  
    }  
  
    function modifyDomain() {  
      // 修改托管域名的跳转逻辑  
      window.location.href = "/path.php";  
    }  
  
    function jumpToCommunity() {  
      // 跳转社区的跳转逻辑  
      window.location.href = "//www.flweb.cn";  
    }  
  </script>  
</body>  
</html><?php
    } else {  
        // 如果没有hostpath字段，则跳转到create.php  
        header("Location: path.php");  
        exit;  
    }  
} else {  
    // 如果$userdata[id]不存在，则进行错误处理  
    header("Location: login.php"); 
}  

} else {  
    // 处理没有获取到用户数据的情况  
    header("Location: login.php"); 
} 