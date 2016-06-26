<?php
	header('Content-type: text/plain');
	require 'config.php';
	$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
	mysql_select_db($dbname);
	$result = mysql_query("DELETE FROM sessions WHERE created_at < DATE_SUB(NOW(),INTERVAL 30 SECOND);");
	echo(mysql_affected_rows());
	mysql_close($conn);
?>