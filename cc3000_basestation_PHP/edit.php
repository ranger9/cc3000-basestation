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
		
		<?php
		
			//include the connection parameters
			include('sql-connect.php');

			//collect passed value for record ID from the GET arg
			$id=$_GET['id'];
			
//CUSTOMIZATION SPOT
			//set this to the number of command fields you have
			//or comment out if you want to show data fields too
			$fieldcount = 3;

			//if no id parameter was passed, assume we are inserting a new record
			if ($id=="")
				{
				
				echo "<h3>Insert a new record:</h3>";

				//start the form
				echo "<form action='sql-insert.php' method='post'>";

				//start of table
				echo "<table>";

				//build rows using fieldnames
				for ($i=0; $i<$fieldcount; $i++)
					{
						echo "<tr>";
						echo "<th>";
						echo $fieldnames[$i];
						echo "</th>";
						echo "<td>";
						echo "<input type='text' name='" . $fieldnames[$i] . "' value=''>";
						echo "</td>";
						echo "</tr>";
					}
				//end of building rows

				echo "<tr><td colspan='2'><input type='submit' value='Insert'></td></tr>";

				//end of table
				echo "</table>";
				echo "</form>";
				}
			
			//if an id parameter was passed, assume we are editing an existing record
			else
				{
				//get the record requested by id
				$result = mysqli_query($con,"SELECT * FROM " . $table . " WHERE recordID =" . $id);

				//get the data in an array
				$row = mysqli_fetch_array($result);
				
				echo "<h3>Edit a record:</h3>";

				//start the form
				echo "<form action='sql-update.php' method='post'>";

				echo "<input type='hidden' name='ud_id' value='" . $id . "'>";

				//start of table
				echo "<table>";

				//build rows using fieldnames
				for ($i=0; $i<$fieldcount; $i++)
					{
						echo "<tr>";
						echo "<th>";
						echo $fieldnames[$i];
						echo "</th>";
						echo "<td>";
						echo "<input type='text' name='" . $fieldnames[$i] . "' value='" . $row[$i+2] . "'>";
						echo "</td>";
						echo "</tr>";
					}
				
				//end of building rows

				echo "<tr><td colspan='2'><input type='submit' value='Update'></td></tr>";

				//end of table
				echo "</table>";
				echo "</form>";
				}
				
				
				//close the mysql connection
				mysqli_close($con);
		
		?>
		</div>
	</body>
</html>