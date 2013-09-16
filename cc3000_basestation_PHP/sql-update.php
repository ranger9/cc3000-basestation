<?php

	//This section is called to update the data in an existing record
	
	//do not redirect after execution; use Javascript instead
	//header("Location: edit.php");

	include('sql-connect.php');
	
	//get POST data from form and save in variables
	//build variables using field name info from sql-connect.php
	
	//these two are standard
	$recordID=$_POST['recordID'];
	$timestamp=$_POST['timestamp'];
	
	//this one is built into the edit form
	$ud_id=$_POST['ud_id'];
	
	//push the other posted values into an array
	
	$postdata=array();
	for ($i=0; $i<$fieldcount; $i++)
		{
			$postdata[] = $_POST[$fieldnames[$i]];
		}
	
	//build a mySQL query string using posted values from array
	//$fieldnames, $fieldcount come from sql-connect
	$query = "UPDATE " . $table . " SET ";
	
	//build the fieldnames specified in sql-connect
	for ($i=0; $i<$fieldcount; $i++)
		{
			$query .= ($fieldnames[$i] . "=" . "'" . $postdata[$i] . "'");
			//add a comma after all except last
			if ($i <($fieldcount - 1))
				{
					$query .= ", ";
				}
		}
		$query .= (" WHERE recordID = " . $ud_id) ;
	
	
	//insert values into the database
	//$con comes from sql-connect
	$result=mysqli_query($con,$query);
	
	if ($result != 1)
		{
		echo "Sorry, there was a problem inserting your data.";
		}
	
	//close mySQL connection	
	mysqli_close($con);
	

	echo "<script>document.location.href=\"edit.php?id=";
	echo $ud_id;
	echo "\";</script>";
	

?>