<?php

require_once('common.php');

if ($user->auth())
	$template->assign_var('USER_AUTH_VALID', true);
else
{
	$template->assign_vars(array('USER_AUTH_VALID' => false,
		'LOGIN_FORM_ACTION' => 'login/index.php',
		'LOGIN_FORM_METHOD' => 'POST'));

}

$template->set_filenames(array('body' => 'body.html'));

$template->display('body');

?>
