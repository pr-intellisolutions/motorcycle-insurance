<?php

require_once('../../common.php');

$username = isset($_POST['username']) ? $_POST['username'] : "";

if ($user->user_valid($username))
	if ($user->user_locked($username))
	{
		$user->user_unlock($username);
		echo "success";
	}
	else
		echo "failed";
else
	echo "invalid"
		

?>
