#include <LWiFi.h>
#include "DHT.h"
#include <stdio.h>
#include <SoftwareSerial.h>
SoftwareSerial SoftSerial(2, 3);
#define DHTPIN A0            //接溫溼度模組資料的腳位
#define DHTTYPE DHT11       //溫溼度模組的類型
DHT dht(DHTPIN, DHTTYPE);
String data = "";
boolean Start_Flag = false;
boolean valid = false;
String GGAUTCtime, GGAlatitude, GGAlongitude;
String GPStatus, SatelliteNum, HDOPfactor, Height;
String PositionValid, RMCUTCtime, RMClatitude;
String RMClongitude, Speed, Direction, Date, Declination, Mode;
const int Xpin = A1;                  // x-axis
const int Ypin = A2;                  // y-axis
const int Zpin = A3;                  // z-axis

const String XHEADER = "X: ";
const String YHEADER = "Y: ";
const String ZHEADER = "Z: ";
const String TAB = "\t";
float hum;
float temp;

char ssid[] = "JOJO"; //WiFi連線的名稱
char pass[] = "033176949"; //WiFi連線的密碼
int status = WL_IDLE_STATUS;

WiFiClient client;
IPAddress server(122, 116, 196, 99); //伺服器的IP位置
//char server[] = "..."; //使用DNS位址時的設定

int port = 8000; //伺服器的Port位置
unsigned long lastConnectionTime = 0;
const unsigned long postingInterval = 1L * 1000L; //傳輸的週期時間

void setup() {
  Serial.begin(9600);
  dht.begin();
  SoftSerial.begin(9600);
  for (; !Serial;);

  while (status != WL_CONNECTED) {
    Serial.print("Attempting to connect to SSID: ");
    Serial.println(ssid);
    status = WiFi.begin(ssid, pass);
    delay(200);
  }

  printWifiStatus();
}
void loop() {
  while (client.available()) {
    char c = client.read();
    Serial.write(c);
  }
  if (millis() - lastConnectionTime > postingInterval) {
    httpRequest();
  }

  while (SoftSerial.available())
  {
    if (Start_Flag) {
      data = readGPS();
      if (data.equals("GPRMC")) {
        RMCUTCtime = readGPS();
        PositionValid = readGPS(); // 讀取到 A 才是有效封包
        RMClatitude = readGPS();
        RMClatitude += readGPS();
        RMClongitude = readGPS();
        RMClongitude += readGPS();
        Speed = readGPS(); //速度
        Direction = readGPS(); //方向
        Date = readGPS(); // 日期
        Declination = readGPS();
        valid = true;
        Start_Flag = false;
        data = "";
      }
      else {
        Start_Flag = false;
        data = "";
      }
    }
    if (valid) {
      if (PositionValid == "A")
      {
        // output(); // 印出前面抓到的正確訊息
      }
      else
        Serial.println("Not a valid position");
      valid = false;
      PositionValid = ""; // 重新把檢查旗標清空，才能確實判斷下
    }
    if (SoftSerial.find("$")) { // 若從 GPS 來的資料裡面含有$
      Start_Flag = true; // 就可以把開始旗標設定為true準備開始
    }
  }

  delay(200);
}


void httpRequest() {
  client.stop();
  if (client.connect(server, port)) {
    Serial.println();
    Serial.println("connecting...");
    char tmp[100];
    hum = dht.readHumidity();
    temp = dht.readTemperature();
    int xpin = analogRead(Xpin);
    int ypin = analogRead(Ypin);
    int zpin = analogRead(Zpin);
    if (PositionValid == "A")
    {
      sprintf(tmp, "GET /?hum=%f&temp=%f&xpin=%d&ypin=%d&zpin=%d 緯度=%S 經度=%S  HTTP/1.1", hum, temp, xpin, ypin, zpin, RMClatitude.c_str(), RMClongitude.c_str());
    }
    else
      sprintf(tmp, "GET /?hum=%f&temp=%f&xpin=%d&ypin=%d&zpin=%d HTTP/1.1", hum, temp, xpin, ypin, zpin);
    client.println(tmp);
    client.println("User-Agent: LinkIt7697_WiFi/1.1");
    client.println("Connection: close");
    client.println();

    lastConnectionTime = millis();
  } else {
    Serial.println("connection failed");

  }

}

void printWifiStatus() {
  Serial.print("SSID: ");
  Serial.println(WiFi.SSID());

  IPAddress ip = WiFi.localIP();
  Serial.print("IP Address: ");
  Serial.println(ip);

  long rssi = WiFi.RSSI();
  Serial.print("signal strength (RSSI):");
  Serial.print(rssi);
  Serial.println(" dBm");
}

String readGPS() {
  String value = "";
  int temp;
startRead:
  if (SoftSerial.available() > 0)
  {
    temp = SoftSerial.read(); // 一個字一個字讀取出來
    if ((temp == ',') || (temp == '*')) //需要一直讀取，直到讀到逗號或是*號才是一段完整資訊
    {
      if (value.length() > 0) // value裡面的長度比0大代表有確實讀到資訊，直接傳回
        return value;
      else
        return ""; // 否則就傳回空字串
    }
    else if (temp == '$') // 讀到$代表這是一段訊息的最開頭，準備開始截取資訊
      Start_Flag = false;
    else
      value += char(temp); // 把每一個讀到的字都加入 value 字串
  }                                                        // 這個 delay()的數值需要稍大或稍小調整，1~3 都可以// 等待一下，避免讀取速度過快，GPS 根本沒資訊傳回
  delay(3);
  goto startRead; // 跳回 if 開頭，不斷的讀取，直到確認能讀到完整資訊
}
void output() // 輸出相關訊息的程式都包裝在這裡
{
  // 底下這一大段 serial.print 純粹是用來把訊息印出來看
  Serial.print("Date:");
  Serial.println(Date);
  Serial.print("UTCtime:");
  Serial.println(RMCUTCtime);
  Serial.print("Latitude:");
  Serial.println(RMClatitude);
  Serial.print("Longitude:");
  Serial.println(RMClongitude);
  Serial.print("Speed:");
  Serial.println(Speed);
  Serial.print("Direction:");
  Serial.println(Direction);
}

