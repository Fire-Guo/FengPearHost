<?php
require_once "../function.php";
if (requirelogin()==1){
    header("Location: /login.php");
    exit;
} else if (requirelogin()==2){
    header("Location: /path.php");
    exit;
}
$file=$_GET['file'];
if (strpos($file, '..') !== false) {  
    die('Invalid file path!'); // 如果包含'..'，则终止脚本执行  
}
if (strpos($file, '/') !== false) {  
    die('Invalid file path!'); // 如果包含'..'，则终止脚本执行  
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
if (isValidFile($file)) {  
    if(filecount(getpath())<20){
        newfile(getpath().$file);
        echo "创建成功";
    }else{
        echo "最大文件数量为20";
    }
} else {  
    echo "文件 $file 不是有效的HTML、CSS、JS或SVG文件，或者没有正确的后缀名。";  
}  