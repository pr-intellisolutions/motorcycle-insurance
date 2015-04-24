<?php

require_once('common.php');

$template->set_custom_template(DIR_BASE.'styles', 'default');
$template->assign_var('SITE_URL', SITE_URL);

if ($user->auth())
{
	$template->assign_var('USER_AUTH_VALID', true);
}

else
{
	$template->assign_var('USER_AUTH_VALID', false);
}

$template->set_filenames(array('body' => 'index.html'));

$template->display('body');

?>
