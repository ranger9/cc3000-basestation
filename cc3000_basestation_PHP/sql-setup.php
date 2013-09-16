<?php
	//This file will create a mySQL database for use with the sketch
	//Default parameters should work as-is with MAMP/WAMP
	//Change them as needed for other mySQL servers
	
//CUSTOMIZATION SPOT
	//specify your connection parameters
	//make sure to copy these to sql-connect.php

	$username="root";		//mySQL user name for your SQL server
	$password="root";		//mySQL password for your SQL server
	$database="test";		//any legal name you want
	$table="json";			//any legal name you want
	$mysqlhost = "localhost";	//for testing with MAMP

	//supply field names and specs in the following array
	
//CUSTOMIZATION SPOT
	//Edit if you want to change 'command_1' etc. to more descriptive names
	//If you change the names, you must also change them in json.php
	//If you change the number of commands, you must also change it in edit.php
	$setupnames=array("command_1 int(6)","command_2 int(6)","command_3 int(6)","data_1 varchar(6)","data_2 varchar(6)","data_3 varchar(6)");
			
	$setupcount=count($setupnames);

	//connect, saving return in a variable
	$con=mysqli_connect($mysqlhost,$username,$password);

	// Check connection
	if (mysqli_connect_errno())
		  {
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }

	//create database
	//comment out if adding a table to an existing database

	$sql="CREATE DATABASE " . $database;

	if (mysqli_query($con,$sql))
		  {
		  echo "Database " .  $database . " created successfully<br>";
		  }
		else
		  {
		  echo "Error creating database: " . mysqli_error($con);
		  }


	//create a new table

	$sql="CREATE TABLE " . $database . "." . $table . "(";
	$sql .= "recordID INT(6) AUTO_INCREMENT, PRIMARY KEY(recordID), ";
	$sql .= "lastModified TIMESTAMP ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP, ";

	//create the fields specified in sql-setup
	for ($i=0; $i<$setupcount; $i++)
		{
			$sql .= ($setupnames[$i]);
			//add a comma after all except last
			if ($i <($setupcount - 1))
				{
					$sql .= ", ";
				}
		}
	
	$sql .= ")";
	
	// Execute query
	if (mysqli_query($con,$sql))
	  {
	  echo "Table ". $table . " created successfully<br>";
	  }
	else
	  {
	  echo "Error creating table: " . mysqli_error($con);
	  }
	  
	  //uncomment the following section if you want recordID to start higher
	  //$sql="ALTER TABLE " . $database . "." . $table . " AUTO_INCREMENT = 100000;";
	  
	  // Execute query
	if (mysqli_query($con,$sql))
	  {
	  echo "Table ". $table . " altered successfully";
	  }
	else
	  {
	  echo "Error altering table: " . mysqli_error($con);
	  }
	  
  
	mysqli_close();

  
?>