<?php
require_once('../common.php');

if ($user->auth())
{
	header('Location: '.SITE_URL);
	die;
}
?>