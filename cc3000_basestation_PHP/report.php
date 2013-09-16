<!--do all our PHP before we do HTML-->
<?php
	//add the links bar
	include('sql-links.php');
	
	include('sql-connect.php');
	
	//collect passed value for sort order from the GET arg
	$sortby=$_GET['sortby'];
	
	//get the table data and load into a variable
	//using sort order picked up from GET arg
	if ($sortby=="")
		{
		
//CUSTOMIZATION SPOT -- remove DESC below if you'd rather see records in ascending order
		$result = mysqli_query($con,"SELECT * FROM " . $table . " ORDER BY recordID DESC");
		}
	else
		{
		$result = mysqli_query($con,"SELECT * FROM " . $table . " ORDER BY " . $sortby);
		}
	
	//count the rows
	$numrows = mysqli_num_rows($result);
	
	//get the field names in an array
	//using these instead of the ones from sql-connect
	//so we can include recordID and lastModified values
	$dbfieldnames = $result->fetch_fields();
	
	//close the mysql connection
	mysqli_close($con);
?>
	
<!DOCTYPE html>
<html>
	<head>
		<!--link to our external stylesheet-->
		<link rel="stylesheet" href="styles.css" id="stylesheet">

		<!--load the file of external javascript functions-->
		<script src="scripts.js"></script>
	</head>

	<body>
		<div id="page-wrap">
			<div id="topbar" class="graphics">
			&nbsp;
			</div>
			
			<?php
				if ($numrows > 0)
					{
					//grammar fix
					if ($numrows > 1)
						{echo "<h3>Displaying " . $numrows . " records:</h3>";}
					else
						{echo "<h3>Displaying " . $numrows . " record:</h3>";}
					}
				else
					{
					echo "<h3>No records</h3>";
					}
			
				//start of table
				echo "<table>";
				
				//build header row using fieldnames
				echo "<tr>";
				foreach ($dbfieldnames as $val)
					{
					echo "<th>";
					echo "<a href='report.php?sortby=";
					echo $val->name;
					echo "'>";
					echo $val->name;
					echo "</a>";
					echo "</th>";
					}
					
				//add a column for delete
					echo "<th>Delete?</th>";
				
					echo "</tr>";
				//end of building header row

				//get data by rows
				while ($row = mysqli_fetch_array($result))
					{
					//get count of array elements
					$max = (count($row)/2);

					//start of table row
					echo "<tr>";

					//make the first column a link for editing
					echo "<td><a href='edit.php?id=";
					echo $row[0];
					echo "'>";
					echo $row[0];
					echo "</a></td>";

					$i = 1;
					while ($i < $max)
						{
							echo "<td>";
							echo $row[$i];
							echo "</td>";
							$i++;
						}
						
					//add a column for deleting				
					echo "<td>
				<form action='sql-delete.php' method='post'>
				<input type='hidden' name='delete_id' value='" . $row[0] . "'>
				<input type='submit' value='Delete' onclick=\"return confirm('Are you sure?')\">
				</form>
				</td>";
						
					echo "</tr>";
					}
				//end of data rows
				//end of table
				echo "</table>";
			?>
		</div>
	</body>
</html>
	
	