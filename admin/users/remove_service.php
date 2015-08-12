<?php

require_once('../../common.php');

if ($user->auth() && $user->role === 'admin')
{
	$service_id = isset($_POST['service_id']) ? $user->sanitize_input($_POST['service_id']) : 0;
	
	$stmnt = sprintf("DELETE FROM services WHERE id = %d", $service_id);
	
	if ($user->sql_conn->query($stmnt))
	{
		echo "success";
		die();
	}
	else
	{
		echo $user->sql_conn->error;
		die();
	}
}
else
{
	http_response_code(400);
	die();
}
