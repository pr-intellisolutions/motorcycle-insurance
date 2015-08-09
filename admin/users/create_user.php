<?php

require_once('../../common.php');

if ($user->create_account($_POST))
{
	echo 'success';
	die();
}
else
{
	echo $user->error;
	die();
}

?>