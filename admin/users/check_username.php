<?php

require_once('../../common.php');

$stmnt = sprintf("SELECT user FROM login WHERE user='%s'", $_POST['username']);

$result = $user->sql_conn->query($stmnt);

/*
	The output of echo will be sent to the data variable in the front end through JQuery.
*/
if ($result->num_rows > 0)
	echo "not available";
else
	echo "available";

$result->close();

?>
