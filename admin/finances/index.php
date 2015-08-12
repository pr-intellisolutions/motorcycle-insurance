<?php

require_once('../../common.php');

$template->set_custom_template(DIR_BASE.'styles', 'default');

if ($user->auth() && $user->role === 'admin')
{
	$template->assign_vars(array('SITE_URL', SITE_URL,
		'SIDE_CONTENT' => 'home',
		'FORM_ACTION' => $_SERVER['PHP_SELF'],
		'FORM_METHOD' => 'POST',
		'USERNAME' => $user->user));

	$template->set_filenames(array('body' => 'admin_finances.html'));
	$template->display('body');
}
else
{
	header('Location: '.SITE_URL);
	die();
}
?>
