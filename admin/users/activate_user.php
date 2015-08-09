<?php

require_once('../../common.php');

$username = isset($_POST['username']) ? $_POST['username'] : "";

$username = $user->sanitize_input($username);

if ($user->user_valid($username))
{
	if ($user->user_inactive($username))
	{
		$user->user_activate($username);

		echo "success";
		die();
	}
	else
	{
		echo "El usuario no está inactivo.";
		die();
	}
}
else
{	
	echo "El nombre de usuario es invalido.";
	die();
}
		

?>
