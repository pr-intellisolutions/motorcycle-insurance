<?php

require_once('../../common.php');

if (isset($_POST['username']) && $_POST['username'] !== "")
{
	$username = $user->sanitize_input($_POST['username']);			

	if ($user->delete_account($username))
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
	$user->set_error($user::BAD_INPUT);

	echo $user->error;
	die();
}
?>
