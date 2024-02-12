<?php
 require_once "../function.php";
 if (requirelogin()==1){
    header("Location: /login.php");
    exit;
} else if (requirelogin()==2){
    header("Location: /path.php");
    exit;
}
 function isValidFile($fileName) {  
    $allowedExtensions = array('html', 'css', 'js', 'svg','txt');  
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));  
  
    if (in_array($fileExtension, $allowedExtensions)) {  
        return true;  
    } else {  
        return false;  
    }  
} 
// 检查请求方法是否为 PUT  
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {  
    // 检查 GET 请求中是否有 'file' 参数  
    if (isset($_GET['file'])) {  
        $file=$_GET['file'];
        
        if (strpos($file, '..') !== false) {  
            die('Invalid file path!'); // 如果包含'..'，则终止脚本执行  
        }
        if (strpos($file, '/') !== false) {  
            die('Invalid file path!'); // 如果包含'..'，则终止脚本执行  
        }
        if (!isValidFile($file)) {  
            die('Invalid file!'); // 如果包含'..'，则终止脚本执行  
        }
        $content=file_get_contents('php://input');
        editfile(getpath().$file,$content);
    } else {  
        // GET 请求中没有 'file' 参数，退出脚本  
        exit("GET 请求中没有 'file' 参数，脚本退出。\n");  
    }  
} else {  
    // 不是 PUT 请求，退出脚本  
    exit("不是 PUT 请求，脚本退出。\n");  
}  