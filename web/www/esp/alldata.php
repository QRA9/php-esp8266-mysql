<?php
header("Content-Type: text/html; charset=UTF-8");
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>从mySQL提取到数据显示到此网页中</title>
</head>
<body>
<h1>欢迎</h1>
<h2>从mySQL提取到的所有数据</h2>
<!--<h3>没有未解密的加密数据部分（数据太多啦！这页我随便弄的！布局装不下啦！）</h3>-->
<?php

// 配置与MySQL数据库的连接
//$servername = "192.168.100.100";     // MySQL服务器地址
//$username = "root";              // 数据库用户名
//$password = "qhy2004";           // 数据库密码
//$dbname = "esp";                 // 数据库名称
//$table = "table_esp";            // 表名称
//
//// 创建与MySQL数据库的连接
//$conn = new mysqli($servername, $username, $password, $dbname);
//
//// 检查连接是否成功
//if ($conn->connect_error) {
//    die("连接数据库失败：" . $conn->connect_error);
//}
//
//// 执行SQL查询以检索数据
//$sql = "SELECT time, encrypted_data FROM $table";
//$result = $conn->query($sql);
//
//$data = []; // 用于存储结果数据的数组
//
//// 检查是否有数据行
//if ($result->num_rows > 0) {
//    while ($row = $result->fetch_assoc()) {
//        // mysql加密数据的十六进制表示
//        $encryptedDataHex = $row['encrypted_data'];
//
//        // 使用aes_decrypt函数解密数据
//        $aesKey = '000102030405060708090A0B0C0D0E0F';
//        $decryptedData = aes_decrypt($encryptedDataHex, $aesKey);
//
//        // 将解密后的数据添加到结果数组
//        $row['decrypted_data'] = $decryptedData;
//
//        // 将原始数据也添加到结果数组
//        $row['original_data'] = $encryptedDataHex;
//
//        $data[] = $row;
//    }
//}
//
//// 关闭与数据库的连接
//$conn->close();
//
//// 设置响应头为JSON格式
//header('Content-Type: application/json');
//
//// 输出包含解密后数据和原始数据的数组
//echo json_encode($data);
//

// AES解密函数
function aes_decrypt($encryptedDataHex, $key) {
    // 使用openssl_decrypt函数解密数据（ECB模式）
    $en = base64_decode($encryptedDataHex);
    return openssl_decrypt($en, 'AES-128-ECB', $key, 1);
}
//?
//phpinfo();
//
//$extensionName = 'AES';
//$extensionFile = 'D:\wamp64\bin\php\php8.2.0\ext\AES.dll';
////$extensionName2 = 'base64dll';
////$extensionFile2 = 'D:\wamp64\bin\php\php8.2.0\ext\base64dll.dll';
//
//if (extension_loaded($extensionName))
//{
//    echo "拓展'$extensionName'已经加载\n";
//}
//else
//{
//    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
//    {
//        if (file_exists($extensionFile))
//        {
//            echo "配置不对，AES_dll未加载\n";
//        }
//        else
//        {
//            echo "文件'$extensionFile'没有找到\n";
//        }
//    }
//    else
//    {
//        echo "不是windows系统不支持dll拓展\n";
//    }
//}

//if (extension_loaded($extensionName2))
//{
//    echo "拓展'$extensionName2'已经加载";
//}
//else
//{
//    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
//    {
//        if (file_exists($extensionFile2))
//        {
//            echo "配置不对，base64_dll未加载";
//        }
//        else
//        {
//            echo "文件'$extensionFile2'没有找到";
//        }
//    }
//    else
//    {
//        echo "不是windows系统不支持dll拓展";
//    }
//}

// 配置与MySQL数据库的连接
$servername = "192.168.100.100";     // MySQL服务器地址
$username = "root";              // 数据库用户名
$password = "qhy2004";           // 数据库密码
$dbname = "esp";                 // 数据库名称
$table = "table_esp";            // 表名称

// 创建与MySQL数据库的连接
$conn = new mysqli($servername, $username, $password, $dbname);
//echo("创建连接对象\n");

// 检查连接
if ($conn->connect_error)
{
    echo("连接失败");
    die("连接数据库失败：" . $conn->connect_error);
}


// 查询检索数据
// 执行查询并按时间戳列降序排序
//$sql = "SELECT * FROM $table ORDER BY time DESC LIMIT 1";
//$result = $conn->query($sql);
$sql = "SELECT time, encrypted_t, encrypted_h FROM $table";
$result = $conn->query($sql);

$data = []; // 用于存储结果

//$AES = FFI::cdef('void aes(char* p, int plen, char* key);', '.\\AES.dll');
//$AES_en = FFI::cdef("void aes(char* p, int plen, char* key);", ".\AES.dll");
//$AES_de = FFI::cdef("void deaes(char* c, int clen, char* key);", ".\AES.dll");
//$base64_long_en = FFI::cdef("size_t encodeLength(size_t inputLength);", ".\base64.dll");
//$base64_long_de = FFI::cdef("size_t decodeLength(const char* input);", ".\base64.dll");
//$base64_en = FFI::cdef("void encode(const uint8_t* input, size_t inputLength, char* output);", ".\base64.dll");
//$base64_de = FFI::cdef("void decode(const char* input, uint8_t* output);", ".\base64.dll");

// 检查是否有数据行
if ($result->num_rows > 0)
{
    while ($row = $result->fetch_assoc())
    {
        $name = $row["name"];
        $age = $row["age"];


//    $row = $result->fetch_assoc();
        //加密数据的十六进制表示
        $encrypted_temp = $row['encrypted_t'];
        $encrypted_hum = $row['encrypted_h'];
        //echo("获取到加密数据\n");
        echo("从mysql获取的最新加密数据为：\n");
        echo "<br />";
        echo("温度：");
        echo $encrypted_temp;
        echo("\n");
        echo "<br />";
        echo("湿度：");
        echo $encrypted_hum;
        echo("\n");
        echo "<br />";
        // 使用aes_decrypt(自定义函数）解密数据, md要学疯了
        $aesKey = "qhy2022012359726"; // 十六进制字符串密钥
        $de_temp = aes_decrypt($encrypted_temp, $aesKey);
        $de_hum = aes_decrypt($encrypted_hum, $aesKey);
//    var_dump($de_temp);
//    var_dump($de_hum);
        if ($de_hum === false || $de_temp === false)
        {
            echo "解密失败". openssl_error_string();
            echo "\n";
            echo "<br />";
        }
        echo("将最新加密数据解密后的数据为：\n");
        echo "<br />";
        echo("温度：");
        echo($de_temp);
        echo "%";
        echo("\n");
        echo "<br />";
        echo("湿度：");
        echo($de_hum);
        echo "℃";
        echo("\n");
        echo "<br />";
    }

//        var_dump($encrypted_temp);
//        var_dump($encrypted_hum);
//        var_dump($aesKey);
//        $base_key = base64_encode($aesKey);
//        var_dump($base_key);
//
//        $encrypted_temp = str_replace(' ', '+', $encrypted_temp);
//        $e_temp = base64_decode(strtr($encrypted_temp, '-_', '+/').'==');
//        $encrypted_hum = str_replace(' ','+', $encrypted_hum);
//        $e_hum = base64_decode(strtr($encrypted_hum, '-_', '+/').'==');
//        var_dump($e_temp);
//        var_dump($e_hum);
//
//        $d_hum = aes_decrypt($encrypted_hum, $aesKey);
//        var_dump($d_temp);
//        var_dump($d_hum);
//
//        if ($d_temp === false || $d_hum === false)
//        {
//            echo "解密失败： " . openssl_error_string();
//        }
//        else
//        {
//            echo "解密成功： " . $d_temp;
//            echo "解密成功： " . $d_hum;
//        }
//        $row['original_data_temp'] = $encrypted_temp;
//        $row['original_data_hum'] = $encrypted_hum;
////        $row['temperature'] = $d_temp;
////        $row['humidity'] = $d_hum;
//
//        $data[] = $row;
}
// 关闭与数据库的连接
$conn->close();
// 响应头为JSON格式
//header('Content-Type: application/json');
//
//// 输出
//echo json_encode($data);
?>
<p>获取到的数据为数据库最新一行，每一分钟数据库更新一次数据</p>
</body>
</html>