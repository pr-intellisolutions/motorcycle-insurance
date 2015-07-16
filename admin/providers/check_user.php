<?php
require_once('../../common.php');
require_once('../../includes/profile.php');

$profile = new Profile;
$username = isset($_POST['username']) ? $_POST['username'] : "";

if ($profile->user_available($username))
	echo "available";
else
	echo "not available";

?>
