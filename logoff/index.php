<?php
require_once('../common.php');

if ($user->auth())
	$user->logoff();

header('Location: '.SITE_URL);
die();
?>
