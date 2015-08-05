<?php

require_once('../../common.php');
require_once('../../includes/profile.php');

$username = isset($_POST['search_input']) ? $_POST['search_input'] : "";

$json_data = array();

if ($user->user_verify($username))
{
	$profile = new Profile;
	
	$user_id = $user->get_user_id($username);

	$profile->load_profile($user_id);

	$json_data['status'] = 'success';
	$json_data['name'] = "$profile->first "."$profile->middle "."$profile->last "."$profile->maiden";
}
else
{
	$json_data['status'] = 'failed';
	$json_data['error'] = $user->error;
}

echo json_encode($json_data);
?>
