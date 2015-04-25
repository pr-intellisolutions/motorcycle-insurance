<?php
require_once('../common.php');

if ($user->auth())
{
	$user->session_update('passchg', User::NEWPASS_REQUEST);

	header('Location: '.SITE_URL.'login/password.reset.php');
	die;
}
else
{
	header('Location: '.SITE_URL);
	die;
}
?>