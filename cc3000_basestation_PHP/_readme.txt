CC3000 BASE STATION

I wrote this package of PHP scripts to enable two-way communication between an Internet-connected computer and an Arduino Uno equipped with the Adafruit Industries CC3000 WiFi breakout board (http://www.adafruit.com/products/1469)

The Base Station PHP code and this information are made available as-is, and I assume no responsibility or liability in the event that Things Go Wrong.

*********************
*	REQUIREMENTS	*
*********************

The Base Station package requires a web host running current versions of PHP and the mySQL database. If you use a MacOS computer, an easy way to provide these on your local network is to run MAMP (http://www.mamp.info/en/index.html.) I don't have access to a Windows computer for testing purposes, but WampServer (http://www.wampserver.com/en/) appears to be a similar product for the Windows environment.

If you have an Internet hosting account that provides PHP and mySQL, you also can use the Base Station package on it. This should allow you to connect to your CC3000 from anywhere on the Internet. IMPORTANT NOTE: To keep the PHP easy to understand, I have NOT tried to password-protect it or "harden" it against security threats such as code injection! Unless you provide your own security features, your Base Station/Arduino setup could be hijacked by ANYONE. So if you place your Base Station on the Internet and then find that the National Security Agency has been twiddling with your thermostat or whatever, don't blame me! 

The package probably will work with many implementations of Internet-connected devices, but I made it for use with the Adafruit CC3000 breakout board, which contains a TI CC3000 WiFi module. Adafruit has several models of CC3000 board, including a stand-alone module with built-in antenna, another with a connector for an external antenna, and another built in the form of an Arduino shield. Check www.adafruit.com for the module that best suits your needs and for information on how to configure it and get it working.

The CC3000 module works with an Arduino microcontroller. I have written a simple Arduino sketch that works with the PHP scripts to enable two-way communication between the Arduino and the CC3000 Base Station scripts.

*********************
*	QUICK START		*
*********************

1) Connect your CC3000 module and Arduino Uno, and run some of the Adafruit-supplied example sketches to make sure the CC3000 works and can connect to your WiFi network.

2) Locate the web host you'll use to host the Base Station PHP files. Make a note of the following information about the web hos:
	-- Website host name or IP address (example: "yourhost.net" or "192.168.1.100")
	-- Name of the host's mySQL server
		(often this will be the same as the host name, but not always.)
	-- mySQL user name (needed for administering databases on the mySQL server)
	-- mySQL user password (goes with the above user name)
	
3) Edit the Base Station PHP files sql-connect.php and sql-setup.php so they contain the information you've just collected. There are comments in the files to show what goes where. (The default entries should work for MAMP.)
	
4) Create a directory on the web host to hold the Base Station PHP files. Your Base Station's URI will be the website host name you noted above, plus the name of the directory you just created. Make a note of the URI for the next step.

5) Copy all the Base Station PHP files into the directory you just made. To test it, open a web browser and try to access the test page (test.html) at the URI you noted in the step above; for example, http://yourhost.net/yourdirectory/test.html If everything's working, you'll see the text of the simple test page.

6) Now you're ready to start working with PHP and mySQL. The first step is to create a mySQL database and a table to hold your results. The sql-setup.php file (which you edited in step 3) does this automatically. To activate it, use your web browser to access http://yourhost.net/yourdirectory/sql-setup.php . You'll see a confirmation message when the database and table are created. (You can disregard an error message saying the table already exists. I suppose I ought to fix that...)

7) With the database and tables in place, the Base Station should be ready to work. Try accessing the report page (http://yourhost.net/yourdirectory/report.php) You should see a report showing no records, because your database hasn't received any data yet.

8) To test database entries, you can make a "fake" entry by sending a special URI to the json.php page. This is the page that's normally updated by the CC3000/Arduino, but for test purposes we're going to spoof it. Use your web browser to access a URI that you construct like this:

http://yourhost.net/yourdirectory/json.php?id=1&data_1=100&data_2=200&data_3=300.

If everything works, the browser will return a simple json-encoded string like this: [0,0,0] The json-encoded string represents the numeric commands that you will be able to send to the CC3000/Arduino.

9) Now go to the report page by accessing http://yourhost.net/yourdirectory/report.php . You should see that a record has been created with the numerical data that you put into your special URL. When you're using the Base Station, the CC3000/Arduino will use the same process to transmit the data you see in the data columns of the report, and will receive the numeric commands that you've inserted into the command columns of the report.

10) To make the Base Station do actual work, you'll need to adapt the accompanying Arduino sketch. The places where you'll need to enter your data are marked with comments in the sketch. Enter your WiFi network credentials, which you already should have from your initial testing in step 1. Also in the sketch, define WEBSERVER to be your web host (for example, yourhost.net) and define WEBPAGE to be the path to the json.php file (for example, yourdirectory/json.php). Also choose an interval for how long you want the CC3000/Arduino to wait between connections, or "polls." Test the CC3000/Ardunio with these settings -- if they're correct, it will create a new database record containing dummy data every time the CC3000/Arduino "polls" the Base Station.

11) Once the sketch is working, it's time to adapt it for the kinds of sensor data and commands you want to use. You'll need to write your own Arduino code for the kinds of sensors you want to use and the functions you want the Arduino to perform when it receives numeric commands. There are separate tabs for entering your data handlers and your command handlers.

12) With your code in place, you can put the Base Station to work. Set up your CC3000/Arduino and let it run. From anywhere on your local network, you'll be able to read the logged data by accessing http://yourhost.net/yourdirectory/report.php. Links at the top of the report let you insert commands (which will be executed on the NEXT poll) and dump your data as a CSV file that can be imported into a spreadsheet.







*****************************
*	DETAILED DESCRIPTION	*
*****************************

THEORY OF OPERATION

Communication between the Base Station and the Arduino/CC3000 flows in both directions. The Base Station sends simple numeric command codes to the Arduino via three "command channels," and the Ardunio sends data to the Base Station via three "data channels."

A mySQL database on the Base Station server stores command codes (entered by the user) and data (sent back by the Arduino/CC3000.)

The Arduino script is set up to poll the Base Station at intervals you specify. The Arduino initiates each polling session by sending a URI to the server that hosts the Base Station. The URI passes to the server a "step counter" (an integer updated for each polling session) and the data for the three data channels.

The Base Station receives the data and stores it in the mySQL database. It then retrieves commands from the mySQL database, encodes them into JSON (JavaScript Object Notation) format, and sends the JSON-encoded string back to the Arduino/CC3000. The Arduino sketch decodes the JSON string to read the command codes, and performs whatever actions you have defined for them.


AN EXAMPLE

Let's suppose you have a contract with the National Institutes of Health to research small-scale ways for hospitals to grow medical, uh, parsley. Your test greenhouse is on the roof of your lab building, but your office is in the basement.

To monitor your experiment, you've equipped an Arduino Uno with sensors to measure the greenhouse's temperature, humidity and lux (light) levels, and with controllers to operate lights, a watering system, and fans to control the temperature. These systems are supposed to work automatically, but you want to be able to record the sensor readings and to operate the controllers manually if you notice a problem.

You equip the Arduino with an Adafruit CC3000 module and set it up to connect to the building's WiFi network. You also adapt the Base Station sketch to transmit the sensor readings at your desired intervals, and to operate the controllers in response to the command codes received from the Base Station.

Downstairs in your office, you set up MAMP to run on a Mac and install the Base Station package on it. Whenever the Arduino "checks in" (or polls), it transmits its sensor readings, which are stored in the mySQL database. You can use a web browser from anywhere on your network to view the sensor readings, which are time-stamped by mySQL as they arrive.

If you want to turn on or off the lights, watering system, or fans, you insert a new table line and enter the command codes you've defined to perform those functions. The next time the Arduino "checks in," it will transmit its data, then receive the command codes to operate the controls.


EXPANSION

I chose three command channels and three data channels to keep things simple and to conserve the Arduino's RAM. You can edit the PHP scripts and Arduino sketch to add more channels if you want; just make sure you don't use too much memory on the Arduino.

Also, you can edit the scripts to give the channels descriptive names, instead of 'command_1', etc. For example, in the scenario above, you might want to set up your table so the command channels are named "Lights | Water | Fans" and the data channels are named "Temp | Humidity | Lux".

While it's important to bear the Arduino's memory limits in mind, remember that most of the "heavy lifting" is done on the computer that runs the Base Station package. If you're good with PHP, you can adapt the scripts to incorporate all kinds of other information gathered from the Internet with the Arduino data and commands.


*************************
*	TROUBLESHOOTING		*
*************************

-- If the CC3000 won't join your WiFi network:

The CC3000 module is a bit picky about some details of addressing. Check the Adafruit forums for problem reports.

One common problem is that the CC3000 module doesn't like the default IP addressing scheme used by Apple Airport wireless networks, which assign addresses in the range 10.0.x.x Instead, you need to use the other commonly-used internal address range, which is 192.168.x.x. You can switch the address range by changing settings in the Apple Airport Utility.

-- If you can join the network but can't access your MAMP webserver:

The CC3000 module also doesn't seem to like non-standard ports. The standard webserver port is 80, but MAMP by default uses 8888. To make the CC3000 module connect properly, change your MAMP preferences so that MAMP is running on port 80.

