<?php

require_once('../../common.php');
require_once('./plan.php');

if ($user->auth() && $user->role === 'admin')
{
	$plan = new Plan;

	if ($plan->delete_plan($_POST['name']))
	{
		echo "success";
		die();
	}
	else
	{
		echo $plan->error;
		die();
	}
}
else
{
	http_response_code(400);
	die();
}

?>
