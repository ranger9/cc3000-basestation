<?php

	include('sql-connect.php');
	

	//get the table data and load into a variable
	$result = mysqli_query($con,"SELECT * FROM " . $table . " ORDER BY recordID");
	
	//close the mysql connection
	mysqli_close($con);
	
	
	$filename = $database . " | " . $table . "-" . time() . ".csv";
	
	//comment out the headers to display the CSV file in browser for copy-and-paste
	//may be needed for some hosts that don't generate downloads
	
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header('Content-Description: File Transfer');
	header("Content-type: text/csv");
	header("Content-Disposition: attachment; filename={$filename}");
	header("Expires: 0");
	header("Pragma: public");
	
	
	$fp = @fopen( 'php://output', 'w' );

	foreach ($result as $fields)
		{
			fputcsv($fp, $fields);
		}

	fclose($fp);

	
?>