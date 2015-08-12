<?php

require_once('../../common.php');

if ($user->auth() && $user->role === 'admin')
{
	if ($user->delete_account($_POST['username']))
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
