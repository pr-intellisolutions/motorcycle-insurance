<?php
require_once('../../common.php');


$username = isset($_POST['username']) ? $_POST['username'] : "";

if ($user->uid_available($username))
	echo "available";
else
	echo "not available";

?>
