#include <ESP8266HTTPClient.h>
#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <DHT.h>

const char *ssid = "433";
const char *password = "433433433";
const char *serverAddress = "192.168.18.122";
const int serverPort = 80;
const int DHTPIN = D4;  // DHT11传感器引脚

DHT dht(DHTPIN, DHT11);

void setup(){
  pinMode(DHTPIN, INPUT);
  Serial.begin(9600);
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print("正在连接wifi\n");
  }
  Serial.println("网络连接成功 IP:");
  Serial.println(WiFi.localIP());
}

void loop() {
  float humidity = dht.readHumidity();
  float temperature = dht.readTemperature();
  
  if (isnan(humidity) || isnan(temperature)) {
    Serial.println("无法从DHT传感器读取数据!");
    delay(500);
    return;
  }

  Serial.print("温度：");
  Serial.println(temperature);
  Serial.print("湿度：");
  Serial.println(humidity);

  post(temperature, humidity);

  delay(30000);  // 每30秒上传一次数据
}

void post(float temperature, float humidity) {
  WiFiClient client;
  Serial.println("WIFI客户端对象已创建");
  HTTPClient hc;
  Serial.println("HTTP响应对象已创建成功");

  String url = "http://" + String(serverAddress) + "/esp8266SQL/send.php";
  String postData = "temperature=" + String(temperature) + "&humidity=" + String(humidity);

  hc.begin(client, url);
  Serial.println("URL目标已创建");
  hc.addHeader("Content-Type", "application/x-www-form-urlencoded");
  Serial.println("HTTP请求设置头已创建");

  int httpCode = hc.POST(postData);

  if (httpCode == HTTP_CODE_OK) 
  {
    String responsePayload = hc.getString();
    Serial.println("数据上传成功：");
    Serial.println(responsePayload);
  } 
  else 
  {
    Serial.println("数据上传失败：");
    Serial.print(httpCode);
  }

  hc.end();
}
