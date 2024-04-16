<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>这是发送给数据库的php脚本网址</title>
</head>
<body>
<h1>ESP8266 --->> PHP --->> MySQL</h1>

<?php
// 设置数据库连接参数
$host = "192.168.18.122";
$port = 3306;
$username = "root";
$password = "qhy2004";
$database = "esp";
$table = "table_esp";

echo "数据库主机地址：";
echo $host;
echo "\n";
echo "<br />";
//function AES_decode($input, $key){
//    $de = base64_decode($input);
//    return openssl_decrypt($de, "AES-128-ECB", $key, 1);
//}

function aes_encrypt($input, $key): string
{
    $en = openssl_encrypt($input, "AES-128-ECB", $key, 1);
    return base64_encode($en);
}

if (isset($_POST['temperature']) && isset($_POST['humidity']))
{
    $temperature = $_POST['temperature'];
    $humidity = $_POST['humidity'];
    //$ceshicode = "50.60";
    //$ceshicode2 = "30.20";

    // 在PHP端进行AES-ECB加密
    $encryptionKey = "qhy2022012359726"; // 16字节密钥
    $encryptedTemperature = aes_encrypt($temperature, $encryptionKey);
    $encryptedHumidity = aes_encrypt($humidity, $encryptionKey);

    // 创建数据库连接
    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error)
    {
        die("数据库连接失败: " . $conn->connect_error);
    }

    //$ceshi = "aaaaaaaabbbbbbbb";
    //$ceshi2 = "aaaaaaaaaabbbbbbbbbb";

    // 将加密后的数据插入到数据库
    $stmt = $conn->prepare("INSERT INTO $table (time, encrypted_t, encrypted_h) VALUES (NOW(), ?, ?)");
    //$stmt->bind_param("ss", $ceshi, $ceshi2);
    $stmt->bind_param("ss", $encryptedTemperature, $encryptedHumidity);
    if ($stmt->execute())
    {
        echo "数据插入成功";
    }
    else
    {
        echo "数据插入失败: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
else
{
    echo "怎么回事? 未接收到温度和湿度数据，要不检查一下ESP8266是否在线以及发送成功";
}
?>

<p>ESP8266每一分钟发送一次数据</p>
</body>
</html>
