<?php

require_once('../../common.php');
require_once('./provider.php');

$user = new Provider;

if ($user->add_provider($_POST))
	echo "success";
else
	echo $user->error;
?>
