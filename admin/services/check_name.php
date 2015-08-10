<?php

require_once('../../common.php');
require_once('./plan.php');

if ($user->auth() && $user->role === 'admin')
{
	$plan = New Plan;

	if ($plan->name_available($_POST['name']))
	{
		echo "available";
		die();
	}
	else
	{
		echo "not available";
		die();
	}
}
else
{
	http_response_code(400);
	die();
}

?>
