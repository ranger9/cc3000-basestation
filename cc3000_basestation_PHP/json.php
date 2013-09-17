<?php
//No HTTP header or tags.
//To minimize the amount of data the Arduino has to parse, we send ONLY the JSON string.

//First we collect the info passed from the Arduino in the form of GET arguments

	//collect passed value for record ID from the GET arg
	$id=$_GET['id'];
	
	//collect passed data values from the GET arg
	//if you need more data values, you can create more args --
	//make sure you also include them in sql-setup.php so they get created in the database

	$data_1=$_GET['data_1'];
	$data_2=$_GET['data_2'];
	$data_3=$_GET['data_3'];
	

//Now we connect to our mySQL database to insert data values and download commands

	//include the code that connects to mysql
	//it returns the connection data in $con so we can use it later
	include('sql-connect.php');
	
	//get the highest recordID so we can determine where to post the data
	$result = mysqli_query($con,"SELECT MAX(recordID) FROM " . $table . " ORDER BY recordID DESC");
	$row = mysqli_fetch_array($result);
	$maxRow = $row[0];		
	
	//if $id > $maxRow, we are adding a new record to the database
	if ($id > $maxRow)	{	

		//note that the difference between ` and ' is important!
		$query = "INSERT INTO `json` 
		(`recordID`,`lastModified`,`command_1`,`command_2`,`command_3`,
		`data_1`,`data_2`,`data_3`) VALUES
		(NULL,CURRENT_TIMESTAMP,'0','0','0','" . 
		$data_1 . "','" . $data_2 . "','" . $data_3 . "') ;";

		//send the query to insert the data into a new record			
		$result=mysqli_query($con,$query);
	}
	
	
	else	{

	//if $id !> $maxRow, we are updating an existing database row
	
		//build a mySQL query string using the data values
		//and insert them into the current row of the table
		//the value for $table comes from sql-connect.php

		//note that the difference between ` and ' is important!
		$query = "UPDATE `json` SET
		`data_1` = '" . $data_1 . "',
		`data_2` = '" . $data_2 . "',
		`data_3` = '" . $data_3 . "' WHERE
		`json`.`recordID` = " . $id . ";";
		
		//send the query to insert the values into the database
		$result=mysqli_query($con,$query);


	//this part gets the commands from the database
	//and turns them into a json-formatted string that the Arduino can parse

		//get the record requested by id
		$result = mysqli_query($con,"SELECT * FROM " . $table . " WHERE recordID =" . $id);

		//put the record's data into an array
		$row = mysqli_fetch_array($result);
	}



//Here we create an array named $json and push values onto it that represent the commands
//If you need more commands, you can add them to this list using the same syntax --
//Again, make sure you also include them in sql-setup.php to create database fields

	//the + (unary operator) converts the string to an integer by adding 0 to it
	//this makes parsing a bit easier on the Arduino end

	/*
	$json = array(
	"command_1" => +($row["command_1"]),
	"command_2" => +($row["command_2"]),
	"command_3" => +($row["command_3"])
	);
	*/
	
	$json = array(
	+($row["command_1"]),
	+($row["command_2"]),
	+($row["command_3"])
	);

	
	//return the json value of the array we built so Ardunio can parse it for commands
	echo json_encode($json);
	

//Close the mysql connection
	mysqli_close($con);

?>