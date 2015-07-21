<?php
require_once('../../common.php');
require_once('../../includes/profile.php');

$username = isset($_POST['username']) ? $_POST['username'] : "";

if ($profile->user_available($username))
	echo "available";
else
	echo "not available";

?>
