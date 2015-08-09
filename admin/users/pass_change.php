<?php 

require_once('../../common.php');

if (isset($_POST['username']) && $_POST['username'] !== "" && isset($_POST['newpass']) && $_POST['newpass'] !== "")
{

	if ($user->change_passwd($_POST['username'], $_POST['newpass']))
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