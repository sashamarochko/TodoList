<?php
include 'connectDB.php';

$user = mysql_escape_string($_POST['user']);

$query = "SELECT * FROM users WHERE name = '".$user."' LIMIT 1";
$result = mysql_query($query);
while($r = mysql_fetch_array($result)) {
	echo $r['id'];
}
?>