<?php

	//This section is included in other pages to provide connection info
	//Supplies $fieldnames, $fieldcount, $con to other pages

//CUSTOMIZATION SPOT -- make these match the names you used in sql-setup.php
	$username="root";		//change this for production
	$password="root";		//change this for production
	$database="test";
	$table="json";
	$mysqlhost = "localhost";	//for testing with MAMP

	date_default_timezone_set('America/Chicago');
	$timestamp="'".date('Y-m-d H:i:s')."'";
	
	//Create a connection to mysql using values from connection.php	
	$con=mysqli_connect($mysqlhost,$username,$password,$database); 
	
	
	if (mysqli_connect_errno())
		{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
	else
		{
		}
		
	//Get the first record so we can pull the fieldnames
	$result = mysqli_query($con,"SELECT * FROM " . $table . " WHERE recordID = 1");

	//get the field names
	$dbfieldnames = $result->fetch_fields();
	$dbfieldnamescount = count($dbfieldnames);
	
	//Load them into an array
	$fieldnamestemp=array();
	//gotta catch 'em all
	foreach ($dbfieldnames as $val)
		{
			$fieldnamestemp[] = $val->name;
		}
	//Omit the first two
	$fieldnames=array();
	$tempcount=count($fieldnamestemp);
	for ($i=2; $i<$tempcount; $i++)
		{
			$fieldnames[] = $fieldnamestemp[$i];
		}
	$fieldcount=count($fieldnames);
	
?>
