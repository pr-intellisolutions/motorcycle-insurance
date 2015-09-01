<?php 

require_once('../common.php');

$template->set_custom_template(DIR_BASE.'styles', 'default');

$template->assign_var('SITE_URL', SITE_URL);

if ($user->auth())
{
	header('Location: '.SITE_URL);
	die();
}
else
{
	$template->assign_vars(array('USER_AUTH_VALID' => false,
		'NO_LOGIN_INFO' => true));
}

$template->set_filenames(array('body' => 'register.html'));
$template->display('body');
?>
