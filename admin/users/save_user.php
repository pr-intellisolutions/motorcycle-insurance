<?php

require_once('../../common.php');
require_once('../../includes/profile.php');

$profile = new Profile;

$username = $_POST['username'];
$user_id = $user->get_user_id($username);

if ($profile->save_profile($user_id, $_POST))
{
	echo 'success';
	die();
}
else
{
	echo $profile->error;
	die();
}
?>
