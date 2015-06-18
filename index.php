<?php

require_once('./common.php');

$template->set_custom_template(DIR_BASE.'styles', 'default');

$template->assign_var('SITE_URL', SITE_URL);

if ($user->auth())
{
	$template->assign_vars(array('USER_AUTH_VALID' => true,
		'USER_ROLE' => $user->role));
}
else
{
	$template->assign_vars(array('USER_AUTH_VALID' => false,
		'USER_ROLE' => $user->role));
}

$template->set_filenames(array('body' => 'index.html'));

$template->display('body');

?>
