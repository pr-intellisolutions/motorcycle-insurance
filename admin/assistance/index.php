<?php

require_once('../../common.php');

$template->set_custom_template(DIR_BASE.'styles', 'default');

$template->assign_var('SITE_URL', SITE_URL);

if ($user->auth() && $user->role == 'admin')
{
	$template->assign_vars(array('FORM_ACTION' => $_SERVER['PHP_SELF'],
		'FORM_METHOD' => 'POST',
		'USERNAME' => $user->user));

	if (isset($_GET['option']) && $_GET['option'] == 1)
	{
		$template->assign_var('SIDE_CONTENT', 1);
		
		// Generate providers list
		$stmnt = sprintf("SELECT * FROM providers INNER JOIN login ON providers.userid = login.id INNER JOIN profile ON login.id = profile.userid");
	}
	else if (isset($_GET['option']) && $_GET['option'] == 2)
	{
		$template->assign_var('SIDE_CONTENT', 2);
	}
	else
	{
		$template->assign_var('SIDE_CONTENT', 'home');
	}		
	$template->set_filenames(array('body' => 'admin_assistance.html'));
	$template->display('body');
}
else
{
	header('Location: '.SITE_URL);
	die();
}
?>
