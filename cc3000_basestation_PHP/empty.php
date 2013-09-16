<?php

	//WARNING - CALLING THIS PAGE WILL EMPTY (truncate) YOUR DATABASE!
	//Delete this file from your installation if you want to remove that possibility
	
	include('sql-connect.php');
	
	//redirect after execution
	header("Location: report.php");

	//send the mySQL 'truncate' command to empty the table
	$result = mysqli_query($con,"TRUNCATE TABLE " . $table);
	
	//close the mysql connection
	mysqli_close($con);
	
?>