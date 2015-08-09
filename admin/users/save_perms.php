<?php

require_once('../../common.php');

$username = $user->sanitize_input($_POST['username']);
$permissions = $user->sanitize_input($_POST['permissions']);

$stmnt = sprintf("UPDATE login SET permissions = '%s' WHERE user = '%s'", $permissions, $username);

if ($user->sql_conn->query($stmnt))
{
	echo "success";
	die();
}
else
{
	echo $user->sql_conn->error;
	die();
}
?>