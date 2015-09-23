<?php

require_once('../common.php');

$template->set_custom_template(DIR_BASE.'styles', 'default');

$template->assign_var('SITE_URL', SITE_URL);

if ($user->auth())
{
	$stmnt = sprintf("SELECT first, middle, last FROM profile INNER JOIN login ON profile.userid = login.id WHERE profile.userid=%d", $user->user_id);
	
	$result = $user->sql_conn->query($stmnt);
	
	if ($result->num_rows > 0)
	{
		$row = $result->fetch_assoc();

		$template->assign_var('USERNAME', sprintf("%s %s %s", $row['first'], $row['middle'], $row['last']));

		$result->close();
	}

	$template->assign_vars(array('USER_AUTH_VALID' => true,
		'USER_ROLE' => $user->role,
		'SIDE_CONTENT' => "home",
		'NO_LOGIN_INFO' => false));

	$template->set_filenames(array('body' => 'profile.html'));
	$template->display('body');
}
else
{
	header('Location: '.SITE_URL);
	die();
}

?>
