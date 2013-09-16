<!DOCTYPE html>
<html>
	<head>
		<!--link to our external stylesheet-->
		<link rel="stylesheet" href="styles.css" id="stylesheet">
	</head>

	<body>
		<?php include('sql-links.php');?>
		<div id="page-wrap">
			<div id="topbar" class="graphics">
			&nbsp;
		</div>
		
		<h3>Search for a record:</h3>
		
		<?php
			
			//include the connection parameters
			include('sql-connect.php');

			//set up a search
			//get the first record so we can pull the fieldnames
			$result = mysqli_query($con,"SELECT * FROM " . $table . " WHERE recordID = 1");

			//get the field names in an array
			$fieldnames = $result->fetch_fields();

			//get the data in an array
			$row = mysqli_fetch_array($result);

			//count the fields so we can build a two-column table
			$max = count($fieldnames);

			//close the mysql connection
			mysqli_close($con);
			
			//initialize a counter
			$i = 0;

			//start of table
			echo "<table>";

			//build rows using fieldnames
			foreach ($fieldnames as $val)
			{
				$fieldname = ($val->name);
				echo "<tr>";
				echo "<th>";
				echo $fieldname;
				echo "</th>";
				//add a row for search links
				echo "<td>";
				echo "<form action='sql-find.php' method='post'>";
				echo "<input type='hidden' name='findfield' value='" . $fieldname . "'>";
				echo "<input type='text' name='findvalue' value=''>";
				echo "&nbsp;&nbsp;";
				echo "<input type='submit' value='Find'></form>";
				echo "</td>";
				echo "</tr>";
				$i++;
			}
			//end of building rows

			//end of table
			echo "</table>";
			
		?>
		</div>
	</body>
</html>