<?php

require_once('../../common.php');

$username = isset($_POST['username']) ? $_POST['username'] : "";

if ($user->delete_account($username))
	echo "success";
else
	echo $user->error;
?>
