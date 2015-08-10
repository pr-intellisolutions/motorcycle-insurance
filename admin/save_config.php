<?php

require_once('../common.php');

if ($user->auth() && $user->role === 'admin')
{
	if ($site_config->save_config($_POST))
	{
		echo 'success';
		die();
	}
	else
	{
		echo $site_config->error;
		die();
	}
}
else
{
	http_response_code(400);
	die();
}

?>