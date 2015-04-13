<?php
require_once('../common.php');

if ($user->auth())
{
	header('Location: '.SITE_URL);
	die;
}
else
{
	header('Location: '.SITE_URL.'login/password.recovery.php');
	die;
}
?>