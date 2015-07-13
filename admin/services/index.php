<?php

require_once('../../common.php');

$template->set_custom_template(DIR_BASE.'styles', 'default');

$template->assign_var('SITE_URL', SITE_URL);

if ($user->auth() && $user->role == 'admin')
{
	$template->assign_vars(array('SIDE_CONTENT' => 'home',
		'FORM_ACTION' => $_SERVER['PHP_SELF'],
		'FORM_METHOD' => 'POST',
		'USERNAME' => $user->user));

	if (isset($_GET['option']) && $_GET['option'] == 1)
	{
		$template->assign_var('SIDE_CONTENT', '1');
	}

	$template->set_filenames(array('body' => 'admin_services.html'));

	$template->display('body');
}
else
{
	header('Location: '.SITE_URL);
	die();
}
?>
