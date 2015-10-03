<?php
require_once('../common.php');

function restore_tables($mysql_host, $mysql_username, $mysql_password, $mysql_database, $filename)
{
	// Remove fakepath
	$filename = basename($filename);

	// Connect to MySQL server
	$link = mysqli_connect($mysql_host, $mysql_username, $mysql_password) or die('Error connecting to MySQL server: ' . mysql_error());

	// Drop entire database and then create a new one
	mysqli_query($link, 'DROP DATABASE prmi');
	mysqli_query($link, 'CREATE DATABASE prmi');

	// Select database
	mysqli_select_db($link, 'prmi');

	// Temporary variable, used to store current query
	$templine = '';
	// Read in entire file
	$lines = file(DIR_BASE.'/db/backup/'.$filename);
	// Loop through each line
	foreach ($lines as $line)
	{
		// Skip it if it's a comment
		if (substr($line, 0, 2) == '--' || $line == '')
			continue;

		// Add this line to the current segment
		$templine .= $line;
		// If it has a semicolon at the end, it's the end of the query
		if (substr(trim($line), -1, 1) == ';')
		{
			// Perform the query
			mysqli_query($link, $templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysql_error() . '<br /><br />');
			// Reset temp variable to empty
			$templine = '';
		}
	}
	mysqli_close($link);
	return true;
}

if ($user->auth() && $user->role === 'admin')
{
	if (restore_tables($user->db_host, $user->db_user, $user->db_pass, $user->db_name, $_POST['filename']))
	{
		echo 'success';
		die();
	}
	else
	{
		echo 'Error desconocido';
		die();
	}
}
else
{
	http_response_code(400);
	die();
}
?>