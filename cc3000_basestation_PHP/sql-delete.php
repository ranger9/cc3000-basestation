<?php

//This page is called to delete a single record
	
	//Redirect after execution
	header("Location: report.php");

	include('sql-connect.php');
	
	//Get POST data from form and save in variables
	//Update these names to match those in sql-setup.php
	$delete_final_id=$_POST['delete_id'];
	
	//Build a mySQL query string
	//Update these names to match those in sql-setup.php and above 
	$query = 
	"DELETE FROM " . $table . "
	WHERE recordID = " . $delete_final_id ;
	//note above that last value is NOT followed by a comma
	
	//Insert values into the database
	$result=mysqli_query($con,$query);
	
	if ($result != 1)
		{
		echo "Sorry, there was a problem deleting your data.";
		}
	
	//Close mySQL connection	
	mysqli_close($con);

?>