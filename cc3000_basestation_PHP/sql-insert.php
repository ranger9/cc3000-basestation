<?php

	//This page is called to insert a new record into the database

	//redirect after execution
	header("Location: edit.php");
	
	$postdata = file_get_contents("php://input");
	echo $postdata;
	
	//sql-connect supplies $fieldnames, $fieldcount, $con
	include('sql-connect.php');
	
	//get POST data from form and save in variables
	//build variables using field name info from sql-connect.php
	
	//these two are standard
	$recordID=$_POST['recordID'];
	$timestamp=$_POST['timestamp'];
	
	//push the other posted values into an array
	
	$postdata=array();
	for ($i=0; $i<$fieldcount; $i++)
		{
			$postdata[] = $_POST[$fieldnames[$i]];
		}
		
	//build a mySQL query string using posted values from array
	$query = "INSERT INTO " . $table . "(";
	
	//build using the fieldnames supplied by sql-connect
	for ($i=0; $i<$fieldcount; $i++)
		{
			$query .= $fieldnames[$i];
			//add a comma after all except last
			if ($i <($fieldcount - 1))
				{
					$query .= ", ";
				}
		}
	$query .= ") VALUES (";
		
	//add the posted values from array
	$postdatacount = count($postdata);
	for ($i=0; $i<$postdatacount; $i++)
	{
		$query .= "'" . $postdata[$i] . "'";
		//add a comma after all except last
		if ($i < ($postdatacount - 1) )
			{
				$query .= ", ";
			}
	}
	
	$query .= ")";

	//insert values into the database
	$result=mysqli_query($con,$query);
	
	if ($result != 1)
		{
		echo "Sorry, there was a problem inserting your data.";
		}
	
	//close mySQL connection	
	mysqli_close($con);

?>