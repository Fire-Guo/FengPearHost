<?php
require_once "../function.php";
$serconfig = require("../config.php");
if (isset($_COOKIE['access_token'])) {  
    $accessToken = $_COOKIE['access_token'];  
} else {  
    echo "Access Token cookie not found.";  
    exit;
}
$userData = getuserdata($accessToken);
if (isset($userData) && is_array($userData)) {  
    if (isset($userData['ID'])) {  
    $pdo = new PDO('mysql:host='.$serconfig["dbhost"].';dbname='.$serconfig["dbname"], $serconfig["dbuser"], $serconfig["dbpass"]);
      $stmt = $pdo->prepare("SELECT hostpath FROM hostpath WHERE id = :id");  
    $stmt->bindParam(':id', $userData['ID'], PDO::PARAM_INT);  
    $stmt->execute();  
    $result = $stmt->fetch(PDO::FETCH_ASSOC);  
    if ($result && isset($result['hostpath'])) {  
        
    } else {  
        header("Location: /path.php");  
        exit;  
    }  
} else {  
    header("Location: /login.php"); 
    exit;
}}
?>
<!DOCTYPE html>  
<html lang="en">  
<head>  
    <meta charset="UTF-8">  
    <title>文件编辑器</title>  
    <link rel="stylesheet" href="style.css">  
    <script src="script.js"></script>
</head>  
<body>  
  
<div id="sidebar">  
    <div id="logo">  
        
    </div>  
    <div id="file-list">  
        <?php  
        require_once '../function.php';
        $editableFolder = getpath();  
        $files = scandir($editableFolder);  
        echo '文件列表';
        foreach ($files as $file) {  
            if (in_array($file, array('.', '..'))) continue;  
            $isSelected = false;  
              
            if (isset($_GET['file']) && $_GET['file'] === $file) {  
                $isSelected = true;  
                $content = file_get_contents($editableFolder . $file);  
            }  
            echo '<div class="file-item' . ($isSelected ? ' selected' : '') . '" data-filename="' . $file . '">'  
                 . $file  
                 . '<button class="rename-btn" data-filename="' . $file . '">重命名</button>'  
                 . '</div>';  
        }  
        ?>  
        <button id="new-file-btn">新建文件</button>  
    </div>  
</div>  
<div id="content-wrapper">  
    <div id="editor-area">  
        <textarea id="file-content" wrap="off"><?php echo isset($content) ? htmlspecialchars($content) : ''; ?></textarea>  
    </div>  
    <div id="preview-area">  
        <iframe id="preview-iframe" srcdoc="" style="width: 100%; height: 100%;"></iframe>  
    </div>  
</div>  
  
<div id="toolbar">  
    <button id="save-btn">保存</button>  
</div>  
  
</body>  
</html>