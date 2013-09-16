/*************************************************** 
  This sketch is based on the Adafruit example sketch referenced below
 ****************************************************/

/*************************************************** 
  This is an example for the Adafruit CC3000 Wifi Breakout & Shield

  Designed specifically to work with the Adafruit WiFi products:
  ----> https://www.adafruit.com/products/1469

  Adafruit invests time and resources providing this open source code, 
  please support Adafruit and open-source hardware by purchasing 
  products from Adafruit!

  Written by Limor Fried & Kevin Townsend for Adafruit Industries.  
  BSD license, all text above must be included in any redistribution
 ****************************************************/

#include <Adafruit_CC3000.h>
#include <ccspi.h>
#include <SPI.h>
#include <string.h>
#include "utility/debug.h"

// These are the interrupt and control pins
#define ADAFRUIT_CC3000_IRQ   3  // MUST be an interrupt pin!
// These can be any two pins
#define ADAFRUIT_CC3000_VBAT  5
#define ADAFRUIT_CC3000_CS    10
// Use hardware SPI for the remaining pins
// On an UNO, SCK = 13, MISO = 12, and MOSI = 11
Adafruit_CC3000 cc3000 = Adafruit_CC3000(ADAFRUIT_CC3000_CS, ADAFRUIT_CC3000_IRQ, ADAFRUIT_CC3000_VBAT,
                                         SPI_CLOCK_DIV2); // you can change this clock speed

#define WLAN_SSID       "yourSSID"           // cannot be longer than 32 characters!
#define WLAN_PASS       "yourSSIDpassword"
// Security can be WLAN_SEC_UNSEC, WLAN_SEC_WEP, WLAN_SEC_WPA or WLAN_SEC_WPA2
#define WLAN_SECURITY   WLAN_SEC_WPA2

Adafruit_CC3000_Client www;  //defines how we will refer to the cc3000 connection object

// What page to grab!
#define WEBSITE      "localhost"  //NOTE: CC3000 doesn't seem to like the default localhost address of 127.0.0.1 - need to enter actual IP
#define WEBPAGE     "/json.php"


//define Arduino pins to operate status LEDs
  const int signalPin = 8;
  const int failPin = 9;

//this is the counter that will be used for uploading data
  long pollCounter = 1; 

//used for storing data for upload
//set it to the data type that suits your data
int
  data_1, data_2, data_3;

//used for data received from base station
int
  command_1, command_2, command_3;
  
//variables for poll timer
//this is a 'non-blocking' timer -- does not use delay(), so operations such as sensor reads can continue between polls

//interval at which to poll base station
//takes a value in seconds; add more multipliers if you want a more convenient unit
  long pollInterval = 10L * 1000L;  //converts from millis; initial default value is to poll every 10 sec
  long lastPoll;                     //stores the last time we polled
  boolean pollFlag = false;          //tells the sketch whether to poll or not


//declare a variable to hold a numeric IP address
//can be overridden below if you use lookup
  uint32_t ip = (192L << 24) | (168L<<16) | (1<<8) | 13;

void setup(void)
{
  //set the LEDs
  pinMode(signalPin, OUTPUT);
  pinMode(failPin, OUTPUT);
  
  //turn on the signal LED to show we're starting
  digitalWrite(signalPin, HIGH);
  
  //flash the fail LED to confirm it works
  digitalWrite(failPin, HIGH);
  delay(200);
  digitalWrite(failPin, LOW);
  

  
  Serial.begin(115200);
  Serial.println(F("Hello, CC3000!\n")); 

  Serial.print("Free RAM: "); Serial.println(getFreeRam(), DEC);
  
  /* Initialise the module */
  Serial.println(F("\nInitializing..."));
  if (!cc3000.begin())
    {
      digitalWrite(failPin, HIGH);  //turn on the fail LED
      Serial.println(F("Couldn't begin()! Check your wiring?"));
      while(1);
    }
  
  cc3000.connectToAP(WLAN_SSID, WLAN_PASS, WLAN_SECURITY);
   
  Serial.println(F("Connected!"));
  
  //flash the LED
  for (int i=0; i<5; i++)  {
  digitalWrite(signalPin, HIGH);
  delay(200);
  digitalWrite(signalPin, LOW);
  delay(200);  
  }

  
  /* Wait for DHCP to complete */
  Serial.println(F("Request DHCP"));
  while (!cc3000.checkDHCP())
    {
      delay(100); // ToDo: Insert a DHCP timeout!
    }  

  /* Display the IP address DNS, Gateway, etc. */  
  while (! displayConnectionDetails())
    {
      delay(1000);
    }
    
   //comment out the following if you've entered a numeric IP in the variable declaration section
   /* override the IP lookup
  ip = 0;
  // Try looking up the website's IP address
  Serial.print(WEBSITE); Serial.print(F(" -> "));
  while (ip == 0) {
    if (! cc3000.getHostByName(WEBSITE, &ip)) {
      Serial.println(F("Couldn't resolve!"));
    }
    delay(500);
  }
    */
      
  cc3000.printIPdotsRev(ip);

  }

void loop(void)
{
   digitalWrite(signalPin, LOW);  //turn off the signal LED

  //check if it is time to poll
  if(pollFlag == true)
  
  {
  
      Serial.println();
      Serial.print("Poll counter: "); Serial.println(pollCounter);
      
      //execute our data handlers to get data to send back to base station
      data_1_handler();
      data_2_handler();
      data_3_handler();
    
      //build the URI and GET args
      //we have to do this within the loop to get fresh values for data
      String targetURI = WEBPAGE;
      targetURI = targetURI + "?id=" + pollCounter;
      targetURI = targetURI + "&data_1=" + data_1;
      targetURI = targetURI + "&data_2=" + data_2;
      targetURI = targetURI + "&data_3=" + data_3;  
      
      /* Try connecting to the website */
      Serial.print("\r\nAttempting connection with data ");
      Serial.println(targetURI);
      www = cc3000.connectTCP(ip, 80);
      if (www.connected()) {
        digitalWrite(signalPin, HIGH);  //turn on the signal LED to show we're connected
        Serial.println("Connected; requesting commands");
        www.fastrprint(F("GET "));
        //www.fastrprint(WEBPAGE);
        www.print(targetURI);  //can't use fastrprint because it won't accept a variable
        www.fastrprint(F(" HTTP/1.0\r\n"));
        www.fastrprint(F("Host: ")); www.fastrprint(WEBSITE); www.fastrprint(F("\n"));
        www.fastrprint(F("Connection: close\n"));
        www.fastrprint(F("\n"));
        www.println();
        digitalWrite(failPin, LOW);    //make sure fail LED is off after successful poll
      } else {
        Serial.println(F("Connection failed"));
        digitalWrite(failPin, HIGH);  //turn fail LED on after unsuccessful poll
        return;
      }
    
    
      //define a String to hold our json object
      String commands = "";
      //set a flag for when to start adding to String
      boolean readflag = false;
      
      //read in data one character at a time while connected    
      while (www.connected()) {
        while (www.available()) {
          char c = www.read();
         
         //start storing at the [ marker
         if (c=='[') {readflag = true;}
         if (c==']') {readflag = false;}
         //build data read from www into a String
         if ((readflag == true) && (c != '['))  {
          commands = commands + c;
         }
         
        }
      }
      www.close();
      digitalWrite(signalPin, LOW);  //turn off the LED
       
        
      //now we should have all our commands in the String 'commands'
      //next we need to parse it into three separate integers
      //find out where the commas are
      int comma1 = commands.indexOf(',');
      int comma2 = commands.lastIndexOf(',');
    
      command_1 = (commands.substring(0,comma1)).toInt();
      command_2 = (commands.substring(comma1+1,comma2)).toInt();
      command_3 = (commands.substring(comma2+1)).toInt();
    
    
      
      Serial.print(("\r\nRESULTS:\r\n  command_1: "));
      Serial.println(command_1);
      Serial.print(("  command_2: "));
      Serial.println(command_2);
      Serial.print(("  command_3: "));
      Serial.println(command_3);
      
      //execute our command handlers to process commands we received from the base station
      command_1_handler();
      command_2_handler();
      command_3_handler();
      
      //now that we've polled, get ready for next poll
      pollCounter = pollCounter + 1;  //increment the counter
      pollFlag = false;
      lastPoll = millis();
      Serial.print("\r\n Next poll in ");
      Serial.println(pollInterval/1000);
  }  //end of what we do if pollFlag == true
  
  else  //what we do if pollFlag == flase
  {
    
    //check to see if it's time to poll
    if (millis() >= (lastPoll + pollInterval))
     { //it's time to poll!
     pollFlag = true;
     }
     
  }  //end of what we do if pollFlag == false  
     
     
}  //end of the loop



/**************************************************************************/
/*!
    @brief  Begins an SSID scan and prints out all the visible networks
*/
/**************************************************************************/

void listSSIDResults(void)
{
  uint8_t valid, rssi, sec, index;
  char ssidname[33]; 

  index = cc3000.startSSIDscan();

  Serial.print(F("Networks found: ")); Serial.println(index);
  Serial.println(F("================================================"));

  while (index) {
    index--;

    valid = cc3000.getNextSSID(&rssi, &sec, ssidname);
    
    Serial.print(F("SSID Name    : ")); Serial.print(ssidname);
    Serial.println();
    Serial.print(F("RSSI         : "));
    Serial.println(rssi);
    Serial.print(F("Security Mode: "));
    Serial.println(sec);
    Serial.println();
  }
  Serial.println(F("================================================"));

  cc3000.stopSSIDscan();
}

/**************************************************************************/
/*!
    @brief  Tries to read the IP address and other connection details
*/
/**************************************************************************/
bool displayConnectionDetails(void)
{
  uint32_t ipAddress, netmask, gateway, dhcpserv, dnsserv;
  
  if(!cc3000.getIPAddress(&ipAddress, &netmask, &gateway, &dhcpserv, &dnsserv))
  {
    Serial.println(F("Unable to retrieve the IP Address!\r\n"));
    return false;
  }
  else
  {
    Serial.print(F("\nIP Addr: ")); cc3000.printIPdotsRev(ipAddress);
    Serial.print(F("\nNetmask: ")); cc3000.printIPdotsRev(netmask);
    Serial.print(F("\nGateway: ")); cc3000.printIPdotsRev(gateway);
    Serial.print(F("\nDHCPsrv: ")); cc3000.printIPdotsRev(dhcpserv);
    Serial.print(F("\nDNSserv: ")); cc3000.printIPdotsRev(dnsserv);
    Serial.println();
    return true;
  }
}


