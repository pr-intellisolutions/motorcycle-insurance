<?php

require_once('../common.php');

$user->db_host = $_POST['dbHost'];
$user->db_port = $_POST['dbPort'];
$user->db_user = $_POST['dbUser'];
$user->db_pass = $_POST['dbPass'];
$user->db_name = $_POST['dbName'];

if ($user->save_db_config())
{
	echo "success";
	die();
}
else
{
	echo $user->error;
	die();
}
?>