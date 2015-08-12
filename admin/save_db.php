<?php

require_once('../common.php');

if ($user->auth() && $user->role === 'admin')
{
	if ($user->save_db_config($_POST))
	{
		echo "success";
		die();
	}
	else
	{
		echo $user->error;
		die();
	}
}
else
{
	http_response_code(400);
	die();
}
?>