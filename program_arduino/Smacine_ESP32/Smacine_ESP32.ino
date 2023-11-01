#include <SPI.h>
#include <MFRC522.h>
#include <WiFi.h>
#include <HTTPClient.h>

#define letak kasir

const char* ssid = "WIFI DALAM";
const char* password = "Sagitarius";
const String domain = "http://smacine.eepis.tech";

#define SS_PIN 16
#define RST_PIN 17

#define gudang 1

#define kasir 2
#define apotek 2
#define depan 2

MFRC522 rfid(SS_PIN, RST_PIN);

void setup() {
  Serial.begin(115200);
  SPI.begin();
  pinMode(22, OUTPUT);
  pinMode(4, OUTPUT);
  digitalWrite(22, HIGH);
  rfid.PCD_Init();

  WiFi.begin(ssid, password);
  Serial.println("Connecting");
  unsigned long lst = millis(); //mencatat start waktu esp connecting wifi
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
    if (millis() - lst >= 10000)ESP.restart(); //restart ESP apabila tidak konek wifi selama 10 detik
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());
}

void loop() {
  if ( ! rfid.PICC_IsNewCardPresent()) return;
  if ( ! rfid.PICC_ReadCardSerial()) return;
  String id = readRFID();
  Serial.println(id);
  check_card(id);
  rfid.PICC_HaltA();
  rfid.PCD_StopCrypto1();
}

String readRFID() {
  digitalWrite(22, LOW);
  digitalWrite(4, HIGH);
  String rv = "";
  for (byte i = 0; i < rfid.uid.size; i++) {
    rv += (rfid.uid.uidByte[i] < 0x10 ? "0" : "");
    rv += String(rfid.uid.uidByte[i], HEX);
    if (i < rfid.uid.size - 1) rv += ":";
  }
  rv.toUpperCase();
  delay(100);
  digitalWrite(22, HIGH);
  digitalWrite(4, LOW);
  return rv;
}

void check_card(String card) {
  if (WiFi.status() == WL_CONNECTED) {
    WiFiClient client;
    HTTPClient http;
    if (letak == gudang) http.begin(client, domain + "/check_card.php?card=" + card);
    else if (letak == kasir) http.begin(client, domain + "/check_card_kasir.php?card=" + card);

    int httpResponseCode = http.GET();

    if (httpResponseCode > 0) {
      Serial.print("HTTP Response code: ");
      Serial.println(httpResponseCode);
      String payload = http.getString();
      Serial.println("Payload :");
      Serial.println(payload);
    }
    else {
      Serial.print("Error code: ");
      Serial.println(httpResponseCode);
    }
    http.end();
  }
  else {
    Serial.println("WiFi Disconnected");
  }
}
