<?php  
$serconfig=require("config.php");
require_once "function.php";
$pdo=new PDO('mysql:host='.$serconfig['dbhost'].';dbname='.$serconfig['dbname'],$serconfig['dbuser'],$serconfig['dbpass']);
// 获取用户输入  
if ($_SERVER['REQUEST_METHOD'] === 'POST') {  
    // 获取用户输入的托管站点路径  
    $newHostPath = $_POST['hostpath'];  
  
    // 获取$userdata
    $accessToken = $_COOKIE['access_token'];  
$userData = getuserdata($accessToken);
// 显示用户数据  
if (isset($userData) && is_array($userData)) {  
    // 假设用户数据是一个数组，你可以根据实际情况遍历和显示数据  
    /*foreach ($userData as $key => $value) {  
        echo $key . ': ' . $value . '<br>';  
    } */
    $userId=$userData['ID'];
    // 准备 SQL 语句，用于更新或插入记录  
    $sql = "INSERT INTO hostpath (id, hostpath) VALUES (?, ?) ON DUPLICATE KEY UPDATE hostpath = VALUES(hostpath)";  
  
    try {  
        // 准备预处理语句  
        $stmt = $pdo->prepare($sql);  
  
        // 绑定参数并执行  
        $stmt->execute([$userId, $newHostPath]);  
  
        // 处理执行结果  
        if ($stmt->rowCount() > 0) {  
            rrmdir(getpath());
            echo "托管站点已新建或更新成功！";  
        } else {  
            echo "没有发生任何更改。";  
        }  
    } catch (PDOException $e) {  
        // 处理 PDO 异常  
        echo "数据库操作失败: " . $e->getMessage();  
    }  
    } else {  
    // 处理没有获取到用户数据的情况  
    echo 'No user data retrieved.';  
}  
} else {  
    // 显示表单以获取用户输入  
    ?>  
    <html>  
    <head>  
        <title>新建或修改托管站点</title>  
    </head>  
    <body>  
        <h2>新建或修改托管站点</h2>  
        <p>注：修改后会清空所有文件！</p>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">  
            <label for="hostpath">托管站点路径:</label>  
            <input type="text" id="hostpath" name="hostpath" required>  
            <label for="hostpath">.kobai.asia</label>  
            <button type="submit">提交</button>  
        </form>  
    </body>  
    </html>  
    <?php  
}  
?>