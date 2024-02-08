<?php
require_once "../function.php";
function isValidFile($fileName) {  
    $allowedExtensions = array('html', 'css', 'js', 'svg','txt');  
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));  
  
    if (in_array($fileExtension, $allowedExtensions)) {  
        return true;  
    } else {  
        return false;  
    }  
} 
$file=$_POST['old'];
$new=$_POST['new'];
if (strpos($file, '..') !== false) {  
    die('Invalid file path!'); // 如果包含'..'，则终止脚本执行  
}
if (strpos($file, '/') !== false) {  
    die('Invalid file path!'); // 如果包含'..'，则终止脚本执行  
}
if (strpos($new, '..') !== false) {  
    die('Invalid file path!'); // 如果包含'..'，则终止脚本执行  
}
if (strpos($new, '/') !== false) {  
    die('Invalid file path!'); // 如果包含'..'，则终止脚本执行  
}
if (!isValidFile($file)) {  
    die('Invalid file path!'); // 如果包含'..'，则终止脚本执行  
}
renamefile(getpath().$file,$new);