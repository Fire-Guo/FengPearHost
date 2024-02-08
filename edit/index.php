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
        <img src="logo.png" alt="Logo" width="100">  
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
  
<div id="content-area">  
    <textarea id="file-content" wrap="off"><?php echo isset($content) ? htmlspecialchars($content) : ''; ?></textarea>  
    <div id="toolbar">  
        <button id="save-btn">保存</button>  
    </div>  
</div>  
  
</body>  
</html>