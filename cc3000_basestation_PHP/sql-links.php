<?php

//This section generates the links bar used on other pages

echo "<table id='linkstable'><tr><td><div id='linksbar'><a href='edit.php'>Insert commands</a>&nbsp;|&nbsp;<a href='find.php'>Find a record</a>&nbsp;|&nbsp;<a href='report.php'>Show report</a>&nbsp;|&nbsp;<a href='get.php'>Get as CSV</a>&nbsp;|&nbsp;<a href='empty.php' onclick=\"return confirm('Are you sure?')\">Delete all (no undo!)</a></div></td></tr></table>";
?>

